<?php

namespace RezuanKassim\BQAnalytic\Exceptions;

use Exception;

class InvalidPeriod extends Exception
{
    public static function periodIsInvalid()
    {
        return new static("Period should be in format of 'YYYYMMDD'");
    }
}
