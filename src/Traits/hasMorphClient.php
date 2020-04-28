<?php

namespace RezuanKassim\BQAnalytic\Traits;

use Illuminate\Support\Facades\File;

trait hasMorphClient
{
    public function analyticPreferences()
    {
        return $this->morphMany(config('bqanalytic.analyticPreferences'), 'filterable');
    }
}