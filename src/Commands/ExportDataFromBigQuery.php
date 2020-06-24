<?php

namespace RezuanKassim\BQAnalytic\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use RezuanKassim\BQAnalytic\Actions\GetPeriod;
use RezuanKassim\BQAnalytic\Actions\GetProject;
use RezuanKassim\BQAnalytic\BQAnalyticClientFactory;
use RezuanKassim\BQAnalytic\Models\BQTable;
use RezuanKassim\BQAnalytic\Models\BQData;
use RezuanKassim\BQAnalytic\ProgressBar;

/**
 * List all locally installed packages.
 *
 * @author JeroenG
 **/
class ExportDataFromBigQuery extends Command
{
    use ProgressBar;
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'bqanalytic:export
                            {start? : Start date}
                            {end? : End date}
                            {--id=*}
                            {--first}';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Export data in big query into database';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $accounts = (new GetProject())->execute(config('bqanalytic.project_from_db'), $this->option('id'));

        foreach ($accounts as $account) {
            $period = (new GetPeriod($account, $this->argument('start'), $this->argument('end'), $this->option('first')))->execute();

            $BQAnalyticClient = BQAnalyticClientFactory::create([
                'credential' => storage_path('app/' . $account['google_credential_path']),
                'project' => $account['google_project_id'],
                'auth_cache_store' => 'file',
                'client_options' => ['retries' => 3]
            ]);

            foreach ($period as $date) {
                $this->getDataFromBigQuery($BQAnalyticClient, $date, $account);
            }
        }
    }

    protected function getDataFromBigQuery($BQAnalyticClient, $start_date, $account)
    {
        if ($BQAnalyticClient->dataset($account['google_bq_dataset_name'])->table('events_' . $start_date->format('Ymd'))->exists()) {
            $query = "
                SELECT 
                    event_date,
                    event_name,
                    (SELECT key FROM UNNEST(event_params) WHERE key = 'currentPage') AS currentPage,
                    (SELECT value.string_value FROM UNNEST(event_params) WHERE key = 'currentPage') AS currentPage_value,
                    (SELECT key FROM UNNEST(event_params) WHERE key = 'subCategory') AS subCategory,
                    (SELECT value.string_value FROM UNNEST(event_params) WHERE key = 'subCategory') as subCategory_value,
                    user_id,
                    user_pseudo_id,
                    device.mobile_brand_name as device_mobile_brand_name,
                    device.mobile_model_name as device_mobile_model_name,
                    device.mobile_marketing_name as device_mobile_marketing_name,
                    device.operating_system_version as device_operating_system_version,
                    geo.city as geo_city,
                    geo.region as geo_region,
                    geo.country as geo_country,
                    geo.continent as geo_continent,
                    geo.sub_continent as geo_sub_continent,
                    app_info.id as app_info_id,
                    app_info.version as app_info_version,
                    traffic_source.name as traffic_source_name,
                    traffic_source.medium as traffic_source_medium,
                    traffic_source.source as traffic_source_source,
                platform,
                FROM 
                    " . $account['google_bq_dataset_name'] . ".events_" . $start_date->format('Ymd') . "
            ";

            $results = collect($this->returnResults($BQAnalyticClient, $query));

            foreach ($results->chunk(1000) as $result) {
                foreach ($result as $r) {
                    config('bqanalytic.bigquery')::create(collect($r)->merge([
                        'dataset' => $account['google_bq_dataset_name']
                    ])->toArray());
                }
            }

            if (config('bqanalytic.project_from_db')) {
                config('bqanalytic.models.project.class')::find($account['id'])->update([
                    'last_imported_date' => $start_date
                ]);
            }

            return BQTable::updateOrCreate([
                'table_date' => $start_date->format('Y-m-d'),
                'bqproject_name' => $account['name']
            ], [
                'status' => 1
            ]);
        } elseif ($BQAnalyticClient->dataset($account['google_bq_dataset_name'])->table('events_intraday_' . $start_date->format('Ymd'))->exists()) {
            $this->removeDataWithStartDate($start_date, $account['google_bq_dataset_name']);

            return BQTable::updateOrCreate([
                'table_date' => $start_date->format('Y-m-d'),
                'bqproject_name' => $account['name']
            ], [
                'status' => 0
            ]);
        }
    }

    protected function removeDataWithStartDate($start_date, $dataset)
    {
        BQData::where('event_date', $start_date)->where('dataset', $dataset)->delete();
    }

    protected function returnResults($BQAnalyticClient, $query)
    {
        $query = $BQAnalyticClient->query($query);
        $rawResults = $BQAnalyticClient->runQuery($query);

        $results = [];

        foreach ($rawResults as $key => $row) {
            foreach ($row as $column => $value) {
                $results[$key][$column] = $value;
            }
        }

        return $results;
    }
}
