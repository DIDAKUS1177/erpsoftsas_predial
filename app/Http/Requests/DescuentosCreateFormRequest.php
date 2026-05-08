<?php

namespace App\Http\Requests;
//namespace App\Http\Requests\Rule;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DescuentosCreateFormRequest extends FormRequest
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
            // 'anio'  => [
            //     'required',
            //     Rule::unique('descuentos')->where(function ($query) {
            //         $query->where('anio', $this->anio)
            //               ->where('fecha_limite', $this->fecha_limite);
            //     })
            // ],
            // //'anio'  => 'required|size:4|unique:descuentos,anio,' . $this->anio . ',id,fecha_limite,' . $this->fecha_limite,
            'fecha_inicio'  => 'required',
            'fecha_fin'  => 'required',
            'porcentaje'  => 'required|max:100'
        ];
    }

    public function messages()
    {
        return [
            'anio.required' => 'El campo año es obligatorio.',
            'anio.size' => 'El campo año debe contener 4 digitos.',
            //'anio.unique' => 'Combinacion de Año y Fecha limite debe ser unica.',
            'fecha_inicio.required' => 'El campo fecha inicio es obligatorio.',
            'fecha_fin.required' => 'El campo fecha fin es obligatorio.',
            'porcentaje.required' => 'El campo porcentaje es obligatorio.',
            'porcentaje.max' => 'Valor máximo :max.'
        ];
    }
}
