<?php

namespace App\Http\Controllers;

use App\Models\ArchivoAsobancaria;
use App\Models\Banco;
use App\Models\Pago;
use App\Models\PredioPago;
use App\Models\Predio;
use App\Models\PredioAcuerdoPagoDetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use Carbon\Carbon;

class UploadController extends Controller
{
    public function uploadFileAsobancaria(Request $request) {
        if($request->file()) {
            $name = time().'_'.$request->file->getClientOriginalName();
            $filePath = $request->file('file')->storeAs('uploads', $name, 'public');
            $realPath = join(DIRECTORY_SEPARATOR, array(Storage::path('public'), $filePath));

            if(file_exists($realPath)) {
                $count_pagos = 0;
                $count_pagos_saved = 0;
                $count_pagos_not_saved = 0;
                $control_archivo_registros = 0;
                $control_archivo_recaudo = 0;
                $fecha_pago = '';
                $banco_archivo = '';
                $paquete_archivo = '';
                $pagos_ya_realizados = [];
                $pagos_no_realizados = [];
                $facturas_inexistentes = [];
                $objBancoArchivo = new Banco;

                // Abrir el archivo para leerlo linea a linea y guardar los pagos 1 a 1
                $getFile = fopen($realPath, "r");
                $linea = 1;
                $formato_correcto = true;
                while(!feof($getFile)) {
                    $line = fgets($getFile);
                    $tipo_registro = substr($line, 0, 2);
                    if ($linea == 1 && $tipo_registro != '01' && strlen($line) != 55) {
                        $formato_correcto = false;
                        break;
                    }
                    $linea++;
                    switch ($tipo_registro) {
                        case '01':
                            /*
                                - Registro de encabezado de archivo
                                    Tipo registro: 2
                                    Nit: 10
                                    Fecha: 8
                                    Banco: 3
                                    Numero cuenta: 17
                                    Fecha archivo: 8
                                    Hora: 4
                                    Modificador: 1
                                    Tipo cuenta: 2
                            */
                            $fecha_pago = substr($line, 12, 8);
                            $banco_archivo = substr($line, 20, 3);
                            $objBancoArchivo = DB::table('bancos')
                                                ->select('bancos.id')
                                                ->where('bancos.asobancaria', $banco_archivo)
                                                ->first();
                            break;
                        case '05':
                            /*
                                - Registro de encabezado de lote
                                    Tipo registro: 2
                                    Código del servicio recaudado: 13 ... EAN-13, o NIT
                                    Lote: 4
                            */
                            $paquete_archivo = substr($line, 15, 4);
                            break;
                        case '06':
                            $count_pagos += 1;
                            /*
                                - Registro de detalle
                                    Tipo de registro: 2
                                    Referencia principal del usuario: 48
                                    Valor recaudado: 14
                                    Procedencia de pago: 2
                                    Medios de pago: 2
                                    No. de Operación: 6
                                    No. de Autorización: 6
                                    Código de la entidad financiera debitada: 3
                                    Código de sucursal: 4
                                    Secuencia: 7
                            */
                            $numero_recibo = intval(substr($line, 2, 48));

                            $objPago = DB::table('pagos')
                                                ->where('numero_recibo', strval($numero_recibo))
                                                ->first();

                            if($objPago == null) {

                                // Verificar si el registro ya existe en predios_pagos (factura normal)
                                $max_pago = DB::table('predios_pagos')
                                            ->selectRaw("MAX(predios_pagos.ultimo_anio) as ultimo_anio")
                                            ->where('predios_pagos.factura_pago', strval($numero_recibo))
                                            ->where('predios_pagos.pagado', 0)
                                            ->where('predios_pagos.anulada', 0)
                                            ->first();

                                if($max_pago->ultimo_anio != null) {
                                    // --- PAGO NORMAL (factura encontrada en predios_pagos) ---
                                    $predios_pago = DB::table('predios_pagos')
                                                    ->select("predios_pagos.*")
                                                    ->where('predios_pagos.factura_pago', strval($numero_recibo))
                                                    ->where('predios_pagos.ultimo_anio', $max_pago->ultimo_anio)
                                                    ->where('predios_pagos.pagado', 0)
                                                    ->where('predios_pagos.anulada', 0)
                                                    ->first();

                                    $valor_facturado = floatval(substr($line, 50, 12) . ',' . substr($line, 62, 2));
                                    $banco_factura = substr($line, 80, 3);
                                    if ($banco_factura == '000' || $banco_factura == '999')
                                        $banco_factura = $banco_archivo;
                                    $objBancoFactura = DB::table('bancos')
                                                        ->select('bancos.id')
                                                        ->where('bancos.asobancaria', $banco_factura)
                                                        ->first();

                                    // Guardar informacion de pago
                                    $pago = new Pago;
                                    $pago->fecha_pago = Carbon::createFromFormat("Ymd", $fecha_pago)->format('Y-m-d');
                                    $pago->numero_recibo = $numero_recibo;
                                    $pago->id_predio = $predios_pago->id_predio;
                                    $pago->valor_facturado = $valor_facturado;
                                    $pago->anio_pago = $predios_pago->ultimo_anio;
                                    $pago->fecha_factura = Carbon::createFromFormat("Y-m-d H:i:s.u", $predios_pago->fecha_emision)->format('Y-m-d');
                                    $pago->id_banco_factura = $objBancoFactura->id;
                                    $pago->id_banco_archivo = $objBancoArchivo->id;
                                    $pago->paquete_archivo = substr($paquete_archivo, 2);
                                    $pago->origen = 'A';
                                    $pago->id_usuario = $request->session()->get('userid');
                                    $saved_pago = $pago->save();

                                    if($saved_pago) {
                                        // Actualizar informacion de predio pago
                                        $updated = PredioPago::where('factura_pago', strval($numero_recibo))
                                                    ->where('pagado', 0)
                                                    ->update([
                                                        'fecha_pago' => Carbon::createFromFormat("Ymd", $fecha_pago)->format('Y-m-d'),
                                                        'valor_pago' => DB::raw("CASE WHEN CONVERT(datetime, '" . $fecha_pago . " 00:00:00.000') <= CONVERT(datetime, '" . $predios_pago->primer_fecha . "') THEN total_calculo WHEN CONVERT(datetime, '" . $fecha_pago . " 00:00:00.000') <= CONVERT(datetime, '" . $predios_pago->segunda_fecha . "') THEN total_dos WHEN CONVERT(datetime, '" . $fecha_pago . " 00:00:00.000') <= CONVERT(datetime, '" . $predios_pago->tercera_fecha . "') THEN total_tres ELSE total_calculo END"),
                                                        'id_banco' => $objBancoFactura->id,
                                                        'pagado' => -1
                                                    ]);


                                        if($updated) {
                                            // Actualizar ultimo anio pago en la tabla predios
                                            $predio = new Predio;
                                            $predio = Predio::find($pago->id_predio);
                                            $predio->ultimo_anio_pago = $predios_pago->ultimo_anio;
                                            $predio->save();
                                            $count_pagos_saved += 1;
                                        }
                                        else {
                                            // ROLLBACK PAGO
                                            $count_pagos_not_saved += 1;
                                            $pago->delete();
                                        }
                                    }
                                    else {
                                        $count_pagos_not_saved += 1;
                                        array_push($pagos_no_realizados, $numero_recibo);
                                    }
                                }
                                else {
                                    // --- VERIFICAR SI ES UN ACUERDO DE PAGO ---
                                    // Las facturas de acuerdo de pago tienen formato: {año}9{secuencia}
                                    // Ejemplo: 20269XXXX (el 5to dígito es '9')
                                    $str_numero = strval($numero_recibo);
                                    $es_acuerdo_pago = (strlen($str_numero) >= 5 && substr($str_numero, 4, 1) === '9');

                                    if ($es_acuerdo_pago) {
                                        // Buscar primero si existe el acuerdo
                                        $existe_acuerdo = DB::table('predios_acuerdos_pago_detalle')
                                            ->where('factura_pago', $str_numero)
                                            ->where('estado', 1)
                                            ->first();

                                        if ($existe_acuerdo != null) {
                                            if ($existe_acuerdo->pagado == 0) {
                                                // Buscar info completa para guardar
                                                $acuerdo_cuota = DB::table('predios_acuerdos_pago_detalle')
                                                    ->join('predios_acuerdos_pago', 'predios_acuerdos_pago.id', '=', 'predios_acuerdos_pago_detalle.id_acuerdo')
                                                    ->select('predios_acuerdos_pago_detalle.*', 'predios_acuerdos_pago.id_predio', 'predios_acuerdos_pago.numero_acuerdo')
                                                    ->where('predios_acuerdos_pago_detalle.id', $existe_acuerdo->id)
                                                    ->first();

                                                $valor_facturado = floatval(substr($line, 50, 12) . ',' . substr($line, 62, 2));
                                                $banco_factura = substr($line, 80, 3);
                                                if ($banco_factura == '000' || $banco_factura == '999')
                                                    $banco_factura = $banco_archivo;
                                                $objBancoFactura = DB::table('bancos')
                                                                    ->select('bancos.id')
                                                                    ->where('bancos.asobancaria', $banco_factura)
                                                                    ->first();

                                                // Guardar informacion de pago en tabla pagos
                                                $pago = new Pago;
                                                $pago->fecha_pago = Carbon::createFromFormat("Ymd", $fecha_pago)->format('Y-m-d');
                                                $pago->numero_recibo = $numero_recibo;
                                                $pago->id_predio = $acuerdo_cuota->id_predio;
                                                $pago->valor_facturado = $valor_facturado;
                                                $pago->anio_pago = intval(substr($str_numero, 0, 4));
                                                $pago->fecha_factura = $acuerdo_cuota->fecha_emision != null
                                                    ? Carbon::parse($acuerdo_cuota->fecha_emision)->format('Y-m-d')
                                                    : Carbon::createFromFormat("Ymd", $fecha_pago)->format('Y-m-d');
                                                $pago->id_banco_factura = $objBancoFactura->id;
                                                $pago->id_banco_archivo = $objBancoArchivo->id;
                                                $pago->paquete_archivo = substr($paquete_archivo, 2);
                                                $pago->origen = 'A';
                                                $pago->id_usuario = $request->session()->get('userid');
                                                $saved_pago = $pago->save();

                                                if ($saved_pago) {
                                                    // Actualizar cuota(s) del acuerdo como pagada(s)
                                                    $updated = PredioAcuerdoPagoDetalle::where('id', $existe_acuerdo->id)
                                                        ->update([
                                                            'fecha_pago' => Carbon::createFromFormat("Ymd", $fecha_pago)->format('Y-m-d'),
                                                            'id_banco' => $objBancoFactura->id,
                                                            'pagado' => -1,
                                                            'fecha_real_pago' => Carbon::now()
                                                        ]);

                                                    if ($updated) {
                                                        $count_pagos_saved += 1;
                                                    } else {
                                                        $count_pagos_not_saved += 1;
                                                        $pago->delete();
                                                    }
                                                } else {
                                                    $count_pagos_not_saved += 1;
                                                    array_push($pagos_ya_realizados, $numero_recibo);
                                                }
                                            } else {
                                                // El acuerdo existe pero ya está pagado
                                                $count_pagos_not_saved += 1;
                                                array_push($pagos_ya_realizados, $numero_recibo);
                                            }
                                        } else {
                                            // Acuerdo de pago no encontrado
                                            $count_pagos_not_saved += 1;
                                            array_push($facturas_inexistentes, $numero_recibo);
                                        }
                                    } else {
                                        // Factura normal no encontrada
                                        $count_pagos_not_saved += 1;
                                        array_push($facturas_inexistentes, $numero_recibo);
                                    }
                                }
                            }
                            else {
                                array_push($pagos_ya_realizados, $numero_recibo);
                            }

                            break;
                        case '08':
                            /*
                                - Registro de control de lote
                                    Tipo registro: 2
                                    Total registros en lote: 9
                                    Valor total recaudado en lote: 18
                                    Número de lote: 4
                            */
                            break;
                        case '09':
                            /*
                                - Registro de control de archivo
                                    Tipo registro: 2
                                    Total registros recaudados en archivo: 9
                                    Valor total recaudado en archivo: 18
                            */
                            $control_archivo_registros = floatval(substr($line, 2, 9));
                            $control_archivo_recaudo = floatval(substr($line, 11, 16) . ',' . substr($line, 27, 2));
                            break;
                        default:
                            // code to be executed if $tipo_registro is different from all labels
                            break;
                    }
                }
                fclose($getFile);

                unlink($realPath);
                //Storage::disk('public')->delete('uploads/' . $name);
                // File::delete($realPath);

                if($formato_correcto) {
                    $descripcion = '';
                    $complemento = '';
                    $error = false;
                    if ($count_pagos_not_saved > 0) {
                        $error = true;
                        $descripcion = '<br />No se pudo guardar toda la informaci&oacute;n.<br />Registros en archivo asobancaria: <b>' . $count_pagos . '</b><br />Registros guardados: <b>' . $count_pagos_saved . '</b>';

                        if(count($pagos_ya_realizados) > 0) {
                            $descripcion = $descripcion . '<br />Pagos ya registrados previamente: <b>' .
                                            count($pagos_ya_realizados) . '</b>';
                            $complemento = $complemento . '<br />Lista de pagos ya registrados: ' . implode(', ', $pagos_ya_realizados);
                        }
                        if(count($pagos_no_realizados) > 0) {
                            $descripcion = $descripcion . '<br />Pagos no registrados: <b>' .
                                            count($pagos_no_realizados) . '</b>';
                            $complemento = $complemento . '<br />Lista de facturas no registradas: ' . implode(', ', $pagos_no_realizados);
                        }
                        if(count($facturas_inexistentes) > 0) {
                            $descripcion = $descripcion . '<br />Facturas inexistentes: <b>' .
                                            count($facturas_inexistentes) . '</b>';
                            $complemento = $complemento . '<br />Lista de facturas inexistentes: ' . implode(', ', $facturas_inexistentes);
                        }
                    }
                    else {
                        if($count_pagos_saved > 0) {
                            $descripcion = '<br />La informaci&oacute;n se guard&oacute; satisfactoriamente.<br />Registros en archivo asobancaria: <b>' . $count_pagos . '</b><br />Cantidad de pagos registrados: <b>' . $count_pagos_saved . '</b>';
                        }
                        else {
                            $descripcion = '<br />No se realiz&oacute; ninguna acci&oacute;n.<br />Registros en archivo asobancaria: <b>' . $count_pagos . '</b>';
                        }
                        if(count($pagos_ya_realizados) > 0) {
                            $descripcion = $descripcion . '<br />Pagos ya registrados previamente: <b>' .
                                            count($pagos_ya_realizados) . '</b>';
                            $complemento = $complemento . '<br />Lista de pagos ya registrados: ' . implode(', ', $pagos_ya_realizados);
                        }
                    }

                    if($count_pagos_saved > 0) {
                        $file = new ArchivoAsobancaria;
                        $file->id_usuario = $request->session()->get('userid');
                        $file->path = $filePath;
                        $file->name = $name;
                        $file->type = $request->file->getClientMimeType();
                        $file->size = $request->file->getSize();
                        $file->total_registros = $count_pagos;
                        $file->total_guardados = $count_pagos_saved;
                        $file->total_existentes = count($pagos_ya_realizados);
                        $file->total_fallidos = $count_pagos_not_saved;
                        $file->descripcion = $descripcion . $complemento;
                        $file->control_archivo_registros = $control_archivo_registros;
                        $file->control_archivo_recaudo = $control_archivo_recaudo;
                        $file->fecha_pago = Carbon::createFromFormat("Ymd", $fecha_pago)->format('Y-m-d');
                        $file->id_banco = $objBancoArchivo->id;
                        $file->save();
                    }

                    return response()->json([
                        'message'  => $descripcion,
                        'error'    => $error
                    ]);
                }
                else {
                    return response()->json([
                        'message' => '<br />Formato de archivo asobancaria incorrecto. Verifique que el archivo cargado cumpla con el est&aacute;ndar o solicite un nuevo archivo a la entidad financiera correspondiente.',
                        'error'    => true
                    ]);
                }
            }
            else {
                return response()->json([
                    'message' => 'Error cargando el archivo.',
                    'error'    => true
                ]);
            }
        }
    }

