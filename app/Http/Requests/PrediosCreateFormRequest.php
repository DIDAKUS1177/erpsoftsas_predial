<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PrediosCreateFormRequest extends FormRequest
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
            'codigo_predio'  => 'required|unique:predios|15o25',
            // 'tipo'  => 'required|size:2',
            // 'sector'  => 'required|size:2',
            // 'manzana'  => 'required|size:4',
            // 'predio'  => 'required|size:4',
            // 'mejora'  => 'required|3o13',
            'id_zona'  => 'required',
            'direccion'  => 'required|max:128',
            'area_metros'  => 'required',
            'area_construida'  => 'required',
            'area_hectareas'  => 'required',
            'tarifa_actual'  => 'required',
            'avaluo'  => 'required',
            'ultimo_anio_pago'  => 'required|size:4'
        ];
    }

    public function messages()
    {
        return [
            'codigo_predio.required' => 'El campo código predio es obligatorio.',
            'codigo_predio.unique' => 'El código de predio ya esta registrado.',
            'codigo_predio.15o25' => 'El campo código predio debe contener 15 o 25 digitos.',
            // 'tipo.required' => 'El campo tipo es obligatorio.',
            // 'tipo.size' => 'El campo tipo debe contener :size digitos.',
            // 'sector.required' => 'El campo sector es obligatorio.',
            // 'sector.size' => 'El campo sector debe contener :size digitos.',
            // 'manzana.required' => 'El campo manzana es obligatorio.',
            // 'manzana.size' => 'El campo manzana debe contener :size digitos.',
            // 'predio.required' => 'El campo predio es obligatorio.',
            // 'predio.size' => 'El campo predio debe contener :size digitos.',
            // 'mejora.required' => 'El campo mejora es obligatorio.',
            // 'mejora.3o13' => 'El campo mejora debe contener 3 o 13 digitos.',
            'id_zona.required'  => 'El campo zona es obligatorio.',
            'direccion.required' => 'El campo direccion es obligatorio.',
            'area_metros.required'  => 'El campo area metros es obligatorio.',
            'area_construida.required'  => 'El campo area construida es obligatorio.',
            'area_hectareas.required'  => 'El campo area hectareas es obligatorio.',
            'tarifa_actual.required'  => 'El campo tarifa actual es obligatorio.',
            'avaluo.required'  => 'El campo avaluo es obligatorio.',
            'ultimo_anio_pago.required' => 'El campo ultimo año pago es obligatorio.',
            'ultimo_anio_pago.size' => 'El campo ultimo año pago debe contener :size digitos.',
        ];
    }
}
