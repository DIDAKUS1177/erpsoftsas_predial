<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PrediosExencionesCreateFormRequest extends FormRequest
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
            'porcentaje_ex' => 'required|numeric|min:1|max:100',
            'exencion_hasta' => 'required',
            // 'file_name' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'id_predio.required' => 'El campo código predio es obligatorio.',
            'porcentaje_ex.required' => 'El campo porcentaje es obligatorio.',
            'porcentaje_ex.max' => 'Máximo porcentaje permitido :max',
            'porcentaje_ex.min' => 'Mínimo porcentaje permitido :min',
            'exencion_hasta.required' => 'El campo año es obligatorio.',
            // 'file_name.required' => 'Archivo de resolución es obligatorio'
        ];
    }
}
