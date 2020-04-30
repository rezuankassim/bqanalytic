<?php

namespace RezuanKassim\BQAnalytic\Tests\Features;

use Illuminate\Foundation\Testing\RefreshDatabase;
use RezuanKassim\BQAnalytic\Actions\GetProject;
use RezuanKassim\BQAnalytic\Models\BQProject;
use RezuanKassim\BQAnalytic\Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_retrieve_projects_from_config()
    {
        config()->set('bqanalytic.project_from_db', false);
        config()->set('bqanalytic.google.accounts', [
            [
                'name' => 'testing',
                'google_credential' => 'testing_credential',
                'google_project_id' => 'testing_project_id',
                'google_bq_dataset_name' => 'testing_bq_dataset_name'
            ]
        ]);

        $accounts = (new GetProject())->execute(config('bqanalytic.project_from_db'));

        $this->assertContains([
            'name' => 'testing',
            'google_credential' => 'testing_credential',
            'google_project_id' => 'testing_project_id',
            'google_bq_dataset_name' => 'testing_bq_dataset_name'
        ], $accounts);
    }

    /** @test */
    public function it_can_retrieve_project_from_database()
    {
        config()->set('bqanalytic.project_from_db', true);
        $project = factory(BQProject::class)->create()->toArray();

        $accounts = (new GetProject())->execute(config('bqanalytic.project_from_db'));

        $this->assertCount(1, $accounts);
        $this->assertContains($project, $accounts);
    }
}
