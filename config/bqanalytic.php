<?php

return [
    /**
     * User model
     *
     * This is the directory of the user model in the project
     */
    'user' => env('BQANALYTIC_USER_MODEL', 'App\User'),

    /**
     * Analytic model
     *
     * This is the directory of the analytic model in the project
     */
    'analytic' => env('BQANALYTIC_ANALYTIC_MODEL', \RezuanKassim\BQAnalytic\Analytic::class),

    /**
     * Big Query model
     *
     * This is the directory of the bigquery model in the project
     */
    'bigquery' => env('BQANALYTIC_BQ_MODEL', \RezuanKassim\BQAnalytic\BQData::class),

    /**
     * Big Query Table model
     * 
     * This is the directory of the bigquery table model in the project
     */
    'bigquery_table' => env('BQANALYTIC_BQ_TABLE', \RezuanKassim\BQAnalytic\BQTable::class),

     /**
     * Big Query Table Name 
     * 
     * This is for the table name in Big Query
     */
    'big_query_table_name' => env('BQANALYTIC_BQ_TABLE_NAME', 'Testing'),
];
