<?php

namespace RezuanKassim\BQAnalytic;

use Illuminate\Database\Eloquent\Model;

class Analytic extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsToMany(
            config('bqanalytics.user'), 
            config('bqanalytics.analytic_user_table'),
            'analytic_id',
            'user_id');
    }
}
