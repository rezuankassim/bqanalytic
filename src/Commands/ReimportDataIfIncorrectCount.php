<?php

namespace RezuanKassim\BQAnalytic\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RezuanKassim\BQAnalytic\Actions\GetProject;
use RezuanKassim\BQAnalytic\Models\BQTable;

class ReimportDataIfIncorrectCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bqanalytic:reimport-invalid';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reimport bigquery data if the number is correct';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $tables = BQTable::whereColumn('bigquery_count', '<>', 'bqdata_count')
            ->get();

        foreach ($tables as $table) {
            Log::debug("Reimporting $table->table_date of table $table->bqproject_name");
            
            $project = collect((new GetProject())->execute(config('bqanalytic.project_from_db')))->where('name', $table->bqproject_name)->first();

            if (config('bqanalytic.multiple_table')) {
                $query = DB::table($project['google_bq_dataset_name']);
            } else {
                $query = config('bqanalytic.bigquery')::where('dataset', $project['google_bq_dataset_name']);
            }

            $query->where('event_date', $table->table_date->format('Ymd'))->delete();

            $table->update([
                'status' => 0,
                'bigquery_count' => null,
                'bqdata_count' => null
            ]);
        }
    }
}
