<?php

namespace App\Http\Controllers;

use App\Events\CalculoBatch;
use App\Exports\ExportAvaluos;
use App\Exports\ExportCartera;
use App\Exports\ExportEstadoCuenta;
use App\Exports\ExportExenciones;
use App\Exports\ExportPrescripciones;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\Http\Requests\PrediosCreateFormRequest;
use App\Http\Requests\PrediosUpdateFormRequest;
use App\Models\Anio;
use App\Models\Predio;
use App\Models\Opcion;
use App\Models\PredioAbono;
use App\Models\PredioAcuerdoPago;
use App\Models\PredioCalculo;
use App\Models\PredioDato;
use App\Models\PredioPago;
use App\Models\PredioPropietario;
use App\Models\Propietario;
use App\Models\Resolucion;
use App\Models\ResolucionPredio;
use App\Models\PredioPrescripcion;
use App\Models\Pago;

use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use \stdClass;
use App\Models\QrValidation;
use Illuminate\Support\Str;
use App\Http\Controllers\QrCodeHelper;

class PrediosController extends Controller
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $id) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }
        $opcion = Opcion::where('id','=', base64_decode($id))->first();
        $predios = [];

        $zonas = DB::table('zonas')
                    ->select('zonas.id', 'zonas.descripcion')
                    ->get();
        $tipos_predio = DB::table('tipos_predio')
                            ->select('tipos_predio.id', 'tipos_predio.nombre')
                            ->get();
        $clases_predio = DB::table('clases_predio')
                            ->select('clases_predio.id', 'clases_predio.nombre')
                            ->get();
        $clases_mutacion = DB::table('clases_mutacion')
                            ->select('clases_mutacion.id', 'clases_mutacion.nombre')
                            ->get();
        $tarifas_predial = DB::table('tarifas_predial')
                            ->select('tarifas_predial.id', 'tarifas_predial.descripcion', 'tarifas_predial.anio', 'tarifas_predial.codigo', 'tarifas_predial.tarifa')
                            ->get();
        $bancos = DB::table('bancos')
                            ->select('bancos.id', 'bancos.codigo', 'bancos.asobancaria', 'bancos.nombre')
                            ->get();
        $batchs = DB::table('batchs')
                    ->where('batchs.estado', 1)
                    ->first();

        $dt = Carbon::now();
        $current_time = $dt->timestamp;
        $currentYear = $dt->year;
        $maxFechaDescuentos = DB::table('descuentos')
                            ->select(DB::raw('max(fecha_fin) as max_fecha')) // verificar si ya esta relacionada con otras tablas
                            ->where('anio', $currentYear)
                            ->first();

        $tab_current = 'li-section-bar-1';
        if ($request->has('page')) {
            $tab_current = 'li-section-bar-2';
        }

        //$request->session()->put('display_buttons', '1');

        return view('predios.create', ['opcion' => $opcion,
                                     'predios' => $predios,
                                     'zonas' => $zonas,
                                     'tipos_predio' => $tipos_predio,
                                     'clases_predio' => $clases_predio,
                                     'clases_mutacion' => $clases_mutacion,
                                     'tarifas_predial' => $tarifas_predial,
                                     'bancos' => $bancos,
                                     'current_time' => $current_time,
                                     'tab_current' => $tab_current,
                                     'max_fecha_descuentos' => $maxFechaDescuentos->max_fecha,
                                     'fecha_actual' => $dt->toDateString(),
                                     'batchs' => $batchs
                                    ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  PrediosCreateFormRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PrediosCreateFormRequest $request) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }

        $predio = Predio::where('codigo_predio', $request->codigo_predio)->first();
        $tab_current = 'li-section-bar-1';

        if($predio == null) {
            $predio = new Predio();
            $predio->codigo_predio = $request->codigo_predio;

            $predio->tipo = $request->filled('tipo') ? $request->tipo : NULL;//2
            $predio->zona = $request->filled('zona') ? $request->zona : NULL;//2

            $predio->sector = $request->sector;//2

            $predio->comuna = $request->filled('comuna') ? $request->comuna : NULL;//2
            $predio->barrio = $request->filled('barrio') ? $request->barrio : NULL;//2

            $predio->manzana = $request->manzana;//4

            $predio->predio = $request->predio;//4
            $predio->terreno = $request->filled('terreno') ? $request->terreno : NULL;//4

            $predio->mejora = $request->mejora;//3

            $predio->condicion = $request->filled('condicion') ? $request->condicion : NULL;//1
            $predio->edificio_torre = $request->filled('edificio_torre') ? $request->edificio_torre : NULL;//2
            $predio->piso = $request->filled('piso') ? $request->piso : NULL;//2
            $predio->propiedad = $request->filled('propiedad') ? $request->propiedad : NULL;//4

            $predio->id_zona = $request->id_zona;
            $predio->direccion = $request->direccion;
            $predio->area_metros = str_replace(",", "", $request->area_metros);
            $predio->area_construida = str_replace(",", "", $request->area_construida);
            $predio->area_hectareas = str_replace(",", "", $request->area_hectareas);
            $predio->tarifa_actual = str_replace(",", "", $request->tarifa_actual);
            $predio->avaluo = str_replace(",", "", $request->avaluo);
            $predio->ultimo_anio_pago = $request->ultimo_anio_pago;
            $predio->ind_ley1995 = $request->filled('ind_ley1995') ? intval($request->ind_ley1995) * -1 : 0;
            $predio->ind_excento_impuesto = $request->filled('ind_excento_impuesto') ? intval($request->ind_excento_impuesto) * -1 : 0;
            $predio->ind_plusvalia = $request->filled('ind_plusvalia') ? intval($request->ind_plusvalia) * -1 : 0;
            $predio->ind_invalidez = $request->filled('ind_invalidez') ? intval($request->ind_invalidez) * -1 : 0;
            $query = $predio->save();

            if($query) {
                return back()->with(['success' => 'La información se guardó satisfactoriamente.', 'tab_current' => $tab_current]);
            }
            else {
                return back()->with(['fail' => 'No se pudo guardar la información. Intente nuevamente.', 'tab_current' => $tab_current]);
            }
        }
        else {
            return back()->with(['fail' => 'No se pudo guardar la información. El código de predio ingresado ya existe. Si lo que desea es realizar un cambio de código de predio anterior, por favor acceda a la sección Opciones de predio y realice la actualización del código antarior por un código nuevo.', 'tab_current' => $tab_current]);
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
     * @param  PrediosUpdateFormRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(PrediosUpdateFormRequest $request) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }

        $tab_current = 'li-section-bar-2';
        $predio_validar = new Predio();
        $predio = new Predio();

        $predio = Predio::find($request->id_edit);
        $predio_validar = DB::table('predios')
                        ->where('codigo_predio', $request->codigo_predio_edit)
                        ->first();

        if($predio_validar == null || $predio->id == $predio_validar->id) {
            DB::beginTransaction();
            try {
                $predio_tmp = new Predio();
                $predio_tmp = Predio::find($request->id_edit);

                $predio->codigo_predio = $request->codigo_predio_edit;

                if((strlen($predio_tmp->codigo_predio) == 15 && strlen($request->codigo_predio_edit) == 25) ||
                (strlen($predio_tmp->codigo_predio) == 25 && strlen($request->codigo_predio_edit) == 15)) {
                    $predio->codigo_predio_anterior = $predio_tmp->codigo_predio;
                }

                $predio->tipo = $request->filled('tipo_edit') ? $request->tipo_edit : NULL;//2
                $predio->zona = $request->filled('zona_edit') ? $request->zona_edit : NULL;//2

                $predio->sector = $request->sector_edit;//2

                $predio->comuna = $request->filled('comuna_edit') ? $request->comuna_edit : NULL;//2
                $predio->barrio = $request->filled('barrio_edit') ? $request->barrio_edit : NULL;//2

                $predio->manzana = $request->manzana_edit;//4

                $predio->predio = $request->predio_edit;//4
                $predio->terreno = $request->filled('terreno_edit') ? $request->terreno_edit : NULL;//4

                $predio->mejora = $request->mejora_edit;//3

                $predio->condicion = $request->filled('condicion_edit') ? $request->condicion_edit : NULL;//1
                $predio->edificio_torre = $request->filled('edificio_torre_edit') ? $request->edificio_torre_edit : NULL;//2
                $predio->piso = $request->filled('piso_edit') ? $request->piso_edit : NULL;//2
                $predio->propiedad = $request->filled('propiedad_edit') ? $request->propiedad_edit : NULL;//4

                $predio->id_zona = $request->id_zona_edit;
                $predio->direccion = $request->direccion_edit;
                $predio->area_metros = str_replace(",", "", $request->area_metros_edit);
                $predio->area_construida = str_replace(",", "", $request->area_construida_edit);
                $predio->area_hectareas = str_replace(",", "", $request->area_hectareas_edit);
                $predio->tarifa_actual = str_replace(",", "", $request->tarifa_actual_edit);
                $predio->avaluo = str_replace(",", "", $request->avaluo_edit);
                $predio->ultimo_anio_pago = $request->ultimo_anio_pago_edit;
                $predio->ind_ley1995 = $request->filled('ind_ley1995_edit') ? intval($request->ind_ley1995_edit) * -1 : 0;
                $predio->ind_excento_impuesto = $request->filled('ind_excento_impuesto_edit') ? intval($request->ind_excento_impuesto_edit) * -1 : 0;
                $predio->ind_plusvalia = $request->filled('ind_plusvalia_edit') ? intval($request->ind_plusvalia_edit) * -1 : 0;
                $predio->ind_invalidez = $request->filled('ind_invalidez_edit') ? intval($request->ind_invalidez_edit) * -1 : 0;
                //$query = $predio->save();
                $predio->save();

                // if($query) {
                    $resolucion = new Resolucion();
                    $resolucion->numero_resolucion = $request->numero_resolucion;
                    $resolucion->fecha_resolucion = Carbon::createFromFormat("Y-m-d", $request->fecha_resolucion)->format('Y-m-d');
                    $resolucion->firma_resolucion = strtoupper($request->firma_resolucion);
                    // $query = $resolucion->save();
                    $resolucion->save();
                    // if($query) {
                        $resolucion_predio = new ResolucionPredio();
                        $resolucion_predio->id_predio = $predio->id;
                        $resolucion_predio->id_resolucion = $resolucion->id;
                        $resolucion_predio->id_usuario = $request->session()->get('userid');
                        $resolucion_predio->descripcion = 'Actualización predio ' . $predio->codigo_predio;
                        // $query = $resolucion_predio->save();
                        $resolucion_predio->save();

                        DB::commit();

                        // if($query) {
                            return back()->with(['success' => 'La información se actualizó satisfactoriamente.', 'tab_current' => $tab_current]);
                        // }
                        // else {
                        //     $resolucion->delete();
                        //     $predio_tmp->save();
                        //     return back()->with(['fail' => 'No se pudo generar la información de resolución predio. Intente nuevamente.', 'tab_current' => $tab_current]);
                        // }
                    // }
                    // else {
                    //     $predio_tmp->save();
                    //     return back()->with(['fail' => 'No se pudo generar la información de resolución. Intente nuevamente.', 'tab_current' => $tab_current]);
                    // }
                // }
                // else {
                //     return back()->with(['fail' => 'No se pudo actualizar la información. Intente nuevamente.', 'tab_current' => $tab_current]);
                // }
            }
            catch(\Exception $e) {
              DB::rollback();
              return back()->with(['fail' => $e->getMessage(), 'tab_current' => $tab_current]);
            }
        }
        else if($predio_validar != null && $predio->id != $predio_validar->id) {
            // Cambio de cedula catastral
            DB::beginTransaction();
            try {
                //////////// mutar pagos
                // $pagos = Pago::where('id_predio', $predio->id)->count();

                //if($pagos > 0) {
                    $updated = Pago::where('id_predio', $predio->id)
                                ->update([
                                    'id_predio' => $predio_validar->id
                                ]);

                    //if($updated == $pagos) {
                        //////////// mutar predios_pagos
                        //$predios_pagos = PredioPago::where('id_predio', $predio->id)->count();

                        //if($predios_pagos > 0) {

                            $dt = Carbon::now();
                            $currentYear = $dt->year;

                            // Verificar si el predio nuevo tiene facturas generadas para el año actual
                            $facturas_nuevo = PredioPago::where('id_predio', $predio_validar->id)
                                              ->where('ultimo_anio', $currentYear)
                                              ->first();

                            // Verificar si el predio anterior tiene facturas generadas para el año actual
                            $facturas_anterior = PredioPago::where('id_predio', $predio->id)
                                                 ->where('ultimo_anio', $currentYear)
                                                 ->first();

                            if($facturas_nuevo != null && $facturas_anterior != null) {
                                // Dejar la factura mas reciente
                                // Eliminar la factura menor
                                if($facturas_nuevo->factura_pago > $facturas_anterior->factura_pago) {
                                    $deleted = PredioPago::where('id', $facturas_anterior->id)
                                               ->where('ultimo_anio', $currentYear)
                                               ->delete();
                                }
                                else if($facturas_nuevo->factura_pago < $facturas_anterior->factura_pago) {
                                    $deleted = PredioPago::where('id', $facturas_nuevo->id)
                                               ->where('ultimo_anio', $currentYear)
                                               ->delete();
                                }
                            }
                            else if(($facturas_nuevo != null && $facturas_anterior == null) || ($facturas_nuevo == null && $facturas_anterior != null)) {
                                // Eliminar todas las facturas anteriores al año actual del nuevo predio
                                $deleted = PredioPago::where('id_predio', $predio_validar->id)
                                           ->where('ultimo_anio', '<', $currentYear)
                                           ->delete();
                            }

                            // Establecer al los registros del predio anterior, el identificador del nuevo predio
                            $updated = PredioPago::where('id_predio', $predio->id)
                                        ->update([
                                            'id_predio' => $predio_validar->id
                                        ]);

                            //if($updated == $predios_pagos) {
                                //////////// mutar predios_propietarios
                                //$predios_propietarios = PredioPropietario::where('id_predio', $predio->id)->count();

                                //if($predios_propietarios > 0) {
                                    $deleted = PredioPropietario::where('id_predio', $predio_validar->id)
                                                ->delete();

                                    $updated = PredioPropietario::where('id_predio', $predio->id)
                                                ->update([
                                                    'id_predio' => $predio_validar->id
                                                ]);

                                    //if($updated == $predios_propietarios) {
                                        //////////// mutar predios_abonos
                                        $deleted = PredioAbono::where('id_predio', $predio_validar->id)
                                                    ->delete();

                                        PredioAbono::where('id_predio', $predio->id)
                                        ->update([
                                            'id_predio' => $predio_validar->id
                                        ]);

                                        //////////// mutar predios_acuerdos_pago
                                        $deleted = PredioAcuerdoPago::where('id_predio', $predio_validar->id)
                                                    ->delete();

                                        PredioAcuerdoPago::where('id_predio', $predio->id)
                                        ->update([
                                            'id_predio' => $predio_validar->id
                                        ]);

                                        //////////// mutar predios_calculos
                                        $deleted = PredioCalculo::where('id_predio', $predio_validar->id)
                                                    ->delete();

                                        PredioCalculo::where('id_predio', $predio->id)
                                        ->update([
                                            'id_predio' => $predio_validar->id
                                        ]);

                                        //////////// mutar predios_datos
                                        $deleted = PredioDato::where('id_predio', $predio_validar->id)
                                                    ->delete();

                                        PredioDato::where('id_predio', $predio->id)
                                        ->update([
                                            'id_predio' => $predio_validar->id
                                        ]);

                                        //////////// mutar resoluciones_predio
                                        ResolucionPredio::where('id_predio', $predio->id)
                                        ->update([
                                            'id_predio' => $predio_validar->id
                                        ]);

                                        // predios_exenciones -> NO, se mantiene la informacion asociada al predio anterior
                                        // predios_prescripciones -> NO, se mantiene la informacion asociada al predio anterior

                                        // Eliminar el predio anterior
                                        $deleted = Predio::where('id', $predio->id)
                                        ->delete();

                                        //////////// Asignar al predio nuevo el codigo predio anterior
                                        Predio::where('id', $predio_validar->id)
                                        ->update([
                                            'codigo_predio_anterior' => $predio->codigo_predio
                                        ]);

                                        $resolucion = new Resolucion();
                                        $resolucion->numero_resolucion = $request->numero_resolucion;
                                        $resolucion->fecha_resolucion = Carbon::createFromFormat("Y-m-d", $request->fecha_resolucion)->format('Y-m-d');
                                        $resolucion->firma_resolucion = strtoupper($request->firma_resolucion);
                                        $resolucion->save();

                                        $resolucion_predio = new ResolucionPredio();
                                        $resolucion_predio->id_predio = $predio_validar->id;
                                        $resolucion_predio->id_resolucion = $resolucion->id;
                                        $resolucion_predio->id_usuario = $request->session()->get('userid');
                                        $resolucion_predio->descripcion = 'Actualización cédula catastral. Código anterior: ' . $predio->codigo_predio . ', Código nuevo: ' . $predio_validar->codigo_predio;
                                        $resolucion_predio->save();

                                        DB::commit();

                                        return back()->with(['success' => 'La información se actualizó satisfactoriamente.', 'tab_current' => $tab_current]);

                                    //}
                                //}
                            //}
                        //}
                    //}
                //}
            }
            catch(\Exception $e) {
              DB::rollback();
              return back()->with(['fail' => $e->getMessage(), 'tab_current' => $tab_current]);
            }
        }
        else {
            return back()->with(['fail' => 'No se pudo actualizar la información. Intente nuevamente.', 'tab_current' => $tab_current]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }

        $countPredios = DB::table('predios')
                            ->select(DB::raw('count(*) as cm_count')) // verificar si ya esta relacionada con otras tablas
                            ->where('id', $request->input_delete)
                            ->where('estado', 1)
                            ->first();

        $tab_current = 'li-section-bar-2';
        $predio = new Predio();
        $predio = Predio::find($request->input_delete);

        if($countPredios->cm_count > 0) {
            $predio->estado = 0;
            $query = $predio->save();
            if($query) {
                $resolucion = new Resolucion();
                $resolucion->numero_resolucion = $request->numero_resolucion;
                $resolucion->fecha_resolucion = Carbon::createFromFormat("Y-m-d", $request->fecha_resolucion)->format('Y-m-d');
                $resolucion->firma_resolucion = strtoupper($request->firma_resolucion);
                $query = $resolucion->save();
                if($query) {
                    $resolucion_predio = new ResolucionPredio();
                    $resolucion_predio->id_predio = $predio->id;
                    $resolucion_predio->id_resolucion = $resolucion->id;
                    $resolucion_predio->id_usuario = $request->session()->get('userid');
                    $resolucion_predio->descripcion = 'Anulación predio ' . $predio->codigo_predio;

                    $query = $resolucion_predio->save();
                    if($query) {
                        return back()->with(['success' => 'El registro se anulo satisfactoriamente.', 'tab_current' => $tab_current]);
                    }
                    else {
                        $resolucion->delete();
                        $predio->estado = 1;
                        $predio->save();
                        return back()->with(['fail' => 'No se pudo generar la información de resolución predio. Intente nuevamente.', 'tab_current' => $tab_current]);
                    }
                }
                else {
                    $predio->estado = 1;
                    $predio->save();
                    return back()->with(['fail' => 'No se pudo generar la información de resolución. Intente nuevamente.', 'tab_current' => $tab_current]);
                }
            }
            else {
                return back()->with(['fail' => 'No se pudo anular el predio. Intente nuevamente.', 'tab_current' => $tab_current]);
            }
        }
        // else {
        //     return back()->with(['fail' => 'No se pudo eliminar la información. La clase de mutación <b>' . $predio->nombre . ' (' . $predio->codigo . ')</b> ya posee información asociada.', 'tab_current' => $tab_current]);
        // }
    }

    /**
     * Prescribe the specified resource from storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function prescribe(Request $request) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }

        $tab_current = 'li-section-bar-2';

        DB::beginTransaction();
        try {
            $predio = new Predio();
            $predio = Predio::find($request->input_prescribe);

            for($i = intval($request->prescribe_desde); $i <= intval($request->prescribe_hasta); $i++) {
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
                $predio_prescripcion->valor_concepto3 = $valores_predio_pago->valor_concepto3;
                $predio_prescripcion->valor_concepto4 = $valores_predio_pago->valor_concepto4;
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
                $predio_prescripcion->avaluo = $valores_predio_pago->avaluo;
                $query = $predio_prescripcion->save();
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
                    //     return back()->with(['fail' => 'No se pudo generar la información de resolución predio. Intente nuevamente.', 'tab_current' => $tab_current]);
                    // }
                // }
                // else {
                //     $predio_prescripcion->delete();
                //     return back()->with(['fail' => 'No se pudo generar la información de resolución. Intente nuevamente.', 'tab_current' => $tab_current]);
                // }
            // }
            // else {
            //     return back()->with(['fail' => 'No se pudo prescribir el predio. Intente nuevamente.', 'tab_current' => $tab_current]);
            // }
        }
        catch(\Exception $e) {
          DB::rollback();
          return back()->with(['fail' => $e->getMessage(), 'tab_current' => $tab_current]);
        }
    }

    public function store_predios_datos_basicos(Request $request) {

        $data = json_decode($request->form);

        $predio_dato = new PredioDato();

        if(property_exists($data, 'id')) {
            $predio_dato = PredioDato::find($data->{'id'});
        }
        $predio_dato->id_predio = $data->{'id_predio'};
        $predio_dato->id_tipo_predio = $data->{'id_tipo_predio'};
        $predio_dato->matricula_inmobiliaria = $data->{'matricula_inmobiliaria'};
        $predio_dato->avaluo_presente_anio = str_replace(",", "", $data->{'avaluo_presente_anio'});
        $predio_dato->excento_impuesto = property_exists($data, 'excento_impuesto') ? $data->{'excento_impuesto'} : '0';
        $predio_dato->id_clase_predio = $data->{'id_clase_predio'};
        //$predio_dato->id_clase_mutacion = $data->{'id_clase_mutacion'};
        //$predio_dato->predio_incautado = property_exists($data, 'predio_incautado') ? $data->{'predio_incautado'} : '0';
        //$predio_dato->aplica_ley44 = property_exists($data, 'aplica_ley44') ? $data->{'aplica_ley44'} : '0';

        $query = $predio_dato->save();

        $result = array("success"=>$query);

        if($query) {
            $result['message'] = 'Información básica del predio actualizada satisfactoriamente.';
            return response()->json([
                'data' => $result,
                'obj' => $predio_dato
            ]);
        }
        else {
            $result['message'] = 'No se pudo actualizar la información básica del predio.';
            return response()->json([
                'data' => $result
            ]);
        }
    }

    public function store_predios_datos_propietarios(Request $request) {

        $data = json_decode($request->form);

        $propietario = new Propietario();

        if(property_exists($data, 'id')) {
            $propietario = Propietario::find($data->{'id'});
        }
        else if(property_exists($data, 'id_propietario')) {
            $propietario->id = $data->{'id_propietario'};
        }

        if(!property_exists($data, 'id_propietario')) {
            $propietario->identificacion = $data->{'identificacion'};
            $propietario->nombre = $data->{'nombre'};
            $propietario->direccion = $data->{'direccion'};
            $propietario->correo_electronico = $data->{'correo_electronico'};
            $query = $propietario->save();
        }
        else {
            $query = true;
        }

        $result = array("success" => $query);

        if($query) {

            if(property_exists($data, 'id')) {

                $predio_propietario = PredioPropietario::find($data->{'id_predio_propietario'});
                $predio_propietario->id_propietario = $data->{'id'};
                $query = $predio_propietario->save();

                if($query) {
                    $result['message'] = 'Información de propietario del predio actualizada satisfactoriamente.';
                    $propietarios = DB::table('predios_propietarios')
                                    ->join('propietarios', 'predios_propietarios.id_propietario', '=', 'propietarios.id')
                                    ->select('propietarios.*', 'predios_propietarios.jerarquia', 'predios_propietarios.id AS id_predio_propietario')
                                    ->where('predios_propietarios.id_predio', $data->{'id_predio'})
                                    ->orderBy('predios_propietarios.jerarquia', 'asc')
                                    ->get();

                    return response()->json([
                        'data' => $result,
                        'obj' => $propietarios
                    ]);
                } else {
                    $result['message'] = 'No se pudo actualizar la información de propietario del predio.';
                    return response()->json([
                        'data' => $result
                    ]);
                }
            }
            else {
                $predio_propietario = new PredioPropietario();

                if(property_exists($data, 'id_predio_propietario')) {
                    $predio_propietario = PredioPropietario::find($data->{'id_predio_propietario'});
                } else {
                    $predio_propietario->id_predio = $data->{'id_predio'};
                    $predio_propietario->jerarquia = str_pad(intval($data->{'jerarquia'}), 3, "0", STR_PAD_LEFT);
                }

                $predio_propietario->id_propietario = $propietario->id;
                $query = $predio_propietario->save();

                $result = array("success"=>$query);

                if($query) {
                    $result['message'] = 'Información de propietario del predio actualizada satisfactoriamente.';

                    $propietarios = DB::table('predios_propietarios')
                                    ->join('propietarios', 'predios_propietarios.id_propietario', '=', 'propietarios.id')
                                    ->select('propietarios.*', 'predios_propietarios.jerarquia', 'predios_propietarios.id AS id_predio_propietario')
                                    ->where('predios_propietarios.id_predio', $data->{'id_predio'})
                                    ->orderBy('predios_propietarios.jerarquia', 'asc')
                                    ->get();

                    return response()->json([
                        'data' => $result,
                        'obj' => $propietarios
                    ]);
                }
                else {
                    $propietario->delete();
                    $result['message'] = 'No se pudo actualizar la información de propietario del predio.';
                    return response()->json([
                        'data' => $result
                    ]);
                }
            }
        }
        else {
            $result['message'] = 'No se pudo actualizar la información de propietario del predio.';
            return response()->json([
                'data' => $result
            ]);
        }
    }

    public function store_predios_propietarios_jerarquia(Request $request) {
        $data = json_decode($request->form);

        $prev_predio_propietarios = DB::table('predios_propietarios')
                                    ->select('predios_propietarios.*')
                                    ->where('predios_propietarios.id_predio', $data->{'id_predio'})
                                    ->whereRaw('CONVERT(int, predios_propietarios.jerarquia) = ' . intval($data->{'jerarquia_anterior'}))
                                    ->first();

        if(intval($data->{'jerarquia_nueva'}) > intval($data->{'jerarquia_anterior'})) {
            $predio_propietarios = DB::table('predios_propietarios')
                                    ->join('propietarios', 'predios_propietarios.id_propietario', '=', 'propietarios.id')
                                    ->select('predios_propietarios.id', 'predios_propietarios.jerarquia')
                                    ->where('predios_propietarios.id_predio', $data->{'id_predio'})
                                    ->whereRaw('CONVERT(int, predios_propietarios.jerarquia) > ' . intval($data->{'jerarquia_anterior'}))
                                    ->whereRaw('CONVERT(int, predios_propietarios.jerarquia) <= '. intval($data->{'jerarquia_nueva'}))
                                    ->orderBy('predios_propietarios.jerarquia', 'asc')
                                    ->get();
        }
        else if(intval($data->{'jerarquia_nueva'}) < intval($data->{'jerarquia_anterior'})) {
            $predio_propietarios = DB::table('predios_propietarios')
                                    ->join('propietarios', 'predios_propietarios.id_propietario', '=', 'propietarios.id')
                                    ->select('predios_propietarios.id', 'predios_propietarios.jerarquia')
                                    ->where('predios_propietarios.id_predio', $data->{'id_predio'})
                                    ->whereRaw('CONVERT(int, predios_propietarios.jerarquia) >= ' . intval($data->{'jerarquia_nueva'}))
                                    ->whereRaw('CONVERT(int, predios_propietarios.jerarquia) < '. intval($data->{'jerarquia_anterior'}))
                                    ->orderBy('predios_propietarios.jerarquia', 'asc')
                                    ->get();
        }

        $init_jerarquia = intval($data->{'jerarquia_anterior'});
        if(intval($data->{'jerarquia_nueva'}) < intval($data->{'jerarquia_anterior'})) {
            $init_jerarquia = intval($data->{'jerarquia_nueva'}) + 1;
        }

        foreach ($predio_propietarios as $registro) {
            $pp = new PredioPropietario;
            $pp = PredioPropietario::find($registro->id);
            $pp->jerarquia = str_pad($init_jerarquia, 3, "0", STR_PAD_LEFT);
            $pp->save();
            $init_jerarquia++;
        }

        $pp = new PredioPropietario;
        $pp = PredioPropietario::find($prev_predio_propietarios->id);
        $pp->jerarquia = $data->{'jerarquia_nueva'};
        $pp->save();

        $nuevos_predio_propietarios = DB::table('predios_propietarios')
                                    ->join('propietarios', 'predios_propietarios.id_propietario', '=', 'propietarios.id')
                                    ->select('propietarios.*', 'predios_propietarios.jerarquia', 'predios_propietarios.id AS id_predio_propietario')
                                    ->where('predios_propietarios.id_predio', $data->{'id_predio'})
                                    ->orderBy('predios_propietarios.jerarquia', 'asc')
                                    ->get();

        return response()->json([
            'data' => $nuevos_predio_propietarios
        ]);
    }

    public function store_predios_propietarios_delete(Request $request) {
        $data = json_decode($request->form);

        DB::beginTransaction();
        try {
            $prev_predio_propietario = DB::table('predios_propietarios')
                                    ->select('predios_propietarios.*')
                                    ->where('predios_propietarios.id', $data->{'id'})
                                    ->first();

            $predio_propietarios = DB::table('predios_propietarios')
                                    ->join('propietarios', 'predios_propietarios.id_propietario', '=', 'propietarios.id')
                                    ->select('predios_propietarios.id', 'predios_propietarios.jerarquia')
                                    ->where('predios_propietarios.id_predio', $prev_predio_propietario->id_predio)
                                    ->whereRaw('CONVERT(int, predios_propietarios.jerarquia) > ' . intval($prev_predio_propietario->jerarquia))
                                    ->orderBy('predios_propietarios.jerarquia', 'asc')
                                    ->get();

            // Eliminar el predio anterior
            $deleted = PredioPropietario::where('id', $prev_predio_propietario->id)
            ->delete();

            $count_propietarios = PredioPropietario::where('id_propietario', $prev_predio_propietario->id_propietario)->count();

            if($count_propietarios == 0) {
                $deleted = Propietario::where('id', $prev_predio_propietario->id_propietario)
                ->delete();
            }

            if($deleted) {
                $init_jerarquia = intval($prev_predio_propietario->jerarquia);
                foreach ($predio_propietarios as $registro) {
                    $pp = new PredioPropietario;
                    $pp = PredioPropietario::find($registro->id);
                    $pp->jerarquia = str_pad($init_jerarquia, 3, "0", STR_PAD_LEFT);
                    $pp->save();
                    $init_jerarquia++;
                }

                $nuevos_predio_propietarios = DB::table('predios_propietarios')
                                            ->join('propietarios', 'predios_propietarios.id_propietario', '=', 'propietarios.id')
                                            ->select('propietarios.*', 'predios_propietarios.jerarquia', 'predios_propietarios.id AS id_predio_propietario')
                                            ->where('predios_propietarios.id_predio', $prev_predio_propietario->id_predio)
                                            ->orderBy('predios_propietarios.jerarquia', 'asc')
                                            ->get();

                DB::commit();

                return response()->json([
                    'data' => $nuevos_predio_propietarios
                ]);
            }
            else {
                DB::rollback();
                return response()->json([
                    'error' => 'No se pudo eliminar el propietario asignado al predio. Contacte al administrador del sistema.'
                ]);
            }
        }
        catch(\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => $e
            ]);
        }
    }

    public function store_predios_datos_calculo(Request $request) {

        $data = json_decode($request->form);

        $predio_calculo = new PredioCalculo();

        if(property_exists($data, 'id')) {
            $predio_calculo = PredioCalculo::find($data->{'id'});
        }
        $predio_calculo->id_predio = $data->{'id_predio'};
        $predio_calculo->estrato = $data->{'estrato'};
        $predio_calculo->id_tarifa_predial = $data->{'id_tarifa_predial'};
        $predio_calculo->destino_economico = $data->{'destino_economico'};
        $predio_calculo->numero_resolucion = $data->{'numero_resolucion'};
        $predio_calculo->numero_ultima_factura = $data->{'numero_ultima_factura'};
        $predio_calculo->uso_suelo = property_exists($data, 'uso_suelo') ? $data->{'uso_suelo'} : '0';

        $query = $predio_calculo->save();

        $result = array("success"=>$query);

        if($query) {
            $result['message'] = 'Información de cálculo del predio actualizada satisfactoriamente.';
            return response()->json([
                'data' => $result,
                'obj' => $predio_calculo
            ]);
        }
        else {
            $result['message'] = 'No se pudo actualizar la información de cálculo del predio.';
            return response()->json([
                'data' => $result
            ]);
        }
    }

    public function store_predios_datos_pagos(Request $request) {

        $data = json_decode($request->form);

        $predio_pago = new PredioPago();

        if(property_exists($data, 'id')) {
            $predio_pago = PredioPago::find($data->{'id'});
        }
        $predio_pago->id_predio = $data->{'id_predio'};
        $predio_pago->ultimo_anio_pago = $data->{'ultimo_anio_pago'};
        $predio_pago->valor_pago = str_replace(",", "", $data->{'valor_pago'});
        $predio_pago->fecha_pago = Carbon::createFromFormat("Y-m-d", $data->{'fecha_pago'})->format('Y-m-d');
        $predio_pago->factura_pago = $data->{'factura_pago'};
        $predio_pago->id_banco = $data->{'id_banco'};
        $predio_pago->acuerdo_pago = property_exists($data, 'acuerdo_pago') ? $data->{'acuerdo_pago'} : '0';

        $query = $predio_pago->save();

        $result = array("success"=>$query);

        if($query) {
            $result['message'] = 'Información de pago del predio actualizada satisfactoriamente.';
            return response()->json([
                'data' => $result,
                'obj' => $predio_pago
            ]);
        }
        else {
            $result['message'] = 'No se pudo actualizar la información de pago del predio.';
            return response()->json([
                'data' => $result
            ]);
        }
    }

    public function store_predios_datos_abonos(Request $request) {

        $data = json_decode($request->form);

        $predio_abono = new PredioAbono();

        if(property_exists($data, 'id')) {
            $predio_abono = PredioAbono::find($data->{'id'});
        }
        $predio_abono->id_predio = $data->{'id_predio'};
        $predio_abono->anio_abono = $data->{'anio_abono'};
        $predio_abono->factura_abono = $data->{'factura_abono'};
        $predio_abono->valor_abono = str_replace(",", "", $data->{'valor_abono'});

        $query = $predio_abono->save();

        $result = array("success"=>$query);

        if($query) {
            $result['message'] = 'Información de abono del predio actualizada satisfactoriamente.';

            $predio_abonos = PredioAbono::where('id_predio', $data->{'id_predio'})
                            ->orderBy('id', 'asc')
                            ->get();

            return response()->json([
                'data' => $result,
                'obj' => $predio_abonos
            ]);
        }
        else {
            $result['message'] = 'No se pudo actualizar la información de abono del predio.';
            return response()->json([
                'data' => $result
            ]);
        }
    }

    public function store_predios_datos_acuerdos_pago(Request $request) {

        $data = json_decode($request->form);

        $predio_acuerdo_pago = new PredioAcuerdoPago();

        if(property_exists($data, 'id')) {
            $predio_acuerdo_pago = PredioAcuerdoPago::find($data->{'id'});
            if(intval($data->{'anulado_acuerdo'}) == 0) {
                $numero = $predio_acuerdo_pago->numero_acuerdo;
            }
        } else {
            $numero = DB::table('predios_acuerdos_pago')
                    ->select(DB::raw('ISNULL(max(numero_acuerdo), 0) + 1 as numero'))
                    ->first();
            $numero = $numero->numero;
        }
        if(intval($data->{'anulado_acuerdo'}) == 0) {
            $predio_acuerdo_pago->id_predio = $data->{'id_predio'};
            $predio_acuerdo_pago->numero_acuerdo = $numero;
            $predio_acuerdo_pago->anio_inicial_acuerdo = $data->{'anio_inicial_acuerdo'};
            $predio_acuerdo_pago->anio_final_acuerdo = $data->{'anio_final_acuerdo'};
            $predio_acuerdo_pago->responsable_propietario_acuerdo = property_exists($data, 'responsable_propietario_acuerdo') ? $data->{'responsable_propietario_acuerdo'} : '0';
            $predio_acuerdo_pago->identificacion_acuerdo = $data->{'identificacion_acuerdo'};
            $predio_acuerdo_pago->nombre_acuerdo = trim($data->{'nombre_acuerdo'});
            $predio_acuerdo_pago->direccion_acuerdo = trim($data->{'direccion_acuerdo'});
            $predio_acuerdo_pago->telefono_acuerdo = $data->{'telefono_acuerdo'};
            $predio_acuerdo_pago->cuotas_acuerdo = $data->{'cuotas_acuerdo'};
            $predio_acuerdo_pago->numero_resolucion_acuerdo = $data->{'numero_resolucion_acuerdo'};
            $predio_acuerdo_pago->dia_pago_acuerdo = $data->{'dia_pago_acuerdo'};
            $predio_acuerdo_pago->abono_inicial_acuerdo = str_replace(",", "", $data->{'abono_inicial_acuerdo'});
            $predio_acuerdo_pago->id_usuario_crea = intval($request->session()->get('userid'));
            $predio_acuerdo_pago->fecha_acuerdo = Carbon::createFromFormat("Y-m-d", $data->{'fecha_acuerdo'})->format('Y-m-d');

            $dt = Carbon::now();
            $dt_now = Carbon::now();
            $mes_final = $dt->month + intval($data->{'cuotas_acuerdo'});
            $dt_final = $dt_now->year($dt->year)->month($mes_final)->day(intval($data->{'dia_pago_acuerdo'}));
            $predio_acuerdo_pago->fecha_final_acuerdo = $dt_final->format('Y-m-d');

            $total = DB::table('predios_pagos')
                    ->select(DB::raw('ISNULL(SUM(total_calculo), 0) as total'))
                    ->where('id_predio', $data->{'id_predio'})
                    ->where('pagado', 0)
                    ->where('anulada', 0)
                    ->whereBetween('ultimo_anio', array(
                        $data->{'anio_inicial_acuerdo'},
                        $data->{'anio_final_acuerdo'}
                    ))
                    ->first();
            $predio_acuerdo_pago->total_acuerdo = floatval($total->total) - floatval(str_replace(",", "", $data->{'abono_inicial_acuerdo'}));
            // TODO: ejecutar procedimiento calculo cuotas

        } else {
            $predio_acuerdo_pago->estado_acuerdo = 0; // inactivo
            $predio_acuerdo_pago->id_usuario_anula = intval($request->session()->get('userid'));
            $predio_acuerdo_pago->fecha_anulacion = Carbon::createFromFormat("Y-m-d H:i:s", Carbon::now()->toDateTimeString())->format('Y-m-d H:i:s');
        }
        $query = $predio_acuerdo_pago->save();

        $result = array("success"=>$query);

        if($query) {
            $predio_acuerdo_pago = PredioAcuerdoPago::find($predio_acuerdo_pago->id);
            $result['message'] = intval($data->{'anulado_acuerdo'}) == 0 ? 'Información del acuerdo de pago actualizada satisfactoriamente.' : 'Acuerdo de pago anulado satisfactoriamente.';
            return response()->json([
                'data' => $result,
                'obj' => $predio_acuerdo_pago
            ]);
        }
        else {
            $result['message'] = intval($data->{'anulado_acuerdo'}) == 0 ? 'No se pudo actualizar la información del acuerdo de pago.' : 'No se pudo anular el acuerdo de pago.';
            return response()->json([
                'data' => $result
            ]);
        }
    }

    public function show_predios_datos(Request $request) {
        $predio_dato = PredioDato::where('id_predio', $request->id_predio)->first();

        $predio_propietarios = DB::table('predios_propietarios')
                                   ->join('propietarios', 'predios_propietarios.id_propietario', '=', 'propietarios.id')
                                   ->select('propietarios.*', 'predios_propietarios.jerarquia', 'predios_propietarios.id AS id_predio_propietario')
                                   ->where('predios_propietarios.id_predio', $request->id_predio)
                                   ->orderBy('predios_propietarios.jerarquia', 'asc')
                                   ->get();

        $predio_calculo = PredioCalculo::where('id_predio', $request->id_predio)->first();

        $predio_pago = PredioPago::select('id', 'id_predio', 'ultimo_anio', 'valor_pago', 'fecha_pago', 'factura_pago', 'id_banco')
                        ->where('id_predio', $request->id_predio)
                        ->where('pagado', '<>', 0)
                        ->orderBy('ultimo_anio', 'desc')
                        ->first();

        $predio_acuerdo_pago = PredioAcuerdoPago::where('id_predio', $request->id_predio)
            ->where('estado_acuerdo', 1) // activo
            ->first();

        $predio_abonos = PredioAbono::where('id_predio', $request->id_predio)
                        ->orderBy('id', 'asc')
                        ->get();

        //$predio_proceso_historico = PredioAbono::where('id_predio', $request->id_predio)->first();

        return response()->json([
            'predio_dato' => $predio_dato,
            'predio_propietarios' => $predio_propietarios,
            'predio_calculo' => $predio_calculo,
            'predio_pago' => $predio_pago,
            'predio_acuerdo_pago' => $predio_acuerdo_pago,
            'predio_abonos' => $predio_abonos
        ]);
    }

    public function generate_factura_pdf(Request $request, $id, $tmp, $anios, $fecha_pago, $informativa, $propietario, $vigencias) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }

        if (count(explode(',', $anios)) > 1 || $vigencias == 1) {
            if (count(explode(',', $anios)) == 1) {
                $anio_ini = explode(',', $anios)[0];
                $anio_fin = explode(',', $anios)[0];
            } else {
                $anio_ini = explode(',', $anios)[0];
                $anio_fin = explode(',', $anios)[1];
            }
            return self::generate_factura_pdf_vigencias($request, $id, $tmp, $anio_ini, $anio_fin, $fecha_pago, $informativa, $propietario);
        } else {
            return self::generate_factura_pdf_no_vigencias($request, $id, $tmp, $anios, $fecha_pago, $informativa, $propietario);
        }
    }

    // TODO: Impresion para un solo anio enviado desde la interfaz
    public function generate_factura_pdf_no_vigencias($request, $id, $tmp, $anio_ini, $fecha_pago, $informativa, $propietario) {
        DB::beginTransaction();
        try {
            $submit = [];
            $dt = Carbon::now();
            $dt_emision = Carbon::now();
            $fecha_emision = $dt_emision;
            $currentYear = $dt->year;
            $primerCalculo = 0;
            $facturaYaPagada = false;

            if ($fecha_pago == '-') {
                $fecha_pago = $fecha_emision->format('Y-m-d');
            }

            $ultimo_anio_pagar = DB::table('predios_pagos')
                                    ->where('id_predio', $id)
                                    ->where('ultimo_anio', $anio_ini)
                                    // ->where('pagado', '<>', 0) // 0: Pendiente, -1: Pagado
                                    ->first();

            if (intval($informativa) == 1) {
                if ($ultimo_anio_pagar->factura_pago != null) {
                    $temp_factura_pago = $ultimo_anio_pagar->factura_pago;
                    $count_factura_pago = DB::table('predios_pagos')
                                        ->where('id_predio', $id)
                                        ->where('factura_pago', $temp_factura_pago)
                                        ->where('pagado', 0)
                                        ->where('anulada', 0)
                                        ->count();
                    if ($count_factura_pago > 1) {
                        $ultimo_anio_pagar = DB::table('predios_pagos')
                                            ->where('id_predio', $id)
                                            ->where('factura_pago', $temp_factura_pago)
                                            ->orderBy('ultimo_anio', 'desc')
                                            ->first();

                        $anio_ini = $ultimo_anio_pagar->ultimo_anio;
                    }
                }
            }

            if($ultimo_anio_pagar != null && intval($ultimo_anio_pagar->pagado) != 0) {
                $facturaYaPagada = true;
            }
            // else {
            //     // Verificar si el registro ya existe
            //     $ultimo_anio_pagar = DB::table('predios_pagos')
            //                         ->where('id_predio', $id)
            //                         ->where('ultimo_anio', $anio_ini)
            //                         ->where('pagado', 0)
            //                         ->first();
            // }

            // Si no existe un predio_pago para el año actual, entonces EJECUTAR PROCEDIMIENTO DE CALCULO
            if(($ultimo_anio_pagar == null ||
                ($ultimo_anio_pagar != null &&
                 $ultimo_anio_pagar->valor_pago == 0 &&
                 $ultimo_anio_pagar->fecha_pago == null &&
                 $ultimo_anio_pagar->id_banco == null)
               ) && intval($informativa) == 0
            ) {
                // if(intval($tmp) == 0 && $ultimo_anio_pagar != null) {
                    $fecha_emision = $ultimo_anio_pagar->fecha_emision != null && $facturaYaPagada ? Carbon::createFromFormat('Y-m-d H:i:s.u', $ultimo_anio_pagar->fecha_emision) : $fecha_emision;
                // }

                $submit = DB::select("SET NOCOUNT ON; EXEC SP_CALCULO_PREDIAL ?,?,?,?,?",
                    array(
                        intval($request->session()->get('userid')),                                                 // @USUARIO_ID
                        $anio_ini,                                                                                  // @ANO
                        0,                                                                                          // @ANO_FIN
                        $id,                                                                                        // @PREDIO_ID
                        Carbon::createFromFormat("Y-m-d H:i:s", $fecha_pago . ' 00:00:00')->format('Y-m-d H:i:s')   // @FECHA_PAGO
                    )
                );
                if ($ultimo_anio_pagar == null) {
                    $primerCalculo = 1;
                }
                else {
                    $ultimo_anio_pagar = DB::table('predios_pagos')
                                        ->where('id_predio', $id)
                                        ->where('ultimo_anio', $anio_ini)
                                        ->where('pagado', 0)
                                        ->first();
                }
            }

            if(count($submit) > 0 || $ultimo_anio_pagar != null) {

                $anio = Anio::where('anio', $currentYear)->first();

                $ultimo_numero_factura = 0;

                // Si se EJECUTO EL PROCEDIMIENTO DE CALCULO, entonces se genera un nuevo numero de factura
                // Generar informacion de numero de factura solo si se realizo un nuevo calculo
                if (
                    (
                     (count($submit) > 0 && $primerCalculo == 1) ||
                     ($ultimo_anio_pagar != null && $ultimo_anio_pagar->factura_pago == null)
                    ) && intval($tmp) == 0
                ) {
                    $init_anio = new Anio;
                    $init_anio = Anio::find($anio->id);
                    $case_ultimo_numero_factura = 0;

                    if($anio->numero_factura_actual == null || $anio->numero_factura_actual == 0) {
                        if($anio->numero_factura_inicial == null || $anio->numero_factura_inicial == 0) {
                            $ultimo_numero_factura = 1;
                            $case_ultimo_numero_factura = 1;
                        }
                        else {
                            $ultimo_numero_factura = $anio->numero_factura_inicial;
                            $case_ultimo_numero_factura = 2;
                        }
                    }
                    else {
                        if($anio->numero_factura_inicial == null || $anio->numero_factura_inicial == 0) {
                            $ultimo_numero_factura = $anio->numero_factura_actual;
                            $case_ultimo_numero_factura = 3;
                        }
                        else if($anio->numero_factura_inicial > $anio->numero_factura_actual) {
                            $ultimo_numero_factura = $anio->numero_factura_inicial;
                            $case_ultimo_numero_factura = 4;
                        }
                        else {
                            $ultimo_numero_factura = $anio->numero_factura_actual;
                        }
                    }

                    // Maximo numero de factura 99999 cada anio
                    if($ultimo_numero_factura > 99999) {
                        dd('El número de factura excede el límite permitido de 99999. Valor actual: ' . $ultimo_numero_factura);
                        return null;
                    }

                    if($case_ultimo_numero_factura == 1) {
                        $init_anio->numero_factura_inicial = $ultimo_numero_factura;
                        $init_anio->numero_factura_actual = $ultimo_numero_factura;
                        $init_anio->save();
                    }
                    else if($case_ultimo_numero_factura == 2 || $case_ultimo_numero_factura == 4) {
                        $init_anio->numero_factura_actual = $ultimo_numero_factura;
                        $init_anio->save();
                    }
                    else if($case_ultimo_numero_factura == 3) {
                        $init_anio->numero_factura_inicial = $ultimo_numero_factura;
                        $init_anio->save();
                    }

                    $numero_factura = $currentYear . (str_pad($ultimo_numero_factura, 5, "0", STR_PAD_LEFT));

                    // Configurar informacion del ultimo año a pagar
                    if(count($submit) > 0) {
                        $ultimo_anio_pagar = $submit[0];
                    }

                    // Actualizar el consecutivo de numero de factura disponible para la proxima impresion
                    // Guardar informacion solo si se realizo un nuevo calculo
                    $init_anio->numero_factura_actual = $ultimo_numero_factura + 1;
                    $init_anio->save();

                }
                else {
                    if(intval($tmp) == 0) {
                        $numero_factura = $ultimo_anio_pagar->factura_pago;
                    }
                    else {
                        $numero_factura = '000000000';
                        $ultimo_anio_pagar = DB::table('predios_pagos')
                                ->where('id_predio', $id)
                                ->where('ultimo_anio', $anio_ini)
                                ->where('pagado', 0)
                                ->first();
                    }
                    // $fecha_emision = $ultimo_anio_pagar->fecha_emision != null ? Carbon::createFromFormat('Y-m-d H:i:s.u', $ultimo_anio_pagar->fecha_emision) : $fecha_emision;
                }

                $predios = DB::table('predios')->join('zonas', function ($join) {
                    $join->on('predios.id_zona', '=', 'zonas.id');
                })

                // ->leftJoin('predios_prescripciones', 'predios.id', '=', 'predios_prescripciones.id_predio')
                // ->select(DB::raw('predios.*, zonas.descripcion, CASE WHEN COALESCE(predios_prescripciones.prescribe_anio, 0) >= YEAR(GETDATE()) THEN 1 ELSE 0 END AS prescrito, predios_prescripciones.prescribe_anio'))

                ->select(DB::raw('predios.*, zonas.descripcion'))

                ->where('predios.estado', 1)
                ->where('predios.id', $id)
                ->get();

                if ($propietario == '-1') {
                    $propietarios = DB::table('predios')
                                            ->join('predios_propietarios', 'predios.id', '=', 'predios_propietarios.id_predio')
                                            ->join('propietarios', 'propietarios.id', '=', 'predios_propietarios.id_propietario')
                                            ->join('zonas', 'zonas.id', '=', 'predios.id_zona')
                        ->select(DB::raw('predios_propietarios.id_predio, STRING_AGG(TRIM(propietarios.nombre), \'<br />\') AS propietarios, STRING_AGG(propietarios.identificacion, \'<br />\') AS identificaciones'))
                        ->where('predios.estado', 1)
                        ->where('predios.id', $id)
                        ->where('predios_propietarios.jerarquia', '001')
                        ->groupBy('predios_propietarios.id_predio')
                        ->get();
                } else {
                    $propietarios = DB::table('predios')
                                            ->join('predios_propietarios', 'predios.id', '=', 'predios_propietarios.id_predio')
                                            ->join('propietarios', 'propietarios.id', '=', 'predios_propietarios.id_propietario')
                                            ->join('zonas', 'zonas.id', '=', 'predios.id_zona')
                        ->select(DB::raw('predios_propietarios.id_predio, STRING_AGG(TRIM(propietarios.nombre), \'<br />\') AS propietarios, STRING_AGG(propietarios.identificacion, \'<br />\') AS identificaciones'))
                        ->where('predios.estado', 1)
                        ->where('predios.id', $id)
                        ->where('predios_propietarios.id', $propietario)
                        ->groupBy('predios_propietarios.id_predio')
                        ->get();
                }

                if($propietarios) {
                    foreach ($predios as $key => $predio) {
                        $desired_object = self::findInCollection($propietarios, 'id_predio', $predio->id);
                        if($desired_object) {
                            $predio->propietarios = $desired_object->propietarios;
                            $predio->identificaciones = $desired_object->identificaciones;
                        }
                        else {
                            $predio->propietarios = 'Sin asignar';
                            $predio->identificaciones = 'Sin asignar';
                        }
                        // Se muestra un solo propietario
                        break;
                    }
                }
                else {
                    foreach ($predios as $key => $predio) {
                        $predio->propietarios = 'Sin asignar';
                        $predio->identificaciones = 'Sin asignar';
                    }
                }

                $parametro_logo = DB::table('parametros')
                                ->select('parametros.valor')
                                ->where('parametros.nombre', 'logo')
                                ->first();

                $parametro_nit = DB::table('parametros')
                                ->select('parametros.valor')
                                ->where('parametros.nombre', 'nit')
                                ->first();

                $parametro_ean = DB::table('parametros')
                                ->select('parametros.valor')
                                ->where('parametros.nombre', 'ean')
                                ->first();

                $parametro_alcaldia = DB::table('parametros')
                                ->select('parametros.valor')
                                ->where('parametros.nombre', 'alcaldia')
                                ->first();

                $parametro_formato_factura = DB::table('parametros')
                                ->select('parametros.valor')
                                ->where('parametros.nombre', 'formato-factura')
                                ->first();

                $parametro_unir_impuesto_car = DB::table('parametros')
                                ->select('parametros.valor')
                                ->where('parametros.nombre', 'unir-impuesto-car')
                                ->first();

                $logo = $parametro_logo->valor;
                $nit = $parametro_nit->valor;
                $ean = $parametro_ean->valor;
                $alcaldia = $parametro_alcaldia->valor;
                $formato_factura = $parametro_formato_factura->valor;
                $unir_impuesto_car = $parametro_unir_impuesto_car->valor;
                $lista_pagos = new Collection();
                $lista_pagos_depurada = new Collection();
                $ultimo_anio_pagado = new Collection();
                $suma_total = new Collection();
                $suma_intereses = 0;
                $suma_trece = 0;
                $valores_factura = new Collection();
                $fechas_pago_hasta = new Collection();
                $barras = new Collection();
                $barras_texto = new Collection();
                $porcentajes_descuento = new Collection();

                if(!$facturaYaPagada) {
                    // Obtener informacion del ultimo año pagado
                    $ultimo_anio_pagado = DB::table('predios_pagos')
                                        ->where('id_predio', $id)
                                        ->where('pagado', '<>', 0)
                                        // ->whereNotNull('factura_pago') // TODO: ejemplo: imprimir 2023 por vigencias, despues imprimir 2024 normal, esto deberia incluir 2020 y 2024, buscar: 30207000
                                        ->where('ultimo_anio', '<=', $anio_ini)
                                        ->orderBy('ultimo_anio', 'desc')
                                        ->first();
                }
                else {
                    // Obtener informacion del ultimo año pagado
                    $ultimo_anio_pagado = DB::table('predios_pagos')
                                        ->where('id_predio', $id)
                                        ->where('pagado', '<>', 0)
                                        ->where('ultimo_anio', '<=', $anio_ini)
                                        ->where('factura_pago', '<>', $ultimo_anio_pagar->factura_pago)
                                        ->orderBy('ultimo_anio', 'desc')
                                        ->first();
                }

                //establecer años a pagar
                // if($ultimo_anio_pagado == null || $ultimo_anio_pagado->ultimo_anio + 1 == $anio_ini || $ultimo_anio_pagado->ultimo_anio == $currentYear) {
                //     $predio->anios_a_pagar = $anio_ini;
                // }
                // else {
                //     $predio->anios_a_pagar = ($ultimo_anio_pagado->ultimo_anio + 1) . ' A ' . $anio_ini;
                // }

                // if(!$facturaYaPagada) {
                //     // Obtener informacion del ultimo año pagado
                //     $ultimo_anio_pagado = DB::table('predios_pagos')
                //                         ->where('id_predio', $id)
                //                         ->where('pagado', '<>', 0)
                //                         ->where('ultimo_anio', '<=', $anio_ini)
                //                         ->orderBy('ultimo_anio', 'desc')
                //                         ->first();
                // }

                if ($ultimo_anio_pagado == null) {
                    $obj = new StdClass();
                    $obj->factura_pago = '';
                    $obj->ultimo_anio = '';
                    $obj->fecha_pago = '';
                    $obj->valor_pago = 0;
                    $ultimo_anio_pagado = $obj;
                }
                else {
                    $ultimo_anio_pagado->fecha_pago = $ultimo_anio_pagado->fecha_pago !== null ? Carbon::createFromFormat("Y-m-d", substr($ultimo_anio_pagado->fecha_pago, 0, 10))->format('d/m/Y') : 'N/D';
                }

                if(!$facturaYaPagada) {
                    // Obtener informacion de los pagos pendientes por año
                    if ($ultimo_anio_pagar->factura_pago == null) {
                        $pagos_pendientes = DB::table('predios_pagos')
                                        ->where('id_predio', $id)
                                        ->where('pagado', 0)
                                        ->where('ultimo_anio', '<=', $anio_ini)
                                        ->where(function($query) use($tmp, $numero_factura, $ultimo_anio_pagar) {
                                            $query->whereNull('factura_pago');
                                        })
                                        ->orderBy('ultimo_anio', 'asc')
                                        ->get();
                    } else {
                        $pagos_pendientes = DB::table('predios_pagos')
                                            ->where('id_predio', $id)
                                            ->where('pagado', 0)
                                            ->where('ultimo_anio', '<=', $anio_ini)
                                            ->where(function($query) use($tmp, $numero_factura, $ultimo_anio_pagar) {
                                                $query->//whereNull('factura_pago')
                                                    //->orWhere
                                                    where('factura_pago', (intval($tmp) == 0) ? $numero_factura : $ultimo_anio_pagar->factura_pago);
                                            })
                                            ->orderBy('ultimo_anio', 'asc')
                                            ->get();
                    }
                }
                else {
                    // Obtener informacion de los registros que coinciden con la factura
                    $pagos_pendientes = DB::table('predios_pagos')
                                        ->where('id_predio', $id)
                                        ->where('pagado', '<>', 0)
                                        ->where('factura_pago', $ultimo_anio_pagar->factura_pago)
                                        ->orderBy('ultimo_anio', 'asc')
                                        ->get();
                }

                if(count($pagos_pendientes) == 1 || strlen($ultimo_anio_pagado->ultimo_anio) == 0) {
                    $predio->anios_a_pagar = $anio_ini;
                }
                else {
                    if ($ultimo_anio_pagar->factura_pago == null) {
                        $predio->anios_a_pagar = ($ultimo_anio_pagado->ultimo_anio + 1) . ' A ' . $anio_ini;
                    } else {
                        $minAnioCalculado = DB::table('predios_pagos')
                                ->select(DB::raw('min(ultimo_anio) as min_anio_calculo'))
                                ->where('predios_pagos.id_predio', $id)
                                ->where('predios_pagos.factura_pago', $ultimo_anio_pagar->factura_pago)
                                ->first();
                        $predio->anios_a_pagar = $minAnioCalculado->min_anio_calculo . ' A ' . $anio_ini;
                    }
                }

                $suma_total[0] = 0;
                $suma_total[1] = 0;
                $suma_total[2] = 0;
                foreach ($pagos_pendientes as $pago_pendiente) {
                    $obj = new StdClass();
                    $obj->anio = $pago_pendiente->ultimo_anio;
                    $obj->m_tar = $pago_pendiente->tarifa == null ? 0 : $pago_pendiente->tarifa * 1000;
                    $obj->avaluo = $pago_pendiente->avaluo == null ? 0 : $pago_pendiente->avaluo;

                    $obj->impuesto = $pago_pendiente->valor_concepto1 == null ? 0 : $pago_pendiente->valor_concepto1;
                    $obj->impuesto_interes = $pago_pendiente->valor_concepto2 == null ? 0 : $pago_pendiente->valor_concepto2;

                    $obj->car = $pago_pendiente->valor_concepto3 == null ? 0 : $pago_pendiente->valor_concepto3;
                    $obj->car_interes = $pago_pendiente->valor_concepto4 == null ? 0 : $pago_pendiente->valor_concepto4;

                    $obj->trece = $pago_pendiente->valor_concepto13 == null ? 0 : $pago_pendiente->valor_concepto13;
                    $obj->catorce = $pago_pendiente->valor_concepto14 == null ? 0 : $pago_pendiente->valor_concepto14;
                    $obj->quince = $pago_pendiente->valor_concepto15 == null ? 0 : $pago_pendiente->valor_concepto15;
                    $obj->dieciseis = $pago_pendiente->valor_concepto16 == null ? 0 : $pago_pendiente->valor_concepto16;
                    $obj->diecisiete = $pago_pendiente->valor_concepto17 == null ? 0 : $pago_pendiente->valor_concepto17;
                    $obj->dieciocho = $pago_pendiente->valor_concepto18 == null ? 0 : $pago_pendiente->valor_concepto18;
                    $obj->blanco = 0;
                    $obj->otros = 0;
                    $obj->total = $pago_pendiente->total_calculo == null ? 0 : $pago_pendiente->total_calculo;

                    $suma_total[0] += $pago_pendiente->ultimo_anio < $anio_ini ? $pago_pendiente->total_calculo : 0; // al ultimo año se le calculan descuentos
                    $suma_total[1] += $pago_pendiente->ultimo_anio < $anio_ini ? $pago_pendiente->total_dos : 0; // al ultimo año se le calculan descuentos
                    $suma_total[2] += $pago_pendiente->ultimo_anio < $anio_ini ? $pago_pendiente->total_tres : 0; // al ultimo año se le calculan descuentos

                    $suma_intereses += $pago_pendiente->ultimo_anio < $anio_ini ? $pago_pendiente->valor_concepto2 : 0; // al ultimo año se le calculan descuentos
                    $suma_trece += $pago_pendiente->ultimo_anio < $anio_ini ? $pago_pendiente->valor_concepto13 : 0; // al ultimo año se le calculan descuentos
                    $lista_pagos->push($obj);
                }

                $inc = 0;
                // Validar primer fecha
                if (
                    $ultimo_anio_pagar->primer_fecha != null &&
                    Carbon::now()->format('Y-m-d') <= Carbon::createFromFormat("Y-m-d", substr($ultimo_anio_pagar->primer_fecha, 0, 10))->format('Y-m-d')
                    ) {
                    $valores_factura[$inc] = (round($suma_total[$inc] + $ultimo_anio_pagar->total_calculo, 0));
                    $fechas_pago_hasta[$inc] = (Carbon::createFromFormat('Y-m-d H:i:s.u', $ultimo_anio_pagar->primer_fecha)->toDateString());
                    $porcentajes_descuento[$inc] = ($ultimo_anio_pagar->porcentaje_uno);
                    $inc = $inc + 1;
                } else {
                    $valores_factura[$inc] = (round($suma_total[$inc] + $ultimo_anio_pagar->total_calculo, 0));
                    if ($ultimo_anio_pagar->primer_fecha != null) {
                        $fechas_pago_hasta[$inc] = (Carbon::createFromFormat('Y-m-d H:i:s.u', $ultimo_anio_pagar->primer_fecha)->toDateString());
                    } else {
                        $fechas_pago_hasta[$inc] = (Carbon::now()->toDateString());
                    }
                    $porcentajes_descuento[$inc] = ($ultimo_anio_pagar->porcentaje_uno);
                    $inc = $inc + 1;
                }

                if(count($lista_pagos) == 1 && $ultimo_anio_pagar->primer_fecha != null) {
                    // Validar segunda fecha
                    if (
                        $ultimo_anio_pagar->segunda_fecha != null &&
                        Carbon::now()->format('Y-m-d') <= Carbon::createFromFormat("Y-m-d", substr($ultimo_anio_pagar->segunda_fecha, 0, 10))->format('Y-m-d') &&
                        Carbon::createFromFormat("Y-m-d", substr($ultimo_anio_pagar->primer_fecha, 0, 10))->format('Y-m-d') < Carbon::createFromFormat("Y-m-d", substr($ultimo_anio_pagar->segunda_fecha, 0, 10))->format('Y-m-d')
                    ) {
                        $valores_factura[$inc] = (round($ultimo_anio_pagar->total_dos, 0));
                        $fechas_pago_hasta[$inc] = (Carbon::createFromFormat('Y-m-d H:i:s.u', $ultimo_anio_pagar->segunda_fecha)->toDateString());
                        $porcentajes_descuento[$inc] = ($ultimo_anio_pagar->porcentaje_dos);
                        $inc = $inc + 1;
                    }

                    // Validar tercer fecha
                    if (
                        $ultimo_anio_pagar->tercera_fecha != null &&
                        Carbon::now()->format('Y-m-d') <= Carbon::createFromFormat("Y-m-d", substr($ultimo_anio_pagar->tercera_fecha, 0, 10))->format('Y-m-d') &&
                        Carbon::createFromFormat("Y-m-d", substr($ultimo_anio_pagar->segunda_fecha, 0, 10))->format('Y-m-d') < Carbon::createFromFormat("Y-m-d", substr($ultimo_anio_pagar->tercera_fecha, 0, 10))->format('Y-m-d')
                    ) {
                        $valores_factura[$inc] = (round($ultimo_anio_pagar->total_tres, 0));
                        $fechas_pago_hasta[$inc] = (Carbon::createFromFormat('Y-m-d H:i:s.u', $ultimo_anio_pagar->tercera_fecha)->toDateString());
                        $porcentajes_descuento[$inc] = ($ultimo_anio_pagar->porcentaje_tres);
                    }
                }
                else if(count($lista_pagos) > 1 && $ultimo_anio_pagar->primer_fecha != null) {
                    $offset = str_contains(strtolower($alcaldia), 'de leyva') ? $currentYear - 2009 : 5;
                    if(count($lista_pagos) > $offset && !str_contains(strtolower($alcaldia), 'guateque') && !str_contains(strtolower($alcaldia), 'sutatenza')) {
                        $obj = new StdClass();
                        $obj->anio = '< ' . $lista_pagos[count($lista_pagos) - $offset]->anio;
                        $obj_m_tar = 0;
                        $obj_avaluo = 0;
                        $obj_impuesto = 0;
                        $obj_impuesto_interes = 0;
                        $obj_car = 0;
                        $obj_car_interes = 0;
                        $obj_trece = 0;
                        $obj_catorce = 0;
                        $obj_quince = 0;
                        $obj_dieciseis = 0;
                        $obj_diecisiete = 0;
                        $obj_dieciocho = 0;
                        $obj_blanco = 0;
                        $obj_otros = 0;
                        $obj_total = 0;

                        for ($x = 0; $x < count($lista_pagos) - $offset; $x++) {
                            $obj_m_tar += $lista_pagos[$x]->m_tar == null ? 0 : $lista_pagos[$x]->m_tar;
                            $obj_avaluo += $lista_pagos[$x]->avaluo == null ? 0 : $lista_pagos[$x]->avaluo;
                            $obj_impuesto += $lista_pagos[$x]->impuesto == null ? 0 : $lista_pagos[$x]->impuesto;
                            $obj_impuesto_interes += $lista_pagos[$x]->impuesto_interes == null ? 0 : $lista_pagos[$x]->impuesto_interes;
                            $obj_car += $lista_pagos[$x]->car == null ? 0 : $lista_pagos[$x]->car;
                            $obj_car_interes += $lista_pagos[$x]->car_interes == null ? 0 : $lista_pagos[$x]->car_interes;
                            $obj_trece += $lista_pagos[$x]->trece;
                            $obj_catorce += $lista_pagos[$x]->catorce;
                            $obj_quince += $lista_pagos[$x]->quince;
                            $obj_dieciseis += $lista_pagos[$x]->dieciseis;
                            $obj_diecisiete += $lista_pagos[$x]->diecisiete;
                            $obj_dieciocho += $lista_pagos[$x]->dieciocho;
                            $obj_blanco += $lista_pagos[$x]->blanco;
                            $obj_otros += $lista_pagos[$x]->otros;
                            $obj_total += $lista_pagos[$x]->total;
                        }

                        $obj->m_tar = $obj_m_tar;
                        $obj->avaluo = $obj_avaluo;
                        $obj->impuesto = $obj_impuesto;
                        $obj->impuesto_interes = $obj_impuesto_interes;
                        $obj->car = $obj_car;
                        $obj->car_interes = $obj_car_interes;
                        $obj->trece = $obj_trece;
                        $obj->catorce = $obj_catorce;
                        $obj->quince = $obj_quince;
                        $obj->dieciseis = $obj_dieciseis;
                        $obj->diecisiete = $obj_diecisiete;
                        $obj->dieciocho = $obj_dieciocho;
                        $obj->blanco = $obj_blanco;
                        $obj->otros = $obj_otros;
                        $obj->total = $obj_total;

                        $lista_pagos_depurada->push($obj);
                        for ($x = count($lista_pagos) - $offset; $x < count($lista_pagos); $x++) {
                            $lista_pagos_depurada->push($lista_pagos[$x]);
                        }

                        $lista_pagos = $lista_pagos_depurada;

                    }
                }

                if (count($valores_factura) == 0) {
                    // TODO: Preguntar si esta validacion esta bien.
                    // Cuando la fecha de generacion es mayor a las tres fechas posibles de pago, entonces,
                    // la fecha de pago hasta debe ser el mismo dia, maximo a las 11:59pm
                    $dt_now = Carbon::now();
                    $dt_pago = $dt_now->year($dt->year)->month($dt->month)->day($dt->day)->hour(23)->minute(59)->second(59)->toDateString();
                    $valores_factura[0] = (round($suma_total[0] + $ultimo_anio_pagar->total_calculo, 0));
                    $fechas_pago_hasta[0] = $dt_pago;
                    $porcentajes_descuento[0] = ($ultimo_anio_pagar->porcentaje_uno);
                }

                for ($x = 0; $x < count($valores_factura); $x++) {
                    $barras[$x] = (chr(241) . '415' . $ean . '8020' . str_pad($numero_factura , 24, "0", STR_PAD_LEFT) . chr(241) . '3900' . str_pad($valores_factura[$x], 14, "0", STR_PAD_LEFT) . chr(241) . '96' . str_replace('-', '', $fechas_pago_hasta[$x]));
                    $barras_texto[$x] = ('(415)' . $ean . '(8020)' . str_pad($numero_factura , 24, "0", STR_PAD_LEFT) . '(3900)' . str_pad($valores_factura[$x], 14, "0", STR_PAD_LEFT) . '(96)' . str_replace('-', '', $fechas_pago_hasta[$x]));
                }

                if (str_contains(strtolower($alcaldia), 'paipa') && count($valores_factura) > 1) {
                    // $paso = 0;
                    // foreach ($fechas_pago_hasta as $fecha) {
                    //     if ($fecha)
                    // }
                    // $filter = 0; // primeros dos codigos
                    $filter = 1; // ultimos dos codigos
                    // dd($porcentajes_descuento);
                    // dd($valores_factura);
                    // $filter = 2; // ultimo codigo
                    for ($x = 0; $x < 2; $x++) {
                        if ($x + $filter < 2) {
                            $barras_filtrado[$x] = $barras[$x + $filter];
                            $barras_texto_filtrado[$x] = $barras_texto[$x + $filter];
                            $fechas_pago_hasta_filtrado[$x] = $fechas_pago_hasta[$x + $filter];
                            $porcentajes_descuento_filtrado[$x] = $porcentajes_descuento[$x + $filter];
                            $valores_factura_filtrado[$x] = $valores_factura[$x + $filter];
                        }
                    }
                } else {
                    $barras_filtrado = $barras;
                    $barras_texto_filtrado = $barras_texto;
                    $fechas_pago_hasta_filtrado = $fechas_pago_hasta;
                    $porcentajes_descuento_filtrado = $porcentajes_descuento;
                    $valores_factura_filtrado = $valores_factura;
                }

                $data = [
                    'title' => 'Predio',
                    'fecha' => $ultimo_anio_pagar->primer_fecha != null ? $fecha_emision->format('d/m/Y') : 'INDEFINIDA',
                    'hora' => $ultimo_anio_pagar->primer_fecha != null ? $fecha_emision->isoFormat('h:mm:ss a') : '',
                    'numero_factura' => $numero_factura,
                    'predio' => $predio,
                    'ultimo_anio_pagado' => $ultimo_anio_pagado,
                    'lista_pagos' => $lista_pagos,
                    'barras' => $barras_filtrado,
                    'barras_texto' => $barras_texto_filtrado,
                    'fechas_pago_hasta' => $fechas_pago_hasta_filtrado,
                    'porcentajes_descuento' => $porcentajes_descuento_filtrado,
                    'valores_factura' => $valores_factura_filtrado,
                    'temporal' => $tmp,
                    'facturaYaPagada' => $facturaYaPagada,
                    'nit' => $nit,
                    'logo' => $logo,
                    'alcaldia' => $alcaldia,
                    'informativa' => $informativa,
                    'unir_impuesto_car' => $unir_impuesto_car,
                    'usuario' => $request->session()->get('username') . ' ' . $request->session()->get('userlastname'),
                ];

                $pdf = PDF::loadView($formato_factura, $data);

                // Actualizar datos pago: valor_pago, numero_factura, fecha emision
                // Guardar informacion solo si se realizo un nuevo calculo
                if(((count($submit) > 0 && $primerCalculo == 1) || ($ultimo_anio_pagar != null && $ultimo_anio_pagar->factura_pago == null)) && intval($tmp) == 0) {
                    foreach ($pagos_pendientes as $pago_pendiente) {
                        $pp = new PredioPago;
                        $pp = PredioPago::find($pago_pendiente->id);
                        $pp->factura_pago = $numero_factura;
                        $pp->fecha_emision = Carbon::createFromFormat("Y-m-d H:i:s", $fecha_emision->toDateTimeString())->format('Y-m-d H:i:s');
                        $pp->save();
                    }
                }
                else if(count($submit) > 0) {
                    foreach ($pagos_pendientes as $pago_pendiente) {
                        $pp = new PredioPago;
                        $pp = PredioPago::find($pago_pendiente->id);
                        $pp->fecha_emision = Carbon::createFromFormat("Y-m-d H:i:s", $fecha_emision->toDateTimeString())->format('Y-m-d H:i:s');
                        $pp->save();
                    }
                }

                DB::commit();

                // Nombre del archivo obtenido a partir de la fecha exacta de solicitud de generación del PDF
                if(intval($tmp) == 0) {
                    return $pdf->download($numero_factura . '_' . $fecha_emision->toDateString() . '_' . str_replace(':', '-', $fecha_emision->toTimeString()) . '.pdf');
                }
                else {
                    return $pdf->download('temporal_' . $fecha_emision->toDateString() . '_' . str_replace(':', '-', $fecha_emision->toTimeString()) . '.pdf');
                }
            }
            else {
                DB::rollback();
                return null;
            }
        }
        catch(\Exception $e) {
            DB::rollback();
            return null;
        }
    }

    // TODO: Impresion para vigencia inicial y vigencia final enviados desde la interfaz
    public function generate_factura_pdf_vigencias($request, $id, $tmp, $anio_ini, $anio_fin, $fecha_pago, $informativa, $propietario) {
        DB::beginTransaction();
        try {
            $anios = $anio_fin;
            $submit = [];
            $dt = Carbon::now();
            $dt_emision = Carbon::now();
            $fecha_emision = $dt_emision;
            $currentYear = $dt->year;
            $primerCalculo = 0;
            $facturaYaPagada = false;

            if ($fecha_pago == '-') {
                $fecha_pago = $fecha_emision->format('Y-m-d');
            }

            $ultimo_anio_pagar = DB::table('predios_pagos')
                                    ->where('id_predio', $id)
                                    ->where('ultimo_anio', $anios)
                                    // ->where('pagado', '<>', 0) // 0: Pendiente, -1: Pagado
                                    ->first();

            if($ultimo_anio_pagar != null && intval($ultimo_anio_pagar->pagado) != 0) {
                $facturaYaPagada = true;
            }
            // else {
            //     // Verificar si el registro ya existe
            //     $ultimo_anio_pagar = DB::table('predios_pagos')
            //                         ->where('id_predio', $id)
            //                         ->where('ultimo_anio', $anios)
            //                         ->where('pagado', 0)
            //                         ->first();
            // }

            // Si no existe un predio_pago para el año actual, entonces EJECUTAR PROCEDIMIENTO DE CALCULO
            if(($ultimo_anio_pagar == null ||
                ($ultimo_anio_pagar != null &&
                 $ultimo_anio_pagar->valor_pago == 0 &&
                 $ultimo_anio_pagar->fecha_pago == null &&
                 $ultimo_anio_pagar->id_banco == null)
               ) && intval($informativa) == 0
            ) {
                // if(intval($tmp) == 0 && $ultimo_anio_pagar != null) {
                    $fecha_emision = $ultimo_anio_pagar->fecha_emision != null && $facturaYaPagada ? Carbon::createFromFormat('Y-m-d H:i:s.u', $ultimo_anio_pagar->fecha_emision) : $fecha_emision;
                // }

                $submit = DB::select(
                    "SET NOCOUNT ON; EXEC SP_CALCULO_PREDIAL ?,?,?,?,?",
                    array(
                        intval($request->session()->get('userid')),
                        $anio_ini,
                        $anio_fin,
                        $id,
                        Carbon::createFromFormat("Y-m-d H:i:s", $fecha_pago . ' 00:00:00')->format('Y-m-d H:i:s')
                    )
                );

                if ($ultimo_anio_pagar == null) {
                    $primerCalculo = 1;
                }
                else {
                    $ultimo_anio_pagar = DB::table('predios_pagos')
                                        ->where('id_predio', $id)
                                        ->where('ultimo_anio', $anios)
                                        ->where('pagado', 0)
                                        ->first();
                }
            }

            if(count($submit) > 0 || $ultimo_anio_pagar != null) {

                $anio = Anio::where('anio', $currentYear)->first();
                $ultimo_numero_factura = 0;

                // Si se EJECUTO EL PROCEDIMIENTO DE CALCULO, entonces se genera un nuevo numero de factura
                // Generar informacion de numero de factura solo si se realizo un nuevo calculo
                if(((count($submit) > 0 && $primerCalculo == 1) || ($ultimo_anio_pagar != null && $ultimo_anio_pagar->factura_pago == null)) && intval($tmp) == 0) {
                    $init_anio = new Anio;
                    $init_anio = Anio::find($anio->id);
                    $case_ultimo_numero_factura = 0;

                    if($anio->numero_factura_actual == null || $anio->numero_factura_actual == 0) {
                        if($anio->numero_factura_inicial == null || $anio->numero_factura_inicial == 0) {
                            $ultimo_numero_factura = 1;
                            $case_ultimo_numero_factura = 1;
                        }
                        else {
                            $ultimo_numero_factura = $anio->numero_factura_inicial;
                            $case_ultimo_numero_factura = 2;
                        }
                    }
                    else {
                        if($anio->numero_factura_inicial == null || $anio->numero_factura_inicial == 0) {
                            $ultimo_numero_factura = $anio->numero_factura_actual;
                            $case_ultimo_numero_factura = 3;
                        }
                        else if($anio->numero_factura_inicial > $anio->numero_factura_actual) {
                            $ultimo_numero_factura = $anio->numero_factura_inicial;
                            $case_ultimo_numero_factura = 4;
                        }
                        else {
                            $ultimo_numero_factura = $anio->numero_factura_actual;
                        }
                    }

                    // Maximo numero de factura 99999 cada anio
                    if($ultimo_numero_factura > 99999) {
                        dd('El número de factura excede el límite permitido de 99999. Valor actual: ' . $ultimo_numero_factura);
                        return null;
                    }

                    if($case_ultimo_numero_factura == 1) {
                        $init_anio->numero_factura_inicial = $ultimo_numero_factura;
                        $init_anio->numero_factura_actual = $ultimo_numero_factura;
                        $init_anio->save();
                    }
                    else if($case_ultimo_numero_factura == 2 || $case_ultimo_numero_factura == 4) {
                        $init_anio->numero_factura_actual = $ultimo_numero_factura;
                        $init_anio->save();
                    }
                    else if($case_ultimo_numero_factura == 3) {
                        $init_anio->numero_factura_inicial = $ultimo_numero_factura;
                        $init_anio->save();
                    }

                    $numero_factura = $currentYear . (str_pad($ultimo_numero_factura, 5, "0", STR_PAD_LEFT));

                    // Configurar informacion del ultimo año a pagar
                    if(count($submit) > 0) {
                        $ultimo_anio_pagar = $submit[0];
                    }

                    // Actualizar el consecutivo de numero de factura disponible para la proxima impresion
                    // Guardar informacion solo si se realizo un nuevo calculo
                    $init_anio->numero_factura_actual = $ultimo_numero_factura + 1;
                    $init_anio->save();

                }
                else {
                    if(intval($tmp) == 0) {
                        $numero_factura = $ultimo_anio_pagar->factura_pago;
                    }
                    else {
                        $numero_factura = '000000000';
                        $ultimo_anio_pagar = DB::table('predios_pagos')
                                ->where('id_predio', $id)
                                ->where('ultimo_anio', $anios)
                                ->where('pagado', 0)
                                ->first();
                    }
                    // $fecha_emision = $ultimo_anio_pagar->fecha_emision != null ? Carbon::createFromFormat('Y-m-d H:i:s.u', $ultimo_anio_pagar->fecha_emision) : $fecha_emision;
                }

                $predios = DB::table('predios')->join('zonas', function ($join) {
                    $join->on('predios.id_zona', '=', 'zonas.id');
                })

                // ->leftJoin('predios_prescripciones', 'predios.id', '=', 'predios_prescripciones.id_predio')
                // ->select(DB::raw('predios.*, zonas.descripcion, CASE WHEN COALESCE(predios_prescripciones.prescribe_anio, 0) >= YEAR(GETDATE()) THEN 1 ELSE 0 END AS prescrito, predios_prescripciones.prescribe_anio'))

                ->select(DB::raw('predios.*, zonas.descripcion'))

                ->where('predios.estado', 1)
                ->where('predios.id', $id)
                ->get();

                if ($propietario == '-1') {
                    $propietarios = DB::table('predios')
                                            ->join('predios_propietarios', 'predios.id', '=', 'predios_propietarios.id_predio')
                                            ->join('propietarios', 'propietarios.id', '=', 'predios_propietarios.id_propietario')
                                            ->join('zonas', 'zonas.id', '=', 'predios.id_zona')
                        ->select(DB::raw('predios_propietarios.id_predio, STRING_AGG(TRIM(propietarios.nombre), \'<br />\') AS propietarios, STRING_AGG(propietarios.identificacion, \'<br />\') AS identificaciones'))
                        ->where('predios.estado', 1)
                        ->where('predios.id', $id)
                        ->where('predios_propietarios.jerarquia', '001')
                        ->groupBy('predios_propietarios.id_predio')
                        ->get();
                } else {
                    $propietarios = DB::table('predios')
                                        ->join('predios_propietarios', 'predios.id', '=', 'predios_propietarios.id_predio')
                                        ->join('propietarios', 'propietarios.id', '=', 'predios_propietarios.id_propietario')
                                        ->join('zonas', 'zonas.id', '=', 'predios.id_zona')
                    ->select(DB::raw('predios_propietarios.id_predio, STRING_AGG(TRIM(propietarios.nombre), \'<br />\') AS propietarios, STRING_AGG(propietarios.identificacion, \'<br />\') AS identificaciones'))
                    ->where('predios.estado', 1)
                    ->where('predios.id', $id)
                    ->where('predios_propietarios.id', $propietario)
                    ->groupBy('predios_propietarios.id_predio')
                    ->get();
                }

                if($propietarios) {
                    foreach ($predios as $key => $predio) {
                        $desired_object = self::findInCollection($propietarios, 'id_predio', $predio->id);
                        if($desired_object) {
                            $predio->propietarios = $desired_object->propietarios;
                            $predio->identificaciones = $desired_object->identificaciones;
                        }
                        else {
                            $predio->propietarios = 'Sin asignar';
                            $predio->identificaciones = 'Sin asignar';
                        }
                        // Se muestra un solo propietario
                        break;
                    }
                }
                else {
                    foreach ($predios as $key => $predio) {
                        $predio->propietarios = 'Sin asignar';
                        $predio->identificaciones = 'Sin asignar';
                    }
                }

                $parametro_logo = DB::table('parametros')
                                ->select('parametros.valor')
                                ->where('parametros.nombre', 'logo')
                                ->first();

                $parametro_nit = DB::table('parametros')
                                ->select('parametros.valor')
                                ->where('parametros.nombre', 'nit')
                                ->first();

                $parametro_ean = DB::table('parametros')
                                ->select('parametros.valor')
                                ->where('parametros.nombre', 'ean')
                                ->first();

                $parametro_alcaldia = DB::table('parametros')
                                ->select('parametros.valor')
                                ->where('parametros.nombre', 'alcaldia')
                                ->first();

                $parametro_formato_factura = DB::table('parametros')
                                ->select('parametros.valor')
                                ->where('parametros.nombre', 'formato-factura')
                                ->first();

                $parametro_unir_impuesto_car = DB::table('parametros')
                                ->select('parametros.valor')
                                ->where('parametros.nombre', 'unir-impuesto-car')
                                ->first();

                $logo = $parametro_logo->valor;
                $nit = $parametro_nit->valor;
                $ean = $parametro_ean->valor;
                $alcaldia = $parametro_alcaldia->valor;
                $formato_factura = $parametro_formato_factura->valor;
                $unir_impuesto_car = $parametro_unir_impuesto_car->valor;
                $lista_pagos = new Collection();
                $lista_pagos_depurada = new Collection();
                $ultimo_anio_pagado = new Collection();
                $suma_total = new Collection();
                $suma_intereses = 0;
                $suma_trece = 0;
                $valores_factura = new Collection();
                $fechas_pago_hasta = new Collection();
                $barras = new Collection();
                $barras_texto = new Collection();
                $porcentajes_descuento = new Collection();

                if(!$facturaYaPagada) {
                    // Obtener informacion del ultimo año pagado
                    $ultimo_anio_pagado = DB::table('predios_pagos')
                                        ->where('id_predio', $id)
                                        ->where('pagado', '<>', 0)
                                        ->where('ultimo_anio', '<=', $anios)
                                        ->orderBy('ultimo_anio', 'desc')
                                        ->first();
                }
                else {
                    // Obtener informacion del ultimo año pagado
                    $ultimo_anio_pagado = DB::table('predios_pagos')
                                        ->where('id_predio', $id)
                                        ->where('pagado', '<>', 0)
                                        ->where('ultimo_anio', '<=', $anios)
                                        ->where('factura_pago', '<>', $ultimo_anio_pagar->factura_pago)
                                        ->orderBy('ultimo_anio', 'desc')
                                        ->first();
                }

                //establecer años a pagar
                // if($ultimo_anio_pagado == null || $ultimo_anio_pagado->ultimo_anio + 1 == $anios) {
                //     $predio->anios_a_pagar = $anios;
                // }
                // else {
                if ($anio_ini == $anio_fin) {
                    $predio->anios_a_pagar = $anio_ini;
                } else {
                    if ($ultimo_anio_pagar->factura_pago == null) {
                        $predio->anios_a_pagar = $anio_ini . ' A ' . $anio_fin;
                    } else {
                        $minAnioCalculado = DB::table('predios_pagos')
                                ->select(DB::raw('min(ultimo_anio) as min_anio_calculo'))
                                ->where('predios_pagos.id_predio', $id)
                                ->where('predios_pagos.factura_pago', $ultimo_anio_pagar->factura_pago)
                                ->first();
                        $predio->anios_a_pagar = $minAnioCalculado->min_anio_calculo . ' A ' . $anio_fin;
                    }
                }
                // }

                if ($ultimo_anio_pagado == null) {
                    $obj = new StdClass();
                    $obj->factura_pago = '';
                    $obj->ultimo_anio = '';
                    $obj->fecha_pago = '';
                    $obj->valor_pago = 0;
                    $ultimo_anio_pagado = $obj;
                }
                else {
                    $ultimo_anio_pagado->fecha_pago = $ultimo_anio_pagado->fecha_pago !== null ? Carbon::createFromFormat("Y-m-d", substr($ultimo_anio_pagado->fecha_pago, 0, 10))->format('d/m/Y') : 'N/D';
                }

                if(!$facturaYaPagada) {
                    // Obtener informacion de los pagos pendientes por año
                    if ($ultimo_anio_pagar->factura_pago == null) {
                        $pagos_pendientes = DB::table('predios_pagos')
                                        ->where('id_predio', $id)
                                        ->where('pagado', 0)
                                        ->whereBetween('ultimo_anio', array(
                                            $anio_ini,
                                            $anio_fin
                                        ))
                                        ->where(function($query) use($tmp, $numero_factura, $ultimo_anio_pagar) {
                                            $query->whereNull('factura_pago');
                                        })
                                        ->orderBy('ultimo_anio', 'asc')
                                        ->get();
                    } else {
                        $pagos_pendientes = DB::table('predios_pagos')
                                            ->where('id_predio', $id)
                                            ->where('pagado', 0)
                                            ->whereBetween('ultimo_anio', array(
                                                $anio_ini,
                                                $anio_fin
                                            ))
                                            ->where(function($query) use($tmp, $numero_factura, $ultimo_anio_pagar) {
                                                $query->//whereNull('factura_pago')
                                                    //->orWhere
                                                    where('factura_pago', (intval($tmp) == 0) ? $numero_factura : $ultimo_anio_pagar->factura_pago);
                                            })
                                            ->orderBy('ultimo_anio', 'asc')
                                            ->get();
                    }
                }
                else {
                    // Obtener informacion de los registros que coinciden con la factura
                    $pagos_pendientes = DB::table('predios_pagos')
                                        ->where('id_predio', $id)
                                        ->where('pagado', '<>', 0)
                                        ->where('factura_pago', $ultimo_anio_pagar->factura_pago)
                                        ->orderBy('ultimo_anio', 'asc')
                                        ->get();
                }

                $suma_total[0] = 0;
                $suma_total[1] = 0;
                $suma_total[2] = 0;
                foreach ($pagos_pendientes as $pago_pendiente) {
                    $obj = new StdClass();
                    $obj->anio = $pago_pendiente->ultimo_anio;
                    $obj->m_tar = $pago_pendiente->tarifa == null ? 0 : $pago_pendiente->tarifa * 1000;
                    $obj->avaluo = $pago_pendiente->avaluo == null ? 0 : $pago_pendiente->avaluo;

                    $obj->impuesto = $pago_pendiente->valor_concepto1 == null ? 0 : $pago_pendiente->valor_concepto1;
                    $obj->impuesto_interes = $pago_pendiente->valor_concepto2 == null ? 0 : $pago_pendiente->valor_concepto2;

                    $obj->car = $pago_pendiente->valor_concepto3 == null ? 0 : $pago_pendiente->valor_concepto3;
                    $obj->car_interes = $pago_pendiente->valor_concepto4 == null ? 0 : $pago_pendiente->valor_concepto4;

                    $obj->trece = $pago_pendiente->valor_concepto13 == null ? 0 : $pago_pendiente->valor_concepto13;
                    $obj->catorce = $pago_pendiente->valor_concepto14 == null ? 0 : $pago_pendiente->valor_concepto14;
                    $obj->quince = $pago_pendiente->valor_concepto15 == null ? 0 : $pago_pendiente->valor_concepto15;
                    $obj->dieciseis = $pago_pendiente->valor_concepto16 == null ? 0 : $pago_pendiente->valor_concepto16;
                    $obj->diecisiete = $pago_pendiente->valor_concepto17 == null ? 0 : $pago_pendiente->valor_concepto17;
                    $obj->dieciocho = $pago_pendiente->valor_concepto18 == null ? 0 : $pago_pendiente->valor_concepto18;
                    $obj->blanco = 0;
                    $obj->otros = 0;
                    $obj->total = $pago_pendiente->total_calculo == null ? 0 : $pago_pendiente->total_calculo;

                    $suma_total[0] += $pago_pendiente->ultimo_anio < $anios ? $pago_pendiente->total_calculo : 0; // al ultimo año se le calculan descuentos
                    $suma_total[1] += $pago_pendiente->ultimo_anio < $anios ? $pago_pendiente->total_dos : 0; // al ultimo año se le calculan descuentos
                    $suma_total[2] += $pago_pendiente->ultimo_anio < $anios ? $pago_pendiente->total_tres : 0; // al ultimo año se le calculan descuentos

                    $suma_intereses += $pago_pendiente->ultimo_anio < $anios ? $pago_pendiente->valor_concepto2 : 0; // al ultimo año se le calculan descuentos
                    $suma_trece += $pago_pendiente->ultimo_anio < $anios ? $pago_pendiente->valor_concepto13 : 0; // al ultimo año se le calculan descuentos
                    $lista_pagos->push($obj);
                }

                $inc = 0;
                // Validar primer fecha
                if (
                    $ultimo_anio_pagar->primer_fecha != null &&
                    Carbon::now()->format('Y-m-d') <= Carbon::createFromFormat("Y-m-d", substr($ultimo_anio_pagar->primer_fecha, 0, 10))->format('Y-m-d')
                ) {
                    $valores_factura[$inc] = (round($suma_total[$inc] + $ultimo_anio_pagar->total_calculo, 0));
                    $fechas_pago_hasta[$inc] = (Carbon::createFromFormat('Y-m-d H:i:s.u', $ultimo_anio_pagar->primer_fecha)->toDateString());
                    $porcentajes_descuento[$inc] = ($ultimo_anio_pagar->porcentaje_uno);
                    $inc = $inc + 1;
                } else {
                    $valores_factura[$inc] = (round($suma_total[$inc] + $ultimo_anio_pagar->total_calculo, 0));
                    if ($ultimo_anio_pagar->primer_fecha != null) {
                        $fechas_pago_hasta[$inc] = (Carbon::createFromFormat('Y-m-d H:i:s.u', $ultimo_anio_pagar->primer_fecha)->toDateString());
                    } else {
                        $fechas_pago_hasta[$inc] = (Carbon::now()->toDateString());
                    }
                    $porcentajes_descuento[$inc] = ($ultimo_anio_pagar->porcentaje_uno);
                    $inc = $inc + 1;
                }

                if(count($lista_pagos) == 1 && $ultimo_anio_pagar->primer_fecha != null) {
                    // Validar segunda fecha
                    if (
                        $ultimo_anio_pagar->segunda_fecha != null &&
                        Carbon::now()->format('Y-m-d') <= Carbon::createFromFormat("Y-m-d", substr($ultimo_anio_pagar->segunda_fecha, 0, 10))->format('Y-m-d') &&
                        Carbon::createFromFormat("Y-m-d", substr($ultimo_anio_pagar->primer_fecha, 0, 10))->format('Y-m-d') < Carbon::createFromFormat("Y-m-d", substr($ultimo_anio_pagar->segunda_fecha, 0, 10))->format('Y-m-d')
                    ) {
                        $valores_factura[$inc] = (round($ultimo_anio_pagar->total_dos, 0));
                        $fechas_pago_hasta[$inc] = (Carbon::createFromFormat('Y-m-d H:i:s.u', $ultimo_anio_pagar->segunda_fecha)->toDateString());
                        $porcentajes_descuento[$inc] = ($ultimo_anio_pagar->porcentaje_dos);
                        $inc = $inc + 1;
                    }

                    // Validar tercer fecha
                    if (
                        $ultimo_anio_pagar->tercera_fecha != null &&
                        Carbon::now()->format('Y-m-d') <= Carbon::createFromFormat("Y-m-d", substr($ultimo_anio_pagar->tercera_fecha, 0, 10))->format('Y-m-d') &&
                        Carbon::createFromFormat("Y-m-d", substr($ultimo_anio_pagar->segunda_fecha, 0, 10))->format('Y-m-d') < Carbon::createFromFormat("Y-m-d", substr($ultimo_anio_pagar->tercera_fecha, 0, 10))->format('Y-m-d')
                    ) {
                        $valores_factura[$inc] = (round($ultimo_anio_pagar->total_tres, 0));
                        $fechas_pago_hasta[$inc] = (Carbon::createFromFormat('Y-m-d H:i:s.u', $ultimo_anio_pagar->tercera_fecha)->toDateString());
                        $porcentajes_descuento[$inc] = ($ultimo_anio_pagar->porcentaje_tres);
                    }
                }
                else if(count($lista_pagos) > 1 && $ultimo_anio_pagar->primer_fecha != null) {
                    $offset = str_contains(strtolower($alcaldia), 'de leyva') ? $currentYear - 2009 : 5;
                    if(count($lista_pagos) > $offset && !str_contains(strtolower($alcaldia), 'guateque') && !str_contains(strtolower($alcaldia), 'sutatenza')) {
                        $obj = new StdClass();
                        $obj->anio = '< ' . $lista_pagos[count($lista_pagos) - $offset]->anio;
                        $obj_m_tar = 0;
                        $obj_avaluo = 0;
                        $obj_impuesto = 0;
                        $obj_impuesto_interes = 0;
                        $obj_car = 0;
                        $obj_car_interes = 0;
                        $obj_trece = 0;
                        $obj_catorce = 0;
                        $obj_quince = 0;
                        $obj_dieciseis = 0;
                        $obj_diecisiete = 0;
                        $obj_dieciocho = 0;
                        $obj_blanco = 0;
                        $obj_otros = 0;
                        $obj_total = 0;

                        for ($x = 0; $x < count($lista_pagos) - $offset; $x++) {
                            $obj_m_tar += $lista_pagos[$x]->m_tar == null ? 0 : $lista_pagos[$x]->m_tar;
                            $obj_avaluo += $lista_pagos[$x]->avaluo == null ? 0 : $lista_pagos[$x]->avaluo;
                            $obj_impuesto += $lista_pagos[$x]->impuesto == null ? 0 : $lista_pagos[$x]->impuesto;
                            $obj_impuesto_interes += $lista_pagos[$x]->impuesto_interes == null ? 0 : $lista_pagos[$x]->impuesto_interes;
                            $obj_car += $lista_pagos[$x]->car == null ? 0 : $lista_pagos[$x]->car;
                            $obj_car_interes += $lista_pagos[$x]->car_interes == null ? 0 : $lista_pagos[$x]->car_interes;
                            $obj_trece += $lista_pagos[$x]->trece;
                            $obj_catorce += $lista_pagos[$x]->catorce;
                            $obj_quince += $lista_pagos[$x]->quince;
                            $obj_dieciseis += $lista_pagos[$x]->dieciseis;
                            $obj_diecisiete += $lista_pagos[$x]->diecisiete;
                            $obj_dieciocho += $lista_pagos[$x]->dieciocho;
                            $obj_blanco += $lista_pagos[$x]->blanco;
                            $obj_otros += $lista_pagos[$x]->otros;
                            $obj_total += $lista_pagos[$x]->total;
                        }

                        $obj->m_tar = $obj_m_tar;
                        $obj->avaluo = $obj_avaluo;
                        $obj->impuesto = $obj_impuesto;
                        $obj->impuesto_interes = $obj_impuesto_interes;
                        $obj->car = $obj_car;
                        $obj->car_interes = $obj_car_interes;
                        $obj->trece = $obj_trece;
                        $obj->catorce = $obj_catorce;
                        $obj->quince = $obj_quince;
                        $obj->dieciseis = $obj_dieciseis;
                        $obj->diecisiete = $obj_diecisiete;
                        $obj->dieciocho = $obj_dieciocho;
                        $obj->blanco = $obj_blanco;
                        $obj->otros = $obj_otros;
                        $obj->total = $obj_total;

                        $lista_pagos_depurada->push($obj);
                        for ($x = count($lista_pagos) - $offset; $x < count($lista_pagos); $x++) {
                            $lista_pagos_depurada->push($lista_pagos[$x]);
                        }

                        $lista_pagos = $lista_pagos_depurada;

                    }
                }

                if (count($valores_factura) == 0) {
                    // TODO: Preguntar si esta validacion esta bien.
                    // Cuando la fecha de generacion es mayor a las tres fechas posibles de pago, entonces,
                    // la fecha de pago hasta debe ser el mismo dia, maximo a las 11:59pm
                    $dt_now = Carbon::now();
                    $dt_pago = $dt_now->year($dt->year)->month($dt->month)->day($dt->day)->hour(23)->minute(59)->second(59)->toDateString();
                    $valores_factura[0] = (round($suma_total[0] + $ultimo_anio_pagar->total_calculo, 0));
                    $fechas_pago_hasta[0] = $dt_pago;
                    $porcentajes_descuento[0] = ($ultimo_anio_pagar->porcentaje_uno);
                }

                for ($x = 0; $x < count($valores_factura); $x++) {
                    $barras[$x] = (chr(241) . '415' . $ean . '8020' . str_pad($numero_factura , 24, "0", STR_PAD_LEFT) . chr(241) . '3900' . str_pad($valores_factura[$x], 14, "0", STR_PAD_LEFT) . chr(241) . '96' . str_replace('-', '', $fechas_pago_hasta[$x]));
                    $barras_texto[$x] = ('(415)' . $ean . '(8020)' . str_pad($numero_factura , 24, "0", STR_PAD_LEFT) . '(3900)' . str_pad($valores_factura[$x], 14, "0", STR_PAD_LEFT) . '(96)' . str_replace('-', '', $fechas_pago_hasta[$x]));
                }

                if (str_contains(strtolower($alcaldia), 'paipa') && count($valores_factura) > 1) {
                    // $filter = 0; // primeros dos codigos
                    // $filter = 1; // ultimos dos codigos
                    $filter = 2; // ultimo codigo
                    for ($x = 0; $x < 2; $x++) {
                        if ($x + $filter <= 2) {
                            $barras_filtrado[$x] = $barras[$x + $filter];
                            $barras_texto_filtrado[$x] = $barras_texto[$x + $filter];
                            $fechas_pago_hasta_filtrado[$x] = $fechas_pago_hasta[$x + $filter];
                            $porcentajes_descuento_filtrado[$x] = $porcentajes_descuento[$x + $filter];
                            $valores_factura_filtrado[$x] = $valores_factura[$x + $filter];
                        }
                    }
                } else {
                    $barras_filtrado = $barras;
                    $barras_texto_filtrado = $barras_texto;
                    $fechas_pago_hasta_filtrado = $fechas_pago_hasta;
                    $porcentajes_descuento_filtrado = $porcentajes_descuento;
                    $valores_factura_filtrado = $valores_factura;
                }

                $data = [
                    'title' => 'Predio',
                    'fecha' => $ultimo_anio_pagar->primer_fecha != null ? $fecha_emision->format('d/m/Y') : 'INDEFINIDA',
                    'hora' => $ultimo_anio_pagar->primer_fecha != null ? $fecha_emision->isoFormat('h:mm:ss a') : '',
                    'numero_factura' => $numero_factura,
                    'predio' => $predio,
                    'ultimo_anio_pagado' => $ultimo_anio_pagado,
                    'lista_pagos' => $lista_pagos,
                    'barras' => $barras_filtrado,
                    'barras_texto' => $barras_texto_filtrado,
                    'fechas_pago_hasta' => $fechas_pago_hasta_filtrado,
                    'porcentajes_descuento' => $porcentajes_descuento_filtrado,
                    'valores_factura' => $valores_factura_filtrado,
                    'temporal' => $tmp,
                    'facturaYaPagada' => $facturaYaPagada,
                    'nit' => $nit,
                    'logo' => $logo,
                    'alcaldia' => $alcaldia,
                    'informativa' => $informativa,
                    'unir_impuesto_car' => $unir_impuesto_car,
                    'usuario' => $request->session()->get('username') . ' ' . $request->session()->get('userlastname'),
                ];

                $pdf = PDF::loadView($formato_factura, $data);

                // Actualizar datos pago: valor_pago, numero_factura, fecha emision
                // Guardar informacion solo si se realizo un nuevo calculo
                if(((count($submit) > 0 && $primerCalculo == 1) || ($ultimo_anio_pagar != null && $ultimo_anio_pagar->factura_pago == null)) && intval($tmp) == 0) {
                    foreach ($pagos_pendientes as $pago_pendiente) {
                        $pp = new PredioPago;
                        $pp = PredioPago::find($pago_pendiente->id);
                        $pp->factura_pago = $numero_factura;
                        $pp->fecha_emision = Carbon::createFromFormat("Y-m-d H:i:s", $fecha_emision->toDateTimeString())->format('Y-m-d H:i:s');
                        $pp->save();
                    }
                }
                else if(count($submit) > 0) {
                    foreach ($pagos_pendientes as $pago_pendiente) {
                        $pp = new PredioPago;
                        $pp = PredioPago::find($pago_pendiente->id);
                        $pp->fecha_emision = Carbon::createFromFormat("Y-m-d H:i:s", $fecha_emision->toDateTimeString())->format('Y-m-d H:i:s');
                        $pp->save();
                    }
                }

                DB::commit();

                // Nombre del archivo obtenido a partir de la fecha exacta de solicitud de generación del PDF
                if(intval($tmp) == 0) {
                    return $pdf->download($numero_factura . '_' . $fecha_emision->toDateString() . '_' . str_replace(':', '-', $fecha_emision->toTimeString()) . '.pdf');
                }
                else {
                    return $pdf->download('temporal_' . $fecha_emision->toDateString() . '_' . str_replace(':', '-', $fecha_emision->toTimeString()) . '.pdf');
                }
            }
            else {
                DB::rollback();
                return null;
            }
        }
        catch(\Exception $e) {
            DB::rollback();
            return null;
        }
    }

    public function generate_paz_pdf(Request $request, $id, $destino, $fecha, $valor) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }

        $dt = Carbon::now();
        $currentYear = $dt->year;
        $numero_certificado = 0;

        $predio = DB::table('predios')->join('zonas', function ($join) {
            $join->on('predios.id_zona', '=', 'zonas.id');
        })
        ->select(DB::raw('predios.*, zonas.descripcion AS zonas_descripcion'))
        ->where('predios.estado', 1)
        ->where('predios.id', $id)
        ->first();

        $propietarios = DB::table('predios')
                                ->join('predios_propietarios', 'predios.id', '=', 'predios_propietarios.id_predio')
                                ->join('propietarios', 'propietarios.id', '=', 'predios_propietarios.id_propietario')
            ->select(DB::raw('propietarios.*, predios_propietarios.jerarquia'))
            ->where('predios.estado', 1)
            ->where('predios.id', $id)
            ->get();

        $propietario_ppal = DB::table('predios')
                ->join('predios_propietarios', 'predios.id', '=', 'predios_propietarios.id_predio')
                ->join('propietarios', 'propietarios.id', '=', 'predios_propietarios.id_propietario')
                ->select(DB::raw('propietarios.*, predios_propietarios.jerarquia'))
                ->where('predios_propietarios.jerarquia', '001')
                ->where('predios.estado', 1)
                ->where('predios.id', $id)
                ->first();

        // Obtener informacion del ultimo año pagado
        $ultimo_anio_pagado = DB::table('predios_pagos')
                                ->where('id_predio', $id)
                                ->where('pagado', '<>', 0)
                                ->orderBy('ultimo_anio', 'desc')
                                ->first();

        $anio = Anio::where('anio', $currentYear)
                ->first();

        $numero_certificado = $anio->consecutivo_paz + 1;
        $str_numero_certificado = $currentYear . str_pad($numero_certificado, 5, "0", STR_PAD_LEFT);

        $parametro_logo = DB::table('parametros')
                              ->select('parametros.valor')
                              ->where('parametros.nombre', 'logo')
                              ->first();

        $parametro_alcaldia = DB::table('parametros')
                              ->select('parametros.valor')
                              ->where('parametros.nombre', 'alcaldia')
                              ->first();

        $parametro_formato_paz = DB::table('parametros')
                              ->select('parametros.valor')
                              ->where('parametros.nombre', 'formato-paz')
                              ->first();

        $parametro_nit = DB::table('parametros')
                              ->select('parametros.valor')
                              ->where('parametros.nombre', 'nit')
                              ->first();

        $parametro_codigo_postal = DB::table('parametros')
                              ->select('parametros.valor')
                              ->where('parametros.nombre', 'codigo_postal')
                              ->first();

        $logo = $parametro_logo->valor;
        $alcaldia = $parametro_alcaldia->valor;
        $formato_paz = $parametro_formato_paz->valor;
        $nit = $parametro_nit->valor;
        $codigo_postal = $parametro_codigo_postal != null ? $parametro_codigo_postal->valor : '';

        // Generate QR Code for validation
        $qr_token = Str::random(32) . hash('sha256', $id . $str_numero_certificado . time());
        $validation_url = url('validate-qr/' . $qr_token);

        // QR code is valid until December 31st of current year
        $qr_expiry_date = Carbon::create($currentYear, 12, 31);

        // Create QR validation record
        $qr_validation = QrValidation::create([
            'token' => $qr_token,
            'id_predio' => $id,
            'certificado_numero' => $str_numero_certificado,
            'propietario_principal' => $propietario_ppal->nombre,
            'fecha_expedicion' => $dt->toDateString(),
            'fecha_validez' => $qr_expiry_date->toDateString(),
        ]);

        // Generate QR code as base64 encoded PNG
        $qr_code = QrCodeHelper::generateQrCode($validation_url, 100);

        $data = [
            'title' => 'Paz y salvo',
            'fecha_expedicion' => $dt->translatedFormat('l j \d\e F \d\e Y'),
            'fecha_validez' => $dt->format('d/m/Y'),
            'usuario' => $request->session()->get('username') . ' ' . $request->session()->get('userlastname'),
            'numero_certificado' => $str_numero_certificado,
            'codigo_postal' => $codigo_postal,
            'predio' => $predio,
            'propietario_ppal' => $propietario_ppal,
            'propietarios' => $propietarios,
            'ultimo_anio_pagado' => $ultimo_anio_pagado,
            'destino' => strtoupper($destino),
            'fecha_validez' => $fecha,
            'valor' => $valor,
            'logo' => $logo,
            'alcaldia' => $alcaldia,
            "nit" => $nit,
            'qr_token' => $qr_token,
            'qr_code' => $qr_code
        ];

        $pdf = PDF::loadView($formato_paz, $data);

        $update_anio = new Anio;
        $update_anio = Anio::find($anio->id);
        $update_anio->consecutivo_paz = $numero_certificado;
        $query = $update_anio->save();

        if($query) {
            return $pdf->download($str_numero_certificado . '_' . $dt->toDateString() . '_' . str_replace(':', '-', $dt->toTimeString()) . '.pdf');
        }
        else {
            return null;
        }
    }

    public static function findInCollection(Collection $collection, $key, $value) {
        foreach ($collection as $item) {
            if (isset($item->$key) && $item->$key == $value) {
                return $item;
            }
        }
        return FALSE;
    }

    public function autocomplete(Request $request) {
        $data = $request->all();
        $term = preg_replace('/\s/', '%', preg_replace('/\s\s+/', ' ', trim($data['q'])));

        // $filter_data = DB::table('predios')
        //                     ->leftJoin('predios_propietarios', 'predios.id', '=', 'predios_propietarios.id_predio')
        //                     ->leftJoin('propietarios', 'propietarios.id', '=', 'predios_propietarios.id_propietario')
        //                 ->selectRaw('predios.id AS id, TRIM(predios.direccion) AS text, predios.codigo_predio, predios_propietarios.id_predio, STRING_AGG(CONCAT(TRIM(propietarios.nombre), \' - \', propietarios.identificacion), \'<br />\') AS propietarios')
        //                 ->where('predios.estado', 1)
        //                 ->where(function($query) use($term) {
        //                     $query->whereRaw('LOWER(predios.direccion) LIKE \'%?%\'', [strtolower($term)])
        //                           ->orWhereRaw('LOWER(predios.codigo_predio) LIKE \'%?%\'', [strtolower($term)])
        //                           ->orwhereRaw('LOWER(propietarios.nombre) LIKE \'%?%\'', [strtolower($term)])
        //                           ->orWhereRaw('LOWER(propietarios.identificacion) LIKE \'%?%\'', [strtolower($term)]);
        //                 })
        //                 ->groupBy('predios.id', 'predios.direccion', 'predios.codigo_predio', 'predios_propietarios.id_predio')
        //                 ->get();

        $filter_data = DB::select('select
                                        id,
                                        text,
                                        codigo_predio,
                                        id_predio,
                                        STRING_AGG(propietarios, \'<br />\') AS propietarios,
                                        tiene_pago
                                    from (
                                        select
                                            distinct p.id AS id,
                                            TRIM(p.direccion) AS text,
                                            p.codigo_predio,
                                            ppr.id_predio,
                                            CONCAT(\'(\', pro3.jerarquia, \') \', TRIM(pro3.nombre), \' - \', pro3.identificacion) AS propietarios,
                                            ISNULL(ppa.id_predio, 0) AS tiene_pago
                                        from predios p left join
                                            predios_propietarios ppr
                                            on p.id = ppr.id_predio left join
                                            predios_pagos ppa
                                            on p.id = ppa.id_predio left join
                                            propietarios pro
                                            on (pro.id = ppr.id_propietario) left join
                                            (select
                                                p2.id, pro2.nombre, pro2.identificacion, ppr2.jerarquia
                                            from
                                                predios p2 left join
                                                predios_propietarios ppr2
                                                on (p2.id = ppr2.id_predio) left join
                                                propietarios pro2
                                                on (pro2.id = ppr2.id_propietario and ppr2.jerarquia = \'001\')
                                            where pro2.id IS NOT NULL
                                            ) pro3
                                            on (pro3.id = p.id)
                                        where p.estado = 1 and
                                            (LOWER(p.direccion) LIKE \'%'.$term.'%\' or
                                            LOWER(p.codigo_predio) LIKE \'%'.$term.'%\' or
                                            LOWER(pro.nombre) LIKE \'%'.$term.'%\' or
                                            LOWER(pro.identificacion) LIKE \'%'.$term.'%\')
                                    ) a
                                    group by
                                        id,
                                        text,
                                        codigo_predio,
                                        id_predio,
                                        tiene_pago
                                    order by text, codigo_predio');

        $result = array("items" => $filter_data, "total_count" => count($filter_data));

        return response()->json($result);
    }

    public function autocomplete_check(Request $request) {
        $data = $request->all();
        $term = preg_replace('/\s/', '%', preg_replace('/\s\s+/', ' ', trim($data['q'])));

        $filter_data = DB::select('select
                                        id,
                                        text,
                                        codigo_predio,
                                        id_predio,
                                        STRING_AGG(propietarios, \'<br />\') AS propietarios,
                                        tiene_pago
                                    from (
                                        select
                                            distinct p.id AS id,
                                            TRIM(p.direccion) AS text,
                                            p.codigo_predio,
                                            ppr.id_predio,
                                            CONCAT(TRIM(pro.nombre), \' - \', pro.identificacion) AS propietarios,
                                            ISNULL(ppa.id_predio, 0) AS tiene_pago
                                        from predios p left join
                                            predios_propietarios ppr
                                            on p.id = ppr.id_predio left join
                                            propietarios pro
                                            on (pro.id = ppr.id_propietario and ppr.jerarquia = \'001\') left join
                                            predios_pagos ppa
                                            on p.id = ppa.id_predio
                                        where p.estado = 1 and
                                              LOWER(p.codigo_predio) LIKE \'%'.$term.'%\'
                                    ) a
                                    group by
                                        id,
                                        text,
                                        codigo_predio,
                                        id_predio,
                                        tiene_pago
                                    order by codigo_predio');

        $result = array("items" => $filter_data, "total_count" => count($filter_data));

        return response()->json($result);
    }

    public function get_predio(Request $request) {
        $data = json_decode($request->form);
        $dt = Carbon::now();
        $currentYear = $dt->year;

        $predio =  DB::table('predios')->join('zonas', function ($join) {
                        $join->on('predios.id_zona', '=', 'zonas.id');
                    })
                    ->select(DB::raw('predios.*, zonas.descripcion'))
                    ->where('predios.estado', 1)
                    ->where('predios.id', $data->{'id_predio'})
                    ->first();

        if (!property_exists($data, 'notas') && !property_exists($data, 'acuerdos')) {
            // seleccionar minimo ultimo_año predios pagos
            // maximo año seria el actual
            // verificar si hay años intermedios que no tengan calculo
            // si hay años intermedios, ejecutar calculo, sino no ejecutar
            if (property_exists($data, 'calcular')) {
                $minAnioCalculado = DB::table('predios_pagos')
                                    ->select(DB::raw('min(ultimo_anio) as min_anio_calculo'))
                                    ->where('predios_pagos.id_predio', $data->{'id_predio'})
                                    // ->where('prescrito', 0)
                                    // ->where('exencion', 0)
                                    ->first();

                $listaAnios = [];
                for($i = intval($minAnioCalculado->min_anio_calculo); $i <= $currentYear; $i++) {
                    array_push($listaAnios, $i); // implode(",", $listaAnios)
                }

                $distinct_calcs = DB::table('predios_pagos')
                    ->select(DB::raw('distinct ultimo_anio as ultimo_anio'))
                    ->where('predios_pagos.id_predio', $data->{'id_predio'})
                    // ->where('prescrito', 0)
                    // ->where('exencion', 0)
                    ->get();

                // Solo si hay años intermedios sin calculo, se ejecutaria el calculo para poder
                // tener la lista de años con deuda
                if (count($distinct_calcs) < count($listaAnios)) {
                    DB::select("SET NOCOUNT ON; EXEC SP_CALCULO_PREDIAL ?,?,?,?,?", array(
                        intval($request->session()->get('userid')),
                        $currentYear,
                        0, // vista previa
                        $data->{'id_predio'},
                        Carbon::createFromFormat("Y-m-d H:i:s", $dt->format('Y-m-d') . ' 00:00:00')->format('Y-m-d H:i:s')
                    ));
                }
            }

            $lista_propietarios = DB::table('predios')
                        ->join('predios_propietarios', 'predios.id', '=', 'predios_propietarios.id_predio')
                        ->join('propietarios', 'propietarios.id', '=', 'predios_propietarios.id_propietario')
                        ->join('zonas', 'zonas.id', '=', 'predios.id_zona')
                ->select(DB::raw('predios_propietarios.id, predios_propietarios.jerarquia, TRIM(propietarios.nombre) as nombre'))
                ->where('predios.estado', 1)
                ->where('predios_propietarios.id_predio', $data->{'id_predio'})
                ->orderBy('predios_propietarios.jerarquia')
                ->get();

            $propietario_ppal = DB::table('predios')
                                        ->join('predios_propietarios', 'predios.id', '=', 'predios_propietarios.id_predio')
                                        ->join('propietarios', 'propietarios.id', '=', 'predios_propietarios.id_propietario')
                                        ->join('zonas', 'zonas.id', '=', 'predios.id_zona')
                    ->select(DB::raw('predios_propietarios.id_predio, STRING_AGG(CONCAT(\'(\',predios_propietarios.jerarquia, \') \', TRIM(propietarios.nombre), \' - \', propietarios.identificacion), \'<br />\') AS propietarios'))
                    ->where('predios.estado', 1)
                    ->where('predios_propietarios.jerarquia', '001')
                    ->where('predios_propietarios.id_predio', $data->{'id_predio'})
                    ->groupBy('predios_propietarios.id_predio')
                    ->get();

            if($propietario_ppal) {
                // foreach ($predios as $key => $predio) {
                $desired_object = self::findInCollection($propietario_ppal, 'id_predio', $predio->id);
                if($desired_object) {
                    $predio->propietarios = $desired_object->propietarios;
                }
                else {
                    $predio->propietarios = 'Sin asignar';
                }
                // }
            }
            else {
                // foreach ($predios as $key => $predio) {
                $predio->propietarios = 'Sin asignar';
                // }
            }

            $acuerdo_pago = PredioAcuerdoPago::where('id_predio', $data->{'id_predio'})
                            ->where('estado_acuerdo', 1) // 1 activo, 0 anulado
                            // ->where('anulado_acuerdo', 0)
                            ->first();

            $propietario = DB::table('predios')
                                ->join('predios_propietarios', 'predios.id', '=', 'predios_propietarios.id_predio')
                                ->join('propietarios', 'propietarios.id', '=', 'predios_propietarios.id_propietario')
                            ->select(DB::raw('propietarios.*'))
                            ->where('predios.estado', 1)
                            ->where('predios_propietarios.jerarquia', '001')
                            ->where('predios_propietarios.id_predio', $data->{'id_predio'})
                            ->first();

            // Para cada factura extraer el maximo año no pagado (Solo para registros que si tienen numero de factura)
            // Esto debido a que aveces con un mismo numero de factura se liquidan varios años.
            $anios_con_factura = DB::table('predios_pagos')
                    ->where('id_predio', $data->{'id_predio'})
                    ->where('pagado', 0)
                    ->where('anulada', 0)
                    // ->where('prescrito', 0)
                    // ->where('exencion', 0)
                    ->whereNotNull('factura_pago')
                    ->select(DB::raw('MAX(predios_pagos.ultimo_anio) AS ultimo_anio, predios_pagos.factura_pago, predios_pagos.fecha_emision'))
                    ->groupBy('predios_pagos.factura_pago', 'predios_pagos.fecha_emision')
                    ->orderBy('ultimo_anio', 'desc')
                    ->get();

            // if(count($anios) == 0) {
            // Todos los años no pagados y que no tienen numero de factura
            $anios_sin_factura = DB::table('predios_pagos')
                ->where('id_predio', $data->{'id_predio'})
                ->where('pagado', 0)
                ->where('anulada', 0)
                // ->where('prescrito', 0)
                // ->where('exencion', 0)
                ->whereNull('factura_pago')
                ->select(DB::raw('predios_pagos.ultimo_anio, predios_pagos.factura_pago, ISNULL(predios_pagos.total_calculo, 0) AS total_calculo, \'-\' AS fecha_emision'))
                ->orderBy('ultimo_anio', 'desc')
                ->get();
            // }

            $anios = [];
            $array_anios = [];

            foreach ($anios_con_factura as $anio) {
                $lista_anios = [];
                $anios_factura = DB::table('predios_pagos')
                    ->where('id_predio', $data->{'id_predio'})
                    ->where('pagado', 0)
                    ->where('anulada', 0)
                    ->where('factura_pago', $anio->factura_pago)
                    ->select('predios_pagos.ultimo_anio')
                    ->orderBy('ultimo_anio', 'desc')
                    ->get();
                if (count($anios_factura) > 1) {
                    foreach ($anios_factura as $anio_factura) {
                        array_push($lista_anios, $anio_factura->ultimo_anio);
                    }
                    $anio->lista_anios = $lista_anios;
                } else {
                    $anio->lista_anios = $lista_anios;
                }
                array_push($anios, $anio);
            }
            foreach ($anios_sin_factura as $anio) {
                array_push($anios, $anio);
            }

            rsort($anios);
            foreach ($anios as $anio) {
                array_push($array_anios, $anio);
            }

            $exists_current_anio = false;
            foreach ($array_anios as $anio) {
                if ($anio->ultimo_anio == $currentYear) {
                    $exists_current_anio = true;
                }
            }

            // Verificar si el año actual ya tiene un calculo con o sin pago
            $ultimo_anio_pagar = DB::table('predios_pagos')
                                ->where('id_predio', $data->{'id_predio'})
                                ->where('ultimo_anio', $currentYear)
                                ->where('anulada', 0)
                                // ->where('prescrito', 0)
                                // ->where('exencion', 0)
                                ->first();

            if ($ultimo_anio_pagar->exencion != 0) {
                $exencion = DB::table('predios_exenciones')
                    ->where('id_predio', $data->{'id_predio'})
                    ->where('exencion_anio', $currentYear)
                    ->first();

                if ($exencion != null) {
                    $ultimo_anio_pagar->exencion_porcentaje = $exencion->porcentaje;
                }
            }

            // Si no existe un calculo para el año actual o si el calculo existe pero aun no tiene un numero
            // de factura asignado, entonces, se agrega el año a la lista
            if($ultimo_anio_pagar->ultimo_anio != $currentYear && !$exists_current_anio && count($array_anios) > 0) {
                array_unshift($array_anios, ['ultimo_anio' => strval($currentYear), 'factura_pago' => null, 'total_calculo' => 0]);
            }

            // Verificar prescripcion
            $anios_prescripcion = [];
            $anios_exencion = [];
            $count_no_pagos = PredioPago::where('id_predio', $data->{'id_predio'})
                ->where('pagado', 0)
                ->where('anulada', 0)
                ->where('prescrito', 0)
                ->where('exencion', 0)
                ->count();
            if($count_no_pagos > 0) {
                $anios_prescripcion = DB::select('
                    select
                        pp.ultimo_anio
                    from
                        predios_pagos pp
                    where
                        pp.id_predio = '. $data->{'id_predio'} .' and
                        pp.pagado = 0 and
                        pp.anulada = 0 and
                        pp.prescrito = 0 and
                        pp.exencion = 0
                    order by pp.ultimo_anio'
                );

                $anios_exencion = DB::select('
                    select
                        pp.ultimo_anio
                    from
                        predios_pagos pp
                    where
                        pp.id_predio = '. $data->{'id_predio'} .' and
                        pp.pagado = 0 and
                        pp.anulada = 0 and
                        pp.prescrito = 0 and
                        pp.exencion = 0
                    order by pp.ultimo_anio'
                );
            }

            return response()->json(
                [
                    'predio' => $predio,
                    'acuerdo_pago' => $acuerdo_pago,
                    'propietario' => $propietario,
                    'propietarios' => $lista_propietarios,
                    'anios' => $array_anios,
                    'anio_actual' => $currentYear,
                    'ultimo_anio' => $ultimo_anio_pagar,
                    'anios_prescripcion' => $anios_prescripcion,
                    'anios_exencion' => $anios_exencion
                ]
            );
        } else if (property_exists($data, 'notas')) {
            $facturas_pendientes = [];
            $count_facturas_no_pagadas = PredioPago::where('id_predio', $data->{'id_predio'})
                ->where('pagado', 0)
                ->where('anulada', 0)
                ->where('prescrito', 0)
                ->where('exencion', 0)
                ->whereNotNull('factura_pago')
                ->count();

            if($count_facturas_no_pagadas > 0) {
                $facturas_pendientes = DB::table('predios_pagos')
                    ->where('id_predio', $data->{'id_predio'})
                    ->where('pagado', 0)
                    ->where('anulada', 0)
                    ->where('prescrito', 0)
                    ->where('exencion', 0)
                    ->whereNotNull('factura_pago')
                    ->select(DB::raw('predios_pagos.factura_pago AS factura_pago'))
                    ->groupBy('predios_pagos.factura_pago')
                    ->get();
            }

            return response()->json(
                [
                    'predio' => $predio,
                    'facturas_pendientes' => $facturas_pendientes
                ]
            );
        } else if (property_exists($data, 'acuerdos')) {
            $anios = [];
            $array_anios = [];

            $acuerdo_pago = PredioAcuerdoPago::where('id_predio', $data->{'id_predio'})
                ->where('estado_acuerdo', 1) // 1 activo, 0 anulado
                // ->where('anulado_acuerdo', 0)
                ->first();

            // Para cada factura extraer el maximo año no pagado (Solo para registros que si tienen numero de factura)
            // Esto debido a que aveces con un mismo numero de factura se liquidan varios años.
            $anios_con_factura = DB::table('predios_pagos')
                    ->where('id_predio', $data->{'id_predio'})
                    ->where('pagado', 0)
                    ->where('anulada', 0)
                    // ->where('prescrito', 0)
                    // ->where('exencion', 0)
                    // ->whereNotNull('factura_pago')
                    // ->select(DB::raw('MAX(predios_pagos.ultimo_anio) AS ultimo_anio, predios_pagos.factura_pago'))
                    // ->groupBy('predios_pagos.factura_pago')
                    ->select(DB::raw('predios_pagos.ultimo_anio, predios_pagos.factura_pago, ISNULL(predios_pagos.total_calculo, 0) AS total_calculo'))
                    ->orderBy('ultimo_anio', 'desc')
                    ->get();

            // // if(count($anios) == 0) {
            // // Todos los años no pagados y que no tienen numero de factura
            // $anios_sin_factura = DB::table('predios_pagos')
            //     ->where('id_predio', $data->{'id_predio'})
            //     ->where('pagado', 0)
            //     ->where('anulada', 0)
            //     // ->where('prescrito', 0)
            //     // ->where('exencion', 0)
            //     ->whereNull('factura_pago')
            //     ->select(DB::raw('predios_pagos.ultimo_anio, predios_pagos.factura_pago, ISNULL(predios_pagos.total_calculo, 0) AS total_calculo'))
            //     ->orderBy('ultimo_anio', 'desc')
            //     ->get();
            // // }

            foreach ($anios_con_factura as $anio) {
                // $lista_anios = [];
                // $anios_factura = DB::table('predios_pagos')
                //     ->where('id_predio', $data->{'id_predio'})
                //     ->where('pagado', 0)
                //     ->where('anulada', 0)
                //     // ->where('factura_pago', $anio->factura_pago)
                //     ->select(DB::raw('predios_pagos.ultimo_anio, ISNULL(predios_pagos.total_calculo, 0) AS total_calculo'))
                //     ->orderBy('ultimo_anio', 'desc')
                //     ->get();
                // if (count($anios_factura) > 1) {
                //     $total = 0;
                //     foreach ($anios_factura as $anio_factura) {
                //         array_push($lista_anios, $anio_factura->ultimo_anio);
                //         $total += $anio_factura->total_calculo;
                //     }
                //     $anio->lista_anios = $lista_anios;
                //     $anio->total_calculo = $total;
                // } else {
                //     $anio->lista_anios = $lista_anios;
                //     $anio->total_calculo = $anios_factura[0]->total_calculo;
                // }
                array_push($anios, $anio);
            }

            // foreach ($anios_sin_factura as $anio) {
            //     array_push($anios, $anio);
            // }

            rsort($anios);
            foreach ($anios as $anio) {
                array_push($array_anios, $anio);
            }

            $exists_current_anio = false;
            foreach ($array_anios as $anio) {
                if ($anio->ultimo_anio == $currentYear) {
                    $exists_current_anio = true;
                }
            }

            // Verificar si el año actual ya tiene un calculo con o sin pago
            $ultimo_anio_pagar = DB::table('predios_pagos')
                ->where('id_predio', $data->{'id_predio'})
                ->where('ultimo_anio', $currentYear)
                ->where('anulada', 0)
                // ->where('prescrito', 0)
                // ->where('exencion', 0)
                ->first();

            // Si no existe un calculo para el año actual o si el calculo existe pero aun no tiene un numero
            // de factura asignado, entonces, se agrega el año a la lista
            if ($ultimo_anio_pagar) {
                if($ultimo_anio_pagar->ultimo_anio != $currentYear && !$exists_current_anio && count($array_anios) > 0) {
                    array_unshift($array_anios, ['ultimo_anio' => strval($currentYear), 'factura_pago' => null, 'total_calculo' => 0]);
                }
            }

            return response()->json(
                [
                    'predio' => $predio,
                    'acuerdo_pago' => $acuerdo_pago,
                    'anios' => $array_anios,
                    'anio_actual' => $currentYear
                ]
            );
        }
    }

    public function avaluos_predio(Request $request) {
        $data = json_decode($request->form);
        $predio = DB::select('select distinct p.id,
                                    pp.ultimo_anio as anio,
                                    pp.avaluo,
                                    CASE WHEN pa.origen = \'M\' THEN \'Manual\' ELSE \'Archivo\' END as tipo_registro,
                                    p.tarifa_actual as tarifa,
                                    pp.tarifa as porcentaje_tarifa,
                                    CONCAT(b.asobancaria, \' - \' , b.nombre) as banco,
                                    ISNULL(CONVERT(VARCHAR, pp.fecha_pago, 101), \'N/D\') as fecha_pago,
                                    pp.valor_pago,
                                    ISNULL(pp.factura_pago, \'N/D\') as factura_pago,
                                    pp.valor_pago AS valor_vigencia,
                                    CASE WHEN pp.prescrito != 0 THEN \'SI\' ELSE \'NO\' END as prescrito,
                                    CASE WHEN pp.exencion != 0 THEN \'SI\' ELSE \'NO\' END as exencion
                            from predios p inner join
                                predios_pagos pp
                                on (p.id = pp.id_predio) left join
                                bancos b
                                on (pp.id_banco = b.id) left join
                                pagos pa
                                on (p.id = pa.id_predio and pp.factura_pago = pa.numero_recibo)
                            where
                                p.id = '. $data->{'id_predio'} .' and
                                pp.pagado <> 0 and
                                pp.anulada = 0
                            order by pp.ultimo_anio');

        $result = array("predio" => $predio);
        return response()->json($result);
    }

    public function generate_avaluos_predio_pdf(Request $request, $id) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }

        $dt = Carbon::now();

        $predio =  DB::table('predios')
                    ->where('predios.estado', 1)
                    ->where('predios.id', $id)
                    ->first();

        $avaluos = DB::select('select distinct p.id,
                                    pp.ultimo_anio as anio,
                                    pp.avaluo,
                                    CASE WHEN pa.origen = \'M\' THEN \'Manual\' ELSE \'Archivo\' END as tipo_registro,
                                    p.tarifa_actual as tarifa,
                                    pp.tarifa as porcentaje_tarifa,
                                    CONCAT(b.asobancaria, \' - \' , b.nombre) as banco,
                                    ISNULL(CONVERT(VARCHAR, pp.fecha_pago, 101), \'N/D\') as fecha_pago,
                                    pp.valor_pago,
                                    ISNULL(pp.factura_pago, \'N/D\') as factura_pago,
                                    CASE
                                        WHEN pa.fecha_pago <= pp.primer_fecha THEN
                                            ISNULL(pp.total_calculo, 0)
                                        WHEN pa.fecha_pago <= pp.segunda_fecha THEN
                                            ISNULL(pp.total_dos, 0)
                                        WHEN pa.fecha_pago <= pp.tercera_fecha THEN
                                            ISNULL(pp.total_tres, 0)
                                        ELSE
                                            0
                                    END AS valor_vigencia,
                                    CASE WHEN pp.prescrito != 0 THEN \'SI\' ELSE \'NO\' END as prescrito,
                                    CASE WHEN pp.exencion != 0 THEN \'SI\' ELSE \'NO\' END as exencion
                            from predios p inner join
                                predios_pagos pp
                                on (p.id = pp.id_predio) left join
                                bancos b
                                on (pp.id_banco = b.id) left join
                                pagos pa
                                on (p.id = pa.id_predio and pp.factura_pago = pa.numero_recibo)
                            where
                                p.id = '. $id .' and
                                pp.pagado <> 0 and
                                pp.anulada = 0
                            order by pp.ultimo_anio');

        $propietario_ppal = DB::table('predios')
                            ->join('predios_propietarios', 'predios.id', '=', 'predios_propietarios.id_predio')
                            ->join('propietarios', 'propietarios.id', '=', 'predios_propietarios.id_propietario')
                            ->select(DB::raw('propietarios.*, predios_propietarios.jerarquia'))
                            ->where('predios_propietarios.jerarquia', '001')
                            ->where('predios.estado', 1)
                            ->where('predios.id', $id)
                            ->first();

        $parametro_logo = DB::table('parametros')
                              ->select('parametros.valor')
                              ->where('parametros.nombre', 'logo')
                              ->first();

        $parametro_nit = DB::table('parametros')
                              ->select('parametros.valor')
                              ->where('parametros.nombre', 'nit')
                              ->first();

        $parametro_alcaldia = DB::table('parametros')
                              ->select('parametros.valor')
                              ->where('parametros.nombre', 'alcaldia')
                              ->first();


        $logo = $parametro_logo->valor;
        $nit = $parametro_nit->valor;
        $alcaldia = $parametro_alcaldia->valor;

        $data = [
            'title' => 'Avaluos',
            'usuario' => $request->session()->get('useremail'),
            'predio' => $predio,
            'avaluos' => $avaluos,
            'propietario_ppal' => $propietario_ppal,
            'logo' => $logo,
            'nit' => $nit,
            'alcaldia' => $alcaldia
        ];

        $pdf = PDF::loadView('predios.avaluosPDF', $data);

        return $pdf->download($dt->toDateString() . '_' . str_replace(':', '-', $dt->toTimeString()) . '.pdf');
    }

    public function estado_cuenta_predio(Request $request) {
        $data = json_decode($request->form);
        $predio = DB::select('select pp.id_predio as id,
                                    pp.ultimo_anio as vigencia,
                                    ISNULL(pp.avaluo, 0) as avaluo,
                                    ISNULL(pp.valor_concepto1, 0) as impuesto,
                                    ISNULL(pp.valor_concepto2, 0) as interes_impuesto,
                                    ISNULL(pp.valor_concepto3, 0) as car,
                                    ISNULL(pp.valor_concepto4, 0) as interes_car,
                                    ISNULL(pp.valor_concepto13, 0) + ISNULL(pp.valor_concepto15, 0) as descuento,
                                    ISNULL(pp.valor_concepto16, 0) as otros,
                                    ISNULL(pp.total_calculo, 0) as total,
                                    CASE WHEN ISNULL(pp.acuerdo, 0) != 0 THEN \'SI\' ELSE \'NO\' END as acuerdo
                            from predios_pagos pp
                            where
                                pp.id_predio = '. $data->{'id_predio'} .' and
                                pp.pagado = 0 and
                                pp.anulada = 0
                            order by pp.ultimo_anio');

        $result = array("predio" => $predio);
        return response()->json($result);
    }

    public function generate_estado_cuenta_predio_pdf(Request $request, $id) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }

        $dt = Carbon::now();

        $predio =  DB::table('predios')
                    ->where('predios.estado', 1)
                    ->where('predios.id', $id)
                    ->first();

        $predio_pago =  DB::table('predios_pagos')
                    ->where('pagado', '<>', 0)
                    ->where('anulada', 0)
                    ->where('id_predio', $id)
                    ->orderBy('ultimo_anio', 'desc')
                    ->first();

        $pendientes = DB::select('select pp.id_predio as id,
                                    pp.ultimo_anio as vigencia,
                                    ISNULL(pp.avaluo, 0) as avaluo,
                                    ISNULL(pp.valor_concepto1, 0) as impuesto,
                                    ISNULL(pp.valor_concepto2, 0) as interes_impuesto,
                                    ISNULL(pp.valor_concepto3, 0) as car,
                                    ISNULL(pp.valor_concepto4, 0) as interes_car,
                                    ISNULL(pp.valor_concepto13, 0) + ISNULL(pp.valor_concepto15, 0) as descuento,
                                    ISNULL(pp.valor_concepto16, 0) as otros,
                                    ISNULL(pp.total_calculo, 0) as total,
                                    CASE WHEN ISNULL(pp.acuerdo, 0) != 0 THEN \'SI\' ELSE \'NO\' END as acuerdo
                            from predios_pagos pp
                            where
                                pp.id_predio = '. $id .' and
                                pp.pagado = 0 and
                                pp.anulada = 0
                            order by pp.ultimo_anio');

        $propietario_ppal = DB::table('predios')
                            ->join('predios_propietarios', 'predios.id', '=', 'predios_propietarios.id_predio')
                            ->join('propietarios', 'propietarios.id', '=', 'predios_propietarios.id_propietario')
                            ->select(DB::raw('propietarios.*, predios_propietarios.jerarquia'))
                            ->where('predios_propietarios.jerarquia', '001')
                            ->where('predios.estado', 1)
                            ->where('predios.id', $id)
                            ->first();

        $parametro_logo = DB::table('parametros')
                              ->select('parametros.valor')
                              ->where('parametros.nombre', 'logo')
                              ->first();

        $parametro_nit = DB::table('parametros')
                              ->select('parametros.valor')
                              ->where('parametros.nombre', 'nit')
                              ->first();

        $parametro_alcaldia = DB::table('parametros')
                              ->select('parametros.valor')
                              ->where('parametros.nombre', 'alcaldia')
                              ->first();


        $logo = $parametro_logo->valor;
        $nit = $parametro_nit->valor;
        $alcaldia = $parametro_alcaldia->valor;

        $data = [
            'title' => 'Estado cuenta',
            'usuario' => $request->session()->get('useremail'),
            'predio' => $predio,
            'predio_pago' => $predio_pago,
            'pendientes' => $pendientes,
            'propietario_ppal' => $propietario_ppal,
            'logo' => $logo,
            'nit' => $nit,
            'alcaldia' => $alcaldia,
            'fecha' => Carbon::now()->format('d/m/Y')
        ];

        $pdf = PDF::loadView('predios.estadoCuentaPDF', $data);

        return $pdf->download('estado_cuenta_' . $dt->toDateString() . '_' . str_replace(':', '-', $dt->toTimeString()) . '.pdf');
    }

    public function get_propietario_by_identificacion(Request $request) {
        $data = json_decode($request->form);

        $propietario = DB::table('propietarios')
                        ->select('propietarios.*')
                        ->where('propietarios.identificacion', $data->{'identificacion'})
                        ->first();

        return response()->json(['propietario' => $propietario]);
    }

    public function get_predios_no_calculados(Request $request) {
        $data = json_decode($request->form);
        if (!property_exists($data, 'id_predio_inicial')) {
            $predios = DB::table('predios')
                            ->leftJoin('predios_propietarios', function ($join) {
                                $join->on('predios.id', '=', 'predios_propietarios.id_predio')
                                    ->where('predios_propietarios.jerarquia', '=', '001');
                            })
                            ->leftJoin('propietarios', 'propietarios.id', '=', 'predios_propietarios.id_propietario')
                            ->select(DB::raw('predios.id, predios.codigo_predio, TRIM(predios.direccion) AS direccion, predios_propietarios.jerarquia'))
                            ->where('predios.estado', 1)
                            ->whereIn('predios.ind_excento', [0])
                            ->whereNotIn('predios.id', function($query) {
                                $query->select(DB::raw('distinct id_predio'))
                                    ->from('predios_pagos')
                                    ->where('ultimo_anio', Carbon::now()->year)
                                    ->where('anulada', 0)
                                    ->where('pagado', '<>', 0);
                            })
                            ->orderBy('predios.codigo_predio')
                            ->get();
        } else {
            $predio = Predio::where('id', $data->{'id_predio_inicial'})->first();

            $predios = DB::table('predios')
                            ->leftJoin('predios_propietarios', function ($join) {
                                $join->on('predios.id', '=', 'predios_propietarios.id_predio')
                                    ->where('predios_propietarios.jerarquia', '=', '001');
                            })
                            ->leftJoin('propietarios', 'propietarios.id', '=', 'predios_propietarios.id_propietario')
                            ->select(DB::raw('predios.id, predios.codigo_predio, TRIM(predios.direccion) AS direccion, predios_propietarios.jerarquia'))
                            ->whereIn('predios.ind_excento', [0])
                            ->where('predios.estado', 1)
                            ->where('predios.codigo_predio', '>', $predio->codigo_predio)
                            ->whereNotIn('predios.id', function($query) use($predio) {
                                $query->select(DB::raw('distinct id_predio'))
                                    ->from('predios_pagos')
                                    ->join('predios', 'predios.id', '=', 'predios_pagos.id_predio')
                                    ->where('ultimo_anio', Carbon::now()->year)
                                    ->where('anulada', 0)
                                    ->where('pagado', '<>', 0)
                                    ->whereIn('predios.ind_excento', [0])
                                    ->where('predios.codigo_predio', '>', $predio->codigo_predio);
                            })
                            ->orderBy('predios.codigo_predio')
                            ->get();
        }

        return response()->json(['predios' => $predios]);
    }

    public function ejecutar_calculo_batch(Request $request) {
        $data = json_decode($request->form);
        $predio_inicial = Predio::where('id', $data->{'id_predio_inicial'})->first();
        $predio_final = Predio::where('id', $data->{'id_predio_final'})->first();

        CalculoBatch::dispatch(
            $data->{'anio'},
            $predio_inicial->id,
            $predio_final->id,
            $request->session()->get('userid')
        );

        $batchs = null;
        while(!$batchs) {
            $batchs = DB::table('batchs')
                        ->where('batchs.estado', 1)
                        ->first();
        }

        return response()->json(['data' => $batchs]);
    }

    public function exportCartera(Request $request){
        return Excel::download(new ExportCartera, 'predios_pagos.xlsx', \Maatwebsite\Excel\Excel::XLSX);

        // $parametro_logo = DB::table('parametros')
        //                       ->select('parametros.valor')
        //                       ->where('parametros.nombre', 'logo')
        //                       ->first();
        // $parametro_nit = DB::table('parametros')
        //                       ->select('parametros.valor')
        //                       ->where('parametros.nombre', 'nit')
        //                       ->first();

        // $parametro_alcaldia = DB::table('parametros')
        //                       ->select('parametros.valor')
        //                       ->where('parametros.nombre', 'alcaldia')
        //                       ->first();
        // return (new ExportCartera($parametro_logo->valor, $parametro_nit->valor, $parametro_alcaldia->valor))->download('predios_pagos.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    public function generate_exenciones_pdf(Request $request, $fecha_minima, $fecha_maxima) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }

        $dt = Carbon::now();

        $exenciones = DB::table('predios_exenciones')
                        ->leftJoin('predios', 'predios.id', '=', 'predios_exenciones.id_predio')
                        ->select(DB::raw('predios_exenciones.*, predios.codigo_predio'))
                        ->where('predios.estado', 1)
                        ->whereRaw(DB::raw("predios_exenciones.created_at >= CONVERT(datetime, '" . $fecha_minima . " 00:00:00.000')"))
                        ->whereRaw(DB::raw("predios_exenciones.created_at <= CONVERT(datetime, '" . $fecha_maxima . " 23:59:59.999')"))
                        ->orderBy('predios_exenciones.created_at')
                        ->orderBy('predios.codigo_predio')
                        ->get();

        $parametro_logo = DB::table('parametros')
                              ->select('parametros.valor')
                              ->where('parametros.nombre', 'logo')
                              ->first();

        $parametro_nit = DB::table('parametros')
                              ->select('parametros.valor')
                              ->where('parametros.nombre', 'nit')
                              ->first();

        $parametro_alcaldia = DB::table('parametros')
                              ->select('parametros.valor')
                              ->where('parametros.nombre', 'alcaldia')
                              ->first();


        $logo = $parametro_logo->valor;
        $nit = $parametro_nit->valor;
        $alcaldia = $parametro_alcaldia->valor;

        $data = [
            'title' => 'Exenciones',
            'usuario' => $request->session()->get('useremail'),
            'exenciones' => $exenciones,
            'logo' => $logo,
            'nit' => $nit,
            'alcaldia' => $alcaldia
        ];

        $pdf = PDF::loadView('predios.exencionesPDF', $data);

        return $pdf->download($dt->toDateString() . '_' . str_replace(':', '-', $dt->toTimeString()) . '.pdf');
    }

    public function generate_prescripciones_pdf(Request $request, $fecha_minima, $fecha_maxima) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }

        $dt = Carbon::now();

        $prescripciones = DB::table('predios_prescripciones')
                        ->leftJoin('predios', 'predios.id', '=', 'predios_prescripciones.id_predio')
                        ->select(DB::raw('predios_prescripciones.*, predios.codigo_predio'))
                        ->where('predios.estado', 1)
                        ->whereRaw(DB::raw("predios_prescripciones.created_at >= CONVERT(datetime, '" . $fecha_minima . " 00:00:00.000')"))
                        ->whereRaw(DB::raw("predios_prescripciones.created_at <= CONVERT(datetime, '" . $fecha_maxima . " 23:59:59.999')"))
                        ->orderBy('predios_prescripciones.created_at')
                        ->orderBy('predios.codigo_predio')
                        ->get();

        $parametro_logo = DB::table('parametros')
                              ->select('parametros.valor')
                              ->where('parametros.nombre', 'logo')
                              ->first();

        $parametro_nit = DB::table('parametros')
                              ->select('parametros.valor')
                              ->where('parametros.nombre', 'nit')
                              ->first();

        $parametro_alcaldia = DB::table('parametros')
                              ->select('parametros.valor')
                              ->where('parametros.nombre', 'alcaldia')
                              ->first();


        $logo = $parametro_logo->valor;
        $nit = $parametro_nit->valor;
        $alcaldia = $parametro_alcaldia->valor;

        $data = [
            'title' => 'Prescripciones',
            'usuario' => $request->session()->get('useremail'),
            'prescripciones' => $prescripciones,
            'logo' => $logo,
            'nit' => $nit,
            'alcaldia' => $alcaldia
        ];

        $pdf = PDF::loadView('predios.prescripcionesPDF', $data);

        return $pdf->download($dt->toDateString() . '_' . str_replace(':', '-', $dt->toTimeString()) . '.pdf');
    }

    public function exportExcelExenciones(Request $request, $fechainicial, $fechafinal) {
        return Excel::download(new ExportExenciones($fechainicial, $fechafinal), 'exenciones.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    public function exportExcelPrescripciones(Request $request, $fechainicial, $fechafinal) {
        return Excel::download(new ExportPrescripciones($fechainicial, $fechafinal), 'prescripciones.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    public function update_anios_factura(Request $request) {
        $data = json_decode($request->form);
        DB::beginTransaction();
        try {
            $updated = PredioPago::where('id_predio', $data->{'id_predio'})
                        ->where('factura_pago', $data->{'factura'})
                        ->whereIn('ultimo_anio', explode(',', $data->{'lista_anios'}))
                        ->where('pagado', 0)
                        ->where('anulada', 0)
                        ->update([
                            'factura_pago' => null
                        ]);
            DB::commit();
            $success = true;
        }
        catch(\Exception $e) {
          DB::rollback();
          $success = false;
        }

        $result = array("success"=>$success);
        if($success) {
            $result['message'] = 'Información de factura ' . $data->{'factura'} . ' actualizada satisfactoriamente.';
        }
        else {
            $result['message'] = 'No se pudo actualizar la información de la factura.';
        }

        return response()->json([
            'data' => $result,
        ]);
    }

    public function exportExcelAvaluos(Request $request, $idpredio) {
        return Excel::download(new ExportAvaluos($idpredio, $request->session()->get('useremail')), 'avaluos.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    public function exportExcelEstadoCuenta(Request $request, $idpredio) {
        return Excel::download(new ExportEstadoCuenta($idpredio, $request->session()->get('useremail')), 'estado_cuenta.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }
}
