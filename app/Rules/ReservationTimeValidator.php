<?php

namespace App\Rules;

use App\Enum\DayOfWeek;
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
            $fail(__('validation.required'));
            return;
        }

        // Llamar al método para crear el objeto Carbon
        $time = $this->createCarbonFromTime($value);

        // Obtiene el día de la semana como un string (por ejemplo, "Monday", "Tuesday")
        $dayOfWeek = Carbon::parse($this->reservationDate)->format('l');

        switch ($dayOfWeek) {
            case DayOfWeek::Sunday->value:
                $start = Carbon::createFromTime(12, 0, 0); // 12:00
                $end = Carbon::createFromTime(16, 0, 0); // 16:00

                if (!$time->between($start, $end)) {
                    $fail(__('validation.day.sunday', ['attribute' => $attribute]));
                }
                break;

            case DayOfWeek::Monday->value:
            case DayOfWeek::Tuesday->value:
            case DayOfWeek::Wednesday->value:
            case DayOfWeek::Thursday->value:
            case DayOfWeek::Friday->value:
                $start = Carbon::createFromTime(10, 0, 0); // 10:00
                $end = Carbon::createFromTime(23, 59, 59); // 24:00

                if (!$time->between($start, $end)) {
                    $fail(__('validation.day.weekday', ['attribute' => $attribute]));
                }
                break;

            case DayOfWeek::Saturday->value:
                $start = Carbon::createFromTime(22, 0, 0); // 22:00
                $end = Carbon::createFromTime(2, 0, 0)->addDay(); // 02:00 del día siguiente

                // Convertimos a timestamps
                $startTimestamp = $start->timestamp;
                $endTimestamp = $end->timestamp;
                $timeTimestamp = $time->timestamp;

                //dd($startTimestamp, $endTimestamp, $timeTimestamp);

                // Verificamos si $time está dentro del rango
                if (!($timeTimestamp >= $startTimestamp && $timeTimestamp <= $endTimestamp)) {
                    $fail(__('validation.day.saturday', ['attribute' => $attribute]));
                }
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
