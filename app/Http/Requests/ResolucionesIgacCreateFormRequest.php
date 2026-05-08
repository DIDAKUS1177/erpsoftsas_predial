<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResolucionesIgacCreateFormRequest extends FormRequest
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
            'resolucion'  => 'required|max:30'
        ];
    }

    public function messages()
    {
        return [
            'resolucion.required' => 'El campo resolucion es obligatorio.',
            'resolucion.max' => 'Máximo :max caracteres.'
        ];
    }
}
