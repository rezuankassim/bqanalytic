<?php

namespace RezuanKassim\BQAnalytic\Tests\Features;

use Illuminate\Foundation\Testing\RefreshDatabase;
use RezuanKassim\BQAnalytic\Actions\GetClient;
use RezuanKassim\BQAnalytic\Actions\GetPeriod;
use RezuanKassim\BQAnalytic\Tests\TestCase;

class BQAnalyticTest extends TestCase
{
    use RefreshDatabase;

    public function test_retrieve_client_from_config()
    {
        config()->set('bqanalytic.client_from_db', false);
        config()->set('bqanalytic.google.accounts', [
            [
                'name' => 'testing',
                'google_credential' => 'testing_credential',
                'google_project_id' => 'testing_project_id',
                'google_bq_dataset_name' => 'testing_bq_dataset_name'
            ]
        ]);

        $accounts = (new GetClient())->execute(config('bqanalytic.client_from_db'));
        
        $this->assertContains([
            'name' => 'testing',
            'google_credential' => 'testing_credential',
            'google_project_id' => 'testing_project_id',
            'google_bq_dataset_name' => 'testing_bq_dataset_name'
        ], $accounts);
    }

    public function test_get_period_from_command_while_table_record_is_empty()
    {
        // $this->assertTrue(true);
        // $period = (new GetPeriod('Test', '20200402', '20200404'))->execute();

        // dd($period);
    }
}
