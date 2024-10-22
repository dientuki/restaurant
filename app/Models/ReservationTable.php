<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservationTable extends Model
{
    public $incrementing = false;

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function table()
    {
        return $this->belongsTo(Table::class);
    }
}
