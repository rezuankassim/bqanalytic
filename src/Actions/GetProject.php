<?php

namespace RezuanKassim\BQAnalytic\Actions;

class GetProject
{
    public function execute($clientFromDB)
    {
        if ($clientFromDB) {
            return config('bqanalytic.project')::all()->toArray();
        } else {
            return config('bqanalytic.google.accounts');
        }
    }
}
