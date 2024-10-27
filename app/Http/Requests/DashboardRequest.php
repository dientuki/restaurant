<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DashboardRequest extends FormRequest
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
        // Solo aplicar reglas de validación si el método es POST
        if ($this->isMethod('post')) {
            return [
                'date' => ['required', 'date'],
                'time' => ['required'],
            ];
        }

        // Si es GET, no aplicar reglas de validación
        return [];
    }


    public function attributes(): array
    {
        return [
            'date' => __('field.date'),
            'time' => __('field.time')
        ];
    }
}
