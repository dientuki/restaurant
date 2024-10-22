<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasUlids;

    protected $fillable = [
        'user_id',
        'reservation_date',
        'reservation_start_time',
        'reservation_end_time',
        'people_count',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
