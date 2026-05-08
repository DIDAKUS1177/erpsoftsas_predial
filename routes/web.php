<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AcuerdosController;
use App\Http\Controllers\AdministracionController;
use App\Http\Controllers\AniosController;
use App\Http\Controllers\BancosController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\ClasesMutacionController;
use App\Http\Controllers\ClasesPredioController;
use App\Http\Controllers\ConceptosPredioController;
use App\Http\Controllers\DescuentosController;
use App\Http\Controllers\ErrorRequestController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PagosController;
use App\Http\Controllers\PrediosController;
use App\Http\Controllers\TarifasPredialController;
use App\Http\Controllers\TasasInteresController;
use App\Http\Controllers\TiposPredioController;
use App\Http\Controllers\ResolucionesIgacController;
use App\Http\Controllers\PrediosExencionesController;
use App\Http\Controllers\PrediosPrescripcionesController;
use App\Http\Controllers\PrediosTarifaController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\NotasController;
use App\Http\Controllers\QrValidationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// QR Validation Route - Public access
Route::get('validate-qr/{token}', [QrValidationController::class, 'validateQr'])->name('qr.validate');

// Temporary debug route
Route::get('test-db', function() {
    return response()->json([
        'pagos_columns' => \Illuminate\Support\Facades\DB::select("SELECT COLUMN_NAME, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'pagos'"),
        'predios_acuerdos_pago_detalle_columns' => \Illuminate\Support\Facades\DB::select("SELECT COLUMN_NAME, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'predios_acuerdos_pago_detalle'")
    ]);
});

// Route::get('/', function () {
//     return view('auth.login');
// });
Route::get('/', [LoginController::class, 'login']);
Route::get('logout', [LoginController::class, 'logout'])->name('auth.logout');

//Login
Route::post('checkLogin', [LoginController::class, 'checkLogin'])->name('auth.checkLogin')->middleware('checkdb');
Route::get('profile', [LoginController::class, 'profile'])->middleware('checkdb');

Route::get('cambiar_password', [ChangePasswordController::class, 'index'])->name('auth.index_change_pass')->middleware('checkdb');
Route::post('changepass', [ChangePasswordController::class, 'changepass'])->name('auth.change_pass')->middleware('checkdb');

//AcuerdosController
Route::get('registro_acuerdos/{id}', [AcuerdosController::class, 'create'])->middleware('checkdb');
Route::get('registro_acuerdos', [ErrorRequestController::class, 'error_request'])->middleware('checkdb');

Route::post('create_acuerdos', [AcuerdosController::class, 'store'])->name('acuerdos.create_acuerdo')->middleware('checkdb');
Route::post('update_acuerdos', [AcuerdosController::class, 'update'])->name('acuerdos.update_acuerdos')->middleware('checkdb');
// Route::post('delete_acuerdos', [AcuerdosController::class, 'destroy'])->name('acuerdos.delete_acuerdos')->middleware('checkdb');

// Route::post('/get_factura', [AcuerdosController::class, 'get_factura']);
// Route::post('/get_factura_anio', [AcuerdosController::class, 'get_factura_anio']);
Route::post('/list/acuerdos', [AcuerdosController::class, 'list_acuerdos']);
Route::post('/list/acuerdo-detalle', [AcuerdosController::class, 'list_acuerdo_detalle']);
Route::post('/list/acuerdo-anios', [AcuerdosController::class, 'list_acuerdo_anios']);
Route::get('/export-excel-acuerdos/{fechainicial}/{fechafinal}',[AcuerdosController::class, 'exportExcelAcuerdos']);
Route::get('/export-excel-acuerdo-detalle/{id_acuerdo}',[AcuerdosController::class, 'exportExcelAcuerdoDetalle']);
Route::post('/store/acuerdos_delete', [AcuerdosController::class, 'store_acuerdos_delete']);
Route::get('generate_factura_acuerdo_pdf/{id}/{tmp}/{lista_cuotas}', [AcuerdosController::class, 'generate_factura_acuerdo_pdf'])->middleware('checkdb');
Route::get('regenerate_factura_acuerdo_pdf/{id_acuerdo_detalle}', [AcuerdosController::class, 'regenerate_factura_acuerdo_pdf'])->middleware('checkdb');

