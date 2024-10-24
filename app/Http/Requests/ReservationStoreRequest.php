<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class ReservationStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'reservation_date' => ['required', 'date'],
            'reservation_start_time' => ['required', 'date_format:H:i'],
            'reservation_end_time' => [
                'required',
                'date_format:H:i',
                function ($attribute, $value, $fail) {
                    try {
                        $startTime = \Carbon\Carbon::createFromFormat('H:i', $this->input('reservation_start_time'));
                        $endTime = \Carbon\Carbon::createFromFormat('H:i', $value);

                        if ($endTime->diffInMinutes($startTime) < 105) {
                            $fail('La hora de finalización debe ser al menos 1 hora y 45 minutos mayor que la hora de inicio.');
                        }
                    } catch (\Exception $e) {
                        $fail('El formato de hora no es válido.');
                    }
                }
            ],
            'people_count' => ['required', 'integer', 'min:1'],
        ];
    }
}
