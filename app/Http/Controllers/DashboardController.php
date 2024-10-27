<?php

namespace App\Http\Controllers;

use App\Http\Requests\DashboardRequest;
use App\Models\Table;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(DashboardRequest $request)
    {
        $validatedData = $request->isMethod('post') ? $request->validated() : [];
        $date = $validatedData['date'] ?? now()->toDateString();
        $time = !empty($validatedData['time']) ? $validatedData['time'] . ':00' : $this->roundToNearestHalfHour(now());

        $tables = Table::getStatusForDateTime($date, $time);

        return view('dashboard', compact('tables', 'date', 'time'));
    }

    private function roundToNearestHalfHour($time)
    {
        $halfHour = 30;
        // Obtiene la hora y minutos
        $hours = $time->format('H');
        $minutes = $time->format('i');

        $roundedMinutes = $minutes < $halfHour ? 0 : $halfHour;

        // Crea una nueva hora redondeada
        return $time->setTime($hours, $roundedMinutes)->format('H:i');
    }
}
