<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class BpAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'address_street',
        'city',
        'postcode',
        'country',
        'phone',
        'email',
        'notes',
        'responsible',
        'nearest_fire_station',
        'work_team',
        'addressable_id',
        'addressable_type',
    ];

    public function addressable(): MorphTo
    {
        return $this->morphTo();
    }
}
