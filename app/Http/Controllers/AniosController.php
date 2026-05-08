<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\AniosCreateFormRequest;
use App\Http\Requests\AniosUpdateFormRequest;
use App\Models\Anio;
use App\Models\Opcion;

class AniosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $id) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }
        $opcion = Opcion::where('id','=', base64_decode($id))->first();
        $anios = DB::table('anios')->join('estados_anio', function ($join) {
                    $join->on('anios.id_estado', '=', 'estados_anio.id');
                })
                ->select('anios.*', 'estados_anio.descripcion')
                ->get(); //paginate(5);

        $estados_anio = DB::table('estados_anio')->get();
        $tipos_tasas = DB::table('tipos_tasas_interes')->get();

        $tab_current = 'li-section-bar-1';
        if ($request->has('page')) {
            $tab_current = 'li-section-bar-2';
        }

        //$request->session()->put('search', '1');

        return view('anios.create', ['opcion' => $opcion,
                                     'anios' => $anios,
                                     'estados_anio' => $estados_anio,
                                     'tipos_tasas' => $tipos_tasas,
                                     'tab_current' => $tab_current]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  AniosCreateFormRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AniosCreateFormRequest $request) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }

        $anio = new Anio();
        $anio->anio = $request->anio;
        $anio->id_estado = $request->id_estado;
        $anio->meses_amnistia = $request->meses_amnistia;
        $anio->id_tipo_tasa_interes = $request->id_tipo_tasa_interes;
        $query = $anio->save();
        $tab_current = 'li-section-bar-1';

        if($query) {
            return back()->with(['success' => 'La informaci&oacute;n se guard&oacute; satisfactoriamente.', 'tab_current' => $tab_current]);
        }
        else {
            return back()->with(['fail' => 'No se pudo guardar la informaci&oacute;n. Intente nuevamente.', 'tab_current' => $tab_current]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  AniosUpdateFormRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(AniosUpdateFormRequest $request) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }

        $anio = new Anio();
        $anio = Anio::find($request->id_edit);
        //$anio->anio = $request->anio_edit;
        $anio->id_estado = $request->id_estado_edit;
        $anio->meses_amnistia = $request->meses_amnistia_edit;
        $anio->id_tipo_tasa_interes = $request->id_tipo_tasa_interes_edit;
        $query = $anio->save();
        $tab_current = 'li-section-bar-2';

        if($query) {
            return back()->with(['success' => 'La informaci&oacute;n se actualiz&oacute; satisfactoriamente.', 'tab_current' => $tab_current]);
        }
        else {
            return back()->with(['fail' => 'No se pudo actualizar la informaci&oacute;n. Intente nuevamente.', 'tab_current' => $tab_current]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }

        $countAnios = DB::table('anios')
                            ->select(DB::raw('count(*) as cm_count')) // verificar si ya esta relacionada con otras tablas
                            ->where('id', $request->input_delete)->first();

        $tab_current = 'li-section-bar-2';
        $anio = new Anio();
        $anio = Anio::find($request->input_delete);

        //if($countAnios->cm_count == 0) {
            $query = $anio->delete();
            if($query) {
                return back()->with(['success' => 'El registro se elimin&oacute; satisfactoriamente.', 'tab_current' => $tab_current]);
            }
            else {
                return back()->with(['fail' => 'No se pudo eliminar la informaci&oacute;n. Intente nuevamente.', 'tab_current' => $tab_current]);
            }
        // }
        // else {
        //     return back()->with(['fail' => 'No se pudo eliminar la informaci&oacute;n. La clase de mutaci&oacute;n <b>' . $anio->nombre . ' (' . $anio->codigo . ')</b> ya posee informaci&oacute;n asociada.', 'tab_current' => $tab_current]);
        // }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function destroy($id)
    // {
    //     if (!$request->session()->exists('userid')) {
    //         return redirect('/');
    //     }

    //     $countAnios = DB::table('usuarios')
    //                         ->leftJoin('fonoaudiologos', 'fonoaudiologos.usuarios_ide_usu', '=', 'usuarios.ide_usu')
    //                         ->select(DB::raw('count(*) as usuarios_count'))
    //                         ->where('usuarios.ide_usu', $request->input_delete)
    //                         ->where(function($query) {
    //                             $query->orWhereNotNull('fonoaudiologos.usuarios_ide_usu');
    //                         })->first();

    //     $tab_current = 'li-section-bar-2';
    //     $usuario = new Usuario;
    //     $usuario = Usuario::find($request->input_delete);

    //     if($countAnios->usuarios_count == 0) {
    //         $query = $usuario->delete();
    //         if($query) {
    //             return back()->with(['success' => 'El registro se elimin&oacute; satisfactoriamente.', 'tab_current' => $tab_current]);
    //         }
    //         else {
    //             return back()->with(['fail' => 'No se pudo eliminar la informaci&oacute;n. Intente nuevamente.', 'tab_current' => $tab_current]);
    //         }
    //     }
    //     else {
    //         return back()->with(['fail' => 'No se pudo eliminar la informaci&oacute;n. El usuario <b>' . $usuario->nom_usu . ' ' . $usuario->ape_usu . '</b> ya posee informaci&oacute;n asociada.', 'tab_current' => $tab_current]);
    //     }
    // }
}
