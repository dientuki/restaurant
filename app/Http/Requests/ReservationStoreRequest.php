<?php

namespace App\Http\Requests;

use App\Rules\ReservationDurationValidator;
use App\Rules\ReservationStartTimeValidator;
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
                new ReservationDurationValidator($this->input('reservation_start_time'))
            ],
            'people_count' => ['required', 'integer', 'min:1'],
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->reservation_start_time) {
            try {
                $this->merge([
                    'reservation_start_time' => Carbon::createFromFormat('H:i:s', trim($this->reservation_start_time))->format('H:i'),
                ]);
            } catch (\Carbon\Exceptions\InvalidFormatException $e) {
                // Si hay un error, intenta con H:i
                $this->merge([
                    'reservation_start_time' => Carbon::createFromFormat('H:i', trim($this->reservation_start_time))->format('H:i'),
                ]);
            }
        }

        if ($this->reservation_end_time) {
            try {
                $this->merge([
                    'reservation_end_time' => Carbon::createFromFormat('H:i:s', trim($this->reservation_end_time))->format('H:i'),
                ]);
            } catch (\Carbon\Exceptions\InvalidFormatException $e) {
                $this->merge([
                    'reservation_end_time' => Carbon::createFromFormat('H:i', trim($this->reservation_end_time))->format('H:i'),
                ]);
            }
        }
    }

}
