<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $fillable = [
        'user_id',
        'phone_number',
        'vehicle_type',
        'plate_number',
        'status',
        'capacity',
    ];

    /**
     * Relasi ke akun user sopir.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke riwayat/perjalanan sopir.
     */
    public function trips()
    {
        return $this->hasMany(RiwayatAntarJemput::class);
    }

    /**
     * Mengecek apakah sopir sedang memiliki perjalanan aktif.
     */
    public function hasActiveTrip()
    {
        return $this->trips()
            ->whereIn('status', [
                'assigned',
                'picked_up',
                'arrived_school',
                'picked_up_school',
            ])
            ->exists();
    }

    /**
     * Label status dinamis untuk ditampilkan di halaman data sopir.
     *
     * Catatan:
     * - On Trip  : jika sopir sedang memiliki perjalanan aktif.
     * - Online   : jika sopir aktif dan tidak sedang trip.
     * - Offline  : jika sopir tidak aktif.
     */
    public function getStatusLabelAttribute()
    {
        if ($this->hasActiveTrip()) {
            return 'On Trip';
        }

        if ($this->status === 'online') {
            return 'Online';
        }

        return 'Offline';
    }

    /**
     * Class badge status untuk kebutuhan tampilan.
     * Bisa dipakai di blade agar warna status lebih konsisten.
     */
    public function getStatusBadgeClassAttribute()
    {
        if ($this->hasActiveTrip()) {
            return 'badge-warning';
        }

        if ($this->status === 'online') {
            return 'badge-success';
        }

        return 'badge-danger';
    }
}
