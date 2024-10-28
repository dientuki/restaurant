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
            $fail(__('validation.custom.required'));
            return;
        }

        // Llamar al método para crear el objeto Carbon
        $time = $this->createCarbonFromTime($value);

        //dd($time);

        // Obtiene el día de la semana como un string (por ejemplo, "Monday", "Tuesday")
        $dayOfWeek = Carbon::parse($this->reservationDate)->format('l');

        switch ($dayOfWeek) {
            case DayOfWeek::Sunday->value:
                $this->validateSunday($time, $fail, $attribute);
                break;

            case DayOfWeek::Monday->value:
            case DayOfWeek::Tuesday->value:
            case DayOfWeek::Wednesday->value:
            case DayOfWeek::Thursday->value:
            case DayOfWeek::Friday->value:
                $this->validateWeekday($time, $fail, $attribute);
                break;

            case DayOfWeek::Saturday->value:
                $this->validateSaturday($time, $fail, $attribute);
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
        // Crea un objeto Carbon a partir de la hora
        $reservationDate = Carbon::createFromFormat('Y-m-d', $this->reservationDate);

        $time = strpos($timeString, ':') === false || substr_count($timeString, ':') === 1
            ? Carbon::createFromFormat('H:i', $timeString)
            : Carbon::createFromFormat('H:i:s', $timeString);

        // Combina la fecha de la reserva con la hora creada
        return $time->setDate($reservationDate->year, $reservationDate->month, $reservationDate->day);
    }

    private function validateSunday($time, Closure $fail, string $attribute): void
    {
        $start = $time->copy()->setHour(12)->setMinute(0);
        $end = $time->copy()->setHour(16)->setMinute(0);

        if (!$time->between($start, $end)) {
            $fail(__('validation.custom.day.sunday'));;
        }
    }

    private function validateWeekday($time, Closure $fail, string $attribute): void
    {
        $start = $time->copy()->setHour(10)->setMinute(0);
        $end = $time->copy()->addDay()->setHour(0)->setMinute(0);

        if ($time->hour === 0 && $time->minute === 0 && $time->second === 0) {
            $time->addDay(); // Sumar un día si la hora es 00:00:00
        }

        if (!$time->between($start, $end)) {
            $fail(__('validation.custom.day.weekday'));
        }
    }

    private function validateSaturday($time, Closure $fail, string $attribute): void
    {
        $start = $time->copy()->setHour(22)->setMinute(0);
        $end = $time->copy()->addDay()->setHour(2)->setMinute(0); // 02:00 del día siguiente
        $early = $time->copy()->setHour(22)->setMinute(0);

        if ($time->isBefore($early)) {
            $time->addday();
        }

        // Verificamos si $time está dentro del rango
        if (!$time->between($start, $end)) {
            $fail(__('validation.custom.day.saturday'));
        }
    }
}
