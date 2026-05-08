<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PrediosTarifaCreateFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id_predio' => 'required',
            // 'tarifa_actual'  => 'required',
            'tarifa_nueva'  => 'required|different:tarifa_anterior|gt:0',
        ];
    }

    public function messages()
    {
        return [
            'id_predio.required' => 'El campo código predio es obligatorio.',
            // 'tarifa_actual.required' => 'El campo tarifa actual es obligatorio.',
            'tarifa_nueva.required' => 'El campo tarifa nueva es obligatorio.',
            'tarifa_nueva.different' => 'Tarifa nueva debe ser diferente de tarifa anterior.',
            'tarifa_nueva.gt' => 'Tarifa nueva debe mayor de cero.',
        ];
    }
}