    public function uploadFileResolucion(Request $request) {
        if($request->file()) {
            $name = time().'_'.$request->file->getClientOriginalName();
            $filePath = $request->file('file')->storeAs('uploads', $name, 'public');
            $realPath = join(DIRECTORY_SEPARATOR, array(Storage::path('public'), $filePath));

            if(file_exists($realPath)) {
                return response()->json([
                    'message'  => 'El archivo de resoluci&oacute;n se carg&oacute; satisfactoriamente.',
                    'file'     => $name,
                    'error'    => false
                ]);
            }
            else {
                return response()->json([
                    'message' => 'Error cargando el archivo.',
                    'error'    => true
                ]);
            }
        }
    }

    public function uploadFileResolucionList(Request $request) {
        if($request->file()) {
            $name = time().'_'.$request->file->getClientOriginalName();
            $filePath = $request->file('file')->storeAs('uploads', $name, 'public');
            $realPath = join(DIRECTORY_SEPARATOR, array(Storage::path('public'), $filePath));

            if(file_exists($realPath)) {
                // Update the agreement with the file name
                DB::table('predios_acuerdos_pago')
                    ->where('id', $request->id_acuerdo)
                    ->update(['file_name' => $name]);

                return response()->json([
                    'success' => true,
                    'message'  => 'El archivo de resoluci&oacute;n se carg&oacute; satisfactoriamente.',
                    'file'     => $name
                ]);
            }
            else {
                return response()->json([
                    'success' => false,
                    'message' => 'Error cargando el archivo.'
                ]);
            }
        }
        return response()->json([
            'success' => false,
            'message' => 'No se recibió ningún archivo.'
        ]);
    }
}
