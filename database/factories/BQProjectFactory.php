<?php

use Carbon\Carbon;
use Faker\Generator as Faker;
use RezuanKassim\BQAnalytic\Models\BQProject;

/** @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(BQProject::class, function (Faker $faker) {
    return [
        'name' => $faker->lastName(),
        'google_project_id' => $faker->text(),
        'google_bq_dataset_name' => $faker->text(),
        'google_credential_path' => $faker->text(),
        'google_credential_file_name' => $faker->text(),
        'start_date' => Carbon::now()->subDay(rand(1, 60)),
    ];
});
