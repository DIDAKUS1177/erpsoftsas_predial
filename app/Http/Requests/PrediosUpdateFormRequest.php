<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PrediosUpdateFormRequest extends FormRequest
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
            'codigo_predio_edit'  => 'required|15o25', // unique:predios|
            // 'tipo_edit'  => 'required|size:2',
            // 'sector_edit'  => 'required|size:2',
            // 'manzana_edit'  => 'required|size:4',
            // 'predio_edit'  => 'required|size:4',
            // 'mejora_edit'  => 'required|3o13',
            'id_zona_edit'  => 'required',
            'direccion_edit'  => 'required|max:128',
            'area_metros_edit'  => 'required',
            'area_construida_edit'  => 'required',
            'area_hectareas_edit'  => 'required',
            'tarifa_actual_edit'  => 'required',
            'avaluo_edit'  => 'required',
            'ultimo_anio_pago_edit'  => 'required|size:4'
        ];
    }

    public function messages()
    {
        return [
            'codigo_predio_edit.required' => 'El campo código predio es obligatorio.',
            //'codigo_predio_edit.unique' => 'El código de predio ya esta registrado.',
            'codigo_predio_edit.15o25' => 'El campo código predio debe contener 15 o 25 digitos.',
            // 'tipo_edit.required' => 'El campo tipo es obligatorio.',
            // 'tipo_edit.size' => 'El campo tipo debe contener :size digitos.',
            // 'sector_edit.required' => 'El campo sector es obligatorio.',
            // 'sector_edit.size' => 'El campo sector debe contener :size digitos.',
            // 'manzana_edit.required' => 'El campo manzana es obligatorio.',
            // 'manzana_edit.size' => 'El campo manzana debe contener :size digitos.',
            // 'predio_edit.required' => 'El campo predio es obligatorio.',
            // 'predio_edit.size' => 'El campo predio debe contener :size digitos.',
            // 'mejora_edit.required' => 'El campo mejora es obligatorio.',
            // 'mejora_edit.3o13' => 'El campo mejora debe contener 3 o 13 digitos.',
            'id_zona_edit.required'  => 'El campo zona es obligatorio.',
            'direccion_edit.required' => 'El campo direccion es obligatorio.',
            'area_metros_edit.required'  => 'El campo area metros es obligatorio.',
            'area_construida_edit.required'  => 'El campo area construida es obligatorio.',
            'area_hectareas_edit.required'  => 'El campo area hectareas es obligatorio.',
            'tarifa_actual_edit.required'  => 'El campo tarifa actual es obligatorio.',
            'avaluo_edit.required'  => 'El campo avaluo es obligatorio.',
            'ultimo_anio_pago_edit.required' => 'El campo ultimo año pago es obligatorio.',
            'ultimo_anio_pago_edit.size' => 'El campo ultimo año pago debe contener :size digitos.',
        ];
    }
}
