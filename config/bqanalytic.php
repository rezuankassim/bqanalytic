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
    'analytic' => env('BQANALYTIC_ANALYTIC_MODEL', \RezuanKassim\BQAnalytic\Models\BQAnalytic::class),

    /**
     * Big Query model
     *
     * This is the directory of the bigquery model in the project
     */
    'bigquery' => env('BQANALYTIC_BQ_MODEL', \RezuanKassim\BQAnalytic\Models\BQData::class),

    /**
     * Big Query Table model
     *
     * This is the directory of the bigquery table model in the project
     */
    'bigquery_table' => env('BQANALYTIC_BQ_TABLE', \RezuanKassim\BQAnalytic\Models\BQTable::class),

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
                'google_credential_path' => env('GOOGLE_CLOUD_APPLICATION_CREDENTIALS', ''),
                'google_project_id' => env('GOOGLE_CLOUD_PROJECT_ID', ''),
                'google_bq_dataset_name' => env('BQANALYTIC_BQ_TABLE_NAME', '')
            ]
        ]
    ],

    'project_from_db' => env('BQANALYTIC_PROJECT_FROM_DB', false),

    'project' => env('BQANALYTIC_PROJECT_MODEL', \RezuanKassim\BQAnalytic\Models\BQProject::class),

    'app' => env('BQANALYTIC_APP_MODEL', \RezuanKassim\BQAnalytic\Models\BQApp::class),

    'client' => env('BQANALYTIC_CLIENT_MODEL', \RezuanKassim\BQAnalytic\Models\BQClient::class),

    'analyticPreferences' => env('BQANALYTIC_ANALYTICUSER_MODEL', \RezuanKassim\BQAnalytic\Models\BQAnalyticPreference::class)
];
