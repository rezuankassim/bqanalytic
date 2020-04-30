<?php 

namespace RezuanKassim\BQAnalytic\Models;

use Illuminate\Database\Eloquent\Model;

class BQProject extends Model
{
    protected $table = 'bqprojects';

    protected $guarded = [];

    public function bqapps()
    {
        return $this->hasMany(BQApp::class, 'bqproject_id');
    }
}