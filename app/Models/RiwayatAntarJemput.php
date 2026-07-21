<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatAntarJemput extends Model
{
    protected $fillable = [
        'kid_id',
        'driver_id',
        'trip_code',
        'trip_date',
        'pickup_time',
        'actual_pickup_time',
        'dropoff_time',
        'status',
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
