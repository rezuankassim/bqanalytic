<?php

namespace RezuanKassim\BQAnalytic\Traits;

use Illuminate\Support\Facades\File;

trait hasClientFromDB
{
    protected $table = 'bq_clients';
    
    protected $guarded = ['created_at', 'updated_at', 'id'];

    protected $casts = [
        'status' => 'boolean'
    ];

    protected static function booted()
    {
        static::deleted(function ($client) {
            if (File::exists(public_path('storage/'.$client->google_credential))) {
                File::delete(public_path('storage/'.$client->google_credential));
            }
        });
    }
}