//Administracion
Route::get('registro_usuarios/{id}', [AdministracionController::class, 'registro_usuarios'])->middleware('checkdb');
Route::get('registro_usuarios', [ErrorRequestController::class, 'error_request'])->middleware('checkdb');

Route::post('createusu', [AdministracionController::class, 'createusu'])->name('admin.create_usu')->middleware('checkdb');
Route::post('updateusu', [AdministracionController::class, 'updateusu'])->name('admin.update_usu')->middleware('checkdb');
Route::post('deleteusu', [AdministracionController::class, 'deleteusu'])->name('admin.delete_usu')->middleware('checkdb');

//AniosController
Route::get('registro_anios/{id}', [AniosController::class, 'create'])->middleware('checkdb');
Route::get('registro_anios', [ErrorRequestController::class, 'error_request'])->middleware('checkdb');

Route::post('create_anios', [AniosController::class, 'store'])->name('anios.create_anios')->middleware('checkdb');
Route::post('update_anios', [AniosController::class, 'update'])->name('anios.update_anios')->middleware('checkdb');
Route::post('delete_anios', [AniosController::class, 'destroy'])->name('anios.delete_anios')->middleware('checkdb');

//BancosController
Route::get('registro_bancos/{id}', [BancosController::class, 'create'])->middleware('checkdb');
Route::get('registro_bancos', [ErrorRequestController::class, 'error_request'])->middleware('checkdb');

Route::post('create_bancos', [BancosController::class, 'store'])->name('bancos.create_bancos')->middleware('checkdb');
Route::post('update_bancos', [BancosController::class, 'update'])->name('bancos.update_bancos')->middleware('checkdb');
Route::post('delete_bancos', [BancosController::class, 'destroy'])->name('bancos.delete_bancos')->middleware('checkdb');

//ClasesMutacionController
Route::get('registro_clasesmutacion/{id}', [ClasesMutacionController::class, 'create'])->middleware('checkdb');
Route::get('registro_clasesmutacion', [ErrorRequestController::class, 'error_request'])->middleware('checkdb');

Route::post('create_clasesmutacion', [ClasesMutacionController::class, 'store'])->name('clasesmutacion.create_clasesmutacion')->middleware('checkdb');
Route::post('update_clasesmutacion', [ClasesMutacionController::class, 'update'])->name('clasesmutacion.update_clasesmutacion')->middleware('checkdb');
Route::post('delete_clasesmutacion', [ClasesMutacionController::class, 'destroy'])->name('clasesmutacion.delete_clasesmutacion')->middleware('checkdb');

//ClasesPredioController
Route::get('registro_clasespredio/{id}', [ClasesPredioController::class, 'create'])->middleware('checkdb');
Route::get('registro_clasespredio', [ErrorRequestController::class, 'error_request'])->middleware('checkdb');

Route::post('create_clasespredio', [ClasesPredioController::class, 'store'])->name('clasespredio.create_clasespredio')->middleware('checkdb');
Route::post('update_clasespredio', [ClasesPredioController::class, 'update'])->name('clasespredio.update_clasespredio')->middleware('checkdb');
Route::post('delete_clasespredio', [ClasesPredioController::class, 'destroy'])->name('clasespredio.delete_clasespredio')->middleware('checkdb');

//ConceptosPredioController
Route::get('registro_conceptospredio/{id}', [ConceptosPredioController::class, 'create'])->middleware('checkdb');
Route::get('registro_conceptospredio', [ErrorRequestController::class, 'error_request'])->middleware('checkdb');

Route::post('create_conceptospredio', [ConceptosPredioController::class, 'store'])->name('conceptospredio.create_conceptospredio')->middleware('checkdb');
Route::post('update_conceptospredio', [ConceptosPredioController::class, 'update'])->name('conceptospredio.update_conceptospredio')->middleware('checkdb');
Route::post('delete_conceptospredio', [ConceptosPredioController::class, 'destroy'])->name('conceptospredio.delete_conceptospredio')->middleware('checkdb');

//DescuentosController
Route::get('registro_descuentos/{id}', [DescuentosController::class, 'create'])->middleware('checkdb');
Route::get('registro_descuentos', [ErrorRequestController::class, 'error_request'])->middleware('checkdb');

