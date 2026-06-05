<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    protected $fillable = [
        'driver_id',
        'status',
        'pickup_time',
        'dropoff_time',
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function tripKids()
    {
        return $this->hasMany(TripKid::class);
    }
}
