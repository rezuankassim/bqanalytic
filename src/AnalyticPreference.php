<?php

namespace RezuanKassim\BQAnalytic;

use Illuminate\Database\Eloquent\Model;

class AnalyticPreference extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public function analytic()
    {
        return $this->belongsTo(config('bqanalytic.analytic'));
    }

    public function user()
    {
        return $this->belongsTo(config('bqanalytic.user'));
    }

    public function filterable()
    {
        return $this->morphTo();
    }
}
