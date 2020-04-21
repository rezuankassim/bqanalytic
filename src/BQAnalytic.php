<?php

namespace RezuanKassim\BQAnalytic;

use Illuminate\Support\Facades\DB;
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

        if (config('bqanalytic.multiple_project')) {
            $accounts = config('bqanalytic.google.accounts');
        } else {
            $accounts = collect(config('bqanalytic.google.accounts'))->filter(function ($account, $key) {
                return $key == 0;
            })->toArray();
        }

        foreach ($accounts as $account) {
            if ($this->analytic->contains('name', 'get active users')) {
                $results[$account['name']]['activeUsers'] = $this->getActiveUsers($account['dataset']);
            }

            if ($this->analytic->contains('name', 'get new users')) {
                $results[$account['name']]['newUsers'] = $this->getNewUsers($account['dataset']);
            }

            if ($this->analytic->contains('name', 'get active users by platform')) {
                $results[$account['name']]['activeUsersByPlatform'] = $this->getActiveUsersByPlatform($account['dataset']);
            }

            if ($this->analytic->contains('name', 'get all event name with event count')) {
                $results[$account['name']]['allEventWithEventCount'] = $this->getAllEventWithEventCount($account['dataset']);
            }

            if ($this->analytic->contains('name', 'get users by country')) {
                $results[$account['name']]['usersByCountry'] = $this->getUsersByCountry($account['dataset']);
            }

            if ($this->analytic->contains('name', 'get total event count by event name')) {
                $results[$account['name']]['totalEventCountByEventName'] = $this->getTotalEventCountByEventName($account['dataset']);
            }

            if ($this->analytic->contains('name', 'get total event count by users')) {
                $results[$account['name']]['totalEventCountByUsers'] = $this->getTotalEventCountByUsers($account['dataset']);
            }
        }

        return $results;
    }

    private function getActiveUsers($dataset)
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
            ->where('dataset', $dataset)
            ->get()->toArray()[0];

        return $results;
    }

    private function getNewUsers($dataset)
    {
        $results = config('bqanalytic.bigquery')::query()
            ->select(DB::raw("DATE_FORMAT(STR_TO_DATE(event_date, '%Y%m%d'), '%d/%m/%Y') as date,
                            COUNT(DISTINCT (CASE
                                    WHEN event_name='first_open' THEN user_pseudo_id
                                    ELSE NULL
                                    END)) AS new_user_count"))
            ->whereBetween('event_date', [$this->start_date, $this->end_date])
            ->where('dataset', $dataset)
            ->groupBy('date')->orderBy('date')->get()->toArray();

        return $results;
    }

    private function getActiveUsersByPlatform($dataset)
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
            ->where('dataset', $dataset)
            ->groupBy('date')->orderBy('date')->get()->toArray();

        return $results;
    }

    private function getAllEventWithEventCount($dataset)
    {
        $results = config('bqanalytic.bigquery')::query()
            ->select(DB::raw('event_name, count(distinct user_pseudo_id) as event_count'))
            ->where('dataset', $dataset)
            ->whereBetween('event_date', [$this->start_date, $this->end_date])->groupBy('event_name')->get()->toArray();

        return $results;
    }

    private function getUsersByCountry($dataset)
    {
        $countries = (new Countries())->all();
        $endResults = collect();

        $results = config('bqanalytic.bigquery')::query()
            ->select(DB::raw("count(distinct user_pseudo_id) as user_count, JSON_UNQUOTE(JSON_EXTRACT(geo, '$.country')) as country"))
            ->where('dataset', $dataset)
            ->whereBetween('event_date', [$this->start_date, $this->end_date])->groupBy('country')->get()->toArray();

        $results = collect($results)->filter(function ($value) {
            return $value['country'] != null;
        })->map(function ($value) use ($countries, $endResults) {
            $endResults[$countries->where('name.common', $value['country'])->first()->cca2] = $value['user_count'];
        });

        $results = $endResults->toArray();

        return $results;
    }

    private function getTotalEventCountByEventName($dataset)
    {
        $results = config('bqanalytic.bigquery')::query()
            ->select(DB::raw('event_name, COUNT(event_name) as event_count'))
            ->where('dataset', $dataset)
            ->whereBetween('event_date', [$this->start_date, $this->end_date])->groupBy('event_name')->get()->toArray();

        return $results;
    }

    private function getTotalEventCountByUsers($dataset)
    {
        $results = config('bqanalytic.bigquery')::query()
            ->select(DB::raw('event_name, COUNT(DISTINCT user_pseudo_id) as event_count'))
            ->where('dataset', $dataset)
            ->whereBetween('event_date', [$this->start_date, $this->end_date])->groupBy('event_name')->get()->toArray();

        return $results;
    }
}
