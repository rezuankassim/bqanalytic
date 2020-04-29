<?php

namespace RezuanKassim\BQAnalytic\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use RezuanKassim\BQAnalytic\Actions\GetClient;
use RezuanKassim\BQAnalytic\Actions\GetPeriod;
use RezuanKassim\BQAnalytic\BQAnalyticClientFactory;
use RezuanKassim\BQAnalytic\BQData;
use RezuanKassim\BQAnalytic\BQTable;
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
                            {end? : End date}';

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
        $this->getAllResultsAndStoreIntoDatabase();
    }

    protected function getAllResultsAndStoreIntoDatabase()
    {
        $accounts = (new GetClient())->execute(config('bqanalytic.client_from_db'));

        foreach ($accounts as $account) {
            $period = (new GetPeriod($account['name'], $this->argument('start'), $this->argument('end')))->execute();

            $BQAnalyticClient = BQAnalyticClientFactory::create([
                'credential' => storage_path('app/' . $account['google_credential']),
                'project' => $account['google_project_id'],
                'auth_cache_store' => 'file',
                'client_options' => ['retries' => 3]
            ]);

            foreach ($period as $date) {
                $this->getDataFromBigQuery($BQAnalyticClient, $date, $account['google_bq_dataset_name'], $account['name']);
            }
        }
    }

    protected function getDataFromBigQuery($BQAnalyticClient, $start_date, $dataset, $name)
    {
        if ($BQAnalyticClient->dataset($dataset)->table('events_' . $start_date->format('Ymd'))->exists()) {
            $query = "
                SELECT 
                    *
                FROM 
                    " . $dataset . ".events_" . $start_date->format('Ymd') . "
            ";

            $results = collect($this->returnResults($BQAnalyticClient, $query));

            foreach ($results->chunk(100) as $result) {
                foreach ($result as $r) {
                    config('bqanalytic.bigquery')::create(collect($r)->merge([
                        'dataset' => $dataset
                    ])->toArray());
                }
            }

            return BQTable::updateOrCreate([
                'table_date' => $start_date->format('Y-m-d'),
                'dataset' => $name
            ], [
                'status' => 1
            ]);
        }

        $this->removeDataWithStartDate($start_date, $dataset);

        return BQTable::updateOrCreate([
            'table_date' => $start_date->format('Y-m-d'),
            'dataset' => $name
        ], [
            'status' => 0
        ]);
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
