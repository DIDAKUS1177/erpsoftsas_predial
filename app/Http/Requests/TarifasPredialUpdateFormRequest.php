<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TarifasPredialUpdateFormRequest extends FormRequest
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
            //'anio_edit'  => 'required|size:4',
            //'meses_amnistia_edit'  => 'required',
            //'codigo_edit'  => 'required|max:10',
            //'rango_edit'  => 'required',
            'descripcion_edit'  => 'required|max:128',
            'avaluo_inicial_edit'  => 'required',
            'avaluo_final_edit'  => 'required',
            'tarifa_edit'  => 'required',
            'porcentaje_car_edit'  => 'required'
        ];
    }

    public function messages()
    {
        return [
            //'anio_edit.required'  => 'El campo año es obligatorio.',
            //'anio_edit.size' => 'El campo año debe contener :size digitos.',
            //'meses_amnistia_edit.required'  => 'El campo mes amnistia es obligatorio.',
            //'codigo_edit.required' => 'El campo código es obligatorio.',
            //'codigo_edit.max' => 'Máximo :max caracteres.',
            //'rango_edit.required'  => 'El campo rango es obligatorio.',
            'descripcion_edit.required' => 'El campo descripcion es obligatorio.',
            'descripcion_edit.max' => 'Máximo :max caracteres.',
            'avaluo_inicial_edit.required'  => 'El campo avaluo inicial es obligatorio.',
            'avaluo_final_edit.required'  => 'El campo avaluo final es obligatorio.',
            'tarifa_edit.required'  => 'El campo tarifa es obligatorio.',
            'porcentaje_car_edit.required'  => 'El campo porcentaje CAR es obligatorio.'
        ];
    }
}
