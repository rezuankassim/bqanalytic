<?php

namespace RezuanKassim\BQAnalytic\Traits;

use RezuanKassim\BQAnalytic\Models\BQAnalyticPreference;

trait hasAnalyticPreferences
{
    public function bqanalyticpreferences()
    {
        return $this->hasMany(BQAnalyticPreference::class, 'user_id');
    }
}
