<?php

namespace RezuanKassim\BQAnalytic\Traits\BQProject;

trait hasApps
{
    public function bqapps()
    {
        return $this->hasMany(config('bqanalytic.models.app.class'), config('bqanalytic.models.project.fk'));
    }
}
