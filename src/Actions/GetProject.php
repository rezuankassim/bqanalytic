<?php

namespace RezuanKassim\BQAnalytic\Actions;

class GetProject
{
    public function execute($clientFromDB, $id = null)
    {
        if (!$clientFromDB) {
            return config('bqanalytic.google.accounts');
        }

        return config('bqanalytic.project')::when($id, function ($query, $id) {
            $query->where('id', $id);
        })->get()->toArray();
    }
}
