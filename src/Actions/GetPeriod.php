<?php

namespace RezuanKassim\BQAnalytic\Actions;

use Carbon\Carbon;
use RezuanKassim\BQAnalytic\Models\BQProject;
use RezuanKassim\BQAnalytic\Models\BQTable;

class GetPeriod
{
    private $startDate;
    private $endData;
    private $project;

    public function __construct($project, $startDate = null, $endDate = null)
    {
        $this->project = $project;
        $this->startDate = $startDate;

        if ($endDate) {
            $this->endDate = $endDate;
        } else {
            $this->endDate = $startDate;
        }
    }

    public function execute()
    {
        $dates = collect();

        if ($this->project['start_date']) {
            if ($this->project['start_date'] instanceof \Illuminate\Support\Carbon) {
                $this->startDate = $this->project['start_date']->startOfDay()->format('Ymd');
            } else {
                $this->startDate = $this->project['start_date'];
            }

            $this->endDate = Carbon::today()->format('Ymd');
        }

        if (!$this->startDate) {
            if (
                BQTable::where('table_date', Carbon::yesterday()->format('Y-m-d'))->where('bqproject_name', $this->project['name'])->count() == 0
            ) {
                $dates->push(Carbon::yesterday()->startOfDay());
            }

            foreach (
                BQTable::where('bqproject_name', $this->project['name'])->where('status', 0)->get() as $failed_dates
            ) {
                $dates->push($failed_dates->table_date->startOfDay());
            }
        } else {
            $startDate = Carbon::createFromFormat('Ymd', $this->startDate)->startOfDay();
            $endDate = Carbon::createFromFormat('Ymd', $this->endDate)->startOfDay();

            while ($startDate <= $endDate) {
                $dates->push($startDate);
                $startDate = Carbon::createFromFormat('Ymd', $startDate->format('Ymd'))->startOfDay()->addDay();
            }
        }

        return $dates->filter(function ($date) {
            return BQTable::where('table_date', $date->format('Y-m-d'))->where('bqproject_name', $this->project['name'])->where('status', 1)->count() == 0;
        })->sort();
    }
}
