<?php

namespace RezuanKassim\BQAnalytic\Traits;

trait hasAnalyticPreferences
{
    public function analytic()
    {
        return $this->belongsToMany(
            config('bqanalytic.analytic'),
            'analytic_user',
            'user_id',
            'analytic_id'
        )->withPivot('client_name');
    }
}
