<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'namespace' => 'RezuanKassim\BQAnalytic\Http\Controllers',
    'prefix' => 'bqanalytics',
    'middleware' => 'web',
], function () {
    Route::get('/', 'BQAnalyticController@index')->name('bqanalytics.index');
    Route::post('/analytics', 'BQAnalyticController@analytic')->name('bqanalytics.analytic');
});
