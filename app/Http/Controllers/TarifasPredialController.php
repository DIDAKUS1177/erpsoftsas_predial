<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\TarifasPredialCreateFormRequest;
use App\Http\Requests\TarifasPredialUpdateFormRequest;
use App\Models\TarifaPredial;
use App\Models\Opcion;

class TarifasPredialController extends Controller
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
        $tarifas_predial = DB::table('tarifas_predial')->get(); //paginate(5);
        //$acudientes->appends($request->all());
        $tab_current = 'li-section-bar-1';
        if ($request->has('page')) {
            $tab_current = 'li-section-bar-2';
        }

        //$request->session()->put('search', '1');

        return view('tarifaspredial.create', ['opcion' => $opcion,
                                             'tarifas_predial' => $tarifas_predial,
                                             'tab_current' => $tab_current]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  TarifasPredialCreateFormRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TarifasPredialCreateFormRequest $request) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }

        $rules = [
            'codigo' => 'unique_with:tarifas_predial,anio',
            'rango' => 'unique_with:tarifas_predial,anio',
        ];

        $validator = Validator::make($request->all(), $rules, $messages = [
            'codigo.unique_with' => 'La combinación de año y código ya existe.',
            'rango.unique_with' => 'La combinación de año y rango ya existe.',
        ]);

        if($validator->fails()) {
            return back()->withErrors($validator)
                         ->withInput();
        }

        $tarifa_predial = new TarifaPredial();
        $tarifa_predial->anio = $request->anio;
        //$tarifa_predial->meses_amnistia = $request->meses_amnistia;
        $tarifa_predial->codigo = $request->codigo;
        $tarifa_predial->rango = $request->rango;
        $tarifa_predial->descripcion = $request->descripcion;
        $tarifa_predial->avaluo_inicial = $request->avaluo_inicial;
        $tarifa_predial->avaluo_final = $request->avaluo_final;
        $tarifa_predial->tarifa = $request->tarifa;
        $tarifa_predial->porcentaje_car = $request->porcentaje_car;
        $tarifa_predial->estrato = $request->filled('estrato') ? $request->estrato : 0;
        $tarifa_predial->destino = $request->filled('destino') ? $request->destino : 0;
        $tarifa_predial->grupo_tarifa = $request->filled('grupo_tarifa') ? $request->grupo_tarifa : 0;
        $query = $tarifa_predial->save();
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
     * @param  TarifasPredialUpdateFormRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(TarifasPredialUpdateFormRequest $request) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }

        $tarifa_predial = new TarifaPredial();
        $tarifa_predial = TarifaPredial::find($request->id_edit);
        //$tarifa_predial->anio = $request->anio_edit;
        //$tarifa_predial->meses_amnistia = $request->meses_amnistia_edit;
        //$tarifa_predial->codigo = $request->codigo_edit;
        //$tarifa_predial->rango = $request->rango_edit;
        $tarifa_predial->descripcion = $request->descripcion_edit;
        $tarifa_predial->avaluo_inicial = $request->avaluo_inicial_edit;
        $tarifa_predial->avaluo_final = $request->avaluo_final_edit;
        $tarifa_predial->tarifa = $request->tarifa_edit;
        $tarifa_predial->porcentaje_car = $request->porcentaje_car_edit;
        $tarifa_predial->estrato = $request->filled('estrato_edit') ? $request->estrato_edit : 0;
        $tarifa_predial->destino = $request->filled('destino_edit') ? $request->destino_edit : 0;
        $tarifa_predial->grupo_tarifa = $request->filled('grupo_tarifa_edit') ? $request->grupo_tarifa_edit : 0;
        $query = $tarifa_predial->save();
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

        $countTarifasPredial = DB::table('tarifas_predial')
                            ->select(DB::raw('count(*) as cm_count')) // verificar si ya esta relacionada con otras tablas
                            ->where('id', $request->input_delete)->first();

        $tab_current = 'li-section-bar-2';
        $tarifa_predial = new TarifaPredial();
        $tarifa_predial = TarifaPredial::find($request->input_delete);

        //if($countTarifasPredial->cm_count == 0) {
            $query = $tarifa_predial->delete();
            if($query) {
                return back()->with(['success' => 'El registro se elimin&oacute; satisfactoriamente.', 'tab_current' => $tab_current]);
            }
            else {
                return back()->with(['fail' => 'No se pudo eliminar la informaci&oacute;n. Intente nuevamente.', 'tab_current' => $tab_current]);
            }
        // }
        // else {
        //     return back()->with(['fail' => 'No se pudo eliminar la informaci&oacute;n. La clase de mutaci&oacute;n <b>' . $tarifa_predial->nombre . ' (' . $tarifa_predial->codigo . ')</b> ya posee informaci&oacute;n asociada.', 'tab_current' => $tab_current]);
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

    //     $countTarifasPredial = DB::table('usuarios')
    //                         ->leftJoin('fonoaudiologos', 'fonoaudiologos.usuarios_ide_usu', '=', 'usuarios.ide_usu')
    //                         ->select(DB::raw('count(*) as usuarios_count'))
    //                         ->where('usuarios.ide_usu', $request->input_delete)
    //                         ->where(function($query) {
    //                             $query->orWhereNotNull('fonoaudiologos.usuarios_ide_usu');
    //                         })->first();

    //     $tab_current = 'li-section-bar-2';
    //     $usuario = new Usuario;
    //     $usuario = Usuario::find($request->input_delete);

    //     if($countTarifasPredial->usuarios_count == 0) {
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
