<?php

namespace App\Http\Controllers;

use App\Exports\ExportNotas;
use App\Exports\ExportAcuerdos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\AcuerdosCreateFormRequest;
use App\Http\Requests\AcuerdosUpdateFormRequest;
use App\Models\Opcion;
use App\Models\Predio;
use App\Models\Resolucion;
use App\Models\ResolucionPredio;
use App\Models\PredioAcuerdoPago;
use App\Models\PredioAcuerdoPagoDetalle;
use App\Models\Anio;

use Carbon\Carbon;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;
use StdClass;


class AcuerdosController extends Controller
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

        $tab_current = 'li-section-bar-1';
        if ($request->has('page')) {
            $tab_current = 'li-section-bar-2';
        }

        //$request->session()->put('search', '1');
        $dt = Carbon::now();
        $current_time = $dt->timestamp;

        return view('acuerdos.create', ['opcion' => $opcion,
                                        'datenow' => $dt->toDateString(),
                                        'current_time' => $current_time,
                                        'tab_current' => $tab_current,
                                        'tab_current' => $tab_current]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  AcuerdosCreateFormRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AcuerdosCreateFormRequest $request) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }

        $tab_current = 'li-section-bar-1';
        DB::beginTransaction();
        try {
            $predio = new Predio();
            $predio = Predio::find($request->id_predio_acuerdo);

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
                    $resolucion_predio->descripcion = 'Nuevo acuerdo de pago predio ' . $predio->codigo_predio;
                    $query = $resolucion_predio->save();
                    // if($query) {
                    $predio_acuerdo_pago = new PredioAcuerdoPago();
                    $prefix = Carbon::now()->format('Ym'); // More concise way to get '202506'

                    // Find the highest agreement number for the current month, and lock the
                    // rows to prevent other processes from reading them until we are done.
                    $lastAcuerdo = DB::table('predios_acuerdos_pago')
                                        ->where('numero_acuerdo', 'like', $prefix . '%')
                                        ->max('numero_acuerdo');

                    $sequence = 0;
                    if ($lastAcuerdo) {
                        // Extract the last 4 digits and convert to an integer
                        $lastSequence = (int) substr($lastAcuerdo, -4);
                        $sequence = $lastSequence;
                    }

                    // Increment the sequence and pad with zeros
                    $newSequence = str_pad($sequence + 1, 4, '0', STR_PAD_LEFT);

                    $numero = $prefix . $newSequence; // e.g., '2025060016'

                    $predio_acuerdo_pago->id_predio = $request->id_predio_acuerdo;
                    $predio_acuerdo_pago->numero_acuerdo = $numero;
                    $predio_acuerdo_pago->anio_inicial_acuerdo = $request->anio_inicial_acuerdo;
                    $predio_acuerdo_pago->anio_final_acuerdo = $request->anio_final_acuerdo;
                    $predio_acuerdo_pago->cuotas_acuerdo = $request->cuotas_acuerdo;

                    // $predio_acuerdo_pago->responsable_propietario_acuerdo = property_exists($data, 'responsable_propietario_acuerdo') ? $request->responsable_propietario_acuerdo : '0';
                    // $predio_acuerdo_pago->identificacion_acuerdo = $request->identificacion_acuerdo;
                    // $predio_acuerdo_pago->nombre_acuerdo = trim($request->nombre_acuerdo);
                    // $predio_acuerdo_pago->direccion_acuerdo = trim($request->direccion_acuerdo);
                    // $predio_acuerdo_pago->telefono_acuerdo = $request->telefono_acuerdo;

                    $predio_acuerdo_pago->numero_resolucion_acuerdo = $request->numero_resolucion;
                    $predio_acuerdo_pago->abono_inicial_acuerdo = floatval(str_replace(",", "", $request->abono_inicial_acuerdo));

                    $predio_acuerdo_pago->estado_acuerdo = 1;

                    $predio_acuerdo_pago->created_at = Carbon::now();
                    $predio_acuerdo_pago->id_usuario_crea = intval($request->session()->get('userid'));

                    $total = DB::table('predios_pagos')
                            ->select(DB::raw('ISNULL(SUM(total_calculo), 0) as total'))
                            ->where('id_predio', $request->id_predio_acuerdo)
                            ->where('pagado', 0)
                            ->where('anulada', 0)
                            ->whereBetween('ultimo_anio', array(
                                $request->anio_inicial_acuerdo,
                                $request->anio_final_acuerdo
                            ))
                            ->first();

                    $predio_acuerdo_pago->total_acuerdo = floatval($total->total) - floatval(str_replace(",", "", $request->abono_inicial_acuerdo));

                    $predio_acuerdo_pago->fecha_inicial_acuerdo = Carbon::createFromFormat("Y-m-d", $request->fecha_inicial_acuerdo)->format('Y-m-d');

                    // --- Get inputs from the request ---
                    // It's good practice to cast to an integer for safety.
                    $numberOfMonths = (int) $request->cuotas_acuerdo;

                    // --- Calculate the final date ---
                    // Start with the current date and time.
                    $finalDate = Carbon::createFromFormat("Y-m-d", $request->fecha_inicial_acuerdo)
                        // Add the specified number of months. This correctly handles year changes.
                        // Use addMonthsNoOverflow() to avoid issues like Jan 31 + 1 month becoming March.
                        ->addMonthsNoOverflow($numberOfMonths);


                    // --- Assign the formatted date string ---
                    // ->toDateString() is a clean shortcut for ->format('Y-m-d').
                    $predio_acuerdo_pago->fecha_final_acuerdo = $finalDate->toDateString();
                    $predio_acuerdo_pago->fecha_acuerdo = Carbon::createFromFormat("Y-m-d", $request->fecha_acuerdo)->format('Y-m-d');


                    $predio_acuerdo_pago->file_name = $request->filled('file_name') ? $request->file_name : NULL;
                    $predio_acuerdo_pago->porcentaje_inicial_acuerdo = $request->filled('porcentaje_inicial_acuerdo') ? floatval(str_replace("%", "", $request->porcentaje_inicial_acuerdo)) : 0;

                    $predio_acuerdo_pago->calcular_intereses = intval($request->calcular_intereses) * -1;

                    $query = $predio_acuerdo_pago->save();

                    DB::commit();

                    $submit = DB::select("SET NOCOUNT ON; EXEC SP_ACUERDOS_PAGOS ?,?,?",
                        array(
                            intval($request->session()->get('userid')), // @USUARIO_ID
                            $predio->id,                                // @PREDIO_ID
                            $numero                                     // @NUMERO_ACUERDO
                        )
                    );

                    return back()->with(['success' => 'La informaci&oacute;n se guard&oacute; satisfactoriamente.', 'tab_current' => $tab_current]);
                    // }
                    // else {
                    //     $resolucion->delete();
                    //     $predio_cambio_tarifa->delete();
                    //     return back()->with(['fail' => 'No se pudo generar la informaci&oacute;n de resoluci&oacute;n predio. Intente nuevamente.', 'tab_current' => $tab_current]);
                    // }
                // }
                // else {
                //     $predio_cambio_tarifa->delete();
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
     * @param  AcuerdosUpdateFormRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(AcuerdosUpdateFormRequest $request) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }

        $tab_current = 'li-section-bar-2';
        DB::beginTransaction();
        try {
            $prev_predio_acuerdo_pago = PredioAcuerdoPago::find($request->id_edit);
            $predio_acuerdo_pago = PredioAcuerdoPago::find($request->id_edit);
            $predio = Predio::find($predio_acuerdo_pago->id_predio);

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
                    $resolucion_predio->descripcion = 'Modificación de acuerdo de pago predio ' . $predio->codigo_predio;
                    $query = $resolucion_predio->save();
                    // if($query) {
                    $predio_acuerdo_pago->anio_inicial_acuerdo = $request->anio_inicial_acuerdo_edit;
                    $predio_acuerdo_pago->anio_final_acuerdo = $request->anio_final_acuerdo_edit;
                    $predio_acuerdo_pago->cuotas_acuerdo = $request->cuotas_acuerdo_edit;
                    $predio_acuerdo_pago->numero_resolucion_acuerdo = $request->numero_resolucion;
                    $predio_acuerdo_pago->abono_inicial_acuerdo = floatval(str_replace(",", "", $request->abono_inicial_acuerdo_edit));
                    $predio_acuerdo_pago->estado_acuerdo = $request->filled('estado_acuerdo_edit') ? intval($request->estado_acuerdo_edit) : 0;
                    $predio_acuerdo_pago->updated_at = Carbon::now();

                    $total = DB::table('predios_pagos')
                            ->select(DB::raw('ISNULL(SUM(total_calculo), 0) as total'))
                            ->where('id_predio', $predio_acuerdo_pago->id_predio)
                            ->where('pagado', 0)
                            ->where('anulada', 0)
                            ->whereBetween('ultimo_anio', array(
                                $request->anio_inicial_acuerdo_edit,
                                $request->anio_final_acuerdo_edit
                            ))
                            ->first();

                    $predio_acuerdo_pago->total_acuerdo = floatval($total->total) - floatval(str_replace(",", "", $request->abono_inicial_acuerdo_edit));
                    $predio_acuerdo_pago->fecha_inicial_acuerdo = Carbon::createFromFormat("Y-m-d", $request->fecha_inicial_acuerdo_edit)->format('Y-m-d');

                    // --- Get inputs from the request ---
                    // It's good practice to cast to an integer for safety.
                    $numberOfMonths = (int) $request->cuotas_acuerdo_edit;

                    // --- Calculate the final date ---
                    // Start with the current date and time.
                    $finalDate = Carbon::createFromFormat("Y-m-d", $request->fecha_inicial_acuerdo_edit)
                        // Add the specified number of months. This correctly handles year changes.
                        // Use addMonthsNoOverflow() to avoid issues like Jan 31 + 1 month becoming March.
                        ->addMonthsNoOverflow($numberOfMonths);


                    // --- Assign the formatted date string ---
                    // ->toDateString() is a clean shortcut for ->format('Y-m-d').
                    $predio_acuerdo_pago->fecha_final_acuerdo = $finalDate->toDateString();
                    $predio_acuerdo_pago->fecha_acuerdo = Carbon::createFromFormat("Y-m-d", $request->fecha_acuerdo_edit)->format('Y-m-d');
                    $predio_acuerdo_pago->file_name = $request->filled('file_name_edit') ? $request->file_name_edit : NULL;
                    $predio_acuerdo_pago->porcentaje_inicial_acuerdo = $request->filled('porcentaje_inicial_acuerdo_edit') ? floatval(str_replace("%", "", $request->porcentaje_inicial_acuerdo_edit)) : 0;
                    $predio_acuerdo_pago->calcular_intereses = intval($request->calcular_intereses_edit) * -1;

                    // $recalcular_acuerdo = false;
                    // if ($predio_acuerdo_pago->porcentaje_inicial_acuerdo != $prev_predio_acuerdo_pago->porcentaje_inicial_acuerdo ||
                    //     $predio_acuerdo_pago->abono_inicial_acuerdo != $prev_predio_acuerdo_pago->abono_inicial_acuerdo ||
                    //     $predio_acuerdo_pago->cuotas_acuerdo != $prev_predio_acuerdo_pago->cuotas_acuerdo ||
                    //     $predio_acuerdo_pago->calcular_intereses != $prev_predio_acuerdo_pago->calcular_intereses) {
                    //     $recalcular_acuerdo = true;
                    // }

                    $recalcular_acuerdo = true;

                    $query = $predio_acuerdo_pago->save();

                    if ($recalcular_acuerdo) {
                        // $deleted = PredioAcuerdoPagoDetalle::where('id_acuerdo', $predio_acuerdo_pago->id)->delete();
                        $updated = PredioAcuerdoPagoDetalle::where('id_acuerdo', $predio_acuerdo_pago->id)
                            ->where('estado', 1)
                            ->where('pagado', 0)
                            ->update([
                                'estado' => 0
                            ]);

                        DB::commit();

                        $submit = DB::select("SET NOCOUNT ON; EXEC SP_ACUERDOS_PAGOS ?,?,?",
                            array(
                                intval($request->session()->get('userid')), // @USUARIO_ID
                                $predio->id,                                // @PREDIO_ID
                                $predio_acuerdo_pago->numero_acuerdo        // @NUMERO_ACUERDO
                            )
                        );
                    }
                    else {
                        DB::commit();
                    }

                    return back()->with(['success' => 'La informaci&oacute;n se actualiz&oacute; satisfactoriamente.', 'tab_current' => $tab_current]);
                    // }
                    // else {
                    //     $resolucion->delete();
                    //     $predio_cambio_tarifa->delete();
                    //     return back()->with(['fail' => 'No se pudo generar la informaci&oacute;n de resoluci&oacute;n predio. Intente nuevamente.', 'tab_current' => $tab_current]);
                    // }
                // }
                // else {
                //     $predio_cambio_tarifa->delete();
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
     * Remove the specified resource from storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request) {
        //
    }

    public function list_acuerdos(Request $request) {
        $acuerdos = DB::table('predios_acuerdos_pago AS acuerdos')
                ->join('usuarios', 'usuarios.id', '=', 'acuerdos.id_usuario_crea')
                ->join('predios', 'predios.id', '=', 'acuerdos.id_predio')
                ->select('acuerdos.*', 'predios.codigo_predio', 'usuarios.nombres', 'usuarios.apellidos')
                // ->where('acuerdos.estado_acuerdo', 1)
                ->orderBy('acuerdos.created_at', 'desc')
                ->get();

        return response()->json([
            'acuerdos' => $acuerdos
        ]);
    }

    public function list_acuerdo_detalle(Request $request) {
        $acuerdo_detalle = DB::table('predios_acuerdos_pago_detalle AS acuerdos')
                ->join('predios_acuerdos_pago', 'predios_acuerdos_pago.id', '=', 'acuerdos.id_acuerdo')
                ->leftJoin('bancos', 'bancos.id', '=', 'acuerdos.id_banco')
                ->select(DB::raw('acuerdos.id, predios_acuerdos_pago.id_predio, acuerdos.factura_pago, acuerdos.cuota_numero, acuerdos.valor_cuota, CASE WHEN acuerdos.pagado = -1 THEN \'SI\' ELSE \'NO\' END as pagado, ISNULL(CONVERT(VARCHAR, acuerdos.fecha_pago, 23), \'N/D\') as fecha_pago, ISNULL(CONVERT(VARCHAR, acuerdos.fecha_real_pago, 23), \'N/A\') as fecha_real_pago, ISNULL(bancos.nombre, \'No disponible\') AS banco, ISNULL(acuerdos.valor_concepto1, 0) AS valor_concepto1, ISNULL(acuerdos.valor_concepto2, 0) AS valor_concepto2, ISNULL(acuerdos.valor_concepto3, 0) AS valor_concepto3, ISNULL(acuerdos.valor_concepto4, 0) AS valor_concepto4, ISNULL(acuerdos.valor_concepto14, 0) AS valor_concepto14, ISNULL(acuerdos.valor_concepto18, 0) AS valor_concepto18, acuerdos.file_factura'))
                ->where('predios_acuerdos_pago.id', $request->id_acuerdo)
                ->where('acuerdos.estado', 1)
                ->orderBy('acuerdos.cuota_numero', 'asc')
                ->get();

        return response()->json([
            'acuerdo_detalle' => $acuerdo_detalle,
        ]);
    }

    public function list_acuerdo_anios(Request $request) {
        $anios = [];
        $array_anios = [];
        $dt = Carbon::now();
        $currentYear = $dt->year;

        $acuerdo_pago = DB::table('predios_acuerdos_pago AS acuerdos')
                ->select('acuerdos.*')
                ->where('acuerdos.id', $request->id_acuerdo)
                // ->where('acuerdos.estado_acuerdo', 1)
                ->first();

        // Para cada factura extraer el maximo año no pagado (Solo para registros que si tienen numero de factura)
        // Esto debido a que aveces con un mismo numero de factura se liquidan varios años.
        $anios_con_factura = DB::table('predios_pagos')
                ->where('id_predio', $acuerdo_pago->id_predio)
                //->where('pagado', 0)
                //->where('anulada', 0)
                ->whereBetween('ultimo_anio', [
                    $acuerdo_pago->anio_inicial_acuerdo,
                    $acuerdo_pago->anio_final_acuerdo
                ])
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
        //     ->where('id_predio', $acuerdo_pago->id_predio)
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
            //     ->where('id_predio', $acuerdo_pago->id_predio)
            //     ->where('pagado', 0)
            //     ->where('anulada', 0)
            //     ->where('factura_pago', $anio->factura_pago)
            //     // ->select('predios_pagos.ultimo_anio', 'predios_pagos.total_calculo')
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
            ->where('id_predio', $acuerdo_pago->id_predio)
            ->where('ultimo_anio', $currentYear)
            ->where('anulada', 0)
            // ->where('prescrito', 0)
            // ->where('exencion', 0)
            ->first();

        // Si no existe un calculo para el año actual o si el calculo existe pero aun no tiene un numero
        // de factura asignado, entonces, se agrega el año a la lista
        if($ultimo_anio_pagar->ultimo_anio != $currentYear && !$exists_current_anio && count($array_anios) > 0) {
            array_unshift($array_anios, ['ultimo_anio' => strval($currentYear), 'factura_pago' => null, 'total_calculo' => 0]);
        }

        return response()->json([
            'anios' => $array_anios
        ]);
    }

    public function store_acuerdos_delete(Request $request) {
        $data = json_decode($request->form);

        DB::beginTransaction();
        try {
            $prev_acuerdo = DB::table('predios_acuerdos_pago AS acuerdos')
                                    ->select('acuerdos.*')
                                    ->where('acuerdos.id', $data->{'id'})
                                    ->first();

            // Eliminar el acuerdo
            $deleted = PredioAcuerdoPago::where('id', $prev_acuerdo->id)
                        ->update([
                            'estado_acuerdo' => 0
                        ]);

            if($deleted) {
                // $predio_pago = DB::table('predios_pagos')
                //                 ->where('id', $prev_acuerdo->id_predio_pago)
                //                 ->first();

                // if ($predio_pago->total_calculo == $prev_acuerdo->total_calculo) {
                //     $updated = PredioPago::where('id', $prev_acuerdo->id_predio_pago)
                //     ->update([
                //         'valor_concepto1' => $prev_acuerdo->prev_valor_concepto1,
                //         'valor_concepto2' => $prev_acuerdo->prev_valor_concepto2,
                //         'valor_concepto3' => $prev_acuerdo->prev_valor_concepto3,
                //         'valor_concepto4' => $prev_acuerdo->prev_valor_concepto4,
                //         'valor_concepto13' => $prev_acuerdo->prev_valor_concepto13,
                //         'valor_concepto14' => $prev_acuerdo->prev_valor_concepto14,
                //         'valor_concepto15' => $prev_acuerdo->prev_valor_concepto15,
                //         'valor_concepto16' => $prev_acuerdo->prev_valor_concepto16,
                //         'valor_concepto17' => $prev_acuerdo->prev_valor_concepto17,
                //         'valor_concepto18' => $prev_acuerdo->prev_valor_concepto18,
                //         'total_calculo' => $prev_acuerdo->prev_total_calculo,
                //         'total_dos' => $prev_acuerdo->prev_total_dos,
                //         'total_tres' => $prev_acuerdo->prev_total_tres
                //     ]);
                // }

                DB::commit();

                $acuerdos = DB::table('predios_acuerdos_pago AS acuerdos')
                    ->join('usuarios', 'usuarios.id', '=', 'acuerdos.id_usuario_crea')
                    ->join('predios', 'predios.id', '=', 'acuerdos.id_predio')
                    ->select('acuerdos.*', 'predios.id', 'predios.codigo_predio', 'usuarios.nombres', 'usuarios.apellidos')
                    ->where('acuerdos.estado_acuerdo', 1)
                    ->orderBy('acuerdos.created_at', 'desc')
                    ->get();

                return response()->json([
                    'acuerdos' => $acuerdos,
                    'message' => 'La información del acuerdo de pago se eliminó satisfactoriamente.',
                    'error' => false
                ]);
            }
            else {
                DB::rollback();
                return response()->json([
                    'message' => 'No se pudo eliminar la información del acuerdo de pago. Contacte al administrador del sistema.',
                    'error' => true
                ]);
            }
        }
        catch(\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => $e,
                'error' => true
            ]);
        }
    }

    public function generate_factura_acuerdo_pdf(Request $request, $id, $tmp, $lista_cuotas) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }

        DB::beginTransaction();
        try {
            $dt_emision = Carbon::now();
            $currentYear = $dt_emision->year;
            $facturaYaPagada = false;

            $acuerdo_pago = DB::table('predios_acuerdos_pago AS acuerdos')
                ->select('acuerdos.*')
                ->where('acuerdos.id', $id)
                ->first();

            if($acuerdo_pago != null) {
                $acuerdo_cuotas = DB::table('predios_acuerdos_pago_detalle AS acuerdos')
                    ->select('acuerdos.*')
                    ->where('acuerdos.id_acuerdo', $acuerdo_pago->id)
                    ->where('acuerdos.estado', 1)
                    ->where('acuerdos.pagado', 0)
                    ->whereIn('acuerdos.cuota_numero', explode(',', $lista_cuotas))
                    ->orderBy('acuerdos.cuota_numero', 'asc')
                    ->get();

                if (count($acuerdo_cuotas) > 0) {
                    $anio = Anio::where('anio', $currentYear)->first();
                    $init_anio = new Anio;
                    $init_anio = Anio::find($anio->id);
                    $case_ultimo_numero_factura_acuerdo = 0;
                    $ultimo_numero_factura_acuerdo = 0;

                    if($anio->numero_factura_acuerdo_actual == null || $anio->numero_factura_acuerdo_actual == 0) {
                        if($anio->numero_factura_acuerdo_inicial == null || $anio->numero_factura_acuerdo_inicial == 0) {
                            $ultimo_numero_factura_acuerdo = 1;
                            $case_ultimo_numero_factura_acuerdo = 1;
                        }
                        else {
                            $ultimo_numero_factura_acuerdo = $anio->numero_factura_acuerdo_inicial;
                            $case_ultimo_numero_factura_acuerdo = 2;
                        }
                    }
                    else {
                        if($anio->numero_factura_acuerdo_inicial == null || $anio->numero_factura_acuerdo_inicial == 0) {
                            $ultimo_numero_factura_acuerdo = $anio->numero_factura_acuerdo_actual;
                            $case_ultimo_numero_factura_acuerdo = 3;
                        }
                        else if($anio->numero_factura_acuerdo_inicial > $anio->numero_factura_acuerdo_actual) {
                            $ultimo_numero_factura_acuerdo = $anio->numero_factura_acuerdo_inicial;
                            $case_ultimo_numero_factura_acuerdo = 4;
                        }
                        else {
                            $ultimo_numero_factura_acuerdo = $anio->numero_factura_acuerdo_actual;
                        }
                    }

                    // Maximo numero de factura 99999 cada anio
                    if($ultimo_numero_factura_acuerdo > 9999) {
                        dd('El número de factura excede el límite permitido de 99999. Valor actual: ' . $ultimo_numero_factura_acuerdo);
                        return null;
                    }

                    if($case_ultimo_numero_factura_acuerdo == 1) {
                        $init_anio->numero_factura_acuerdo_inicial = $ultimo_numero_factura_acuerdo;
                        $init_anio->numero_factura_acuerdo_actual = $ultimo_numero_factura_acuerdo;
                        $init_anio->save();
                    }
                    else if($case_ultimo_numero_factura_acuerdo == 2 || $case_ultimo_numero_factura_acuerdo == 4) {
                        $init_anio->numero_factura_acuerdo_actual = $ultimo_numero_factura_acuerdo;
                        $init_anio->save();
                    }
                    else if($case_ultimo_numero_factura_acuerdo == 3) {
                        $init_anio->numero_factura_acuerdo_inicial = $ultimo_numero_factura_acuerdo;
                        $init_anio->save();
                    }

                    $numero_factura_acuerdo = $currentYear . '9' . (str_pad($ultimo_numero_factura_acuerdo, 4, "0", STR_PAD_LEFT));

                    // Actualizar el consecutivo de numero de factura disponible para la proxima impresion
                    // Guardar informacion solo si se realizo un nuevo calculo
                    $init_anio->numero_factura_acuerdo_actual = $ultimo_numero_factura_acuerdo + 1;
                    $init_anio->save();
                    ////

                    $predio = DB::table('predios')->join('zonas', function ($join) {
                            $join->on('predios.id_zona', '=', 'zonas.id');
                        })
                        ->select(DB::raw('predios.*, zonas.descripcion'))
                        ->where('predios.estado', 1)
                        ->where('predios.id', $acuerdo_pago->id_predio)
                        ->first();

                    $propietarios = DB::table('predios')
                                        ->join('predios_propietarios', 'predios.id', '=', 'predios_propietarios.id_predio')
                                        ->join('propietarios', 'propietarios.id', '=', 'predios_propietarios.id_propietario')
                                        ->join('zonas', 'zonas.id', '=', 'predios.id_zona')
                        ->select(DB::raw('predios_propietarios.id_predio, STRING_AGG(TRIM(propietarios.nombre), \'<br />\') AS propietarios, STRING_AGG(propietarios.identificacion, \'<br />\') AS identificaciones'))
                        ->where('predios.estado', 1)
                        ->where('predios.id', $predio->id)
                        ->where('predios_propietarios.jerarquia', '001')
                        ->groupBy('predios_propietarios.id_predio')
                        ->get();

                    if($propietarios) {
                        $desired_object = self::findInCollection($propietarios, 'id_predio', $predio->id);
                        if($desired_object) {
                            $predio->propietarios = $desired_object->propietarios;
                            $predio->identificaciones = $desired_object->identificaciones;
                        }
                        else {
                            $predio->propietarios = 'Sin asignar';
                            $predio->identificaciones = 'Sin asignar';
                        }
                    }
                    else {
                        $predio->propietarios = 'Sin asignar';
                        $predio->identificaciones = 'Sin asignar';
                    }

                    $ultima_cuota_pagada = DB::table('predios_acuerdos_pago_detalle AS acuerdos')
                        ->join('bancos', 'bancos.id', '=', 'acuerdos.id_banco')
                        ->select(DB::raw('acuerdos.*'))
                        ->where('acuerdos.id_acuerdo', $acuerdo_pago->id)
                        ->where('acuerdos.estado', 1)
                        ->where('acuerdos.pagado', -1)
                        ->orderBy('acuerdos.cuota_numero', 'desc')
                        ->first();

                    if ($ultima_cuota_pagada == null) {
                        $obj = new StdClass();
                        $obj->factura_pago = '';
                        $obj->fecha_pago = '';
                        $obj->valor_cuota = 0;
                        $obj->cuota_numero = '';
                        $ultima_cuota_pagada = $obj;
                    }
                    else {
                        $ultima_cuota_pagada->fecha_pago = $ultima_cuota_pagada->fecha_pago !== null ? Carbon::createFromFormat("Y-m-d", substr($ultima_cuota_pagada->fecha_pago, 0, 10))->format('d/m/Y') : 'N/D';
                    }

                    if ($acuerdo_pago->anio_inicial_acuerdo == $acuerdo_pago->anio_final_acuerdo) {
                        $predio->anios_a_pagar = $acuerdo_pago->anio_inicial_acuerdo;
                    } else {
                        $predio->anios_a_pagar = $acuerdo_pago->anio_inicial_acuerdo . ' A ' . $acuerdo_pago->anio_final_acuerdo;
                    }

                    // Obtener parametros de la aplicacion
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

                    $parametro_formato_acuerdo = DB::table('parametros')
                                    ->select('parametros.valor')
                                    ->where('parametros.nombre', 'formato-acuerdo')
                                    ->first();


                    $logo = $parametro_logo->valor;
                    $nit = $parametro_nit->valor;
                    $ean = $parametro_ean->valor;
                    $alcaldia = $parametro_alcaldia->valor;
                    $formato_acuerdo = $parametro_formato_acuerdo->valor;

                    $barras = new Collection();
                    $barras_texto = new Collection();
                    $lista_pagos = new Collection();
                    $suma_total = 0;

                    foreach ($acuerdo_cuotas as $cuota) {
                        $obj = new StdClass();

                        $concepto_1 = $cuota->valor_concepto1 == null ? 0 : $cuota->valor_concepto1;
                        $concepto_2 = $cuota->valor_concepto2 == null ? 0 : $cuota->valor_concepto2;
                        $concepto_3 = $cuota->valor_concepto3 == null ? 0 : $cuota->valor_concepto3;
                        $concepto_4 = $cuota->valor_concepto4 == null ? 0 : $cuota->valor_concepto4;
                        $concepto_14 = $cuota->valor_concepto14 == null ? 0 : $cuota->valor_concepto14;
                        $concepto_16 = $cuota->valor_concepto16 == null ? 0 : $cuota->valor_concepto16;
                        $concepto_18 = $cuota->valor_concepto18 == null ? 0 : $cuota->valor_concepto18;

                        $obj->cuota_numero = $cuota->cuota_numero;
                        $obj->avaluo = $predio->avaluo;
                        $obj->impuesto = $concepto_1 + $concepto_3;
                        $obj->interes = $concepto_2 + $concepto_4;
                        $obj->bomberos = $concepto_16;
                        $obj->alumbrado = $concepto_18;
                        $obj->interes_acuerdo = $concepto_14;
                        $obj->total = $cuota->valor_cuota;

                        $suma_total += $cuota->valor_cuota;

                        $lista_pagos->push($obj);
                    }

                    $valor_factura = round($suma_total);
                    $fecha_pago_hasta = (Carbon::now()->toDateString());

                    // String para generar el BARCODE
                    $barras = (chr(241) . '415' . $ean . '8020' . str_pad($numero_factura_acuerdo , 24, "0", STR_PAD_LEFT) . chr(241) . '3900' . str_pad($valor_factura, 14, "0", STR_PAD_LEFT) . chr(241) . '96' . str_replace('-', '', $fecha_pago_hasta));

                    // String para el label inferior del BARCODE
                    $barras_texto = ('(415)' . $ean . '(8020)' . str_pad($numero_factura_acuerdo , 24, "0", STR_PAD_LEFT) . '(3900)' . str_pad($valor_factura, 14, "0", STR_PAD_LEFT) . '(96)' . str_replace('-', '', $fecha_pago_hasta));

                    $data = [
                        // 'title' => 'Acuerdo de pago predial',
                        'fecha' => $dt_emision->format('d/m/Y'),
                        'hora' => $dt_emision->isoFormat('h:mm:ss a'),
                        'numero_factura_acuerdo' => $numero_factura_acuerdo,
                        'predio' => $predio,
                        'acuerdo_pago' => $acuerdo_pago,
                        'ultima_cuota_pagada' => $ultima_cuota_pagada,
                        'lista_pagos' => $lista_pagos,
                        'barras' => $barras,
                        'barras_texto' => $barras_texto,
                        'fecha_pago_hasta' => $fecha_pago_hasta,
                        'valor_factura' => $valor_factura,
                        'temporal' => $tmp,
                        'facturaYaPagada' => $facturaYaPagada,
                        'nit' => $nit,
                        'logo' => $logo,
                        'alcaldia' => $alcaldia,
                        // 'usuario' => $request->session()->get('username') . ' ' . $request->session()->get('userlastname'),
                    ];

                    $pdf = PDF::loadView($formato_acuerdo, $data)
                        ->setOptions([
                            'encoding' => 'UTF-8'
                        ]);

                    // Nombre del archivo obtenido a partir de la fecha exacta de solicitud de generación del PDF
                    $filename = '';
                    if(intval($tmp) == 0) {
                        $filename = $numero_factura_acuerdo . '_ap_' . $dt_emision->toDateString() . '_' . str_replace(':', '-', $dt_emision->toTimeString()) . '.pdf';
                    }
                    else {
                        $filename = 'temporal_ap_' . $dt_emision->toDateString() . '_' . str_replace(':', '-', $dt_emision->toTimeString()) . '.pdf';
                    }

                    // Guardar el PDF en la carpeta storage/app/public/facturacion/acuerdos
                    $pdfContent = $pdf->output();
                    Storage::disk('public')->put('facturacion/acuerdos/' . $filename, $pdfContent);

                    // Actualizar datos pago: numero_factura, fecha emision y file_factura
                    PredioAcuerdoPagoDetalle::where('id_acuerdo', $acuerdo_pago->id)
                        ->where('estado', 1)
                        ->where('pagado', 0)
                        ->whereIn('cuota_numero', explode(',', $lista_cuotas))
                        ->update([
                            'factura_pago' => $numero_factura_acuerdo,
                            'fecha_emision' => Carbon::createFromFormat("Y-m-d H:i:s", $dt_emision->toDateTimeString())->format('Y-m-d H:i:s'),
                            'file_factura' => $filename
                        ]);

                    DB::commit();

                    return $pdf->download($filename);
                }
                else {
                    dd('No se encontraron cuotas pendientes para el acuerdo de pago con ID: ' . $id);
                    DB::rollback();
                    return null;
                }
            }
            else {
                dd('No se pudo encontrar el acuerdo de pago con ID: ' . $id);
                DB::rollback();
                return null;
            }
        }
        catch(\Exception $e) {
            dd('Error al generar el PDF: ' . $e->getMessage());
            DB::rollback();
            return null;
        }
    }

    public function regenerate_factura_acuerdo_pdf(Request $request, $id_acuerdo_detalle) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }

        DB::beginTransaction();
        try {
            $dt_emision = Carbon::now();
            $facturaYaPagada = false;

            // Obtener el acuerdo detalle y el número de factura existente
            $acuerdo_detalle = DB::table('predios_acuerdos_pago_detalle AS acuerdos')
                ->select('acuerdos.*')
                ->where('acuerdos.id', $id_acuerdo_detalle)
                ->where('acuerdos.estado', 1)
                ->first();

            if($acuerdo_detalle == null || $acuerdo_detalle->factura_pago == null) {
                dd('No se encontró el detalle del acuerdo o no tiene número de factura asignado. ID: ' . $id_acuerdo_detalle);
                DB::rollback();
                return null;
            }

            // Obtener el acuerdo de pago desde el detalle
            $acuerdo_pago = DB::table('predios_acuerdos_pago AS acuerdos')
                ->select('acuerdos.*')
                ->where('acuerdos.id', $acuerdo_detalle->id_acuerdo)
                ->first();

            if($acuerdo_pago == null) {
                dd('No se encontró el acuerdo de pago para el detalle ID: ' . $id_acuerdo_detalle);
                DB::rollback();
                return null;
            }

            // Obtener todas las cuotas con el mismo número de factura
            $acuerdo_cuotas = DB::table('predios_acuerdos_pago_detalle AS acuerdos')
                ->select('acuerdos.*')
                ->where('acuerdos.id_acuerdo', $acuerdo_pago->id)
                ->where('acuerdos.estado', 1)
                ->where('acuerdos.factura_pago', $acuerdo_detalle->factura_pago)
                ->orderBy('acuerdos.cuota_numero', 'asc')
                ->get();

            if (count($acuerdo_cuotas) > 0) {
                // Usar el número de factura existente (sin incrementar)
                $numero_factura_acuerdo = $acuerdo_detalle->factura_pago;

                $predio = DB::table('predios')->join('zonas', function ($join) {
                        $join->on('predios.id_zona', '=', 'zonas.id');
                    })
                    ->select(DB::raw('predios.*, zonas.descripcion'))
                    ->where('predios.estado', 1)
                    ->where('predios.id', $acuerdo_pago->id_predio)
                    ->first();

                $propietarios = DB::table('predios')
                                    ->join('predios_propietarios', 'predios.id', '=', 'predios_propietarios.id_predio')
                                    ->join('propietarios', 'propietarios.id', '=', 'predios_propietarios.id_propietario')
                                    ->join('zonas', 'zonas.id', '=', 'predios.id_zona')
                    ->select(DB::raw('predios_propietarios.id_predio, STRING_AGG(TRIM(propietarios.nombre), \'<br />\') AS propietarios, STRING_AGG(propietarios.identificacion, \'<br />\') AS identificaciones'))
                    ->where('predios.estado', 1)
                    ->where('predios.id', $predio->id)
                    ->where('predios_propietarios.jerarquia', '001')
                    ->groupBy('predios_propietarios.id_predio')
                    ->get();

                if($propietarios) {
                    $desired_object = self::findInCollection($propietarios, 'id_predio', $predio->id);
                    if($desired_object) {
                        $predio->propietarios = $desired_object->propietarios;
                        $predio->identificaciones = $desired_object->identificaciones;
                    }
                    else {
                        $predio->propietarios = 'Sin asignar';
                        $predio->identificaciones = 'Sin asignar';
                    }
                }
                else {
                    $predio->propietarios = 'Sin asignar';
                    $predio->identificaciones = 'Sin asignar';
                }

                $ultima_cuota_pagada = DB::table('predios_acuerdos_pago_detalle AS acuerdos')
                    ->join('bancos', 'bancos.id', '=', 'acuerdos.id_banco')
                    ->select(DB::raw('acuerdos.*'))
                    ->where('acuerdos.id_acuerdo', $acuerdo_pago->id)
                    ->where('acuerdos.estado', 1)
                    ->where('acuerdos.pagado', -1)
                    ->orderBy('acuerdos.cuota_numero', 'desc')
                    ->first();

                if ($ultima_cuota_pagada == null) {
                    $obj = new StdClass();
                    $obj->factura_pago = '';
                    $obj->fecha_pago = '';
                    $obj->valor_cuota = 0;
                    $obj->cuota_numero = '';
                    $ultima_cuota_pagada = $obj;
                }
                else {
                    $ultima_cuota_pagada->fecha_pago = $ultima_cuota_pagada->fecha_pago !== null ? Carbon::createFromFormat("Y-m-d", substr($ultima_cuota_pagada->fecha_pago, 0, 10))->format('d/m/Y') : 'N/D';
                }

                if ($acuerdo_pago->anio_inicial_acuerdo == $acuerdo_pago->anio_final_acuerdo) {
                    $predio->anios_a_pagar = $acuerdo_pago->anio_inicial_acuerdo;
                } else {
                    $predio->anios_a_pagar = $acuerdo_pago->anio_inicial_acuerdo . ' A ' . $acuerdo_pago->anio_final_acuerdo;
                }

                $parametro_logo = DB::table('parametros')
                                    ->select('parametros.valor')
                                    ->where('parametros.nombre', 'logo')
                                    ->first();

                $parametro_ean = DB::table('parametros')
                                ->select('parametros.valor')
                                ->where('parametros.nombre', 'ean')
                                ->first();

                $parametro_formato_acuerdo = DB::table('parametros')
                                ->select('parametros.valor')
                                ->where('parametros.nombre', 'formato-acuerdo')
                                ->first();

                $logo = $parametro_logo->valor;
                $ean = $parametro_ean->valor;
                $formato_acuerdo = $parametro_formato_acuerdo->valor;

                $barras = new Collection();
                $barras_texto = new Collection();
                $lista_pagos = new Collection();
                $suma_total = 0;

                foreach ($acuerdo_cuotas as $cuota) {
                    $obj = new StdClass();

                    $concepto_1 = $cuota->valor_concepto1 == null ? 0 : $cuota->valor_concepto1;
                    $concepto_2 = $cuota->valor_concepto2 == null ? 0 : $cuota->valor_concepto2;
                    $concepto_3 = $cuota->valor_concepto3 == null ? 0 : $cuota->valor_concepto3;
                    $concepto_4 = $cuota->valor_concepto4 == null ? 0 : $cuota->valor_concepto4;
                    $concepto_14 = $cuota->valor_concepto14 == null ? 0 : $cuota->valor_concepto14;
                    $concepto_16 = $cuota->valor_concepto16 == null ? 0 : $cuota->valor_concepto16;
                    $concepto_18 = $cuota->valor_concepto18 == null ? 0 : $cuota->valor_concepto18;

                    $obj->cuota_numero = $cuota->cuota_numero;
                    $obj->avaluo = $predio->avaluo;
                    $obj->impuesto = $concepto_1 + $concepto_3;
                    $obj->interes = $concepto_2 + $concepto_4;
                    $obj->bomberos = $concepto_16;
                    $obj->alumbrado = $concepto_18;
                    $obj->interes_acuerdo = $concepto_14;
                    $obj->total = $cuota->valor_cuota;

                    $suma_total += $cuota->valor_cuota;

                    $lista_pagos->push($obj);
                }

                $valor_factura = round($suma_total);
                $fecha_pago_hasta = (Carbon::now()->toDateString()); // TODO: Pilas esta fecha

                // String para generar el BARCODE
                $barras = (chr(241) . '415' . $ean . '8020' . str_pad($numero_factura_acuerdo , 24, "0", STR_PAD_LEFT) . chr(241) . '3900' . str_pad($valor_factura, 14, "0", STR_PAD_LEFT) . chr(241) . '96' . str_replace('-', '', $fecha_pago_hasta));

                // String para el label inferior del BARCODE
                $barras_texto = ('(415)' . $ean . '(8020)' . str_pad($numero_factura_acuerdo , 24, "0", STR_PAD_LEFT) . '(3900)' . str_pad($valor_factura, 14, "0", STR_PAD_LEFT) . '(96)' . str_replace('-', '', $fecha_pago_hasta));

                $data = [
                    'fecha' => $dt_emision->format('d/m/Y'),
                    'hora' => $dt_emision->isoFormat('h:mm:ss a'),
                    'numero_factura_acuerdo' => $numero_factura_acuerdo,
                    'predio' => $predio,
                    'acuerdo_pago' => $acuerdo_pago,
                    'ultima_cuota_pagada' => $ultima_cuota_pagada,
                    'lista_pagos' => $lista_pagos,
                    'barras' => $barras,
                    'barras_texto' => $barras_texto,
                    'fecha_pago_hasta' => $fecha_pago_hasta,
                    'valor_factura' => $valor_factura,
                    'temporal' => 0,
                    'facturaYaPagada' => $facturaYaPagada,
                    'logo' => $logo,
                ];

                $pdf = PDF::loadView($formato_acuerdo, $data)
                    ->setOptions([
                        'encoding' => 'UTF-8'
                    ]);

                // Nombre del archivo regenerado
                $filename = $numero_factura_acuerdo . '_ap_regenerado_' . $dt_emision->toDateString() . '_' . str_replace(':', '-', $dt_emision->toTimeString()) . '.pdf';

                // Guardar el PDF en la carpeta storage/app/public/facturacion/acuerdos
                $pdfContent = $pdf->output();
                Storage::disk('public')->put('facturacion/acuerdos/' . $filename, $pdfContent);

                // Actualizar todas las cuotas con el mismo número de factura con el nuevo archivo
                PredioAcuerdoPagoDetalle::where('factura_pago', $numero_factura_acuerdo)
                    ->where('id_acuerdo', $acuerdo_pago->id)
                    ->where('estado', 1)
                    ->update([
                        'file_factura' => $filename,
                        'fecha_emision' => Carbon::createFromFormat("Y-m-d H:i:s", $dt_emision->toDateTimeString())->format('Y-m-d H:i:s')
                    ]);

                DB::commit();

                return $pdf->download($filename);
            }
            else {
                dd('No se encontraron cuotas para el número de factura: ' . $acuerdo_detalle->factura_pago);
                DB::rollback();
                return null;
            }
        }
        catch(\Exception $e) {
            dd('Error al regenerar el PDF: ' . $e->getMessage());
            DB::rollback();
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

    public function exportExcelAcuerdos(Request $request, $fechainicial, $fechafinal) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }

        $usuario = $request->session()->get('usernombres') . ' ' . $request->session()->get('userapellidos');

        return Excel::download(new ExportAcuerdos($usuario, $fechainicial, $fechafinal), 'reporte_acuerdos_pago_' . Carbon::now()->format('YmdHis') . '.xlsx');
    }

    public function exportExcelAcuerdoDetalle(Request $request, $id_acuerdo) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }

        $usuario = $request->session()->get('usernombres') . ' ' . $request->session()->get('userapellidos');

        $acuerdo_pago = DB::table('predios_acuerdos_pago AS acuerdos')
            ->join('predios', 'predios.id', '=', 'acuerdos.id_predio')
            ->select('acuerdos.*', 'predios.codigo_predio')
            ->where('acuerdos.id', $id_acuerdo)
            ->first();

        $acuerdo_detalle = DB::table('predios_acuerdos_pago_detalle AS acuerdos')
            ->join('predios_acuerdos_pago', 'predios_acuerdos_pago.id', '=', 'acuerdos.id_acuerdo')
            ->leftJoin('bancos', 'bancos.id', '=', 'acuerdos.id_banco')
            ->select(DB::raw("acuerdos.id, acuerdos.cuota_numero, acuerdos.factura_pago, acuerdos.valor_cuota,
                CASE WHEN acuerdos.pagado = -1 THEN 'SI' ELSE 'NO' END as pagado,
                ISNULL(CONVERT(VARCHAR, acuerdos.fecha_pago, 103), 'N/D') as fecha_pago,
                ISNULL(CONVERT(VARCHAR, acuerdos.fecha_real_pago, 103), 'N/A') as fecha_real_pago,
                ISNULL(bancos.nombre, 'No disponible') AS banco,
                ISNULL(acuerdos.valor_concepto1, 0) AS valor_concepto1,
                ISNULL(acuerdos.valor_concepto2, 0) AS valor_concepto2,
                ISNULL(acuerdos.valor_concepto3, 0) AS valor_concepto3,
                ISNULL(acuerdos.valor_concepto4, 0) AS valor_concepto4,
                ISNULL(acuerdos.valor_concepto14, 0) AS valor_concepto14,
                ISNULL(acuerdos.valor_concepto18, 0) AS valor_concepto18"))
            ->where('predios_acuerdos_pago.id', $id_acuerdo)
            ->where('acuerdos.estado', 1)
            ->orderBy('acuerdos.cuota_numero', 'asc')
            ->get();

        $parametro_logo = DB::table('parametros')->select('parametros.valor')->where('parametros.nombre', 'logo')->first();
        $parametro_nit = DB::table('parametros')->select('parametros.valor')->where('parametros.nombre', 'nit')->first();
        $parametro_alcaldia = DB::table('parametros')->select('parametros.valor')->where('parametros.nombre', 'alcaldia')->first();

        return Excel::download(
            new \App\Exports\ExportAcuerdoDetalle(
                $usuario,
                $acuerdo_pago,
                $acuerdo_detalle,
                $parametro_logo ? $parametro_logo->valor : '',
                $parametro_nit ? $parametro_nit->valor : '',
                $parametro_alcaldia ? $parametro_alcaldia->valor : '',
                Carbon::now()->format('d/m/Y')
            ),
            'cuotas_acuerdo_' . ($acuerdo_pago ? $acuerdo_pago->numero_acuerdo : $id_acuerdo) . '_' . Carbon::now()->format('YmdHis') . '.xlsx'
        );
    }
}
