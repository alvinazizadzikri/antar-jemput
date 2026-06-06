<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatAntarJemput extends Model
{
    protected $fillable = [
        'driver_id',
        'kid_id',
        'trip_code',
        'status',
        'pickup_time',
        'actual_pickup_time',
        'dropoff_time',
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function kid()
    {
        return $this->belongsTo(Kid::class);
    }
}
