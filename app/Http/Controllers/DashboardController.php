<?php

namespace App\Http\Controllers;

use App\Http\Requests\DashboardRequest;
use App\Models\Table;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(DashboardRequest $request)
    {
        if ($request->isMethod('post')) {
            $validatedData = $request->validated();
            $date = $validatedData['date'];
            $time = $validatedData['time'] . ':00';
        } else {
            $date = now()->toDateString();
            $time = $this->roundToNearestHalfHour(now());
        }

        $tables = Table::getStatusForDateTime($date, $time);

        return view('dashboard', compact('tables', 'date', 'time'));
    }

    private function roundToNearestHalfHour($time)
    {
        // Obtiene la hora y minutos
        $hours = $time->format('H');
        $minutes = $time->format('i');

        // Redondea los minutos
        if ($minutes < 30) {
            $roundedMinutes = 0; // Redondea a la hora completa
        } else {
            $roundedMinutes = 30; // Redondea a la media hora
        }

        // Crea una nueva hora redondeada
        return $time->setTime($hours, $roundedMinutes)->format('H:i');
    }
}
