<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PrediosExencionesUpdateFormRequest extends FormRequest
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
            //'id_predio_edit' => 'required',
            'porcentaje_edit' => 'required|min:1',
            'exencion_hasta_edit' => 'required'
        ];
    }

    public function messages()
    {
        return [
            //'id_predio_edit.required' => 'El campo código predio es obligatorio.',
            'porcentaje_edit.required' => 'El campo porcentaje es obligatorio.',
            'porcentaje_edit.min' => 'Mínimo porcentaje permitido :min',
            'exencion_hasta_edit.required' => 'El campo año es obligatorio.',
        ];
    }
}
