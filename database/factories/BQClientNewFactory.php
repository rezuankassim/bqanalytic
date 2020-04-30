<?php

use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;
use RezuanKassim\BQAnalytic\Models\BQClient;

/** @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(BQClient::class, function (Faker $faker) {
    return [
        'name' => $faker->name(),
        'email' => $faker->email(),
        'password' => Hash::make($faker->password()),
        'email_verified_at' => Carbon::now()
    ];
});