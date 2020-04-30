<?php 

namespace RezuanKassim\BQAnalytic\Models;

use Illuminate\Database\Eloquent\Model;

class BQTable extends Model
{
    protected $table = 'bqtables';

    protected $guarded = [];

    protected $casts = [
        'table_date' => 'date:Y-m-d',
    ];
}