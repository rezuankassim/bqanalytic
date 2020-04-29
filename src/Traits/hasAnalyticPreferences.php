<?php

namespace RezuanKassim\BQAnalytic\Traits;

trait hasAnalyticPreferences
{
    public function analyticPreferences()
    {
        return $this->hasMany(config('bqanalytic.analyticPreferences'));
    }
}
