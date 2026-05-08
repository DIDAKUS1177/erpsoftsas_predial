<?php

namespace App\Http\Requests;
//namespace App\Http\Requests\Rule;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NotasCreateFormRequest extends FormRequest
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
            'id_predio' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'id_predio.required' => 'El campo predio es obligatorio.',
        ];
    }
}
