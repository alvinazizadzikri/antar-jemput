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

    public function trips()
    {
        return $this->hasMany(RiwayatAntarJemput::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class)
            ->where('status', 'active')
            ->latestOfMany();
    }

    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class)
            ->whereIn(
                'status',
                [
                    'pending',
                    'pending_cash',
                    'active',
                ]
            )
            ->latestOfMany();
    }

    public function latestSubscription()
    {
        return $this->hasOne(Subscription::class)
            ->latestOfMany();
    }

    public function tripKids()
    {
        return $this->hasMany(TripKid::class);
    }

    public function absences()
    {
        return $this->hasMany(KidAbsence::class);
    }
}
