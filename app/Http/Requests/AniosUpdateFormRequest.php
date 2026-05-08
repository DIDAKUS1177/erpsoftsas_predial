<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AniosUpdateFormRequest extends FormRequest
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
            'id_estado_edit'  => 'required',
            'meses_amnistia_edit'  => 'required',
            'id_tipo_tasa_interes_edit' => 'required'
        ];
    }

    public function messages()
    {
        return [
            //'anio_edit.required' => 'El campo año es obligatorio.',
            'id_estado_edit.required' => 'El campo estado es obligatorio.',
            'meses_amnistia_edit.required' => 'El campo meses amnistia es obligatorio.',
            'id_tipo_tasa_interes_edit.required' => 'El campo tipo tasa interés es obligatorio.'
        ];
    }
}
