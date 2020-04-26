<?php

namespace RezuanKassim\BQAnalytic\Actions;

class GetClient
{
    public function execute($clientFromDB)
    {
        if ($clientFromDB) {
            return config('bqanalytic.client')::where('status', 1)->get()->toArray();
        } else {
            return config('bqanalytic.google.accounts');
        }
    }
}
