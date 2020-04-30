<?php

namespace RezuanKassim\BQAnalytic\Traits\BQApp;

trait hasBQApp
{
    public function bqclient()
    {
        return $this->belongsTo(config('bqanalytic.models.client.class'), config('bqanalytic.models.client.fk'));
    }

    public function bqproject()
    {
        return $this->belongsTo(config('bqanalytic.models.project.class'), config('bqanalytic.models.project.fk'));
    }

    public function bqanalyticpreferences()
    {
        return $this->hasMany(config('bqanalytic.models.preference.class'), config('bqanalytic.models.app.fk'));
    }
}
