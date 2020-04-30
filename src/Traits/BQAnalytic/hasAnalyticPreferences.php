<?php

namespace RezuanKassim\BQAnalytic\Traits\BQAnalytic;

trait hasAnalyticPreferences
{
    public function bqanalyticpreferences()
    {
        return $this->hasMany(config('bqanalytic.models.preferences.class'), config('bqanalytic.models.analytic.fk'));
    }
}