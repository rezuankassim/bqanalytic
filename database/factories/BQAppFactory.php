<?php

use Faker\Generator as Faker;
use RezuanKassim\BQAnalytic\Models\BQApp;

/** @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(BQApp::class, function (Faker $faker) {
    return [
        'name' => $faker->lastName()
    ];
});
