<?php

namespace Tests\Unit\Rules;

use App\Rules\ReservationTimeValidator;
use Tests\TestCase;

class ReservationTimeValidatorTest extends TestCase
{
    public function test_validation_fails_when_date_is_null()
    {
        $rule = new ReservationTimeValidator(null);

        $fail = function ($message) {
            $this->assertEquals('validation.custom.required', $message);
        };

        $rule->validate('reservation_time', '10:00', $fail);
    }

    public function test_validation_fails_on_sunday_out_of_range()
    {
        $rule = new ReservationTimeValidator('2024-10-27'); // Domingo

        $fail = function ($message) {
            $this->assertEquals(__('validation.custom.day.sunday', ['attribute' => 'reservation_time']), $message);
        };

        // Hora fuera del rango permitido (fuera de 12:00 - 16:00)
        $rule->validate('reservation_time', '11:00', $fail);
    }

    public function test_validation_passes_on_sunday_in_range()
    {
        $rule = new ReservationTimeValidator('2024-10-27'); // Domingo

        $fail = function () {
            $this->fail('Validation should not fail.');
        };

        // Hora dentro del rango permitido
        $rule->validate('reservation_time', '12:30', $fail);
        $this->assertTrue(true); // Si llegamos aquí, significa que no se llamó a $fail
    }

    public function test_validation_fails_on_weekday_out_of_range()
    {
        $rule = new ReservationTimeValidator('2024-10-29'); // Martes

        $fail = function ($message) {
            $this->assertEquals(__('validation.custom.day.weekday', ['attribute' => 'reservation_time']), $message);
        };

        // Hora fuera del rango permitido (fuera de 10:00 - 23:59)
        $rule->validate('reservation_time', '00:30', $fail);
    }

    public function test_validation_passes_on_weekday_in_range()
    {
        $rule = new ReservationTimeValidator('2024-10-29'); // Martes

        $fail = function () {
            $this->fail('Validation should not fail.');
        };

        // Hora dentro del rango permitido
        $rule->validate('reservation_time', '15:00', $fail);
        $this->assertTrue(true); // Si llegamos aquí, significa que no se llamó a $fail
    }

    public function test_validation_fails_on_saturday_out_of_range()
    {
        $rule = new ReservationTimeValidator('2024-10-26'); // Sábado

        $fail = function ($message) {
            $this->assertEquals(__('validation.custom.day.saturday', ['attribute' => 'reservation_time']), $message);
        };

        // Hora fuera del rango permitido (fuera de 22:00 - 02:00)
        $rule->validate('reservation_time', '21:00', $fail);
    }

    public function test_validation_passes_on_saturday_in_range()
    {
        $rule = new ReservationTimeValidator('2024-10-26'); // Sábado

        $fail = function () {
            $this->fail('Validation should not fail.');
        };

        // Hora dentro del rango permitido cruzando la medianoche
        $rule->validate('reservation_time', '23:30', $fail);
        $this->assertTrue(true); // Si llegamos aquí, significa que no se llamó a $fail
    }
}
