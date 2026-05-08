<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConceptosPredioUpdateFormRequest extends FormRequest
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
            //'anio_edit'  => 'required',
            'meses_amnistia_edit'  => 'required',
            'prioridad_edit'  => 'required',
            //'codigo_edit'  => 'required|max:3',
            'nombre_edit'  => 'required|max:128',
            'formula_edit'  => 'required|max:1024',
            //'capital_edit'  => 'required',
            'minimo_urbano_edit'  => 'required',
            'minimo_rural_edit'  => 'required',
            //'aplica_interes_edit' => 'numeric',
            //'interes_edit' => 'required_if:aplica_interes_edit,=,1|numeric|min:1'
        ];
    }

    public function messages()
    {
        return [
            //'anio_edit.required'  => 'El campo año es obligatorio.',
            'meses_amnistia_edit.required'  => 'El campo mes amnistia es obligatorio.',
            'prioridad_edit.required'  => 'El campo prioridad es obligatorio.',
            //'codigo_edit.required' => 'El campo código es obligatorio.',
            'nombre_edit.required' => 'El campo nombre es obligatorio.',
            'formula_edit.required' => 'El campo nombre es obligatorio.',
            //'codigo_edit.max' => 'Máximo :max caracteres.',
            'nombre_edit.max' => 'Máximo :max caracteres.',
            'formula_edit.max' => 'Máximo :max caracteres.',
            //'capital_edit.required'  => 'El campo capital es obligatorio.',
            'minimo_urbano_edit.required'  => 'El campo mínimo urbano es obligatorio.',
            'minimo_rural_edit.required'  => 'El campo mínimo rural es obligatorio.',
            //'aplica_interes_edit.numeric' => 'El campo aplica interés debe ser numerico.',
            //'interes_edit.required' => 'El campo interés es obligatorio.',
            //'interes_edit.min' => 'Valor mínimo de interés :min.'
        ];
    }
}
