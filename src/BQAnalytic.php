<?php

namespace RezuanKassim\BQAnalytic;

use Illuminate\Support\Facades\DB;
use PragmaRX\Countries\Package\Countries;

class BQAnalytic
{
    private $start_date;
    private $end_date;
    private $analytic;
    private $client;
    private $subclient;

    public function __construct($user, $start_date, $end_date, $client = null, $subclient = null, $filterableType = null, $filterableId = null)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->analytic = $user->analyticPreferences()->where('filterable_type', $filterableType)->where('filterable_id', $filterableId)->get()->pluck('analytic') ?? collect([]);
        $this->client = $client;
        $this->subclient = $subclient;
    }

    public function setOption(array $options)
    {
        $this->start_date = $options['start_date'];
        $this->end_date = $options['end_date'];
        $this->client = $options['client'];
    }

    public function getAllAnalytics()
    {
        $results = [];

        $accounts = $this->getClients();

        foreach ($accounts as $account) {
            $results[$account['name']] = [];

            if ($this->analytic->contains('name', 'get active users')) {
                $results[$account['name']]['activeUsers'] = $this->getActiveUsers($account['google_bq_dataset_name']);
            }

            if ($this->analytic->contains('name', 'get new users')) {
                $results[$account['name']]['newUsers'] = $this->getNewUsers($account['google_bq_dataset_name']);
            }

            if ($this->analytic->contains('name', 'get active users by platform')) {
                $results[$account['name']]['activeUsersByPlatform'] = $this->getActiveUsersByPlatform($account['google_bq_dataset_name']);
            }

            if ($this->analytic->contains('name', 'get all event name with event count')) {
                $results[$account['name']]['allEventWithEventCount'] = $this->getAllEventWithEventCount($account['google_bq_dataset_name']);
            }

            if ($this->analytic->contains('name', 'get users by country')) {
                $results[$account['name']]['usersByCountry'] = $this->getUsersByCountry($account['google_bq_dataset_name']);
            }

            if ($this->analytic->contains('name', 'get total event count by event name')) {
                $results[$account['name']]['totalEventCountByEventName'] = $this->getTotalEventCountByEventName($account['google_bq_dataset_name']);
            }

            if ($this->analytic->contains('name', 'get total event count by users')) {
                $results[$account['name']]['totalEventCountByUsers'] = $this->getTotalEventCountByUsers($account['google_bq_dataset_name']);
            }
        }

        return $results;
    }

    private function getClients()
    {
        if (config('bqanalytic.client_from_db')) {
            $accounts = config('bqanalytic.client')::where('name', $this->client)->where('status', 1)->get()->toArray();
        } else {
            $accounts = config('bqanalytic.google.accounts');
        }

        return $accounts;
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
            ->where('dataset', $dataset);

        if ($this->subclient) {
            $results = $results->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(app_info, '$.id')) = ?", $this->subclient);
        }

        return $results->get()->toArray()[0];
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
            ->groupBy('date')->orderBy('date');

        if ($this->subclient) {
            $results = $results->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(app_info, '$.id')) = ?", $this->subclient);
        }

        return $results->get()->toArray();
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
            ->groupBy('date')->orderBy('date');

        if ($this->subclient) {
            $results = $results->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(app_info, '$.id')) = ?", [$this->subclient]);
        }


        return $results->get()->toArray();
    }

    private function getAllEventWithEventCount($dataset)
    {
        $results = config('bqanalytic.bigquery')::query()
            ->select(DB::raw('event_name, count(distinct user_pseudo_id) as event_count'))
            ->where('dataset', $dataset)
            ->whereBetween('event_date', [$this->start_date, $this->end_date])->groupBy('event_name');

        if ($this->subclient) {
            $results = $results->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(app_info, '$.id')) = ?", $this->subclient);
        }

        return $results->get()->toArray();
    }

    private function getUsersByCountry($dataset)
    {
        $countries = (new Countries())->all();
        $endResults = collect();

        $results = config('bqanalytic.bigquery')::query()
            ->select(DB::raw("count(distinct user_pseudo_id) as user_count, JSON_UNQUOTE(JSON_EXTRACT(geo, '$.country')) as country"))
            ->where('dataset', $dataset)
            ->whereBetween('event_date', [$this->start_date, $this->end_date])->groupBy('country');

        if ($this->subclient) {
            $results = $results->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(app_info, '$.id')) = ?", $this->subclient);
        }

        $results = $results->get()->toArray();

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
            ->whereBetween('event_date', [$this->start_date, $this->end_date])->groupBy('event_name');

        if ($this->subclient) {
            $results = $results->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(app_info, '$.id')) = ?", $this->subclient);
        }

        return $results->get()->toArray();
    }

    private function getTotalEventCountByUsers($dataset)
    {
        $results = config('bqanalytic.bigquery')::query()
            ->select(DB::raw('event_name, COUNT(DISTINCT user_pseudo_id) as event_count'))
            ->where('dataset', $dataset)
            ->whereBetween('event_date', [$this->start_date, $this->end_date])->groupBy('event_name');

        if ($this->subclient) {
            $results = $results->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(app_info, '$.id')) = ?", $this->subclient);
        }

        return $results->get()->toArray();
    }
}
