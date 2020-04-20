<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'namespace' => 'RezuanKassim\BQAnalytic\Http\Controllers',
    'prefix' => 'bqanalytics'
], function () {
    Route::get('/', 'BQAnalyticController@index');
});
