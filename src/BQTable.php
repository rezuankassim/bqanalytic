<?php

namespace RezuanKassim\BQAnalytic;

use Illuminate\Database\Eloquent\Model;

class BQTable extends Model
{
    protected $guarded = [];

    protected $table = 'bq_tables';

    protected $casts = [
        'table_date' => 'date:Ymd',
        'status' => 'boolean'
    ];
}
