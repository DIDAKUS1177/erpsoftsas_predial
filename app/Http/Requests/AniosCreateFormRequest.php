<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AniosCreateFormRequest extends FormRequest
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
            'anio'  => 'required|unique:anios|size:4',
            'id_estado'  => 'required',
            'meses_amnistia'  => 'required',
            'id_tipo_tasa_interes' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'anio.required' => 'El campo año es obligatorio.',
            'anio.unique' => 'El año ya esta registrado.',
            'anio.size' => 'El campo año debe contener 4 digitos.',
            'id_estado.required' => 'El campo estado es obligatorio.',
            'meses_amnistia.required' => 'El campo meses amnistia es obligatorio.',
            'id_tipo_tasa_interes.required' => 'El campo tipo tasa interés es obligatorio.'
        ];
    }
}
