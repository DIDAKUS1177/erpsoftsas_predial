<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\PrediosTarifaCreateFormRequest;
use App\Http\Requests\PrediosTarifaUpdateFormRequest;
use App\Models\Opcion;
use App\Models\Predio;
use App\Models\PredioPago;
use App\Models\PredioCambioTarifa;
use App\Models\Resolucion;
use App\Models\ResolucionPredio;

use Carbon\Carbon;

class PrediosTarifaController extends Controller
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

        $cambios_tarifas = DB::table('predios_cambios_tarifas')
                    ->join('predios', 'predios_cambios_tarifas.id_predio', '=', 'predios.id')
                    ->select(DB::raw('predios_cambios_tarifas.id, predios_cambios_tarifas.tarifa_anterior, predios_cambios_tarifas.tarifa_nueva, predios.codigo_predio, predios_cambios_tarifas.file_name, TRY_CONVERT(VARCHAR, predios_cambios_tarifas.created_at, 23) AS created_at'))
                    ->orderBy('predios_cambios_tarifas.id', 'desc')
                    ->get();

        $tab_current = 'li-section-bar-1';
        if ($request->has('page')) {
            $tab_current = 'li-section-bar-2';
        }

        //$request->session()->put('display_buttons', '1');

        return view('tarifapredio.create', ['opcion' => $opcion,
                                            'cambios_tarifas' => $cambios_tarifas,
                                            'tab_current' => $tab_current]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  PrediosTarifaCreateFormRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PrediosTarifaCreateFormRequest $request) {
            if (!$request->session()->exists('userid')) {
                return redirect('/');
            }

            $tab_current = 'li-section-bar-1';
            DB::beginTransaction();
            try {
                if (floatval(str_replace("%", "", $request->tarifa_anterior)) != floatval(str_replace("%", "", $request->tarifa_nueva))) {
                    $dt = Carbon::now();

                    $predio = new Predio();
                    $predio = Predio::find($request->id_predio);
                    $predio_cambio_tarifa = new PredioCambioTarifa();
                    $predio_cambio_tarifa->id_predio = $predio->id;
                    $predio_cambio_tarifa->tarifa_anterior = str_replace("%", "", $request->tarifa_anterior);
                    $predio_cambio_tarifa->tarifa_nueva = str_replace("%", "", $request->tarifa_nueva);
                    $predio_cambio_tarifa->file_name = $request->filled('file_name') ? $request->file_name : NULL;
                    $query = $predio_cambio_tarifa->save();

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
                            $resolucion_predio->descripcion = 'Cambio de tarifa predio ' . $predio->codigo_predio . ', tarifa anterior: ' . $request->tarifa_anterior . ', tarifa nueva: ' . $request->tarifa_nueva;
                            $query = $resolucion_predio->save();
                            // if($query) {
                            Predio::where('id', $predio->id)
                                ->update([
                                    'tarifa_actual' => str_replace("%", "", $request->tarifa_nueva)
                                ]);

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
                } else {
                    DB::rollback();
                    return back()->with(['fail' => 'No se pudo guardar la informaci&oacute;n. Tarifa anterior y tarifa nueva deben ser diferentes. Intente nuevamente.', 'tab_current' => $tab_current]);
                }
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
     * @param  PrediosTarifaUpdateFormRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(PrediosTarifaUpdateFormRequest $request) {
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
