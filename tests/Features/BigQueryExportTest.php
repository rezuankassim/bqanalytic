<?php

namespace RezuanKassim\BQAnalytic\Tests\Features;

use Illuminate\Foundation\Testing\RefreshDatabase;
use RezuanKassim\BQAnalytic\Tests\TestCase;

class BigQueryExportTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_import_data_from_big_query_to_local_database()
    {
        $this->artisan('bqanalytic:export');
    }
}