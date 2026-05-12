<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kid extends Model
{
    protected $fillable = [
        'parent_id',
        'name',
        'school_name',
        'address',
        'pickup_point',
        'dropoff_point',
        'photo',
        'latitude',
        'longitude',
    ];

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }
}
