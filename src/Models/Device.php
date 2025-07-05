<?php

namespace Whilesmart\LaravelDevices\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Device extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'token',
        'deviceable_id',
        'deviceable_type',
        'type',
        'identifier',
        'platform',
    ];

    public function deviceable(): MorphTo
    {
        return $this->morphTo();
    }
}
