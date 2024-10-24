<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Carbon\Carbon;

class ReservationDurationValidator implements ValidationRule
{
    protected string $reservationStartTime;

    public function __construct(string $reservationStartTime)
    {
        $this->reservationStartTime = $reservationStartTime;
    }

    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            //dd($this->reservationStartTime);
            $startTime = Carbon::createFromFormat('H:i', $this->reservationStartTime);
            $endTime = Carbon::createFromFormat('H:i', $value);

            // Verificar que el tiempo de finalización sea al menos 1 hora y 45 minutos mayor que el de inicio
            if ($endTime->diffInMinutes($startTime) < 105) {
                $fail('La hora de finalización debe ser al menos 1 hora y 45 minutos mayor que la hora de inicio.');
            }
        } catch (\Exception $e) {
            $fail('El formato de hora no es válido.');
        }
    }
}