Route::post('create_descuentos', [DescuentosController::class, 'store'])->name('descuentos.create_descuentos')->middleware('checkdb');
Route::post('update_descuentos', [DescuentosController::class, 'update'])->name('descuentos.update_descuentos')->middleware('checkdb');
Route::post('delete_descuentos', [DescuentosController::class, 'destroy'])->name('descuentos.delete_descuentos')->middleware('checkdb');

//NotasController
Route::get('registro_notas/{id}', [NotasController::class, 'create'])->middleware('checkdb');
Route::get('registro_notas', [ErrorRequestController::class, 'error_request'])->middleware('checkdb');

Route::post('create_nota_factura', [NotasController::class, 'store'])->name('notas.create_nota_factura')->middleware('checkdb');
// Route::get('generate_nota_factura_pdf/{id_predio}', [PrediosController::class, 'generate_nota_factura_pdf'])->middleware('checkdb');

Route::post('/get_factura', [NotasController::class, 'get_factura']);
Route::post('/get_factura_anio', [NotasController::class, 'get_factura_anio']);
Route::post('/list/notas', [NotasController::class, 'list_notas']);
Route::get('/export-excel-notas/{fechainicial}/{fechafinal}',[NotasController::class, 'exportExcelNotas']);
Route::post('/store/notas_delete', [NotasController::class, 'store_notas_delete']);

//PagosController
Route::get('registro_pagos/{id}', [PagosController::class, 'create'])->middleware('checkdb');
Route::get('registro_pagos', [ErrorRequestController::class, 'error_request'])->middleware('checkdb');

Route::post('create_pagos', [PagosController::class, 'store'])->name('pagos.create_pagos')->middleware('checkdb');
Route::post('update_pagos', [PagosController::class, 'update'])->name('pagos.update_pagos')->middleware('checkdb');
Route::post('delete_pagos', [PagosController::class, 'destroy'])->name('pagos.delete_pagos')->middleware('checkdb');
Route::post('/store/pagos_delete', [PagosController::class, 'store_pagos_delete']);
Route::post('/store/pagos_edit', [PagosController::class, 'store_pagos_edit']);

//lista de pagos
Route::post('/list/pagos_fecha', [PagosController::class, 'list_pagos_fecha']);
Route::post('/get_info_pago', [PagosController::class, 'get_info_pago']);
// recibo
Route::post('/get_info_recibo', [PagosController::class, 'get_info_recibo']);
Route::get('/export-pagos/{fechainicial}/{fechafinal}/{bancoinicial}/{bancofinal}',[PagosController::class, 'exportPagos']);


//PrediosController
Route::get('registro_predios/{id}', [PrediosController::class, 'create'])->middleware('checkdb');
Route::get('registro_predios', [ErrorRequestController::class, 'error_request'])->middleware('checkdb');

Route::post('create_predios', [PrediosController::class, 'store'])->name('predios.create_predios')->middleware('checkdb');
Route::post('update_predios', [PrediosController::class, 'update'])->name('predios.update_predios')->middleware('checkdb');
Route::post('delete_predios', [PrediosController::class, 'destroy'])->name('predios.delete_predios')->middleware('checkdb');
Route::post('prescribe_predios', [PrediosController::class, 'prescribe'])->name('predios.prescribe_predios')->middleware('checkdb');

