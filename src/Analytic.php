<?php

namespace RezuanKassim\BQAnalytic;

use Illuminate\Database\Eloquent\Model;

class Analytic extends Model
{
    protected $guarded = [];

    public function analyticUser()
    {
        return $this->hasMany(config('bqanalytic.analyticPreferences'));
    }
}
