<?php

namespace RezuanKassim\BQAnalytic\Facades;

use Illuminate\Support\Facades\Facade;

class BQAnalytic extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'bqanalytic';
    }
}
