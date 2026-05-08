<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AcuerdosUpdateFormRequest extends FormRequest
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
            // 'id_predio_acuerdo_edit' => 'required',
            // 'numero_acuerdo_edit' => 'required',
            'fecha_acuerdo_edit' => 'required',
            'anio_inicial_acuerdo_edit' => 'required',
            'anio_final_acuerdo_edit' => 'required',
            'cuotas_acuerdo_edit' => 'required',
            'fecha_inicial_acuerdo_edit' => 'required',
            // 'calcular_intereses_edit' => 'required',
            // 'porcentaje_inicial_acuerdo_edit' => 'required|numeric|min:0|max:100',
            'abono_inicial_acuerdo_edit' => 'required|min:1',
            // 'total_acuerdo_edit' => 'required|numeric|min:0',
        ];
    }

    public function messages()
    {
        return [
            // 'id_predio_acuerdo_edit.required' => 'El campo predio es obligatorio.',
            // 'numero_acuerdo_edit.required' => 'El campo número de acuerdo es obligatorio.',
            'fecha_acuerdo_edit.required' => 'El campo fecha de acuerdo es obligatorio.',
            'anio_inicial_acuerdo_edit.required' => 'El campo año inicial es obligatorio.',
            'anio_final_acuerdo_edit.required' => 'El campo año final es obligatorio.',
            'cuotas_acuerdo_edit.required' => 'El campo cuotas es obligatorio.',
            'fecha_inicial_acuerdo_edit.required' => 'El campo fecha inicial de pago es obligatorio.',
            // 'calcular_intereses_edit.required' => 'El campo calcular intereses es obligatorio.',
            // 'porcentaje_inicial_acuerdo_edit.required' => 'El campo porcentaje es obligatorio.',
            // 'porcentaje_inicial_acuerdo_edit.numeric' => 'El campo porcentaje debe ser un número.',
            // 'porcentaje_inicial_acuerdo_edit.min' => 'El campo porcentaje debe ser al menos 0.',
            // 'porcentaje_inicial_acuerdo_edit.max' => 'El campo porcentaje no puede ser mayor a 100.',
            // 'abono_inicial_acuerdo_edit.required' => 'El campo abono inicial es obligatorio.',
            // 'abono_inicial_acuerdo_edit.numeric' => 'El campo abono inicial debe ser un número.',
            'abono_inicial_acuerdo_edit.min' => 'El campo abono inicial debe ser mayor de 0.',
            // 'total_acuerdo_edit.required' => 'El campo total del acuerdo es obligatorio.',
            // 'total_acuerdo_edit.numeric' => 'El campo total del acuerdo debe ser un número.',
            // 'total_acuerdo_edit.min' => 'El campo total del acuerdo debe ser al menos 0.',
        ];
    }
}
