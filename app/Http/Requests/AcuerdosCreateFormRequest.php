<?php

namespace App\Http\Requests;
//namespace App\Http\Requests\Rule;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AcuerdosCreateFormRequest extends FormRequest
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
            // 'id_predio_acuerdo' => 'required',
            // 'numero_acuerdo' => 'required',
            'fecha_acuerdo' => 'required',
            'anio_inicial_acuerdo' => 'required',
            'anio_final_acuerdo' => 'required',
            'cuotas_acuerdo' => 'required',
            'fecha_inicial_acuerdo' => 'required',
            // 'calcular_intereses' => 'required',
            // 'porcentaje_inicial_acuerdo' => 'required|numeric|min:0|max:100',
            'abono_inicial_acuerdo' => 'required|min:1',
            // 'total_acuerdo' => 'required|numeric|min:0',
        ];
    }

    public function messages()
    {
        return [
            // 'id_predio_acuerdo.required' => 'El campo predio es obligatorio.',
            // 'numero_acuerdo.required' => 'El campo número de acuerdo es obligatorio.',
            'fecha_acuerdo.required' => 'El campo fecha de acuerdo es obligatorio.',
            'anio_inicial_acuerdo.required' => 'El campo año inicial es obligatorio.',
            'anio_final_acuerdo.required' => 'El campo año final es obligatorio.',
            'cuotas_acuerdo.required' => 'El campo cuotas es obligatorio.',
            'fecha_inicial_acuerdo.required' => 'El campo fecha inicial de pago es obligatorio.',
            // 'calcular_intereses.required' => 'El campo calcular intereses es obligatorio.',
            // 'porcentaje_inicial_acuerdo.required' => 'El campo porcentaje es obligatorio.',
            // 'porcentaje_inicial_acuerdo.numeric' => 'El campo porcentaje debe ser un número.',
            // 'porcentaje_inicial_acuerdo.min' => 'El campo porcentaje debe ser al menos 0.',
            // 'porcentaje_inicial_acuerdo.max' => 'El campo porcentaje no puede ser mayor a 100.',
            // 'abono_inicial_acuerdo.required' => 'El campo abono inicial es obligatorio.',
            // 'abono_inicial_acuerdo.numeric' => 'El campo abono inicial debe ser un número.',
            'abono_inicial_acuerdo.min' => 'El campo abono inicial debe ser mayor a 0.',
            // 'total_acuerdo.required' => 'El campo total del acuerdo es obligatorio.',
            // 'total_acuerdo.numeric' => 'El campo total del acuerdo debe ser un número.',
            // 'total_acuerdo.min' => 'El campo total del acuerdo debe ser al menos 0.',
        ];
    }
}
