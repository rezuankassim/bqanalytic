<?php

namespace RezuanKassim\BQAnalytic\Traits\BQAnalyticPreference;

trait hasAnalyticPreferences
{
    public function bqanalytic()
    {
        return $this->belongsTo(config('bqanalytic.models.analytic.class'), config('bqanalytic.models.analytic.fk'));
    }

    public function user()
    {
        return $this->belongsTo(config('bqanalytic.models.user.class'), config('bqanalytic.models.user.fk'));
    }

    public function bqapp()
    {
        return $this->belongsTo(config('bqanalytic.models.app.class'), config('bqanalytic.models.app.fk'));
    }
}
