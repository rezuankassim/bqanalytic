<?php

namespace RezuanKassim\BQAnalytic\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use RezuanKassim\BQAnalytic\BQAnalytic;

class BQAnalyticController extends Controller
{
    public function index()
    {
        return view('bqanalytic::bqanalytics.index');
    }

    public function analytic(Request $request)
    {
        $range = explode(' - ', $request->range);

        $results = (new BQAnalytic(auth()->user(), Carbon::createFromFormat('d/m/Y', $range[0])->format('Ymd'), Carbon::createFromFormat('d/m/Y', $range[1])->format('Ymd')))->getAllAnalytics();

        return $results;
    }
}