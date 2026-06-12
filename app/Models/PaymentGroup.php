<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentGroup extends Model
{
    protected $fillable = [
        'user_id',
        'invoice_code',
        'payment_method',
        'total_price',
        'status',
        'cash_deadline',
        'paid_at',
        'verified_by',
    ];

    protected $casts = [
        'cash_deadline' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
