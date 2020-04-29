<?php

namespace RezuanKassim\BQAnalytic\Traits;

trait hasSubclient
{
    public function subclient()
    {
        return $this->hasMany(config('bqanalytic.subclient'));
    }
}
