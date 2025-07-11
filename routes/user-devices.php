<?php

use Illuminate\Support\Facades\Route;
<<<<<<<< HEAD:routes/user-devices.php
use Whilesmart\UserDevices\Http\Controllers\DeviceController;
========
use Whilesmart\LaravelUserDevices\Http\Controllers\DeviceController;
>>>>>>>> 50b492c (refactor: Update package and file names):routes/laravel-user-devices.php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// User Devices routes

Route::apiResource('devices', DeviceController::class);
