<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PasswordChangeFormRequest;


use App\Models\Usuario;

class ChangePasswordController extends Controller
{
    public function index(Request $request) {

        $request->session()->remove('search');

        return view('auth.change_password');
    }

    public function changepass(PasswordChangeFormRequest $request) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }
        // $rules = [
        //     'current_password' => 'required',
        //     'password' => 'required|string|min:6|confirmed',
        //     'password_confirmation' => 'required|same:password',
        // ];

        // $customMessages = [
        //     'current_password.required' => 'El campo Contraseña actual es obligatorio.',
        //     'password.required' => 'El campo Nueva contraseña es obligatorio.',
        //     'password.min' => 'La nueva contraseña es muy corta.',
        //     'password_confirmation.required' => 'El campo Repita la nueva contraseña es obligatorio.',
        //     'password_confirmation.same' => 'La confirmación de contraseña es erronea.',
        // ];

        // $this->validate($request, $rules, $customMessages);

        $usuario = new Usuario;
        $usuario = Usuario::find($request->session()->get('userid'));

        if (md5($request->current_password) != $usuario->password) {
            return back()->with('fail', 'La contraseña actual no coincide!');
        }

        $usuario->password = md5($request->password);
        $query = $usuario->save();

        if($query) {
            return back()->with('success', 'La contraseña se ha actualizado!');
        }
        else {
            return back()->with('fail', 'No se pudo actualizar la informaci&oacute;n. Intente nuevamente.');
        }
    }
}
