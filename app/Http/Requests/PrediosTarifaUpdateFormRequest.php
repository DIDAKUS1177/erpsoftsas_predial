<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PrediosTarifaUpdateFormRequest extends FormRequest
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
            // 'id_predio_edit' => 'required',
            // 'tarifa_actual_edit'  => 'required',
            'tarifa_nueva_edit'  => 'required',
        ];
    }

    public function messages()
    {
        return [
            // 'id_predio_edit.required' => 'El campo código predio es obligatorio.',
            // 'tarifa_actual_edit.required' => 'El campo tarifa actual es obligatorio.',
            'tarifa_nueva_edit.required' => 'El campo tarifa nueva es obligatorio.',
        ];
    }
}
