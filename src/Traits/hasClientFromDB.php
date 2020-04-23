<?php

namespace RezuanKassim\BQAnalytic\Traits;

use Illuminate\Support\Facades\File;

trait hasClientFromDB
{
    protected static function booted()
    {
        static::deleted(function ($client) {
            if (File::exists(public_path('storage/'.$client->google_credential))) {
                File::delete(public_path('storage/'.$client->google_credential));
            }
        });
    }
}