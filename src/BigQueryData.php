<?php

namespace RezuanKassim\BQAnalytic;

use Illuminate\Database\Eloquent\Model;

class BigQueryData extends Model
{
    protected $guarded = [];

    protected $casts = [
        'event_date' => 'date:Ymd',
        'event_timestamp' => 'timestamp',
        'event_previous_timestamp' => 'timestamp',
        'user_first_touch_timestamp' => 'timestamp',
        'event_params' => 'array',
        'user_properties' => 'array',
        'user_ltv' => 'array',
        'device' => 'array',
        'geo' => 'array',
        'app_info' => 'array',
        'traffic_source' => 'array',
        'event_dimensions' => 'array',
        'ecommerce' => 'array',
        'items' => 'array'
    ];

    public $timestamps = false;
}
