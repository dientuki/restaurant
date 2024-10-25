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

    public function tables()
    {
        return $this->belongsToMany(Table::class, 'reservation_table');
    }

    public static function getReservationsWithTablesByDate($date)
    {
        return self::with('tables')
            ->where('reservation_date', $date)
            ->get()
            ->map(function ($reservation) {
                return [
                    'reservation_date' => $reservation->reservation_date,
                    'reservation_start_time' => $reservation->reservation_start_time,
                    'reservation_end_time' => $reservation->reservation_end_time,
                    'people_count' => $reservation->people_count,
                    'location' => $reservation->tables->first()->location ?? null,
                    'tables' => $reservation->tables->pluck('table_number')->implode(', '),
                ];
            });
    }
}
