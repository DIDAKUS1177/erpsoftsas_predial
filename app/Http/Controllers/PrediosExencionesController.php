<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\PrediosExencionesCreateFormRequest;
use App\Http\Requests\PrediosExencionesUpdateFormRequest;
use App\Models\Opcion;
use App\Models\Predio;
use App\Models\PredioPago;
use App\Models\PredioExencion;
use App\Models\Resolucion;
use App\Models\ResolucionPredio;

use Carbon\Carbon;

class PrediosExencionesController extends Controller
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
     * Show the form for exencion de vigencia of a resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $id) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }
        $opcion = Opcion::where('id','=', base64_decode($id))->first();

        $exenciones = DB::table('predios_exenciones')
                    ->join('predios', 'predios_exenciones.id_predio', '=', 'predios.id')
                    ->select(DB::raw('TRY_CONVERT(VARCHAR, predios_exenciones.created_at, 23) AS created_at, MAX(predios_exenciones.exencion_anio) AS exencion_hasta, MAX(predios_exenciones.id) AS id, predios_exenciones.porcentaje, predios.codigo_predio, predios_exenciones.file_name'))
                    ->groupByRaw('predios.codigo_predio, predios_exenciones.file_name, predios_exenciones.porcentaje, TRY_CONVERT(VARCHAR, predios_exenciones.created_at, 23)')
                    ->orderByRaw('exencion_hasta')
                    ->get();

        $tab_current = 'li-section-bar-1';
        if ($request->has('page')) {
            $tab_current = 'li-section-bar-2';
        }

        //$request->session()->put('display_buttons', '1');

        return view('exenciones.create', ['opcion' => $opcion,
                                            'exenciones' => $exenciones,
                                            //'predios' => $predios,
                                            'tab_current' => $tab_current]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  PrediosExencionesCreateFormRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PrediosExencionesCreateFormRequest $request) {
            if (!$request->session()->exists('userid')) {
                return redirect('/');
            }

            $tab_current = 'li-section-bar-1';
            DB::beginTransaction();
            try {
                $dt = Carbon::now();
                $currentYear = $dt->year;

                $predio = new Predio();
                $predio = Predio::find($request->id_predio);

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
                        predios_pagos.valor_concepto30,
                        predios_pagos.avaluo
                    "))
                    ->where('predios_pagos.id_predio', $predio->id)
                    ->where('predios_pagos.ultimo_anio', '=', intval($request->exencion_hasta))
                    ->where('predios_pagos.pagado', 0)
                    ->where('predios_pagos.anulada', 0)
                    ->where('predios_pagos.prescrito', 0)
                    ->where('predios_pagos.exencion', 0)
                    ->first();

                $predio_exencion = new PredioExencion();
                $predio_exencion->id_predio = $predio->id;
                $predio_exencion->exencion_anio = intval($request->exencion_hasta);
                $predio_exencion->porcentaje = str_replace("%", "", $request->porcentaje_ex);
                $predio_exencion->valor_concepto1 = $valores_predio_pago->valor_concepto1;
                $predio_exencion->valor_concepto2 = $valores_predio_pago->valor_concepto2;
                $predio_exencion->valor_concepto3 = $valores_predio_pago->valor_concepto3;
                $predio_exencion->valor_concepto4 = $valores_predio_pago->valor_concepto4;
                $predio_exencion->valor_concepto5 = $valores_predio_pago->valor_concepto5;
                $predio_exencion->valor_concepto6 = $valores_predio_pago->valor_concepto6;
                $predio_exencion->valor_concepto7 = $valores_predio_pago->valor_concepto7;
                $predio_exencion->valor_concepto8 = $valores_predio_pago->valor_concepto8;
                $predio_exencion->valor_concepto9 = $valores_predio_pago->valor_concepto9;
                $predio_exencion->valor_concepto10 = $valores_predio_pago->valor_concepto10;
                $predio_exencion->valor_concepto11 = $valores_predio_pago->valor_concepto11;
                $predio_exencion->valor_concepto12 = $valores_predio_pago->valor_concepto12;
                $predio_exencion->valor_concepto13 = $valores_predio_pago->valor_concepto13;
                $predio_exencion->valor_concepto14 = $valores_predio_pago->valor_concepto14;
                $predio_exencion->valor_concepto15 = $valores_predio_pago->valor_concepto15;
                $predio_exencion->valor_concepto16 = $valores_predio_pago->valor_concepto16;
                $predio_exencion->valor_concepto17 = $valores_predio_pago->valor_concepto17;
                $predio_exencion->valor_concepto18 = $valores_predio_pago->valor_concepto18;
                $predio_exencion->valor_concepto19 = $valores_predio_pago->valor_concepto19;
                $predio_exencion->valor_concepto20 = $valores_predio_pago->valor_concepto20;
                $predio_exencion->valor_concepto21 = $valores_predio_pago->valor_concepto21;
                $predio_exencion->valor_concepto22 = $valores_predio_pago->valor_concepto22;
                $predio_exencion->valor_concepto23 = $valores_predio_pago->valor_concepto23;
                $predio_exencion->valor_concepto24 = $valores_predio_pago->valor_concepto24;
                $predio_exencion->valor_concepto25 = $valores_predio_pago->valor_concepto25;
                $predio_exencion->valor_concepto26 = $valores_predio_pago->valor_concepto26;
                $predio_exencion->valor_concepto27 = $valores_predio_pago->valor_concepto27;
                $predio_exencion->valor_concepto28 = $valores_predio_pago->valor_concepto28;
                $predio_exencion->valor_concepto29 = $valores_predio_pago->valor_concepto29;
                $predio_exencion->valor_concepto30 = $valores_predio_pago->valor_concepto30;
                $predio_exencion->avaluo = $valores_predio_pago->avaluo;
                $predio_exencion->file_name = $request->filled('file_name') ? $request->file_name : NULL;
                $query = $predio_exencion->save();

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
                        $resolucion_predio->descripcion = 'Exención predio ' . $predio->codigo_predio . ', año exención: ' . $request->exencion_hasta;
                        $query = $resolucion_predio->save();
                        // if($query) {
                            if (intval($request->exencion_hasta) == $currentYear && intval(str_replace("%", "", $request->porcentaje_ex)) == 100) {
                                PredioPago::where('id_predio', $predio->id)
                                    ->where('ultimo_anio', '=', intval($request->exencion_hasta))
                                    ->where('pagado', 0)
                                    ->where('anulada', 0)
                                    ->update([
                                        'exencion' => -1,
                                        'valor_pago' => 0,
                                        'fecha_pago' => Carbon::createFromFormat('Y-m-d', substr($predio_exencion->created_at, 0, 10))->format('Y-m-d'),
                                        'id_banco' => 5,
                                        'pagado' => -1
                                    ]);
                            } else {
                                PredioPago::where('id_predio', $predio->id)
                                    ->where('ultimo_anio', '=', intval($request->exencion_hasta))
                                    ->where('pagado', 0)
                                    ->where('anulada', 0)
                                    ->update([
                                        'exencion' => -1,
                                    ]);
                            }

                            DB::commit();

                            return back()->with(['success' => 'La informaci&oacute;n se guard&oacute; satisfactoriamente.', 'tab_current' => $tab_current]);
                        // }
                        // else {
                        //     $resolucion->delete();
                        //     $predio_exencion->delete();
                        //     return back()->with(['fail' => 'No se pudo generar la informaci&oacute;n de resoluci&oacute;n predio. Intente nuevamente.', 'tab_current' => $tab_current]);
                        // }
                    // }
                    // else {
                    //     $predio_exencion->delete();
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
     * @param  PrediosExencionesUpdateFormRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(PrediosExencionesUpdateFormRequest $request) {
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
