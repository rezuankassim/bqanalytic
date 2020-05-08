<?php

namespace RezuanKassim\BQAnalytic\Models;

use Illuminate\Database\Eloquent\Model;

class BQAnalytic extends Model
{
    protected $table = 'bqanalytics';

    protected $guarded = [];

    public function bqanalyticpreferences()
    {
        return $this->hasMany(BQAnalyticPreference::class, 'bqanalytic_id');
    }
}
