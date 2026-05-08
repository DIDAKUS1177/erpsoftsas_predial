<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\TasasInteresCreateFormRequest;
use App\Http\Requests\TasasInteresUpdateFormRequest;
use App\Models\TasaInteres;
use App\Models\Opcion;

class TasasInteresController extends Controller
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
        $tasas_interes = DB::table('tasas_interes')
                         ->selectRaw("tasas_interes.id, tasas_interes.anio, tasas_interes.mes, FORMAT(tasas_interes.tasa_diaria, 'N12') as tasa_diaria, FORMAT(tasas_interes.tasa_mensual, 'N12') as tasa_mensual, FORMAT(tasas_interes.tasa_acuerdo, 'N12') as tasa_acuerdo, tasas_interes.created_at, tasas_interes.updated_at")
                         ->orderByDesc('anio')
                         ->orderByDesc('mes')
                         ->get(); //paginate(5);

        $tab_current = 'li-section-bar-1';
        if ($request->has('page')) {
            $tab_current = 'li-section-bar-2';
        }

        //$request->session()->put('search', '1');

        return view('tasasinteres.create', ['opcion' => $opcion,
                                             'tasas_interes' => $tasas_interes,
                                             'tab_current' => $tab_current]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  TasasInteresCreateFormRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TasasInteresCreateFormRequest $request) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }

        $rules = [
            'anio' => 'unique_with:tasas_interes,mes'
        ];

        $validator = Validator::make($request->all(), $rules, $messages = [
            'anio.unique_with' => 'La combinación de año y mes ya existe. Verifique la información ingresada.'
        ]);

        if($validator->fails()) {
            return back()->withErrors($validator)
                         ->withInput();
        }

        $tasa_interes = new TasaInteres();
        $tasa_interes->anio = $request->anio;
        $tasa_interes->mes = $request->mes;
        $tasa_interes->tasa_diaria = $request->tasa_diaria;
        $tasa_interes->tasa_mensual = $request->tasa_mensual;
        $tasa_interes->tasa_acuerdo = $request->tasa_acuerdo;
        $query = $tasa_interes->save();
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
     * @param  TasasInteresUpdateFormRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(TasasInteresUpdateFormRequest $request) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }

        $tasa_interes = new TasaInteres();
        $tasa_interes = TasaInteres::find($request->id_edit);
        $tasa_interes->anio = $request->anio_edit;
        $tasa_interes->mes = $request->mes_edit;
        $tasa_interes->tasa_diaria = $request->tasa_diaria_edit;
        $tasa_interes->tasa_mensual = $request->tasa_mensual_edit;
        $tasa_interes->tasa_acuerdo = $request->tasa_acuerdo_edit;
        $query = $tasa_interes->save();
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

        $countTasasInteres = DB::table('tasas_interes')
                            ->select(DB::raw('count(*) as cm_count')) // verificar si ya esta relacionada con otras tablas
                            ->where('id', $request->input_delete)->first();

        $tab_current = 'li-section-bar-2';
        $tasa_interes = new TasaInteres();
        $tasa_interes = TasaInteres::find($request->input_delete);

        //if($countTasasInteres->cm_count == 0) {
            $query = $tasa_interes->delete();
            if($query) {
                return back()->with(['success' => 'El registro se elimin&oacute; satisfactoriamente.', 'tab_current' => $tab_current]);
            }
            else {
                return back()->with(['fail' => 'No se pudo eliminar la informaci&oacute;n. Intente nuevamente.', 'tab_current' => $tab_current]);
            }
        // }
        // else {
        //     return back()->with(['fail' => 'No se pudo eliminar la informaci&oacute;n. La clase de mutaci&oacute;n <b>' . $tasa_interes->nombre . ' (' . $tasa_interes->codigo . ')</b> ya posee informaci&oacute;n asociada.', 'tab_current' => $tab_current]);
        // }
    }
}
