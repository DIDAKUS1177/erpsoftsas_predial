<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BancosCreateFormRequest extends FormRequest
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
            'codigo'  => 'required|unique:bancos|max:10',
            'nombre'  => 'required|max:128',
            'cuenta_contable'  => 'required|max:30',
            'asobancaria'  => 'required|unique:bancos|max:30'
        ];
    }

    public function messages()
    {
        return [
            'codigo.required' => 'El campo código es obligatorio.',
            'codigo.unique' => 'El código bancario ya existe.',
            'nombre.required' => 'El campo nombre es obligatorio.',
            'codigo.max' => 'Máximo :max caracteres.',
            'nombre.max' => 'Máximo :max caracteres.',
            'cuenta_contable.required'  => 'El campo cuenta contable es obligatorio.',
            'asobancaria.required'  => 'El campo asobancaria es obligatorio.',
            'cuenta_contable.max' => 'Máximo :max caracteres.',
            'asobancaria.max' => 'Máximo :max caracteres.',
            'asobancaria.unique' => 'El código asobancario ya existe.'
        ];
    }
}
