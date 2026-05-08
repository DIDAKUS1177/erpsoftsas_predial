<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DescuentosUpdateFormRequest extends FormRequest
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
            //'anio_edit'  => 'required',
            //'fecha_limite_edit'  => 'required',
            'fecha_inicio_edit'  => 'required',
            'fecha_fin_edit'  => 'required',
            'porcentaje_edit'  => 'required|max:100'
        ];
    }

    public function messages()
    {
        return [
            //'anio.required_edit' => 'El campo año es obligatorio.',
            //'fecha_limite_edit.required' => 'El campo fecha limite es obligatorio.',
            'fecha_inicio_edit.required' => 'El campo fecha inicio es obligatorio.',
            'fecha_fin_edit.required' => 'El campo fecha fin es obligatorio.',
            'porcentaje_edit.required' => 'El campo porcentaje es obligatorio.',
            'porcentaje_edit.max' => 'Valor máximo :max.'
        ];
    }
}
