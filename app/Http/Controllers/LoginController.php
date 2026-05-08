<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginFormRequest;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function login(Request $request) {
        if ($request->session()->exists('userid')) {
            return redirect('profile');
        }

        return view('auth.login');
    }

    public function checkLogin(LoginFormRequest $request) {

        // $rules = [
        //     'email'     => 'required|email',
        //     'password'  => 'required'
        // ];

        // $customMessages = [
        //     'email.required' => 'El campo Email es obligatorio.',
        //     'password.required' => 'El campo Contraseña es obligatorio.',
        //     'email.email' => 'EL campo Email no es válido.',
        // ];

        // $this->validate($request, $rules, $customMessages);

        $usuario = DB::table('usuarios')->join('tipos_usuarios', function ($join) use($request) {
            $join->on('usuarios.id_tipo_usuario', '=', 'tipos_usuarios.id')
                ->where('usuarios.correo_electronico', '=', $request->email);
        })
        ->select('usuarios.*', 'tipos_usuarios.view', 'tipos_usuarios.descripcion')
        ->get()->first();

        $admin_text = DB::table('parametros')
                      ->select('parametros.valor')
                      ->where('parametros.nombre', 'admin-text')
                      ->first();

        $admin_text_dark = DB::table('parametros')
                           ->select('parametros.valor')
                           ->where('parametros.nombre', 'admin-text-dark')
                           ->first();

        if($usuario) {
            if(md5($request->password) == $usuario->password) {
                $request->session()->put('userid', $usuario->id);
                $request->session()->put('role', $usuario->view);
                $request->session()->put('desc_role', $usuario->descripcion);
                $request->session()->put('username', $usuario->nombres);
                $request->session()->put('userlastname', $usuario->apellidos);
                $request->session()->put('useremail', $usuario->correo_electronico);
                $request->session()->put('id_tipo_usuario', $usuario->id_tipo_usuario);
                $request->session()->put('admin_text', $admin_text->valor);
                $request->session()->put('admin_text_dark', $admin_text_dark->valor);
                return redirect('profile');
            }
            else {
                return back()->with('fail', 'Contraseña inválida');
            }
        }
        else {
            return back()->with('fail', 'No se ha encontrado el email ingresado');
        }
    }

    public function profile(Request $request) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }
        $opciones = DB::table('opciones')->join('opciones_tipos_usuarios', function ($join) use($request) {
            $join->on('opciones.id', '=', 'opciones_tipos_usuarios.id_opcion')
                ->where('opciones_tipos_usuarios.id_tipo_usuario', '=', $request->session()->get('id_tipo_usuario'));
        })
        ->select('opciones.*', 'opciones_tipos_usuarios.jerarquia')
        ->where('opciones.estado', 'A')
        ->orderBy('opciones_tipos_usuarios.jerarquia', 'asc')
        ->orderBy('opciones_tipos_usuarios.id_opcion', 'asc')
        ->get();

        $request->session()->put('opciones', $opciones);
        $request->session()->remove('search');
        $role = $request->session()->get('role');
        return view($role . '.profile', ['dato' => $role]);
    }

    public function logout(Request $request) {
        $request->session()->invalidate();
        return redirect('/');
    }
}
