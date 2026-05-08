<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\UsuariosCreateFormRequest;
use App\Http\Requests\UsuariosUpdateFormRequest;

use App\Models\Opcion;
use App\Models\Usuario;

class AdministracionController extends Controller {

    public function registro_usuarios(Request $request, $id) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }
        $opcion = Opcion::where('id','=', base64_decode($id))->first();
        $usuarios = DB::table('usuarios')
        ->join('tipos_usuarios', 'usuarios.id_tipo_usuario', '=', 'tipos_usuarios.id')
        ->join('tipos_identificacion', 'usuarios.id_tipo_identificacion', '=', 'tipos_identificacion.id')
        ->where('usuarios.id', '<>', '1')
        ->select('usuarios.*', 'tipos_usuarios.descripcion', 'tipos_identificacion.abreviacion')
        ->get();

        $tipos_usuarios = DB::table('tipos_usuarios')->get();
        $tipos_identificacion = DB::table('tipos_identificacion')->get();
        $tab_current = 'li-section-bar-1';

        return view('admin.registro_usuarios', ['opcion' => $opcion,
                                                'usuarios' => $usuarios,
                                                'tipos_usuarios' => $tipos_usuarios,
                                                'tipos_identificacion' => $tipos_identificacion,
                                                'tab_current' => $tab_current]);
    }

    public function createusu(UsuariosCreateFormRequest $request) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }

        $usuario = new Usuario;
        $usuario->nombres = $request->nombres;
        $usuario->apellidos = $request->apellidos;
        $usuario->id_tipo_identificacion = $request->id_tipo_identificacion;
        $usuario->documento = $request->documento;
        $usuario->correo_electronico = $request->correo_electronico;
        $date_variable = $request->fecha_nacimiento;
        $usuario->fecha_nacimiento = $date_variable;
        $usuario->telefono = $request->telefono;
        $usuario->direccion = $request->direccion;
        $usuario->password = md5($request->password);
        $usuario->id_tipo_usuario = $request->id_tipo_usuario;
        $usuario->estado = $request->estado;
        $query = $usuario->save();
        $tab_current = 'li-section-bar-1';

        if($query) {
            return back()->with(['success' => 'La informaci&oacute;n se guard&oacute; satisfactoriamente.', 'tab_current' => $tab_current]);
        }
        else {
            return back()->with(['fail' => 'No se pudo guardar la informaci&oacute;n. Intente nuevamente.', 'tab_current' => $tab_current]);
        }
    }

    public function updateusu(UsuariosUpdateFormRequest $request) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }

        $usuario = new Usuario;
        $usuario = Usuario::find($request->id_edit);

        $usuario->nombres = $request->nombres_edit;
        $usuario->apellidos = $request->apellidos_edit;
        $usuario->id_tipo_identificacion = $request->id_tipo_identificacion_edit;
        $usuario->documento = $request->documento_edit;
        $usuario->correo_electronico = $request->correo_electronico_edit;
        $date_variable = $request->fecha_nacimiento_edit;
        $usuario->fecha_nacimiento = $date_variable;
        $usuario->telefono = $request->telefono_edit;
        $usuario->direccion = $request->direccion_edit;

        if ($request->password_edit != $usuario->password) {
            $usuario->password = md5($request->password_edit);
        }

        $usuario->id_tipo_usuario = $request->id_tipo_usuario_edit;
        $usuario->estado = $request->estado_edit;

        $query = $usuario->save();
        $tab_current = 'li-section-bar-2';

        if($query) {
            return back()->with(['success' => 'La informaci&oacute;n se actualiz&oacute; satisfactoriamente.', 'tab_current' => $tab_current]);
        }
        else {
            return back()->with(['fail' => 'No se pudo actualizar la informaci&oacute;n. Intente nuevamente.', 'tab_current' => $tab_current]);
        }
    }

    public function deleteusu(Request $request) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }

        $countUsuarios = DB::table('usuarios')
                            ->leftJoin('resoluciones_predios', 'resoluciones_predios.id_usuario', '=', 'usuarios.id')
                            ->select(DB::raw('count(*) as usuarios_count'))
                            ->where('usuarios.id', $request->input_delete)
                            ->where(function($query) {
                                $query->orWhereNotNull('resoluciones_predios.id_usuario');
                            })->first();

        $tab_current = 'li-section-bar-2';
        $usuario = new Usuario;
        $usuario = Usuario::find($request->input_delete);

        if($countUsuarios->usuarios_count == 0) {
            $query = $usuario->delete();
            if($query) {
                return back()->with(['success' => 'El registro se elimin&oacute; satisfactoriamente.', 'tab_current' => $tab_current]);
            }
            else {
                return back()->with(['fail' => 'No se pudo eliminar la informaci&oacute;n. Intente nuevamente.', 'tab_current' => $tab_current]);
            }
        }
        else {
            return back()->with(['fail' => 'No se pudo eliminar la informaci&oacute;n. El usuario <b>' . $usuario->nombres . ' ' . $usuario->apellidos . '</b> ya posee informaci&oacute;n asociada a procesos internos de negocio.', 'tab_current' => $tab_current]);
        }
    }
}
