<?php

namespace App\Http\Requests;

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
            'reservation_end_time' => ['required', 'date_format:H:i'],
            'people_count' => ['required', 'integer'],
        ];
    }
}
