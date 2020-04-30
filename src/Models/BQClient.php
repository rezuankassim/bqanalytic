<?php 

namespace RezuanKassim\BQAnalytic\Models;

use Illuminate\Database\Eloquent\Model;

class BQClient extends Model
{
    protected $table = 'bqclients';

    protected $guarded = [];

    public function bqapps()
    {
        return $this->hasMany(BQApp::class, 'bqclient_id');
    }
}