<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsuariosUpdateFormRequest extends FormRequest
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
            'nombres_edit'  => 'required',
            'apellidos_edit'  => 'required',
            'id_tipo_identificacion_edit'  => 'required',
            'documento_edit'  => 'required',
            'correo_electronico_edit'  => 'required|email|unique:usuarios,correo_electronico,'. $this->documento_edit . ',documento',
            'fecha_nacimiento_edit'  => 'required',
            'telefono_edit'  => 'required',
            'direccion_edit'  => 'required',
            'password_edit'  => 'required|string|min:6|confirmed',
            'password_edit_confirmation' => 'required|same:password_edit',
            'id_tipo_usuario_edit'  => 'required',
            'estado_edit'  => 'required',
        ];
    }

    public function  messages() {
        return [
            'nombres_edit.required' => 'El campo Nombres es obligatorio.',
            'apellidos_edit.required' => 'El campo Apellidos es obligatorio.',
            'id_tipo_identificacion_edit.required' => 'El campo Tipos identificación es obligatorio.',
            'documento_edit.required' => 'El campo Identificación es obligatorio.',
            'correo_electronico_edit.required' => 'El campo Correo electrónico es obligatorio.',
            'correo_electronico_edit.email'   => 'El correo electrónico es inválido.',
            'correo_electronico_edit.unique'   => 'El correo electrónico ya existe.',
            'fecha_nacimiento_edit.required' => 'El campo Fecha nacimiento es obligatorio.',
            'telefono_edit.required' => 'El campo Teléfono es obligatorio.',
            'direccion_edit.required' => 'El campo Dirección es obligatorio.',
            'password_edit.required' => 'El campo Nueva contraseña es obligatorio.',
            'password_edit.min'      => 'La nueva contraseña es muy corta.',
            'password_edit_confirmation.required' => 'El campo Repita la nueva contraseña es obligatorio.',
            'password_edit.confirmed' => 'La confirmación de contraseña es erronea.',
            'password_edit_confirmation.same' => 'La confirmación de contraseña es erronea.',
            'id_tipo_usuario_edit.required' => 'El campo Tipo usuario es obligatorio.',
            'estado_edit.required' => 'El campo Estado es obligatorio.',
        ];
    }
}
