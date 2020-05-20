<?php


namespace RezuanKassim\BQAnalytic\Models;

use Illuminate\Database\Eloquent\Model;

class BQProject extends Model
{
    protected $table = 'bqprojects';

    protected $guarded = [];

    protected $casts = [
        'start_date' => 'date',
        'last_imported_date' => 'date'
    ];

    public function bqapps()
    {
        return $this->hasMany(BQApp::class, 'bqproject_id');
    }
}
