<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\PrediosPrescripcionesCreateFormRequest;
use App\Http\Requests\PrediosPrescripcionesUpdateFormRequest;
use App\Models\Opcion;
use App\Models\Predio;
use App\Models\PredioPago;
use App\Models\PredioPrescripcion;
use App\Models\Resolucion;
use App\Models\ResolucionPredio;

use Carbon\Carbon;

class PrediosPrescripcionesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        //
    }

    /**
     * Show the form for prescripcion de vigencia of a resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $id) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }
        $opcion = Opcion::where('id','=', base64_decode($id))->first();

        $prescripciones = DB::table('predios_prescripciones')
                    ->join('predios', 'predios_prescripciones.id_predio', '=', 'predios.id')
                    ->select(DB::raw('TRY_CONVERT(VARCHAR, predios_prescripciones.created_at, 20) AS created_at, MIN(predios_prescripciones.prescribe_anio) AS prescribe_desde, MAX(predios_prescripciones.prescribe_anio) AS prescribe_hasta, MAX(predios_prescripciones.id) AS id, predios.codigo_predio, predios_prescripciones.file_name'))
                    ->groupByRaw('predios.codigo_predio, predios_prescripciones.file_name, TRY_CONVERT(VARCHAR, predios_prescripciones.created_at, 20)')
                    ->orderByRaw('prescribe_desde')
                    ->get();

        // $predios = DB::table('predios')
        //             ->leftJoin('predios_prescripciones', function ($join) {
        //                 $join->on('predios.id', '=', 'predios_prescripciones.id_predio');
        //             })
        //             ->select('predios.id', 'predios.codigo_predio')
        //             ->whereNull('predios_prescripciones.id')
        //             ->where('predios.estado', 1)
        //             ->get();

        $tab_current = 'li-section-bar-1';
        if ($request->has('page')) {
            $tab_current = 'li-section-bar-2';
        }

        //$request->session()->put('display_buttons', '1');

        return view('prescripciones.create', ['opcion' => $opcion,
                                            'prescripciones' => $prescripciones,
                                            //'predios' => $predios,
                                            'tab_current' => $tab_current]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  PrediosPrescripcionesCreateFormRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PrediosPrescripcionesCreateFormRequest $request) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }

        $tab_current = 'li-section-bar-1';
        DB::beginTransaction();
        try {
            $predio = new Predio();
            $predio = Predio::find($request->id_predio);

            for($i = intval($request->prescribe_desde); $i <= intval($request->prescribe_hasta); $i++) {
                $prescripcion = DB::table('predios_prescripciones')
                    ->where('id_predio', $predio->id)
                    ->where('prescribe_anio', $i)
                    ->first();

                if ($prescripcion == null) {
                    $valores_predio_pago = DB::table('predios_pagos')
                        ->select(DB::raw("
                            predios_pagos.valor_concepto1,
                            predios_pagos.valor_concepto2,
                            predios_pagos.valor_concepto3,
                            predios_pagos.valor_concepto4,
                            predios_pagos.valor_concepto5,
                            predios_pagos.valor_concepto6,
                            predios_pagos.valor_concepto7,
                            predios_pagos.valor_concepto8,
                            predios_pagos.valor_concepto9,
                            predios_pagos.valor_concepto10,
                            predios_pagos.valor_concepto11,
                            predios_pagos.valor_concepto12,
                            predios_pagos.valor_concepto13,
                            predios_pagos.valor_concepto14,
                            predios_pagos.valor_concepto15,
                            predios_pagos.valor_concepto16,
                            predios_pagos.valor_concepto17,
                            predios_pagos.valor_concepto18,
                            predios_pagos.valor_concepto19,
                            predios_pagos.valor_concepto20,
                            predios_pagos.valor_concepto21,
                            predios_pagos.valor_concepto22,
                            predios_pagos.valor_concepto23,
                            predios_pagos.valor_concepto24,
                            predios_pagos.valor_concepto25,
                            predios_pagos.valor_concepto26,
                            predios_pagos.valor_concepto27,
                            predios_pagos.valor_concepto28,
                            predios_pagos.valor_concepto29,
                            predios_pagos.valor_concepto30
                        "))
                        ->where('predios_pagos.id_predio', $predio->id)
                        ->where('predios_pagos.ultimo_anio', '=', $i)
                        ->where('predios_pagos.pagado', 0)
                        ->where('predios_pagos.anulada', 0)
                        ->where('predios_pagos.prescrito', 0)
                        ->where('predios_pagos.exencion', 0)
                        ->first();

                    $predio_prescripcion = new PredioPrescripcion();
                    $predio_prescripcion->id_predio = $predio->id;
                    $predio_prescripcion->prescribe_anio = $i;
                    $predio_prescripcion->valor_concepto1 = $valores_predio_pago->valor_concepto1;
                    $predio_prescripcion->valor_concepto2 = $valores_predio_pago->valor_concepto2;
                    // $predio_prescripcion->valor_concepto3 = $valores_predio_pago->valor_concepto3;
                    // $predio_prescripcion->valor_concepto4 = $valores_predio_pago->valor_concepto4;
                    $predio_prescripcion->valor_concepto5 = $valores_predio_pago->valor_concepto5;
                    $predio_prescripcion->valor_concepto6 = $valores_predio_pago->valor_concepto6;
                    $predio_prescripcion->valor_concepto7 = $valores_predio_pago->valor_concepto7;
                    $predio_prescripcion->valor_concepto8 = $valores_predio_pago->valor_concepto8;
                    $predio_prescripcion->valor_concepto9 = $valores_predio_pago->valor_concepto9;
                    $predio_prescripcion->valor_concepto10 = $valores_predio_pago->valor_concepto10;
                    $predio_prescripcion->valor_concepto11 = $valores_predio_pago->valor_concepto11;
                    $predio_prescripcion->valor_concepto12 = $valores_predio_pago->valor_concepto12;
                    $predio_prescripcion->valor_concepto13 = $valores_predio_pago->valor_concepto13;
                    $predio_prescripcion->valor_concepto14 = $valores_predio_pago->valor_concepto14;
                    $predio_prescripcion->valor_concepto15 = $valores_predio_pago->valor_concepto15;
                    $predio_prescripcion->valor_concepto16 = $valores_predio_pago->valor_concepto16;
                    $predio_prescripcion->valor_concepto17 = $valores_predio_pago->valor_concepto17;
                    $predio_prescripcion->valor_concepto18 = $valores_predio_pago->valor_concepto18;
                    $predio_prescripcion->valor_concepto19 = $valores_predio_pago->valor_concepto19;
                    $predio_prescripcion->valor_concepto20 = $valores_predio_pago->valor_concepto20;
                    $predio_prescripcion->valor_concepto21 = $valores_predio_pago->valor_concepto21;
                    $predio_prescripcion->valor_concepto22 = $valores_predio_pago->valor_concepto22;
                    $predio_prescripcion->valor_concepto23 = $valores_predio_pago->valor_concepto23;
                    $predio_prescripcion->valor_concepto24 = $valores_predio_pago->valor_concepto24;
                    $predio_prescripcion->valor_concepto25 = $valores_predio_pago->valor_concepto25;
                    $predio_prescripcion->valor_concepto26 = $valores_predio_pago->valor_concepto26;
                    $predio_prescripcion->valor_concepto27 = $valores_predio_pago->valor_concepto27;
                    $predio_prescripcion->valor_concepto28 = $valores_predio_pago->valor_concepto28;
                    $predio_prescripcion->valor_concepto29 = $valores_predio_pago->valor_concepto29;
                    $predio_prescripcion->valor_concepto30 = $valores_predio_pago->valor_concepto30;
                    $predio_prescripcion->file_name = $request->filled('file_name') ? $request->file_name : NULL;
                    $query = $predio_prescripcion->save();
                }
            }

            // if($query) {
                $resolucion = new Resolucion();
                $resolucion->numero_resolucion = $request->numero_resolucion;
                $resolucion->fecha_resolucion = Carbon::createFromFormat("Y-m-d", $request->fecha_resolucion)->format('Y-m-d');
                $resolucion->firma_resolucion = strtoupper($request->firma_resolucion);
                $query = $resolucion->save();
                // if($query) {
                    $resolucion_predio = new ResolucionPredio();
                    $resolucion_predio->id_predio = $predio->id;
                    $resolucion_predio->id_resolucion = $resolucion->id;
                    $resolucion_predio->id_usuario = $request->session()->get('userid');
                    $resolucion_predio->descripcion = 'Prescripción predio ' . $predio->codigo_predio . ', prescribe desde: ' . $request->prescribe_desde . ', prescribe hasta: ' . $request->prescribe_hasta;
                    $query = $resolucion_predio->save();
                    // if($query) {
                        PredioPago::where('id_predio', $predio->id)
                        ->where('ultimo_anio', '>=', intval($request->prescribe_desde))
                        ->where('ultimo_anio', '<=', intval($request->prescribe_hasta))
                        ->where('pagado', 0)
                        ->where('anulada', 0)
                        ->update([
                            'prescrito' => -1,
                        ]);

                        DB::commit();

                        return back()->with(['success' => 'La informaci&oacute;n se guard&oacute; satisfactoriamente.', 'tab_current' => $tab_current]);
                    // }
                    // else {
                    //     $resolucion->delete();
                    //     $predio_prescripcion->delete();
                    //     return back()->with(['fail' => 'No se pudo generar la informaci&oacute;n de resoluci&oacute;n predio. Intente nuevamente.', 'tab_current' => $tab_current]);
                    // }
                // }
                // else {
                //     $predio_prescripcion->delete();
                //     return back()->with(['fail' => 'No se pudo generar la informaci&oacute;n de resoluci&oacute;n. Intente nuevamente.', 'tab_current' => $tab_current]);
                // }
            // }
            // else {
            //     return back()->with(['fail' => 'No se pudo guardar la informaci&oacute;n. Intente nuevamente.', 'tab_current' => $tab_current]);
            // }
        }
        catch(\Exception $e) {
          DB::rollback();
          return back()->with(['fail' => $e->getMessage(), 'tab_current' => $tab_current]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  PrediosPrescripcionesUpdateFormRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(PrediosPrescripcionesUpdateFormRequest $request) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request) {
        //
    }
}
