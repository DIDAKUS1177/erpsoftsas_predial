<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResolucionesIgacUpdateFormRequest extends FormRequest
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
            'resolucion_edit'  => 'required|max:25'
        ];
    }

    public function messages()
    {
        return [
            'resolucion_edit.required' => 'El campo resolucion es obligatorio.',
            'resolucion_edit.max' => 'Máximo :max caracteres.'
        ];
    }
}
