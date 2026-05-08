<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ClasesPredioCreateFormRequest;
use App\Http\Requests\ClasesPredioUpdateFormRequest;
use App\Models\ClasePredio;
use App\Models\Opcion;

class ClasesPredioController extends Controller
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
        $clases_predio = DB::table('clases_predio')->get(); //paginate(5);
        //$acudientes->appends($request->all());
        $tab_current = 'li-section-bar-1';
        if ($request->has('page')) {
            $tab_current = 'li-section-bar-2';
        }

        //$request->session()->put('search', '1');

        return view('clasespredio.create', ['opcion' => $opcion,
                                             'clases_predio' => $clases_predio,
                                             'tab_current' => $tab_current]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ClasesPredioCreateFormRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClasesPredioCreateFormRequest $request) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }

        $clase_predio = new ClasePredio();
        $clase_predio->codigo = $request->codigo;
        $clase_predio->nombre = $request->nombre;
        $query = $clase_predio->save();
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
     * @param  ClasesPredioUpdateFormRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(ClasesPredioUpdateFormRequest $request) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }

        $clase_predio = new ClasePredio();
        $clase_predio = ClasePredio::find($request->id_edit);
        //$clase_predio->codigo = $request->codigo_edit;
        $clase_predio->nombre = $request->nombre_edit;
        $query = $clase_predio->save();
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

        $countClasesPredio = DB::table('clases_predio')
                            ->select(DB::raw('count(*) as cm_count')) // verificar si ya esta relacionada con otras tablas
                            ->where('id', $request->input_delete)->first();

        $tab_current = 'li-section-bar-2';
        $clase_predio = new ClasePredio();
        $clase_predio = ClasePredio::find($request->input_delete);

        //if($countClasesPredio->cm_count == 0) {
            $query = $clase_predio->delete();
            if($query) {
                return back()->with(['success' => 'El registro se elimin&oacute; satisfactoriamente.', 'tab_current' => $tab_current]);
            }
            else {
                return back()->with(['fail' => 'No se pudo eliminar la informaci&oacute;n. Intente nuevamente.', 'tab_current' => $tab_current]);
            }
        // }
        // else {
        //     return back()->with(['fail' => 'No se pudo eliminar la informaci&oacute;n. La clase de mutaci&oacute;n <b>' . $clase_predio->nombre . ' (' . $clase_predio->codigo . ')</b> ya posee informaci&oacute;n asociada.', 'tab_current' => $tab_current]);
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

    //     $countClasesPredio = DB::table('usuarios')
    //                         ->leftJoin('fonoaudiologos', 'fonoaudiologos.usuarios_ide_usu', '=', 'usuarios.ide_usu')
    //                         ->select(DB::raw('count(*) as usuarios_count'))
    //                         ->where('usuarios.ide_usu', $request->input_delete)
    //                         ->where(function($query) {
    //                             $query->orWhereNotNull('fonoaudiologos.usuarios_ide_usu');
    //                         })->first();

    //     $tab_current = 'li-section-bar-2';
    //     $usuario = new Usuario;
    //     $usuario = Usuario::find($request->input_delete);

    //     if($countClasesPredio->usuarios_count == 0) {
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
