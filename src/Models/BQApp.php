<?php 

namespace RezuanKassim\BQAnalytic\Models;

use Illuminate\Database\Eloquent\Model;

class BQApp extends Model
{
    protected $table = 'bqapps';

    protected $guarded = [];

    protected $casts = [
        'bundles' => 'array'
    ];

    public function bqclient()
    {
        return $this->belongsTo(BQClient::class, 'bqclient_id');
    }

    public function bqproject()
    {
        return $this->belongsTo(BQProject::class, 'bqproject_id');
    }

    public function bqanalyticpreferences()
    {
        return $this->hasMany(BQAnalyticPreference::class, 'bqapp_id');
    }
}