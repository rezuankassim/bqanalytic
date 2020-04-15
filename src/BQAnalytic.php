<?php

namespace RezuanKassim\BQAnalytic;

use Illuminate\Support\Facades\DB;
use SchulzeFelix\BigQuery\BigQueryFacade as BigQuery;
use PragmaRX\Countries\Package\Countries;

class BQAnalytic
{
    private $start_date;
    private $end_date;
    private $analytic;

    public function __construct($user, $start_date, $end_date)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->analytic = $user->analytic ?? collect([]);
    }

    public function getAllAnalytics()
    {
        $results = [];
        
        if ($this->analytic->contains('name', 'get active users')) {
            $results['activeUsers'] = $this->getActiveUsers();
        }

        if ($this->analytic->contains('name', 'get new users')) {
            $results['newUsers'] = $this->getNewUsers();
        }

        if ($this->analytic->contains('name', 'get active users by platform')) {
            $results['activeUsersByPlatform'] = $this->getActiveUsersByPlatform();
        }

        if ($this->analytic->contains('name', 'get all event name with event count')) {
            $results['allEventWithEventCount'] = $this->getAllEventWithEventCount();
        }

        if ($this->analytic->contains('name', 'get users by country')) {
            $results['usersByCountry'] = $this->getUsersByCountry();
        }

        if ($this->analytic->contains('name', 'get total event count by event name')) {
            $results['totalEventCountByEventName'] = $this->getTotalEventCountByEventName();
        }

        if ($this->analytic->contains('name', 'get total event count by users')) {
            $results['totalEventCountByUsers'] = $this->getTotalEventCountByUsers();
        }


        return $results;
    }

    private function getActiveUsers()
    {
        $results = config('bqanalytic.bigquery')::query()
            ->select(DB::raw("COUNT(DISTINCT
                                (CASE
                                    WHEN event_name = 'user_engagement' THEN user_pseudo_id
                                    ELSE NULL
                                END)) AS active_user_count,
                            COUNT(DISTINCT
                                (CASE
                                    WHEN JSON_UNQUOTE(JSON_EXTRACT(device, '$.category')) = 'mobile' AND event_name = 'user_engagement' THEN user_pseudo_id
                                    ELSE NULL
                                END)) AS active_mobile_user_count,
                            COUNT(DISTINCT
                                (CASE
                                    WHEN JSON_UNQUOTE(JSON_EXTRACT(device, '$.category')) = 'tablet' AND event_name = 'user_engagement' THEN user_pseudo_id
                                    ELSE NULL
                                END)) as active_tablet_user_count,
                            COUNT(DISTINCT
                                (CASE 
                                    WHEN JSON_UNQUOTE(JSON_EXTRACT(device, '$.category')) = 'desktop' AND event_name = 'user_engagement' THEN user_pseudo_id
                                    ELSE NULL
                                END)) AS active_desktop_user_count"))
            ->whereBetween('event_date', [$this->start_date, $this->end_date])
            ->get()->toArray()[0];

        return $results;
    }

    private function getNewUsers()
    {
        $results = config('bqanalytic.bigquery')::query()
            ->select(DB::raw("DATE_FORMAT(STR_TO_DATE(event_date, '%Y%m%d'), '%d/%m/%Y') as date,
                            COUNT(DISTINCT (CASE
                                    WHEN event_name='first_open' THEN user_pseudo_id
                                    ELSE NULL
                                    END)) AS new_user_count"))
            ->whereBetween('event_date', [$this->start_date, $this->end_date])
            ->groupBy('date')->orderBy('date')->get()->toArray();

        return $results;
    }

    private function getActiveUsersByPlatform()
    {  
        $results = config('bqanalytic.bigquery')::query()
            ->select(DB::raw("DATE_FORMAT(STR_TO_DATE(event_date, '%Y%m%d'), '%d/%m/%Y') as date, 
                            COUNT(DISTINCT (CASE
                                    WHEN platform = 'IOS' THEN user_pseudo_id
                                    ELSE NULL
                                    END)) AS ios_platform,
                            COUNT(DISTINCT (CASE
                                    WHEN platform = 'ANDROID' THEN user_pseudo_id
                                    ELSE NULL
                                    END)) AS android_platform,
                            COUNT(DISTINCT (CASE
                                    WHEN platform != 'ANDROID' AND platform != 'IOS' THEN user_pseudo_id
                                    ELSE NULL
                                    END)) as other_platform"))
            ->whereBetween('event_date', [$this->start_date, $this->end_date])
            ->groupBy('date')->orderBy('date')->get()->toArray();

        return $results;
    }

    private function getAllEventWithEventCount()
    {
        $results = config('bqanalytic.bigquery')::query()
            ->select(DB::raw('event_name, count(distinct user_pseudo_id) as event_count'))
            ->whereBetween('event_date', [$this->start_date, $this->end_date])->groupBy('event_name')->get()->toArray();

        return $results;
    }

    private function getUsersByCountry()
    {
        $countries = (new Countries())->all();
        $endResults = collect();

        $results = config('bqanalytic.bigquery')::query()
            ->select(DB::raw("count(distinct user_pseudo_id) as user_count, JSON_UNQUOTE(JSON_EXTRACT(geo, '$.country')) as country"))
            ->whereBetween('event_date', [$this->start_date, $this->end_date])->groupBy('country')->get()->toArray();

        $results = collect($results)->filter(function ($value) {
            return $value['country'] != null;
        })->map(function ($value) use ($countries, $endResults) {
            $endResults[$countries->where('name.common', $value['country'])->first()->cca2] = $value['user_count'];
        });

        $results = $endResults->toArray();

        return $results;
    }

    private function getTotalEventCountByEventName()
    {
        $results = config('bqanalytic.bigquery')::query()
            ->select(DB::raw('event_name, COUNT(event_name) as event_count'))
            ->whereBetween('event_date', [$this->start_date, $this->end_date])->groupBy('event_name')->get()->toArray();

        return $results;
    }

    private function getTotalEventCountByUsers()
    {
        $results = config('bqanalytic.bigquery')::query()
            ->select(DB::raw('event_name, COUNT(DISTINCT user_pseudo_id) as event_count'))
            ->whereBetween('event_date', [$this->start_date, $this->end_date])->groupBy('event_name')->get()->toArray();

        return $results;
    }
}