<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClasesMutacionCreateFormRequest extends FormRequest
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
            'codigo'  => 'required|unique:clases_mutacion',
            'nombre'  => 'required|max:128'
        ];
    }

    public function messages()
    {
        return [
            'codigo.required' => 'El campo código es obligatorio.',
            'codigo.unique' => 'El código ya existe.',
            'nombre.required' => 'El campo nombre es obligatorio.',
            'nombre.max' => 'Máximo :max caracteres.'
        ];
    }
}
