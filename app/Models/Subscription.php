<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'kid_id',
        'payment_group_id',
        'package_name',
        'price',
        'duration_days',
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
        'cash_due_date',
        'cash_paid_at',
        'verified_by',
        'cash_deadline',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'cash_deadline' => 'datetime',
        'cash_paid_at' => 'datetime',
        'is_paused' => 'boolean',
    ];

    public static function syncExpired(): void
    {
        self::where('status', 'active')
            ->whereNotNull('end_date')
            ->whereDate('end_date', '<', Carbon::today())
            ->update([
                'status' => 'expired',
            ]);

        self::where('status', 'pending_cash')
            ->whereNotNull('cash_deadline')
            ->where('cash_deadline', '<', now())
            ->update([
                'status' => 'cancelled',
            ]);
    }

    public function isExpired(): bool
    {
        return $this->status === 'active'
            && $this->end_date
            && Carbon::parse($this->end_date)->lt(Carbon::today());
    }

    public function isActiveToday(): bool
    {
        return $this->status === 'active'
            && $this->start_date
            && $this->end_date
            && Carbon::parse($this->start_date)->lte(Carbon::today())
            && Carbon::parse($this->end_date)->gte(Carbon::today());
    }

    public function getEffectiveStatusAttribute(): string
    {
        if ($this->isExpired()) {
            return 'expired';
        }

        return $this->status;
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->effective_status) {
            'pending' => 'Menunggu Pembayaran QRIS',
            'pending_cash' => 'Menunggu Pembayaran Cash',
            'active' => 'Sedang Berlaku',
            'expired' => 'Berakhir',
            'cancelled' => 'Dibatalkan',
            'paid' => 'Dibayar',
            default => ucfirst($this->effective_status),
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->effective_status) {
            'active' => 'badge-active',
            'pending' => 'badge-pending',
            'pending_cash' => 'badge-assigned',
            'expired', 'cancelled' => 'badge-danger',
            'paid' => 'badge-assigned',
            default => 'badge-neutral',
        };
    }

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

    public function paymentGroup()
    {
        return $this->belongsTo(PaymentGroup::class);
    }
}
