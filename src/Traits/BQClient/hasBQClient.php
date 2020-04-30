<?php

namespace RezuanKassim\BQAnalytic\Traits\BQClient;


trait hasBQClient
{
    public function bqapps()
    {
        return $this->hasMany(config('bqanalytic.models.app.class'), config('bqanalytic.models.client.fk'));
    }
}
