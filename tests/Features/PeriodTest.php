<?php

namespace RezuanKassim\BQAnalytic\Tests\Features;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Foundation\Testing\RefreshDatabase;
use RezuanKassim\BQAnalytic\Actions\GetPeriod;
// use RezuanKassim\BQAnalytic\BQTable;
use RezuanKassim\BQAnalytic\Models\BQProject;
use RezuanKassim\BQAnalytic\Models\BQTable;
use RezuanKassim\BQAnalytic\Tests\TestCase;

class PeriodTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_generates_an_array_of_dates_between_one_date()
    {
        $project = factory(BQProject::class)->create();
        $period = (new GetPeriod($project, Carbon::today()->format('Ymd')))->execute();

        $this->assertCount(1, $period);
        $this->assertContainsEquals(Carbon::createFromFormat('Ymd', '20200429'), $period);
    }

    /** @test */
    public function it_generates_an_array_of_dates_between_start_date_and_end_date()
    {
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();
        $dayAfterTomorrow = Carbon::tomorrow()->addDay();

        $project = factory(BQProject::class)->create();
        $period = (new GetPeriod($project, $today->format('Ymd'), $dayAfterTomorrow->format('Ymd')))->execute();

        $this->assertCount(3, $period);
        $this->assertEquals(collect([
            Carbon::createFromFormat('Ymd', $today->format('Ymd')),
            Carbon::createFromFormat('Ymd', $tomorrow->format('Ymd')),
            Carbon::createFromFormat('Ymd', $dayAfterTomorrow->format('Ymd')),
        ]), $period);
    }

    /** @test */
    public function it_generates_an_array_of_dates_with_bqtable_existed_date()
    {
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();

        $project = factory(BQProject::class)->create();
        $bqtable = factory(BQTable::class)->create([
            'table_date' => $today->format('Y-m-d'),
            'status' => 1,
            'bqproject_id' => $project->id
        ]);

        $period = (new GetPeriod($project, $today->format('Ymd'), $tomorrow->format('Ymd')))->execute();

        $this->assertCount(1, $period);
        $this->assertContainsEquals(Carbon::createFromFormat('Ymd', $tomorrow->format('Ymd')), $period);
    }

    /** @test */
    public function it_generates_an_array_of_dates_if_start_date_not_given()
    {
        $project = factory(BQProject::class)->create();
        $bqtable = factory(BQTable::class)->create([
            'table_date' => Carbon::yesterday()->subDay()->format('Y-m-d'),
            'status' => 0,
            'bqproject_id' => $project->id
        ]);
        $period = (new GetPeriod($project))->execute();

        $this->assertCount(2, $period);
        $this->assertContainsEquals(Carbon::yesterday()->subDay(), $period);
        $this->assertContainsEquals(Carbon::yesterday(), $period);
    }

    /** @test */
    public function it_generates_an_array_of_dates_with_start_date_not_first_time_from_project()
    {
        $project = factory(BQProject::class)->create();
        $period = (new GetPeriod($project))->execute();
        $carbonPeriod = CarbonPeriod::create($project->start_date, Carbon::now());

        $this->assertCount($carbonPeriod->count(), $period);
        $this->assertEquals($carbonPeriod->first(), $period->first());
        $this->assertEquals($carbonPeriod->last(), $period->last());
    }

    /** @test */
    public function it_generates_an_array_of_dates_with_start_date_first_time_project()
    {
        $project = factory(BQProject::class)->create();
        $period = (new GetPeriod($project, null, null, true))->execute();
        $carbonPeriod = CarbonPeriod::create($project->start_date, Carbon::now());

        $this->assertCount($carbonPeriod->count(), $period);
        $this->assertEquals($carbonPeriod->first(), $period->first());
        $this->assertEquals($carbonPeriod->last(), $period->last());
    }
}
