<?php

return [

    'multiple_project' => env('BQANALYTIC_MULTIPLE_PROJECTS', false),
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
    'bigquery_dataset_array' => explode(',', env('BQANALYTIC_BQ_TABLE_NAME_ARRAY')),


    'google' => [
        /**
         *  Google accounts
         */
        'accounts' => [
            [
                'name' => env('GOOGLE_CLOUD_APPLICATION_NAME'),
                'google_credential' => env('GOOGLE_CLOUD_APPLICATION_CREDENTIALS', ''),
                'google_project_id' => env('GOOGLE_CLOUD_PROJECT_ID', ''),
                'google_bq_dataset_name' => env('BQANALYTIC_BQ_TABLE_NAME', '')
            ]
        ]
    ],

    'client_from_db' => env('BQANALYTIC_CLIENT_FROM_DB', false),

    'client' => env('BQANALYTIC_CLIENT_MODEL', \RezuanKassim\BQAnalytic\BQClient::class),

    'subclient' => env('BQANALYTIC_SUBCLIENT_MODEL', \RezuanKassim\BQAnalytic\BQSubclient::class)
];
