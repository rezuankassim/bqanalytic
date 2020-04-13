<?php

namespace RezuanKassim\BQAnalytic\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use RezuanKassim\BQAnalytic\BQTable;
use SchulzeFelix\BigQuery\BigQueryFacade as BigQuery;

/**
 * List all locally installed packages.
 *
 * @author JeroenG
 **/
class ExportDataFromBigQuery extends Command
{
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
        $start_date = collect();

        dd($period = $this->getPeriod());

        if (BQTable::where('table_date', Carbon::yesterday())->count() == 0) {
            $start_date->push(Carbon::yesterday()->format('Ymd'));
        }

        foreach (BQTable::where('status', false)->get() as $failed_data) {
            if ($failed_data->table_date != Carbon::yesterday()->format('Ymd')) {
                $start_date->push($failed_data->table_date->format('Ymd'));
            }
        }

        dd($start_date);
    }

    private function getPeriod()
    {
        $period = collect();

        if ($this->argument('start') && !$this->argument('end')) {
            $this->error('You must provide end date');
        }

        if (!$this->argument('start') && $this->argument('end')) {
            $this->error('You must provide start_date');
        }

        if ($this->argument('start') && $this->argument('end')) {
            $start = Carbon::createFromFormat('Ymd', $this->argument('start'));
            $end = Carbon::createFromFormat('Ymd', $this->argument('end'));

            while ($start <= $end) {
                $period->push($start->format('Ymd'));
                $start->addDay();
            }
        }

        return $period;
    }

    private function getAllResultsAndStoreIntoDatabase($start_date)
    {
        if (BigQuery::dataset(config('bqanalytic.big_query_table_name'))->table('events_'.$start_date)->exists()) {
            $query = "
                SELECT 
                    *
                FROM 
                    ".config('bqanalytic.big_query_table_name').".events_".$start_date."
            ";

            $results = collect($this->returnResults($query));

            foreach ($results->chunk(100) as $result) {
                foreach ($result as $r) {
                    config('bqanalytic.bigquery')::create($r);
                }
            }

            return 'successful imported '.$results->count();
        }

        return 'failed to import data';
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