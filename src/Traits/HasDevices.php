<?php

namespace Whilesmart\LaravelDevices\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Whilesmart\LaravelDevices\Models\Device;

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
