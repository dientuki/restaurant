<?php
namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Table;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function create()
    {
        $tables = Table::all();
        return view('reservations.create', compact('tables'));
    }

    public function store(Request $request)
    {
        $reservation = Reservation::create([
            'user_id' => $request->user_id,
            'reservation_date' => $request->reservation_date,
            'reservation_start_time' => $request->reservation_start_time,
            'reservation_end_time' => $request->reservation_end_time,
            'people_count' => $request->people_count,
        ]);

        $reservation->tables()->sync($request->table_ids);

        return redirect()->route('reservations.index')->with('success', 'Reservación creada con éxito.');
    }
}
