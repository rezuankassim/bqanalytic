<?php

namespace RezuanKassim\BQAnalytic;

use Illuminate\Database\Eloquent\Model;

class BQSubclient extends Model
{
    protected $table = 'bq_subclients';

    protected $guarded = [];

    protected $casts = [
        'status' => 'boolean'
    ];

    public function client()
    {
        return $this->belongsTo(config('bqanalytic.client'), 'client_id');
    }

    public function analyticPreferences()
    {
        return $this->morphMany(config('bqanalytic.analyticPreferences'), 'filterable');
    }
}
