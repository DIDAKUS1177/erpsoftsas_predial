<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PagosUpdateFormRequest extends FormRequest
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
            'fecha_pago_edit'  => 'required',
            // 'codigo_barras_edit'  => 'required',
            // 'id_predio_edit'  => 'required',
            'numero_recibo_edit'  => 'required',
            'valor_facturado_edit'  => 'required',
            'anio_pago_edit'  => 'required|size:4',
            'fecha_factura_edit'  => 'required',
            'id_banco_factura_edit'  => 'required'
        ];
    }

    public function messages()
    {
        return [
            'fecha_pago_edit.required' => 'El campo fecha pago es obligatorio.',
            // 'codigo_barras_edit.required' => 'El campo código de barras es obligatorio.',
            // 'id_predio_edit.required' => 'El campo código predio es obligatorio.',
            'numero_recibo_edit.required' => 'El campo número recibo es obligatorio.',
            'valor_facturado_edit.required' => 'El campo valor facturado es obligatorio.',
            'anio_pago_edit.required' => 'El campo año es obligatorio.',
            'anio_pago_edit.max' => 'Máximo :max caracteres.',
            'fecha_factura_edit.required' => 'El campo fecha de factura es obligatorio.',
            'id_banco_factura_edit.required' => 'El campo banco factura es obligatorio.'
        ];
    }
}
