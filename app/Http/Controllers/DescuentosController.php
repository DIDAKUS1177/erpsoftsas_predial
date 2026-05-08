<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\DescuentosCreateFormRequest;
use App\Http\Requests\DescuentosUpdateFormRequest;
use App\Models\Descuento;
use App\Models\Opcion;

use Carbon\Carbon;


class DescuentosController extends Controller
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
        $descuentos = DB::table('descuentos')->get(); //paginate(5);

        $tab_current = 'li-section-bar-1';
        if ($request->has('page')) {
            $tab_current = 'li-section-bar-2';
        }

        //$request->session()->put('search', '1');
        $dt = Carbon::now();

        return view('descuentos.create', ['opcion' => $opcion,
                                          'descuentos' => $descuentos,
                                          'datenow' => $dt->toDateString(),
                                          'tab_current' => $tab_current]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  DescuentosCreateFormRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DescuentosCreateFormRequest $request) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }

        // $rules = [
        //     'anio' => 'unique_with:descuentos,fecha_limite'
        // ];

        // $validator = Validator::make($request->all(), $rules, $messages = [
        //     'anio.unique_with' => 'La combinación de año y fecha limite ya existe.',
        // ]);

        // if($validator->fails()) {
        //     return back()->withErrors($validator)
        //                  ->withInput();
        // }

        $descuento = new Descuento();
        $descuento->anio = $request->anio;
        $descuento->fecha_inicio = Carbon::createFromFormat("Y-m-d", $request->fecha_inicio)->format('Y-m-d');
        $descuento->fecha_fin = Carbon::createFromFormat("Y-m-d", $request->fecha_fin)->format('Y-m-d');
        $descuento->porcentaje = $request->porcentaje;
        $query = $descuento->save();
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
     * @param  DescuentosUpdateFormRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(DescuentosUpdateFormRequest $request) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }

        // $rules = [
        //     'fecha_limite_edit = fecha_limite' => 'unique_with:descuentos,anio_edit = anio,' . $request->id_edit
        // ];

        // $validator = Validator::make($request->all(), $rules, $messages = [
        //     'fecha_limite_edit.unique_with' => 'La combinacion de año y fecha limite ya existe.',
        // ]);

        // if($validator->fails()) {
        //     return back()->withErrors($validator)
        //                  ->withInput();
        // }

        $descuento = new Descuento();
        $descuento = Descuento::find($request->id_edit);
        //$descuento->anio = $request->anio_edit;
        //$descuento->fecha_limite = Carbon::createFromFormat("Y-m-d", $request->fecha_limite_edit)->format('Y-m-d');
        $descuento->porcentaje = $request->porcentaje_edit;
        $query = $descuento->save();
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

        $countDescuentos = DB::table('descuentos')
                            ->select(DB::raw('count(*) as cm_count')) // verificar si ya esta relacionada con otras tablas
                            ->where('id', $request->input_delete)->first();

        $tab_current = 'li-section-bar-2';
        $descuento = new Descuento();
        $descuento = Descuento::find($request->input_delete);

        //if($countDescuentos->cm_count == 0) {
            $query = $descuento->delete();
            if($query) {
                return back()->with(['success' => 'El registro se elimin&oacute; satisfactoriamente.', 'tab_current' => $tab_current]);
            }
            else {
                return back()->with(['fail' => 'No se pudo eliminar la informaci&oacute;n. Intente nuevamente.', 'tab_current' => $tab_current]);
            }
        // }
        // else {
        //     return back()->with(['fail' => 'No se pudo eliminar la informaci&oacute;n. La clase de mutaci&oacute;n <b>' . $descuento->nombre . ' (' . $descuento->codigo . ')</b> ya posee informaci&oacute;n asociada.', 'tab_current' => $tab_current]);
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

    //     $countDescuentos = DB::table('usuarios')
    //                         ->leftJoin('fonoaudiologos', 'fonoaudiologos.usuarios_ide_usu', '=', 'usuarios.ide_usu')
    //                         ->select(DB::raw('count(*) as usuarios_count'))
    //                         ->where('usuarios.ide_usu', $request->input_delete)
    //                         ->where(function($query) {
    //                             $query->orWhereNotNull('fonoaudiologos.usuarios_ide_usu');
    //                         })->first();

    //     $tab_current = 'li-section-bar-2';
    //     $usuario = new Usuario;
    //     $usuario = Usuario::find($request->input_delete);

    //     if($countDescuentos->usuarios_count == 0) {
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
