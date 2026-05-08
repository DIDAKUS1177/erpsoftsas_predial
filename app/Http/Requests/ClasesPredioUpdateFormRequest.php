<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClasesPredioUpdateFormRequest extends FormRequest
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
            'nombre_edit'  => 'required|max:128'
        ];
    }

    public function messages()
    {
        return [
            //'codigo.required' => 'El campo código es obligatorio.',
            'nombre_edit.required' => 'El campo nombre es obligatorio.',
            //'codigo.max' => 'Máximo :max caracteres.',
            'nombre_edit.max' => 'Máximo :max caracteres.'
        ];
    }
}
