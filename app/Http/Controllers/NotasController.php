<?php

namespace App\Http\Controllers;

use App\Exports\ExportNotas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\NotasCreateFormRequest;
use App\Http\Requests\NotasUpdateFormRequest;
use App\Models\Opcion;
use App\Models\Predio;
use App\Models\PredioPago;
use App\Models\Resolucion;
use App\Models\ResolucionPredio;
use App\Models\Nota;

use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;


class NotasController extends Controller
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
        // $notas = DB::table('notas')
        //         ->join('usuarios', 'usuarios.id', '=', 'notas.id_usuario')
        //         ->join('predios_pagos', 'predios_pagos.id', '=', 'notas.id_predio_pago')
        //         ->join('predios', 'predios.id', '=', 'predios_pagos.id_predio')
        //         ->select('notas.*', 'predios.codigo_predio', 'usuarios.nombres', 'usuarios.apellidos')
        //         ->orderBy('notas.created_at', 'desc')
        //         ->get(); //paginate(5);

        $tab_current = 'li-section-bar-1';
        if ($request->has('page')) {
            $tab_current = 'li-section-bar-2';
        }

        //$request->session()->put('search', '1');
        $dt = Carbon::now();

        return view('notas.create', ['opcion' => $opcion,
                                        //   'notas' => $notas,
                                          'datenow' => $dt->toDateString(),
                                          'tab_current' => $tab_current]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  NotasCreateFormRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NotasCreateFormRequest $request) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }

        $tab_current = 'li-section-bar-1';
        DB::beginTransaction();
        try {
            $predio = new Predio();
            $predio = Predio::find($request->id_predio);
            if($request->has('ultimo_anio')) {
                $predio_pago_tmp = DB::table('predios_pagos')
                                ->where('id_predio', $request->id_predio)
                                ->where('factura_pago', $request->factura_pago)
                                ->where('ultimo_anio', $request->ultimo_anio)
                                ->first();
            } else {
                $predio_pago_tmp = DB::table('predios_pagos')
                                ->where('id_predio', $request->id_predio)
                                ->where('factura_pago', $request->factura_pago)
                                ->first();
            }

            $prev_predio_pago = PredioPago::find($predio_pago_tmp->id);
            $predio_pago = PredioPago::find($predio_pago_tmp->id);
            // dd($predio_pago);

            $predio_pago->valor_concepto1 = floatval(str_replace(",", "", $request->valor_concepto1));
            $predio_pago->valor_concepto2 = floatval(str_replace(",", "", $request->valor_concepto2));
            $predio_pago->valor_concepto3 = floatval(str_replace(",", "", $request->valor_concepto3));
            $predio_pago->valor_concepto4 = floatval(str_replace(",", "", $request->valor_concepto4));
            $predio_pago->valor_concepto13 = floatval(str_replace(",", "", $request->valor_concepto13));
            $predio_pago->valor_concepto14 = floatval(str_replace(",", "", $request->valor_concepto14));
            $predio_pago->valor_concepto15 = floatval(str_replace(",", "", $request->valor_concepto15));
            $predio_pago->valor_concepto16 = floatval(str_replace(",", "", $request->valor_concepto16));
            $predio_pago->valor_concepto17 = floatval(str_replace(",", "", $request->valor_concepto17));
            $predio_pago->valor_concepto18 = floatval(str_replace(",", "", $request->valor_concepto18));
            $predio_pago->total_calculo = floatval(str_replace(",", "", $request->total_calculo));
            $predio_pago->total_dos = floatval(str_replace(",", "", $request->total_calculo));
            $predio_pago->total_tres = floatval(str_replace(",", "", $request->total_calculo));
            $query = $predio_pago->save();

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
                    $resolucion_predio->descripcion = 'Nota debito-credito predio ' . $predio->codigo_predio;
                    $query = $resolucion_predio->save();
                    // if($query) {
                    $nota = new Nota();
                    $nota->id_predio_pago = $predio_pago->id;
                    $nota->id_usuario = $request->session()->get('userid');
                    if($request->has('ultimo_anio')) {
                        $nota->anio = $request->ultimo_anio;
                    } else {
                        $nota->anio = $prev_predio_pago->ultimo_anio;
                    }
                    $nota->factura_pago = $request->factura_pago;
                    $nota->prev_valor_concepto1 = $prev_predio_pago->valor_concepto1;
                    $nota->prev_valor_concepto2 = $prev_predio_pago->valor_concepto2;
                    $nota->prev_valor_concepto3 = $prev_predio_pago->valor_concepto3;
                    $nota->prev_valor_concepto4 = $prev_predio_pago->valor_concepto4;
                    $nota->prev_valor_concepto5 = $prev_predio_pago->valor_concepto5;
                    $nota->prev_valor_concepto6 = $prev_predio_pago->valor_concepto6;
                    $nota->prev_valor_concepto7 = $prev_predio_pago->valor_concepto7;
                    $nota->prev_valor_concepto8 = $prev_predio_pago->valor_concepto8;
                    $nota->prev_valor_concepto9 = $prev_predio_pago->valor_concepto9;
                    $nota->prev_valor_concepto10 = $prev_predio_pago->valor_concepto10;
                    $nota->prev_valor_concepto11 = $prev_predio_pago->valor_concepto11;
                    $nota->prev_valor_concepto12 = $prev_predio_pago->valor_concepto12;
                    $nota->prev_valor_concepto13 = $prev_predio_pago->valor_concepto13;
                    $nota->prev_valor_concepto14 = $prev_predio_pago->valor_concepto14;
                    $nota->prev_valor_concepto15 = $prev_predio_pago->valor_concepto15;
                    $nota->prev_valor_concepto16 = $prev_predio_pago->valor_concepto16;
                    $nota->prev_valor_concepto17 = $prev_predio_pago->valor_concepto17;
                    $nota->prev_valor_concepto18 = $prev_predio_pago->valor_concepto18;
                    $nota->prev_valor_concepto19 = $prev_predio_pago->valor_concepto19;
                    $nota->prev_valor_concepto20 = $prev_predio_pago->valor_concepto20;
                    $nota->prev_valor_concepto21 = $prev_predio_pago->valor_concepto21;
                    $nota->prev_valor_concepto22 = $prev_predio_pago->valor_concepto22;
                    $nota->prev_valor_concepto23 = $prev_predio_pago->valor_concepto23;
                    $nota->prev_valor_concepto24 = $prev_predio_pago->valor_concepto24;
                    $nota->prev_valor_concepto25 = $prev_predio_pago->valor_concepto25;
                    $nota->prev_valor_concepto26 = $prev_predio_pago->valor_concepto26;
                    $nota->prev_valor_concepto27 = $prev_predio_pago->valor_concepto27;
                    $nota->prev_valor_concepto28 = $prev_predio_pago->valor_concepto28;
                    $nota->prev_valor_concepto29 = $prev_predio_pago->valor_concepto29;
                    $nota->prev_valor_concepto30 = $prev_predio_pago->valor_concepto30;
                    $nota->prev_total_calculo = $prev_predio_pago->total_calculo;
                    $nota->prev_total_dos = $prev_predio_pago->total_dos;
                    $nota->prev_total_tres = $prev_predio_pago->total_tres;

                    $nota->valor_concepto1 = floatval(str_replace(",", "", $request->valor_concepto1));
                    $nota->valor_concepto2 = floatval(str_replace(",", "", $request->valor_concepto2));
                    $nota->valor_concepto3 = floatval(str_replace(",", "", $request->valor_concepto3));
                    $nota->valor_concepto4 = floatval(str_replace(",", "", $request->valor_concepto4));
                    $nota->valor_concepto13 = floatval(str_replace(",", "", $request->valor_concepto13));
                    $nota->valor_concepto14 = floatval(str_replace(",", "", $request->valor_concepto14));
                    $nota->valor_concepto15 = floatval(str_replace(",", "", $request->valor_concepto15));
                    $nota->valor_concepto16 = floatval(str_replace(",", "", $request->valor_concepto16));
                    $nota->valor_concepto17 = floatval(str_replace(",", "", $request->valor_concepto17));
                    $nota->valor_concepto18 = floatval(str_replace(",", "", $request->valor_concepto18));
                    $nota->total_calculo = floatval(str_replace(",", "", $request->total_calculo));
                    $nota->total_dos = floatval(str_replace(",", "", $request->total_calculo));
                    $nota->total_tres = floatval(str_replace(",", "", $request->total_calculo));
                    $nota->file_name = $request->filled('file_name') ? $request->file_name : NULL;

                    $query = $nota->save();

                    DB::commit();

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
     * @param  NotasUpdateFormRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(NotasUpdateFormRequest $request) {
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

    public function get_factura(Request $request) {
        $data = json_decode($request->form);

        $count_facturas = PredioPago::where('id_predio', $data->{'id_predio'})
                        ->where('factura_pago', $data->{'numero_factura'})
                        ->count();

        if ($count_facturas == 1) {
            $factura_pendiente = DB::table('predios_pagos')
                    ->where('id_predio', $data->{'id_predio'})
                    ->where('factura_pago', $data->{'numero_factura'})
                    ->first();

            $result = array("data" => $factura_pendiente);
            return response()->json($result);
        } else if ($count_facturas > 1) {
            $anios = DB::table('predios_pagos')
                    ->where('id_predio', $data->{'id_predio'})
                    ->where('factura_pago', $data->{'numero_factura'})
                    ->select('ultimo_anio')
                    ->distinct()
                    ->orderBy('ultimo_anio')
                    ->get();

            $result = array("anios" => $anios);
            return response()->json($result);
        }
    }

    public function get_factura_anio(Request $request) {
        $data = json_decode($request->form);

        $factura_pendiente = DB::table('predios_pagos')
                ->where('id_predio', $data->{'id_predio'})
                ->where('factura_pago', $data->{'numero_factura'})
                ->where('ultimo_anio', $data->{'ultimo_anio'})
                ->first();

        $result = array("data" => $factura_pendiente);
        return response()->json($result);
    }

    public function list_notas(Request $request) {
        $notas = DB::table('notas')
                ->join('usuarios', 'usuarios.id', '=', 'notas.id_usuario')
                ->join('predios_pagos', 'predios_pagos.id', '=', 'notas.id_predio_pago')
                ->join('predios', 'predios.id', '=', 'predios_pagos.id_predio')
                ->select('notas.*', 'predios_pagos.id_predio', 'predios.codigo_predio', 'usuarios.nombres', 'usuarios.apellidos')
                ->where('notas.estado', 1)
                ->orderBy('notas.created_at', 'desc')
                ->get();

        return response()->json([
            'notas' => $notas
        ]);
    }

    public function store_notas_delete(Request $request) {
        $data = json_decode($request->form);

        DB::beginTransaction();
        try {
            $prev_nota = DB::table('notas')
                                    ->select('notas.*')
                                    ->where('notas.id', $data->{'id'})
                                    ->first();

            // Eliminar el nota
            $deleted = Nota::where('id', $prev_nota->id)
                        ->update([
                            'estado' => 0
                        ]);

            if($deleted) {
                $predio_pago = DB::table('predios_pagos')
                                ->where('id', $prev_nota->id_predio_pago)
                                ->first();

                if ($predio_pago->total_calculo == $prev_nota->total_calculo) {
                    $updated = PredioPago::where('id', $prev_nota->id_predio_pago)
                    ->update([
                        'valor_concepto1' => $prev_nota->prev_valor_concepto1,
                        'valor_concepto2' => $prev_nota->prev_valor_concepto2,
                        'valor_concepto3' => $prev_nota->prev_valor_concepto3,
                        'valor_concepto4' => $prev_nota->prev_valor_concepto4,
                        'valor_concepto13' => $prev_nota->prev_valor_concepto13,
                        'valor_concepto14' => $prev_nota->prev_valor_concepto14,
                        'valor_concepto15' => $prev_nota->prev_valor_concepto15,
                        'valor_concepto16' => $prev_nota->prev_valor_concepto16,
                        'valor_concepto17' => $prev_nota->prev_valor_concepto17,
                        'valor_concepto18' => $prev_nota->prev_valor_concepto18,
                        'total_calculo' => $prev_nota->prev_total_calculo,
                        'total_dos' => $prev_nota->prev_total_dos,
                        'total_tres' => $prev_nota->prev_total_tres
                    ]);
                }

                DB::commit();

                $notas = DB::table('notas')
                        ->join('usuarios', 'usuarios.id', '=', 'notas.id_usuario')
                        ->join('predios_pagos', 'predios_pagos.id', '=', 'notas.id_predio_pago')
                        ->join('predios', 'predios.id', '=', 'predios_pagos.id_predio')
                        ->select('notas.*', 'predios_pagos.id_predio', 'predios.codigo_predio', 'usuarios.nombres', 'usuarios.apellidos')
                        ->where('notas.estado', 1)
                        ->orderBy('notas.created_at', 'desc')
                        ->get();

                return response()->json([
                    'notas' => $notas,
                    'message' => 'La información del pago se eliminó satisfactoriamente.',
                    'error' => false
                ]);
            }
            else {
                DB::rollback();
                return response()->json([
                    'message' => 'No se pudo eliminar la información de la nota a factura. Contacte al administrador del sistema.',
                    'error' => true
                ]);
            }
        }
        catch(\Exception $e) {
            DB::rollback();
            dd($e);
            return response()->json([
                'message' => $e,
                'error' => true
            ]);
        }
    }

    public function exportExcelNotas(Request $request, $fechainicial, $fechafinal) {
        return Excel::download(new ExportNotas($request->session()->get('useremail'), $fechainicial, $fechafinal), 'reporte_notas_facturas.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }
}
