<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConceptosPredioCreateFormRequest extends FormRequest
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
            'anio'  => 'required|size:4',
            'meses_amnistia'  => 'required',
            'prioridad'  => 'required',
            'codigo'  => 'required|max:3',
            'nombre'  => 'required|max:128',
            'formula'  => 'required|max:1024',
            //'capital'  => 'required|numeric|min:1',
            'minimo_urbano'  => 'required',
            'minimo_rural'  => 'required'
            //'aplica_interes' => 'numeric|min:1',
            //'interes' => 'numeric|numeric|min:1' //'required_if:aplica_interes,=,1|numeric|min:1'
        ];
    }

    public function messages()
    {
        return [
            'anio.required'  => 'El campo año es obligatorio.',
            'anio.size' => 'El campo año debe contener 4 digitos.',
            'meses_amnistia.required'  => 'El campo mes amnistia es obligatorio.',
            'prioridad.required'  => 'El campo prioridad es obligatorio.',
            'codigo.required' => 'El campo código es obligatorio.',
            'nombre.required' => 'El campo nombre es obligatorio.',
            'formula.required' => 'El campo nombre es obligatorio.',
            'codigo.max' => 'Máximo :max caracteres.',
            'nombre.max' => 'Máximo :max caracteres.',
            'formula.max' => 'Máximo :max caracteres.',
            //'capital.required'  => 'El campo capital es obligatorio.',
            'minimo_urbano.required'  => 'El campo mínimo urbano es obligatorio.',
            'minimo_rural.required'  => 'El campo mínimo rural es obligatorio.'
            //'aplica_interes.numeric' => 'El campo aplica interés debe ser numerico.',
            //'interes.required' => 'El campo interés es obligatorio.',
            //'interes.min' => 'Valor mínimo de interés :min.'
        ];
    }
}
