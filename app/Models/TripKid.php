<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TripKid extends Model
{
    protected $fillable = [
        'trip_id',
        'kid_id',
    ];

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    public function kid()
    {
        return $this->belongsTo(Kid::class);
    }
}
