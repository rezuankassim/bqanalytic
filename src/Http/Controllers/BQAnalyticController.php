<?php

namespace RezuanKassim\BQAnalytic\Http\Controllers;

use App\Http\Controllers\Controller;

class BQAnalyticController extends Controller
{
    public function index()
    {
        return view('bqanalytic::bqanalytics.index');
    }
}