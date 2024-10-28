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
        if ($this->areReservationDetailsMissing($fail)) {
            return;
        }

        $minTime = 105; // 1 hora y 45 minutos en minutos

        try {
            $startTime = Carbon::createFromFormat('H:i', $this->reservationStartTime);
            $endTime = Carbon::createFromFormat('H:i', $value);

            $endTime = $this->adjustEndTime($startTime, $endTime);

            if ($this->isDurationTooShort($startTime, $endTime, $minTime)) {
                $fail(__('validation.custom.min_time'));
            }
        } catch (\Exception $e) {
            $fail(__('validation.custom.date_format'));
        }
    }

    private function areReservationDetailsMissing(Closure $fail): bool
    {
        if (is_null($this->reservationStartTime) || is_null($this->reservationDate)) {
            $fail(__('validation.custom.both_required'));
            return true;
        }
        return false;
    }

    private function adjustEndTime($startTime, $endTime): Carbon
    {
        $isSaturday = Carbon::parse($this->reservationDate)->format('l') === DayOfWeek::Saturday->value;
        $isMidnight = $endTime->hour === 0 && $endTime->minute === 0 && $endTime->second === 0;

        if ($isSaturday && $startTime->greaterThan($endTime)) {
            $endTime->addDay();
        } elseif (!$isSaturday && $isMidnight) {
            $endTime->addDay();
        }

        return $endTime;
    }

    private function isDurationTooShort($startTime, $endTime, $minTime): bool
    {
        return $startTime->diffInMinutes($endTime) < $minTime;
    }
}
