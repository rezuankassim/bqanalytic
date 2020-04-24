<?php

use Illuminate\Database\Seeder;
use RezuanKassim\BQAnalytic\Analytic;

class AnalyticSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        config('bqanalytic.analytic')::create([
            'name' => 'get active users'
        ]);

        config('bqanalytic.analytic')::create([
            'name' => 'get new users'
        ]);

        config('bqanalytic.analytic')::create([
            'name' => 'get active users by platform'
        ]);

        config('bqanalytic.analytic')::create([
            'name' => 'get all event name with event count'
        ]);

        config('bqanalytic.analytic')::create([
            'name' => 'get users by country'
        ]);

        config('bqanalytic.analytic')::create([
            'name' => 'get total event count by event name'
        ]);

        config('bqanalytic.analytic')::create([
            'name' => 'get total event count by users'
        ]);

        $user = config('bqanalytic.user')::find(1);

        if (config('bqanalytic.client_from_db')) {
            $accounts = config('bqanalytic.client')::where('status', 1)->get()->toArray();
        } else {
            $accounts = config('bqanalytic.google.accounts');
        }

        foreach ($accounts as $account) {
            $user->analytic()->sync(Analytic::all()->pluck('id')->mapWithKeys(function ($value) use ($account) {
                return [ $value => ['client_name' => $account['name']]];
            }));
        }
    }
}
