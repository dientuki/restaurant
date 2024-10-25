<?php
namespace App\Http\Controllers;

use App\Enum\Location;
use App\Http\Requests\ReservationStoreRequest;
use App\Http\Requests\SearchDateRequest;
use App\Models\Reservation;
use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ReservationController extends Controller
{

    public function index(){
        return view('reservations.show');
    }

    public function show(SearchDateRequest $request){
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

        $tables = $this->findAvailableTables($reservationDate, $reservationStartTime, $reservationEndTime, $peopleCount);

        $hasReservation = false;
        $reservation = null;

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

        return view('reservations.create', compact('reservation', 'hasReservation'));
    }


    private function getCombinations($peopleCount, $capacities, $maxTables)
    {
        $results = [];
        // Ordenar las capacidades en orden descendente
        rsort($capacities);
        // Llamar a la función recursiva
        $this->findCombinations($peopleCount, $capacities, $maxTables, [], $results);

        // Eliminar duplicados usando un set
        $uniqueResults = [];
        foreach ($results as $combination) {
            sort($combination); // Ordenar cada combinación para asegurar que se pueda comparar correctamente
            $key = implode(',', $combination); // Crear una clave única
            $uniqueResults[$key] = $combination; // Almacenar en un array asociativo
        }

        // Convertir a un array
        $uniqueResults = array_values($uniqueResults);

        // Ordenar las combinaciones por la suma de sus elementos
        usort($uniqueResults, function($a, $b) {
            return array_sum($a) <=> array_sum($b); // Ordenar de menor a mayor
        });

        return $uniqueResults; // Retornar combinaciones ordenadas
    }

    private function findCombinations($remaining, $capacities, $maxTables, $currentCombination, &$results)
    {
        // Si hemos alcanzado el número máximo de mesas
        if (count($currentCombination) >= $maxTables) {
            return;
        }

        // Si la combinación actual suma al menos la cantidad de personas necesarias
        if ($remaining <= 0) {
            $results[] = $currentCombination;
            return;
        }

        // Iterar sobre las capacidades para generar combinaciones
        foreach ($capacities as $capacity) {
            // Agregar la capacidad actual a la combinación y buscar más
            $this->findCombinations(
                $remaining - $capacity,
                $capacities, // Permitir la misma capacidad en combinaciones posteriores
                $maxTables,
                array_merge($currentCombination, [$capacity]),
                $results
            );
        }
    }


    private function findAvailableTables($reservationDate, $startTime, $endTime, $peopleCount)
    {
        $locations = Location::getAllCasesAsArray();
        $capacities = Table::getUniqueMaxCapacities();
        $maxTables = 3; // Máximo de mesas permitidas

        // Obtener combinaciones de capacidades que sumen el número de comensales
        $combinations = $this->getCombinations($peopleCount, $capacities, $maxTables);
        $realStartTime = Carbon::parse($startTime)->subMinutes(15)->toTimeString();

        foreach ($locations as $location) {
            // Buscar mesas libres en la ubicación
            $cacheKey = "available_tables_{$location}_date_{$reservationDate}_start_{$realStartTime}_end_{$endTime}";

            $tables = Cache::remember($cacheKey, 60, function () use ($location, $reservationDate, $realStartTime, $endTime) {
                return Table::where('location', $location)
                    ->whereDoesntHave('reservations', function ($query) use ($reservationDate, $realStartTime, $endTime) {
                        $query->where('reservation_date', $reservationDate)
                            ->where(function ($q) use ($realStartTime, $endTime) {
                                $q->whereBetween('reservation_start_time', [$realStartTime, $endTime])
                                ->orWhereBetween('reservation_end_time', [$realStartTime, $endTime]);
                            });
                    })
                    ->orderBy('max_capacity') // Ordenar mesas de menor a mayor
                    ->get();
            });

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
                if ($totalCapacity >= $peopleCount && count($combinationTables) <= $maxTables) {
                    Cache::forget($cacheKey);
                    return $combinationTables; // Retornar la combinación de mesas
                }
            }
        }

        // Si no hay suficientes mesas, devolver null
        return null;
    }



}
