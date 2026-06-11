<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KidAbsence extends Model
{
    protected $fillable = [
        'user_id',
        'kid_id',
        'absence_type',
        'absence_date',
        'reason_type',
        'note',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kid()
    {
        return $this->belongsTo(Kid::class);
    }
}
