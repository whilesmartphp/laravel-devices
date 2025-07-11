<?php

namespace Whilesmart\LaravelUserDevices\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Whilesmart\LaravelUserDevices\Models\Device;

trait HasDevices
{
    public function getDevicesAttribute()
    {
        return $this->devices()->get();
    }

    public function devices(): MorphMany
    {
        return $this->morphMany(Device::class, 'deviceable');
    }

    public function getDeviceAttribute()
    {
        return $this->devices()->first();
    }
}
