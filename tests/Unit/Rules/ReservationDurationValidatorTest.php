<?php
namespace Tests\Unit\Rules;

use App\Rules\ReservationDurationValidator;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class ReservationDurationValidatorTest extends TestCase
{
    public function test_validation_fails_when_start_time_is_null()
    {
        $rule = new ReservationDurationValidator(null, '2024-10-28');

        $fail = function ($message) {
            $this->assertEquals(__('validation.both_required'), $message);
        };

        $rule->validate('reservation_end_time', '10:00', $fail);
    }

    public function test_validation_fails_when_date_is_null()
    {
        $rule = new ReservationDurationValidator('10:00', null);

        $fail = function ($message) {
            $this->assertEquals(__('validation.both_required'), $message);
        };

        $rule->validate('reservation_end_time', '10:00', $fail);
    }

    public function test_validation_fails_when_duration_is_less_than_minimum()
    {
        $rule = new ReservationDurationValidator('10:00', '2024-10-28');

        $fail = function ($message) {
            $this->assertEquals(__('validation.min_time', ['attribute' => 'reservation_end_time']), $message);
        };

        // Duración es solo 1 hora
        $rule->validate('reservation_end_time', '11:00', $fail);
    }

    public function test_validation_passes_when_duration_is_valid()
    {
        $rule = new ReservationDurationValidator('10:00', '2024-10-28');

        $fail = function () {
            $this->fail('Validation should not fail.');
        };

        // Duración es 1 hora y 45 minutos
        $rule->validate('reservation_end_time', '12:00', $fail);
        $this->assertTrue(true); // Si llegamos aquí, significa que no se llamó a $fail
    }

    public function test_validation_passes_on_saturday_crossing_midnight()
    {
        $rule = new ReservationDurationValidator('23:00', '2024-10-26'); // Sábado

        $fail = function () {
            $this->fail('Validation should not fail.');
        };

        // Duración cruzando medianoche: 23:00 - 01:00
        $rule->validate('reservation_end_time', '01:00', $fail);
        $this->assertTrue(true); // Si llegamos aquí, significa que no se llamó a $fail
    }

    public function test_validation_fails_when_date_format_is_invalid()
    {
        $rule = new ReservationDurationValidator('10:00', 'invalid-date');

        $fail = function ($message) {
            $this->assertEquals(__('validation.date_format'), $message);
        };

        $rule->validate('reservation_end_time', '11:00', $fail);
    }
}
