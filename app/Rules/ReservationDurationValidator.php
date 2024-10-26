<?php

namespace App\Rules;

use App\Enum\DayOfWeek;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Carbon\Carbon;

class ReservationDurationValidator implements ValidationRule
{
    protected ?string $reservationStartTime;
    protected ?string $reservationDate;

    public function __construct(?string $reservationStartTime, ?string $reservationDate)
    {
        $this->reservationStartTime = $reservationStartTime;
        $this->reservationDate = $reservationDate;
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

        if (is_null($this->reservationStartTime) || is_null($this->reservationDate)) {
            $fail(__('validation.both_required'));
            return;
        }

        $minTime = 105; //1 hora y 45 minutos en minutos

        try {
            $startTime = Carbon::createFromFormat('H:i', $this->reservationStartTime);
            $endTime = Carbon::createFromFormat('H:i', $value);

            // Si la fecha de reserva es sábado, permitir reservas entre las 22:00 y 02:
            if (Carbon::parse($this->reservationDate)->format('l') == DayOfWeek::Saturday->value) {
                if ($startTime->greaterThan($endTime)) {
                    // Si el tiempo de inicio es mayor que el de fin, se asume que la reserva cruza la medianoche
                    $endTime->addDay(); // Aumentar el día al final
                }
            }

            // Verificar que el tiempo de finalización sea al menos 1 hora y 45 minutos mayor que el de inicio
            if ($startTime->diffInMinutes($endTime) < $minTime) {
                $fail(__('validation.min_time', ['attribute' => $attribute]));
            }
        } catch (\Exception $e) {
            $fail(__('validation.date_format'));
        }
    }
}
