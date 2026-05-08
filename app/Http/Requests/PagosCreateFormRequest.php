<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PagosCreateFormRequest extends FormRequest
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
            'fecha_pago'  => 'required',
            // 'codigo_barras'  => 'required',
            // 'id_predio'  => 'required',
            'numero_recibo'  => 'required',
            'valor_facturado'  => 'required',
            'anio_pago'  => 'required|size:4',
            'fecha_factura'  => 'required',
            'id_banco_factura'  => 'required'
        ];
    }

    public function messages()
    {
        return [
            'fecha_pago.required' => 'El campo fecha pago es obligatorio.',
            // 'codigo_barras.required' => 'El campo código de barras es obligatorio.',
            // 'id_predio.required' => 'El campo código predio es obligatorio.',
            'numero_recibo.required' => 'El campo número de recibo es obligatorio.',
            'valor_facturado.required' => 'El campo valor facturado es obligatorio.',
            'anio_pago.required' => 'El campo año es obligatorio.',
            'anio_pago.max' => 'Máximo :max caracteres.',
            'fecha_factura.required' => 'El campo fecha de factura es obligatorio.',
            'id_banco_factura.required' => 'El campo banco factura es obligatorio.'
        ];
    }
}
