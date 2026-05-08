<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TarifasPredialCreateFormRequest extends FormRequest
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
            'anio'  => 'required|size:4',
            //'meses_amnistia'  => 'required',
            'codigo'  => 'required|max:10',
            'rango'  => 'required',
            'descripcion'  => 'required|max:128',
            'avaluo_inicial'  => 'required',
            'avaluo_final'  => 'required',
            'tarifa'  => 'required',
            'porcentaje_car'  => 'required'
        ];
    }

    public function messages()
    {
        return [
            'anio.required'  => 'El campo año es obligatorio.',
            'anio.size' => 'El campo año debe contener :size digitos.',
            //'meses_amnistia.required'  => 'El campo mes amnistia es obligatorio.',
            'codigo.required' => 'El campo código es obligatorio.',
            'codigo.max' => 'Máximo :max caracteres.',
            'rango.required'  => 'El campo rango es obligatorio.',
            'descripcion.required' => 'El campo descripcion es obligatorio.',
            'descripcion.max' => 'Máximo :max caracteres.',
            'avaluo_inicial.required'  => 'El campo avaluo inicial es obligatorio.',
            'avaluo_final.required'  => 'El campo avaluo final es obligatorio.',
            'tarifa.required'  => 'El campo tarifa es obligatorio.',
            'porcentaje_car.required'  => 'El campo porcentaje CAR es obligatorio.'
        ];
    }
}
