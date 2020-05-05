<?php

return [
    'user' => env('BQANALYTIC_USER_MODEL', 'App\User'),

    'analytic' => env('BQANALYTIC_ANALYTIC_MODEL', \RezuanKassim\BQAnalytic\Models\BQAnalytic::class),

    'bigquery' => env('BQANALYTIC_BQ_MODEL', \RezuanKassim\BQAnalytic\Models\BQData::class),

    'bigquery_table' => env('BQANALYTIC_BQ_TABLE', \RezuanKassim\BQAnalytic\Models\BQTable::class),

    'google' => [
        'accounts' => [
            [
                'name' => env('GOOGLE_CLOUD_APPLICATION_NAME'),
                'google_credential_path' => env('GOOGLE_CLOUD_APPLICATION_CREDENTIALS', ''),
                'google_project_id' => env('GOOGLE_CLOUD_PROJECT_ID', ''),
                'google_bq_dataset_name' => env('BQANALYTIC_BQ_TABLE_NAME', ''),
                'start_date' => env('GOOGLE_BQANALYTIC_START_DATE'),
            ]
        ]
    ],

    'models' => [
        'project' => [
            'class' => env('BQANALYTIC_PROJECT_MODEL', \RezuanKassim\BQAnalytic\Models\BQProject::class),
            'fk' => env('BQANALYTIC_PROJECT_FK', 'bqproject_id'),
        ],

        'app' => [
            'class' => env('BQANALYTIC_APP_MODEL', \RezuanKassim\BQAnalytic\Models\BQApp::class),
            'fk' => env('BQANALYTIC_PROJECT_FK', 'bqapp_id'),
        ],

        'client' => [
            'class' => env('BQANALYTIC_CLIENT_MODEL', \RezuanKassim\BQAnalytic\Models\BQClient::class),
            'fk' => env('BQANALYTIC_CLIENT_FK', 'bqclient_id'),
        ],

        'preferences' => [
            'class' => env('BQANALYTIC_PREFERENCES_MODEL', \RezuanKassim\BQAnalytic\Models\BQAnalyticPreference::class),
            'fk' => env('BQANALYTIC_PREFERENCES_FK', 'bqanalyticpreference_id')
        ],

        'data' => [
            'class' => env('BQANALYTIC_DATA_MODEL', \RezuanKassim\BQAnalytic\Models\BQData::class),
            'fk' => env('BQANALYTIC_DATA_FK', 'bqdata_id'),
        ],

        'table' => [
            'class' => env('BQANALYTIC_TABLE_MODEL', \RezuanKassim\BQAnalytic\Models\BQTable::class),
            'fk' => env('BQANALYTIC_TABLE_FK', 'bqtable_id'),
        ],

        'analytic' => [
            'class' => env('BQANALYTIC_ANALYTIC_MODEL', \RezuanKassim\BQAnalytic\Models\BQAnalytic::class),
            'fk' => env('BQANALYTIC_ANALYTIC_FK', 'bqtable_id')
        ],

        'user' => [
            'class' => env('BQANALYTIC_USER_MODEL', 'App\User'),
            'fk' => env('BQANALYTIC_USER_FK', 'user_id')
        ]
    ],

    'project_from_db' => env('BQANALYTIC_PROJECT_FROM_DB', false),

    'project' => env('BQANALYTIC_PROJECT_MODEL', \RezuanKassim\BQAnalytic\Models\BQProject::class),

    'app' => env('BQANALYTIC_APP_MODEL', \RezuanKassim\BQAnalytic\Models\BQApp::class),

    'client' => env('BQANALYTIC_CLIENT_MODEL', \RezuanKassim\BQAnalytic\Models\BQClient::class),

    'analyticPreferences' => env('BQANALYTIC_ANALYTICUSER_MODEL', \RezuanKassim\BQAnalytic\Models\BQAnalyticPreference::class)
];
