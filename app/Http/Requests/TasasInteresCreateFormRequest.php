<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TasasInteresCreateFormRequest extends FormRequest
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
            // 'anio'  => 'required|size:4|unique:tasas_interes,anio,' . $this->anio . ',id,mes,' . $this->mes,
            'anio'  => 'required|size:4',
            'mes'  => 'required',
            'tasa_diaria'  => 'required',
            'tasa_mensual'  => 'required',
            'tasa_acuerdo'  => 'required'
        ];
    }

    public function messages()
    {
        return [
            'anio.required'  => 'El campo año es obligatorio.',
            'anio.size' => 'El campo año debe contener :size digitos.',
            // 'anio.unique' => 'La combinación de año y mes debe ser única.',
            'mes.required'  => 'El campo mes es obligatorio.',
            'tasa_diaria.required'  => 'El campo tasa diaria es obligatorio.',
            'tasa_mensual.required'  => 'El campo tasa mensual es obligatorio.',
            'tasa_acuerdo.required'  => 'El campo tasa acuerdo es obligatorio.'
        ];
    }
}
