<?php

namespace RezuanKassim\BQAnalytic;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class BQClient extends Model
{
    protected $table = 'bq_clients';

    protected $guarded = ['created_at', 'updated_at', 'id'];

    protected $casts = [
        'status' => 'boolean'
    ];

    protected static function booted()
    {
        static::deleted(function ($client) {
            if (File::exists(storage_path('app/' . $client->google_credential))) {
                File::delete(storage_path('app/' . $client->google_credential));
            }
        });
    }

    public function subclients()
    {
        return $this->hasMany(config('bqanalytic.subclient'), 'client_id');
    }

    public function analyticPreferences()
    {
        return $this->morphMany(config('bqanalytic.analyticPreferences'), 'filterable');
    }
}
