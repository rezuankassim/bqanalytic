<?php

use Faker\Generator as Faker;

/** @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(RezuanKassim\BQAnalytic\BQClient::class, function (Faker $faker) {
    return [
        'name' => $faker->name(),
        'description' => $faker->text(),
        'status' => 0
    ];
});
