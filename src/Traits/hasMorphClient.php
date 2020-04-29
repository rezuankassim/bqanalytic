<?php

namespace RezuanKassim\BQAnalytic\Traits;

trait hasMorphClient
{
    public function analyticPreferences()
    {
        return $this->morphMany(config('bqanalytic.analyticPreferences'), 'filterable');
    }
}
