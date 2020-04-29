<?php

/** @var Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use RezuanKassim\BQAnalytic\BQSubclient;

$factory->define(BQSubclient::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'description' => $faker->text,
        'app_id' => $faker->uuid,
    ];
});
