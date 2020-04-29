<?php

namespace RezuanKassim\BQAnalytic\Actions;

use Carbon\Carbon;
use RezuanKassim\BQAnalytic\BQTable;

class GetPeriod
{
    private $startDate;
    private $endData;
    private $clientName;

    public function __construct($clientName, $startDate = null, $endDate = null)
    {
        $this->clientName = $clientName;
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

        if (!$this->startDate) {
            if (BQTable::where('table_date', Carbon::yesterday()->format('Y-m-d'))->where('dataset', $this->clientName)->count() == 0) {
                $dates->push(Carbon::yesterday());
            }

            foreach (BQTable::where('dataset', $this->clientName)->where('status', 0)->get() as $failed_dates) {
                $dates->push($failed_dates->table_date);
            }
        } else {
            $startDate = Carbon::createFromFormat('Ymd', $this->startDate);
            $endDate = Carbon::createFromFormat('Ymd', $this->endDate);

            while ($startDate <= $endDate) {
                $dates->push($startDate);
                $startDate = Carbon::parse($startDate)->addDay();
            }
        }

        return $dates->filter(function ($date) {
            return BQTable::where('table_date', $date->format('Y-m-d'))->where('dataset', $this->clientName)->where('status', 1)->count() == 0;
        })->sort();
    }
}