Route::get('/show/predios_datos', [PrediosController::class, 'show_predios_datos']);
Route::get('generate_factura_pdf/{id}/{tmp}/{anios}/{fecha_pago}/{informativa}/{propietario}/{vigencias}', [PrediosController::class, 'generate_factura_pdf'])->middleware('checkdb');
// Route::get('generate_factura_pdf/{id}/{vistaPrevia}/{anio}/{cuotas}/{fecha_pago}', [PrediosController::class, 'generate_factura_pdf'])->middleware('checkdb');
Route::get('generate_paz_pdf/{id}/{destino}/{fecha}/{valor}', [PrediosController::class, 'generate_paz_pdf'])->middleware('checkdb');
Route::get('generate_avaluos_predio_pdf/{id}', [PrediosController::class, 'generate_avaluos_predio_pdf'])->middleware('checkdb');
Route::get('generate_estado_cuenta_predio_pdf/{id}', [PrediosController::class, 'generate_estado_cuenta_predio_pdf'])->middleware('checkdb');
Route::get('/autocomplete', [PrediosController::class, 'autocomplete']);
Route::get('/autocomplete_check', [PrediosController::class, 'autocomplete_check']);
Route::post('/get_predio', [PrediosController::class, 'get_predio']);
Route::post('/avaluos_predio', [PrediosController::class, 'avaluos_predio']);
Route::post('/estado_cuenta_predio', [PrediosController::class, 'estado_cuenta_predio']);
Route::post('/get_propietario_by_identificacion', [PrediosController::class, 'get_propietario_by_identificacion']);
Route::post('/get_predios_no_calculados', [PrediosController::class, 'get_predios_no_calculados']);
Route::post('/ejecutar_calculo_batch', [PrediosController::class, 'ejecutar_calculo_batch']);
Route::post('/update_anios_factura', [PrediosController::class, 'update_anios_factura']);
Route::get('/export-cartera',[PrediosController::class,'exportCartera']);
Route::get('/export-excel-exenciones/{fechainicial}/{fechafinal}',[PrediosController::class, 'exportExcelExenciones']);
Route::get('/export-excel-prescripciones/{fechainicial}/{fechafinal}',[PrediosController::class, 'exportExcelPrescripciones']);
Route::get('/export-excel-avaluos/{idpredio}',[PrediosController::class, 'exportExcelAvaluos']);
Route::get('/export-excel-estado-cuenta/{idpredio}',[PrediosController::class, 'exportExcelEstadoCuenta']);

//datos_basicos
Route::post('/store/predios_datos_basicos', [PrediosController::class, 'store_predios_datos_basicos']);
//datos_propietarios
Route::post('/store/predios_datos_propietarios', [PrediosController::class, 'store_predios_datos_propietarios']);
Route::post('/store/predios_propietarios_jerarquia', [PrediosController::class, 'store_predios_propietarios_jerarquia']);
Route::post('/store/predios_propietarios_delete', [PrediosController::class, 'store_predios_propietarios_delete']);
//datos_calculo
Route::post('/store/predios_datos_calculo', [PrediosController::class, 'store_predios_datos_calculo']);
//datos_pagos
//Route::post('/store/predios_datos_pagos', [PrediosController::class, 'store_predios_datos_pagos']);
//datos_acuerdos_pago
Route::post('/store/predios_datos_acuerdos_pago', [PrediosController::class, 'store_predios_datos_acuerdos_pago']);
//datos_abonos
Route::post('/store/predios_datos_abonos', [PrediosController::class, 'store_predios_datos_abonos']);
//procesos_historicos
Route::post('/store/predios_datos_procesos_historicos', [PrediosController::class, 'store_predios_datos_procesos_historicos']);

//PrediosExencionesController
Route::get('registro_exencion/{id}', [PrediosExencionesController::class, 'create'])->middleware('checkdb');
Route::get('registro_exencion', [ErrorRequestController::class, 'error_request'])->middleware('checkdb');

Route::post('create_exenciones', [PrediosExencionesController::class, 'store'])->name('prediosexenciones.create_exenciones')->middleware('checkdb');
Route::get('generate_exenciones_pdf/{fecha_minima}/{fecha_maxima}', [PrediosController::class, 'generate_exenciones_pdf'])->middleware('checkdb');

// Route::post('update_exenciones', [PrediosExencionesController::class, 'update'])->name('prediosexenciones.update_exenciones')->middleware('checkdb');
// Route::post('delete_exenciones', [PrediosExencionesController::class, 'destroy'])->name('prediosexenciones.delete_exenciones')->middleware('checkdb');

//PrediosPrescripcionesController
Route::get('registro_prescripciones/{id}', [PrediosPrescripcionesController::class, 'create'])->middleware('checkdb');
Route::get('registro_prescripciones', [ErrorRequestController::class, 'error_request'])->middleware('checkdb');

Route::post('create_prescripciones', [PrediosPrescripcionesController::class, 'store'])->name('prediosprescripciones.create_prescripciones')->middleware('checkdb');
Route::get('generate_prescripciones_pdf/{fecha_minima}/{fecha_maxima}', [PrediosController::class, 'generate_prescripciones_pdf'])->middleware('checkdb');

// Route::post('update_prescripciones', [PrediosPrescripcionesController::class, 'update'])->name('prediosprescripciones.update_prescripciones')->middleware('checkdb');
// Route::post('delete_prescripciones', [PrediosPrescripcionesController::class, 'destroy'])->name('prediosprescripciones.delete_prescripciones')->middleware('checkdb');

