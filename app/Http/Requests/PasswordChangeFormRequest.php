<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PasswordChangeFormRequest extends FormRequest
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
            'current_password' => 'required',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required|same:password',
        ];
    }

    public function messages()
    {
        return [
            'current_password.required' => 'El campo Contraseña actual es obligatorio.',
            'password.required' => 'El campo Nueva contraseña es obligatorio.',
            'password.min' => 'La nueva contraseña es muy corta.',
            'password_confirmation.required' => 'El campo Repita la nueva contraseña es obligatorio.',
            'password_confirmation.same' => 'La confirmación de contraseña es erronea.',
        ];
    }
}
