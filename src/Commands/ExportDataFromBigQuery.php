<?php

namespace RezuanKassim\BQAnalytic\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
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

    private function getClients()
    {
        if (config('bqanalytic.client_from_db')) {
            $accounts = config('bqanalytic.client')::where('status', 1)->get()->toArray();
        } else {
            $accounts = config('bqanalytic.google.accounts');
        }

        dd($accounts);
        return $accounts;
    }

    private function getPeriod()
    {
        $period = collect([]);

        if ($this->argument('start') && !$this->argument('end')) {
            $this->error('You must provide end date');
        }

        if (!$this->argument('start') && $this->argument('end')) {
            $this->error('You must provide start_date');
        }

        if ($this->argument('start') && $this->argument('end')) {
            $dates = collect();
            $startDate = Carbon::createFromFormat('Ymd', $this->argument('start'));
            $endDate = Carbon::createFromFormat('Ymd', $this->argument('end'));

            while ($startDate <= $endDate) {
                $dates->push($startDate);
                $startDate = Carbon::parse($startDate)->addDay();
            }

            $period = $dates->filter(function ($date) {
                return BQTable::where('table_date', $date->format('Y-m-d'))->where('status', 1)->count() == 0;
            });
        }

        return $period;
    }

    private function getAllResultsAndStoreIntoDatabase()
    {
        $period = $this->getPeriod();

        $accounts = $this->getClients();

        foreach ($accounts as $account) {
            $BQAnalyticClient = BQAnalyticClientFactory::create([
                'credential' => storage_path('app/'.$account['google_credential']),
                'project' => $account['google_project_id'],
                'auth_cache_store' => 'file',
                'client_options' => ['retries' => 3]
            ]);

            if ($period->isEmpty()) {
                $startDates = collect();

                if (BQTable::where('table_date', Carbon::yesterday()->format('Y-m-d'))->where('dataset', $account['name'])->count() == 0) {
                    $startDates->push(Carbon::yesterday());
                }

                foreach (BQTable::where('status', false)->where('dataset', $account['name'])->get() as $failedData) {
                    $startDates->push($failedData->table_date);
                }

                foreach ($startDates as $startDate) {
                    $this->getDataFromBigQuery($BQAnalyticClient, $startDate, $account['google_bq_dataset_name'], $account['name']);
                }
            } else {
                foreach ($period as $startDate) {
                    $this->getDataFromBigQuery($BQAnalyticClient, $startDate, $account['google_bq_dataset_name'], $account['name']);
                }
            }
        }
    }

    private function getDataFromBigQuery($BQAnalyticClient, $start_date, $dataset, $name)
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

    private function removeDataWithStartDate($start_date, $dataset)
    {
        BQData::where('event_date', $start_date)->where('dataset', $dataset)->delete();
    }

    private function returnResults($BQAnalyticClient, $query)
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
