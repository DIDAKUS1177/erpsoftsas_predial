<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TasasInteresUpdateFormRequest extends FormRequest
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
            // 'anio_edit'  => 'required|size:4|unique:tasas_interes,anio,' . $this->id_edit . ',id,mes,' . $this->mes_edit,
            'anio_edit'  => 'required|size:4',
            'mes_edit'  => 'required',
            'tasa_diaria_edit'  => 'required',
            'tasa_mensual_edit'  => 'required',
            'tasa_acuerdo_edit'  => 'required'
        ];
    }

    public function messages()
    {
        return [
            'anio_edit.required'  => 'El campo año es obligatorio.',
            'anio_edit.size' => 'El campo año debe contener :size digitos.',
            // 'anio_edit.unique' => 'La combinación de año y mes debe ser única.',
            'mes_edit.required'  => 'El campo mes es obligatorio.',
            'tasa_diaria_edit.required'  => 'El campo tasa diaria es obligatorio.',
            'tasa_mensual_edit.required'  => 'El campo tasa mensual es obligatorio.',
            'tasa_acuerdo_edit.required'  => 'El campo tasa acuerdo es obligatorio.'
        ];
    }
}
