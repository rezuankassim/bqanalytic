<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'namespace' => 'RezuanKassim\BQAnalytic\Http\Controllers',
    'prefix' => 'bqanalytics',
    'middleware' => 'web',
], function () {
<<<<<<< HEAD
    Route::get('/', 'BQAnalyticController@index')->name('bqanalytics.index');
    Route::post('/analytics', 'BQAnalyticController@analytic')->name('bqanalytics.analytic');
});
=======
    Route::get('/', 'BQAnalyticController@index');
});
>>>>>>> feature/remove_laravel_biquery_package
