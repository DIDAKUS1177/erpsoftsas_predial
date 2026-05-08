<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PrediosPrescripcionesUpdateFormRequest extends FormRequest
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
            //'id_predio_edit' => 'required',
            'prescribe_desde_edit' => 'required',
            'prescribe_hasta_edit' => 'required'
        ];
    }

    public function messages()
    {
        return [
            //'id_predio_edit.required' => 'El campo código predio es obligatorio.',
            'prescribe_desde_edit.required' => 'El campo prescribe desde es obligatorio.',
            'prescribe_hasta_edit.required' => 'El campo prescribe hasta es obligatorio.'
        ];
    }
}
