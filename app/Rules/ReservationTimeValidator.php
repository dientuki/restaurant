<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Carbon\Carbon;

class ReservationTimeValidator implements ValidationRule
{
    protected ?string $reservationDate;

    public function __construct(?string $reservationDate)
    {
        $this->reservationDate = $reservationDate;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (is_null($this->reservationDate)) {
            $fail('La fecha es obligatoria.');
            return;
        }

        // Llamar al método para crear el objeto Carbon
        $time = $this->createCarbonFromTime($value);

        // Obtener el día de la semana como número (0 = Domingo, 6 = Sábado)
        $dayOfWeek = Carbon::parse($this->reservationDate)->dayOfWeek;

        switch ($dayOfWeek) {
            case 0: // Domingo
                $start = Carbon::createFromTime(12, 0, 0); // 12:00
                $end = Carbon::createFromTime(16, 0, 0); // 16:00

                if (!$time->between($start, $end)) {
                    $fail("La hora debe estar entre 12:00 y 16:00 el domingo.");
                }
                break;

            case 1: // Lunes
            case 2: // Martes
            case 3: // Miércoles
            case 4: // Jueves
            case 5: // Viernes
                $start = Carbon::createFromTime(10, 0, 0); // 10:00
                $end = Carbon::createFromTime(23, 59, 59); // 24:00

                if (!$time->between($start, $end)) {
                    $fail("La hora debe estar entre 10:00 y 24:00 de lunes a viernes.");
                }
                break;

            case 6: // Sábado
                $start = Carbon::createFromTime(22, 0, 0); // 22:00
                $end = Carbon::createFromTime(2, 0, 0)->addDay(); // 02:00 del día siguiente

                // Convertimos a timestamps
                $startTimestamp = $start->timestamp;
                $endTimestamp = $end->timestamp;
                $timeTimestamp = $time->timestamp;

                // Verificamos si $time está dentro del rango
                if (!($timeTimestamp >= $startTimestamp || $timeTimestamp <= $endTimestamp)) {
                    $fail("La hora debe estar entre 22:00 del sábado y 02:00 del día siguiente.");
                }
                break;

            default:
                $fail("El día de la semana no es válido.");
                break;
        }
    }

    /**
     * Create a Carbon instance from the time string.
     *
     * @param string $timeString
     * @return \Carbon\Carbon
     */
    private function createCarbonFromTime(string $timeString): Carbon
    {
        // Verifica si el tiempo tiene segundos y crea el objeto Carbon apropiadamente
        return strpos($timeString, ':') === false || substr_count($timeString, ':') === 1
            ? Carbon::createFromFormat('H:i', $timeString)
            : Carbon::createFromFormat('H:i:s', $timeString);
    }
}
