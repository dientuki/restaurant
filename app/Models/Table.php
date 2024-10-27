<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;

class Table extends Model
{
    use HasUlids;

    protected $fillable = [
        'location',
        'table_number',
        'max_capacity',
    ];

    public function reservations(): BelongsToMany
    {
        return $this->belongsToMany(Reservation::class, 'reservation_table');
    }

    public static function getUniqueMaxCapacities()
    {
        return self::select('max_capacity')
            ->distinct()
            ->pluck('max_capacity')
            ->toArray();
    }

    public static function getStatusForDateTime($date = null, $time = null)
    {
        $tables = DB::select("SELECT
            tables.id,
            tables.location,
            tables.table_number,
            tables.max_capacity,
            CASE
                WHEN reservations.id IS NOT NULL THEN 'ocupada'
                ELSE 'libre'
            END AS estado,
            reservations.reservation_end_time AS hasta_hora_ocupada,
            (
                SELECT MIN(r2.reservation_start_time)
                FROM reservations r2
                INNER JOIN reservation_table rt2 ON r2.id = rt2.reservation_id
                WHERE rt2.table_id = tables.id
                AND r2.reservation_date = '$date'
                AND r2.reservation_start_time > '$time'
            ) AS proxima_reserva
        FROM tables
        LEFT JOIN reservation_table ON tables.id = reservation_table.table_id
        LEFT JOIN reservations ON reservations.id = reservation_table.reservation_id
            AND reservations.reservation_date = '$date'
            AND '$time' BETWEEN reservations.reservation_start_time AND reservations.reservation_end_time
        GROUP BY tables.id, tables.location, tables.table_number,
            tables.max_capacity, estado, reservations.reservation_end_time
        ORDER BY tables.table_number;");

        return $tables;
    }
}
