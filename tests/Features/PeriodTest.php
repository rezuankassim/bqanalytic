<?php

namespace RezuanKassim\BQAnalytic\Tests\Features;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use RezuanKassim\BQAnalytic\Actions\GetPeriod;
use RezuanKassim\BQAnalytic\BQClient;
use RezuanKassim\BQAnalytic\BQTable;
use RezuanKassim\BQAnalytic\Tests\TestCase;

class PeriodTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_generates_an_array_of_dates_between_one_date()
    {
        $client = factory(BQClient::class)->create();
        $period = (new GetPeriod($client->name, '20200429'))->execute();

        $this->assertCount(1, $period);
        $this->assertContainsEquals(Carbon::createFromFormat('Ymd', '20200429'), $period);
    }

    /** @test */
    public function it_generates_an_array_of_dates_between_start_date_and_end_date()
    {
        $client = factory(BQClient::class)->create();
        $period = (new GetPeriod($client->name, '20200429', '20200501'))->execute();

        $this->assertCount(3, $period);
        $this->assertEquals(collect([
            Carbon::createFromFormat('Ymd', '20200429'),
            Carbon::createFromFormat('Ymd', '20200430'),
            Carbon::createFromFormat('Ymd', '20200501'),
        ]), $period);
    }

    /** @test */
    public function it_generates_an_array_of_dates_without_bqtable_existed_date()
    {
        $client = factory(BQClient::class)->create();
        $bqtable = factory(BQTable::class)->create([
            'table_date' => '2020-04-29',
            'status' => 1,
            'dataset' => $client->name
        ]);

        $period = (new GetPeriod($client->name, '20200429', '20200430'))->execute();

        $this->assertCount(1, $period);
        $this->assertContainsEquals(Carbon::createFromFormat('Ymd', '20200430'), $period);
    }

    /** @test */
    public function it_generates_an_array_of_dates_if_start_date_not_given()
    {
        $client = factory(BQClient::class)->create();
        $bqtable = factory(BQTable::class)->create([
            'table_date' => Carbon::yesterday()->subDay()->format('Y-m-d'),
            'status' => 0,
            'dataset' => $client->name
        ]);
        $period = (new GetPeriod($client->name))->execute();

        $this->assertCount(2, $period);
        $this->assertContainsEquals(Carbon::yesterday()->subDay(), $period);
        $this->assertContainsEquals(Carbon::yesterday(), $period);
    }
}
