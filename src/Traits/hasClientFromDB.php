<?php

namespace RezuanKassim\BQAnalytic\Traits;

use Illuminate\Support\Facades\File;

trait hasClientFromDB
{
    protected static function booted()
    {
        static::deleted(function ($client) {
            if (File::exists(storage_path('app/' . $client->google_credential))) {
                File::delete(storage_path('app/' . $client->google_credential));
            }
        });
    }
}
