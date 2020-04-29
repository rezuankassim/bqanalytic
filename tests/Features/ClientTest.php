<?php

namespace RezuanKassim\BQAnalytic\Tests\Features;

use Illuminate\Foundation\Testing\RefreshDatabase;
use RezuanKassim\BQAnalytic\Actions\GetClient;
use RezuanKassim\BQAnalytic\Tests\TestCase;

class ClientTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
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
}
