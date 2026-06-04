<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'kid_id',
        'package_name',
        'price',
        'status',
        'payment_method',
        'qris_image',
        'start_date',
        'end_date',
        'is_paused',
        'pause_start',
        'pause_end',
        'remaining_days',
        'pause_reason',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kid()
    {
        return $this->belongsTo(Kid::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function latestTransaction()
    {
        return $this->hasOne(Transaction::class)->latestOfMany();
    }
}
