<?php

namespace RezuanKassim\BQAnalytic\Models;

use Illuminate\Database\Eloquent\Model;

class BQAnalyticPreference extends Model
{
    protected $table = "bqanalyticpreferences";

    protected $guarded = [];

    public $timestamps = false;

    public function bqanalytic()
    {
        return $this->belongsTo(BQAnalytic::class, 'bqanalytic_id');
    }

    public function user()
    {
        return $this->belongsTo(config('bqanalytic.user'));
    }

    public function bqapp()
    {
        return $this->belongsTo(BQApp::class);
    }
}
