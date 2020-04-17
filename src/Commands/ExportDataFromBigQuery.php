<?php

namespace RezuanKassim\BQAnalytic\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use RezuanKassim\BQAnalytic\BQData;
use RezuanKassim\BQAnalytic\BQTable;
use RezuanKassim\BQAnalytic\ProgressBar;
use SchulzeFelix\BigQuery\BigQueryFacade as BigQuery;

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
        $start_dates = collect();

        $this->startProgressBar(6);

        $period = $this->getPeriod();

        $this->makeProgress();

        if ($period->isEmpty()) {
            if (BQTable::where('table_date', Carbon::yesterday())->count() == 0) {
                $start_dates->push(Carbon::yesterday());
            }

            foreach (BQTable::where('status', false)->get() as $failed_data) {
                if ($failed_data->table_date != Carbon::yesterday()) {
                    $start_dates->push($failed_data->table_date);
                }
            }

            $this->makeProgress();

            foreach ($start_dates as $startdate) {
                $this->getAllResultsAndStoreIntoDatabase($startdate);
            }
        } else {
            $this->makeProgress();
            
            foreach ($period as $startdate) {
                $this->getAllResultsAndStoreIntoDatabase($startdate);
            }
        }


        $this->finishProgress('export finished');
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

    private function getAllResultsAndStoreIntoDatabase($start_date)
    {
        if (BigQuery::dataset(config('bqanalytic.big_query_table_name'))->table('events_'.$start_date->format('Ymd'))->exists()) {
            $query = "
                SELECT 
                    *
                FROM 
                    ".config('bqanalytic.big_query_table_name').".events_".$start_date->format('Ymd')."
            ";

            $results = collect($this->returnResults($query));

            foreach ($results->chunk(100) as $result) {
                foreach ($result as $r) {
                    config('bqanalytic.bigquery')::create($r);
                }
            }

            return BQTable::updateOrCreate([
                'table_date' => $start_date->format('Y-m-d'),
            ], [
                'status' => 1
            ]);
        }

        $this->removeDataWithStartDate($start_date);

        return BQTable::updateOrCreate([
            'table_date' => $start_date->format('Y-m-d'),
        ], [
            'status' => 0
        ]);
    }

    private function removeDataWithStartDate($start_date)
    {
        BQData::where('event_date', $start_date)->delete();
    }

    private function returnResults($query)
    {
        $query = BigQuery::query($query);
        $rawResults = BigQuery::runQuery($query);

        $results = [];
        
        foreach ($rawResults as $key => $row) {
            foreach ($row as $column => $value) {
                $results[$key][$column] = $value;
            }
        }

        return $results;
    }
}