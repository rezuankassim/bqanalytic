<?php

namespace RezuanKassim\BQAnalytic\Exceptions;

use Exception;

class InvalidCredential extends Exception
{
    public static function credentialsJsonDoesNotExist($path)
    {
        return new static("Could not find a credentials file at `{$path}`.");
    }
}
