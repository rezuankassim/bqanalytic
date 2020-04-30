<?php

namespace RezuanKassim\BQAnalytic\Traits\BQUser;

trait hasAnalyticPreferences
{
    public function bqanalyticpreferences()
    {
        return $this->hasMany(config('bqanalytic.models.preferences.class'), config('bqanalytic.models.user.fk'));
    }
}
