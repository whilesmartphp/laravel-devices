# Whilesmart Laravel  User Devices Package

This Laravel package provides a complete devices solution ready to be integrated into your application.

## Features

* **Ready-to-use devices endpoints:**
* **OpenAPI documentation:** Automatically generated documentation using PHP attributes.
* **Configuration file:** Easily customize settings.
* **Laravel agnostic considerations:** designed with future framework agnosticism in mind.

## Installation

### 1. Require the package

   ```bash
   composer require whilesmart/laravel-user-devices
   ```

### 2. Publish the configuration and migrations:

You do not need to publish the migrations and configurations except if you want to make modifications. You can choose to
publish
the migrations, routes, controllers separately or all at once.

#### 2.1 Publishing only the routes

Run the command below to publish only the routes.

```bash
php artisan vendor:publish --tag=user-devices-routes
php artisan migrate
```

The routes will be available at `routes/laravel-user-devices.php`. You should `require` this file in your `api.php`
file.

```php
    require 'laravel-user-devices.php';
```

#### 2.2 Publishing only the migrations

+If you would like to make changes to the migration files, run the command below to publish only the migrations.

```bash
php artisan vendor:publish --tag=laravel-user-devices-migrations
php artisan migrate
```

The migrations will be available in the `database/migrations` folder.

#### 2.3 Publish only the controllers

By default the controllers assign the device to the currently logged in user. If you would like to assign the device to
another model, you can publish the controllers and make the necessary changes to the published file. <br/>
To publish the controllers, run the command below

```bash
php artisan vendor:publish --tag=laravel-user-devices-controllers
php artisan migrate
```

The controllers will be available in the `app/Http/Controllers` directory.
Finally, change the namespace in the published controllers to your namespace.

#### Note: Publishing the controllers will also publish the routes. See section 2.1

#### 2.4 Publish  the config

To publish the config, run the command below

```bash
php artisan vendor:publish --tag=laravel-user-devices-config
```

The config file will be available in the `config/user-devices.php`.
The config file has the folowing variables:

- `register_routes`: Default `true`. Auto registers the routes. If you do not want to auto-register the routes, set the
  value to `false
- `route_prefix`: Default `api`. Defines the prefix for the auto-registered routes.
- `db_table_name`: Default `devices`. Defines the name of the database table to create.

#### 2.5 Publish everything

To publish the migrations, routes and controllers, you can run the command below

```bash
php artisan vendor:publish --tag=laravel-user-devices
php artisan migrate
```

#### Note: See section 2.1 above to make the routes accessible

### 3. Model Relationships

We have implemented a Trait `HasDevices` that handles relationships. If your model has devices, simply use the
`HasDevices` trait in your model definition.

```php
use Whilesmart\UserDevices\Traits\HasDevices
class MyModel {
 use HasDevices;
}
 
```

You can call `yourModel->devices()` to get the list of devices tied to the model

```php
$model = new MyModel();
$model->devices();
```

The `HasDevices` trait also has the `getDevicesAttribute()` method. If you want to append the devices to the model
response, simply add `devices` to your model's $appends

```php
use Whilesmart\UserDevices\Traits\HasDevices;
class MyModel {
 use HasDevices;
 
 protected $appends = ['devices'];
}

```

## Usage

After installation, the following API endpoints will be available:

* **POST /api/devices:** Registers a new device linked to the current logged in user.
* **Get /api/devices:** Retrieves all devices linked to the current logged in user.
* **PUT /api/devices/{id}:** Updates the device information.
* **DELETE /api/devices/{id}:** Deletes a device from the database.
* **OpenAPI Documentation:** Accessible via a route that your OpenAPI package defines.

**Example Registration Request:**

```json
{
  "token":"unique_token_string",
  "name":"Chrome on Windows",
  "type":"web",
  "identifier":"device_identifier",
  "platform":"Windows"
}
