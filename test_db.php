<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$rows = DB::table('predios_acuerdos_pago_detalle')
    ->select('id', 'id_acuerdo', 'factura_pago', 'cuota_numero', 'valor_cuota', 'estado', 'pagado')
    ->whereNotNull('factura_pago')
    ->get();

echo json_encode($rows, JSON_PRETTY_PRINT);