//PrediosTarifaController
Route::get('registro_cambio_tarifa/{id}', [PrediosTarifaController::class, 'create'])->middleware('checkdb');
Route::get('registro_cambio_tarifa', [ErrorRequestController::class, 'error_request'])->middleware('checkdb');
Route::post('create_cambio_tarifa', [PrediosTarifaController::class, 'store'])->name('predioscambiotarifa.create_cambio_tarifa')->middleware('checkdb');

//TarifasPredialController
Route::get('registro_tarifaspredial/{id}', [TarifasPredialController::class, 'create'])->middleware('checkdb');
Route::get('registro_tarifaspredial', [ErrorRequestController::class, 'error_request'])->middleware('checkdb');

Route::post('create_tarifaspredial', [TarifasPredialController::class, 'store'])->name('tarifaspredial.create_tarifaspredial')->middleware('checkdb');
Route::post('update_tarifaspredial', [TarifasPredialController::class, 'update'])->name('tarifaspredial.update_tarifaspredial')->middleware('checkdb');
Route::post('delete_tarifaspredial', [TarifasPredialController::class, 'destroy'])->name('tarifaspredial.delete_tarifaspredial')->middleware('checkdb');

//TasasInteresController
Route::get('registro_tasasinteres/{id}', [TasasInteresController::class, 'create'])->middleware('checkdb');
Route::get('registro_tasasinteres', [ErrorRequestController::class, 'error_request'])->middleware('checkdb');

Route::post('create_tasasinteres', [TasasInteresController::class, 'store'])->name('tasasinteres.create_tasasinteres')->middleware('checkdb');
Route::post('update_tasasinteres', [TasasInteresController::class, 'update'])->name('tasasinteres.update_tasasinteres')->middleware('checkdb');
Route::post('delete_tasasinteres', [TasasInteresController::class, 'destroy'])->name('tasasinteres.delete_tasasinteres')->middleware('checkdb');

//TiposPredioController
Route::get('registro_tipospredio/{id}', [TiposPredioController::class, 'create'])->middleware('checkdb');
Route::get('registro_tipospredio', [ErrorRequestController::class, 'error_request'])->middleware('checkdb');

Route::post('create_tipospredio', [TiposPredioController::class, 'store'])->name('tipospredio.create_tipospredio')->middleware('checkdb');
Route::post('update_tipospredio', [TiposPredioController::class, 'update'])->name('tipospredio.update_tipospredio')->middleware('checkdb');
Route::post('delete_tipospredio', [TiposPredioController::class, 'destroy'])->name('tipospredio.delete_tipospredio')->middleware('checkdb');

//ResolucionesIgacController
Route::get('registro_resolucion_igac/{id}', [ResolucionesIgacController::class, 'create'])->middleware('checkdb');
Route::get('registro_resolucion_igac', [ErrorRequestController::class, 'error_request'])->middleware('checkdb');

Route::post('create_resolucion_igac', [ResolucionesIgacController::class, 'store'])->name('resoluciones_igac.create_resolucion_igac')->middleware('checkdb');
Route::post('update_resolucion_igac', [ResolucionesIgacController::class, 'update'])->name('resoluciones_igac.update_resolucion_igac')->middleware('checkdb');
Route::post('delete_resolucion_igac', [ResolucionesIgacController::class, 'destroy'])->name('resoluciones_igac.delete_resolucion_igac')->middleware('checkdb');

//UploadController
Route::post('uploadFileAsobancaria', [UploadController::class, 'uploadFileAsobancaria'])->name('upload-file-asobancaria');
Route::post('uploadFileResolucion', [UploadController::class, 'uploadFileResolucion'])->name('upload-file-resolucion');
Route::post('uploadFileResolucionList', [UploadController::class, 'uploadFileResolucionList'])->name('upload-file-resolucion-list');

//DownloadController
Route::get('/downloadFileResolucion/{filename}', [DownloadController::class, 'downloadFileResolucion'])->name('download-file-resolucion');
Route::get('/downloadFileAcuerdo/{filename}', [DownloadController::class, 'downloadFileAcuerdo'])->name('download-file-acuerdo');

