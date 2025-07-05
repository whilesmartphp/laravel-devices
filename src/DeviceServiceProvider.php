<?php

namespace Whilesmart\LaravelDevices;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class DeviceServiceProvider extends ServiceProvider
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

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->publishesMigrations([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], ['devices', 'devices-migrations']);

        if (config('devices.register_routes', true)) {
            Route::prefix('api')->group(function () {
                $this->loadRoutesFrom(__DIR__ . '/../routes/devices.php');
            });
        }

        $this->publishes([
            __DIR__ . '/../routes/devices.php' => base_path('routes/devices.php'),
        ], ['devices', 'devices-routes', 'devices-controllers']);

        $this->publishes([
            __DIR__ . '/Http/Controllers' => app_path('Http/Controllers'),
        ], ['devices', 'devices-controllers']);

        // Publish config
        $this->publishes([
            __DIR__ . '/../config/devices.php' => config_path('devices.php'),
        ], ['devices', 'devices-controllers']);
    }
}
