<?php

namespace App\Http\Controllers;

use App\Enum\Location;
use App\Helpers\CombinationHelper;
use App\Http\Requests\ReservationStoreRequest;
use App\Http\Requests\SearchDateRequest;
use App\Models\Reservation;
use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ReservationController extends Controller
{
    private $capacities = [];
    private $maxTables = 3;

    public function index()
    {
        return view('reservations.show');
    }

    public function show(SearchDateRequest $request)
    {
        $validatedData = $request->validated();

        $reservations = Reservation::getReservationsWithTablesByDate($validatedData['date']);

        return view('reservations.show', compact('reservations'));
    }

    public function create()
    {
        return view('reservations.create');
    }

    public function store(ReservationStoreRequest $request)
    {
        $validatedData = $request->validated();

        $reservationDate = $validatedData['reservation_date'];
        $reservationStartTime = $validatedData['reservation_start_time'];
        $reservationEndTime = $validatedData['reservation_end_time'];
        $peopleCount = $validatedData['people_count'];

        $canReserve = $this->canReserve($peopleCount);
        $hasReservation = false;
        $reservation = null;

        if ($canReserve) {
            $tables = $this->findAvailableTables(
                $reservationDate,
                $reservationStartTime,
                $reservationEndTime,
                $peopleCount
            );

            if ($tables !== null) {
                $reservation = Reservation::create([
                    'user_id' => Auth::id(),
                    'reservation_date' => $reservationDate,
                    'reservation_start_time' => $reservationStartTime,
                    'reservation_end_time' => $reservationEndTime,
                    'people_count' => $peopleCount,
                ]);

                $reservation->tables()->sync($tables);
                $hasReservation = true;
            }
        }


        return view('reservations.create', compact('reservation', 'hasReservation', 'canReserve'));
    }

    private function canReserve($people): bool
    {
        if ($this->capacities === []) {
            $this->capacities = Table::getUniqueMaxCapacities();
        }

        $maxPeople = max($this->capacities) * $this->maxTables;

        return $people <= $maxPeople;
    }


    private function findAvailableTables($reservationDate, $startTime, $endTime, $peopleCount)
    {
        $locations = Location::getAllCasesAsArray();

        // Obtener combinaciones de capacidades que sumen el número de comensales
        $combinations = CombinationHelper::getCombinations($peopleCount, $this->capacities, $this->maxTables);
        $realStartTime = Carbon::parse($startTime)->subMinutes(15)->toTimeString();

        foreach ($locations as $location) {
            // Buscar mesas libres en la ubicación
            $cacheKey = "available_tables_{$location}_date_{$reservationDate}_start_{$realStartTime}_end_{$endTime}";

            $tables = Cache::remember(
                $cacheKey,
                60,
                function () use ($location, $reservationDate, $realStartTime, $endTime) {
                    return Table::where('location', $location)
                    ->whereDoesntHave(
                        'reservations',
                        function ($query) use ($reservationDate, $realStartTime, $endTime) {
                            $query->where('reservation_date', $reservationDate)
                                ->where(function ($q) use ($realStartTime, $endTime) {
                                    $q->whereBetween('reservation_start_time', [$realStartTime, $endTime])
                                    ->orWhereBetween('reservation_end_time', [$realStartTime, $endTime]);
                                });
                        }
                    )
                     ->orderBy('max_capacity') // Ordenar mesas de menor a mayor
                     ->get();
                }
            );

            // Verificar las combinaciones de mesas y buscar las disponibles
            foreach ($combinations as $combination) {
                $combinationTables = [];
                $totalCapacity = 0;

                // Clonar las mesas disponibles para evitar seleccionar la misma mesa
                $availableTablesClone = clone $tables;

                foreach ($combination as $capacity) {
                    // Buscar la mesa disponible para la capacidad actual
                    $table = $availableTablesClone->firstWhere('max_capacity', $capacity);
                    if ($table) {
                        // Agregar la mesa seleccionada a la combinación y eliminarla de las mesas disponibles
                        $combinationTables[] = $table->id;
                        $totalCapacity += $table->max_capacity;
                        // Eliminar la mesa seleccionada para no repetirla
                        $availableTablesClone = $availableTablesClone->reject(function ($t) use ($table) {
                            return $t->id === $table->id;
                        });
                    }
                }

                // Verificar si la combinación encontrada cubre la capacidad requerida
                if ($totalCapacity >= $peopleCount && count($combinationTables) <= $this->maxTables) {
                    Cache::forget($cacheKey);
                    return $combinationTables; // Retornar la combinación de mesas
                }
            }
        }

        // Si no hay suficientes mesas, devolver null
        return null;
    }
}
