<?php

namespace RezuanKassim\BQAnalytic\Actions;

use Carbon\Carbon;
use RezuanKassim\BQAnalytic\Models\BQProject;
use RezuanKassim\BQAnalytic\Models\BQTable;

class GetPeriod
{
    protected $startDate;
    protected $endData;
    protected $project;
    protected $first;

    public function __construct($project, $startDate = null, $endDate = null, $first = false)
    {
        $this->project = $project;
        $this->startDate = $startDate;

        if ($endDate) {
            $this->endDate = $endDate;
        } else {
            $this->endDate = $startDate;
        }

        $this->first = $first;
    }

    public function execute()
    {
        $dates = collect();

        if ($this->first && $this->project['start_date']) {
            $this->startDate = Carbon::parse($this->project['start_date'])->startOfDay()->format('Ymd');
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
