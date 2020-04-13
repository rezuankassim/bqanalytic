<?php

namespace RezuanKassim\BQAnalytic;

use Illuminate\Support\ServiceProvider;

class BQAnalyticServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'rezuankassim');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'rezuankassim');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/bqanalytic.php', 'bqanalytic');

        // Register the service the package provides.
        $this->app->singleton('bqanalytic', function ($app) {
            return new BQAnalytic;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['bqanalytic'];
    }
    
    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/bqanalytic.php' => config_path('bqanalytic.php'),
        ], 'bqanalytic.config');

        $this->publishes([
            __DIR__.'/../database/seeds/AnalyticSeeder.php' => database_path('/seeds/AnalyticSeeder.php'),
        ], 'bqanalytic.seeder');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/rezuankassim'),
        ], 'bqanalytic.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/rezuankassim'),
        ], 'bqanalytic.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/rezuankassim'),
        ], 'bqanalytic.views');*/

        // Registering package commands.
        // $this->commands([]);
    }
}
