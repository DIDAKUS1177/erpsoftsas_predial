<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsuariosCreateFormRequest extends FormRequest
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
            'nombres'  => 'required',
            'apellidos'  => 'required',
            'id_tipo_identificacion'  => 'required',
            'documento'  => 'required|unique:usuarios,documento',
            'correo_electronico'  => 'required|email|unique:usuarios,correo_electronico',
            'fecha_nacimiento'  => 'required',
            'telefono'  => 'required',
            'direccion'  => 'required',
            'password'  => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required|same:password',
            'id_tipo_usuario'  => 'required',
            'estado'  => 'required',
        ];
    }

    public function messages()
    {
        return [
            'nombres.required' => 'El campo Nombres es obligatorio.',
            'apellidos.required' => 'El campo Apellidos es obligatorio.',
            'id_tipo_identificacion.required' => 'El campo Tipos identificación es obligatorio.',
            'documento.required' => 'El campo Identificación es obligatorio.',
            'correo_electronico.required' => 'El campo Correo electrónico es obligatorio.',
            'correo_electronico.unique'   => 'El correo electrónico ya existe.',
            'correo_electronico.email'    => 'El correo electrónico es inválido.',
            'fecha_nacimiento.required' => 'El campo Fecha nacimiento es obligatorio.',
            'telefono.required' => 'El campo Teléfono es obligatorio.',
            'direccion.required' => 'El campo Dirección es obligatorio.',
            'documento.unique'   => 'La identificación ya existe.',
            'password.required' => 'El campo Nueva contraseña es obligatorio.',
            'password.min'      => 'La nueva contraseña es muy corta.',
            'password_confirmation.required' => 'El campo Repita la nueva contraseña es obligatorio.',
            'password.confirmed' => 'La confirmación de contraseña es erronea.',
            'password_confirmation.same' => 'La confirmación de contraseña es erronea.',
            'id_tipo_usuario.required' => 'El campo Tipo usuario es obligatorio.',
            'estado.required' => 'El campo Estado es obligatorio.',
        ];
    }
}
