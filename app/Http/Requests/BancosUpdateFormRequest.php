<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BancosUpdateFormRequest extends FormRequest
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
            //'codigo'  => 'required|max:10',
            'nombre_edit'  => 'required|max:128',
            'cuenta_contable_edit'  => 'required|max:30',
            //'asobancaria_edit'  => 'required|max:30'
        ];
    }

    public function messages()
    {
        return [
            //'codigo.required' => 'El campo código es obligatorio.',
            'nombre_edit.required' => 'El campo nombre es obligatorio.',
            //'codigo_edit.max' => 'Máximo :max caracteres.',
            'nombre_edit.max' => 'Máximo :max caracteres.',
            'cuenta_contable_edit.required'  => 'El campo cuenta contable es obligatorio.',
            //'asobancaria_edit.required'  => 'El campo asobancaria es obligatorio.',
            'cuenta_contable_edit.max' => 'Máximo :max caracteres.',
            //'asobancaria_edit.max' => 'Máximo :max caracteres.',
        ];
    }
}
