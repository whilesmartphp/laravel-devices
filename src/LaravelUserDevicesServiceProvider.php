<?php

namespace Whilesmart\LaravelUserDevices;

use Illuminate\Support\ServiceProvider;

class LaravelUserDevicesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->publishesMigrations([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], ['laravel-user-devices', 'laravel-user-devices-migrations']);

        if (config('laravel-user-devices.register_routes', true)) {
            $this->loadRoutesFrom(__DIR__.'/../routes/laravel-user-devices.php');
        }

        $this->publishes([
            __DIR__.'/../routes/laravel-user-devices.php' => base_path('routes/laravel-user-devices.php'),
        ], ['laravel-user-devices', 'laravel-user-devices-routes', 'laravel-user-devices-controllers']);

        $this->publishes([
            __DIR__.'/Http/Controllers' => app_path('Http/Controllers'),
        ], ['laravel-user-devices', 'laravel-user-devices-controllers']);

        // Publish config
        $this->publishes([
            __DIR__.'/../config/laravel-user-devices.php' => config_path('laravel-user-devices.php'),
        ], ['laravel-user-devices', 'laravel-user-devices-controllers']);
    }
}
