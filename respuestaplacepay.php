<?php
// ===============================
// CONFIGURACIÓN
// ===============================
define('PLACETOPAY_SECRET_KEY', 'RX1Rg44g5u12Y2UM');

// ===============================
// SOLO POST
// ===============================
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

// ===============================
// LEER JSON CRUDO
// ===============================
$rawBody = file_get_contents('php://input');
$data = json_decode($rawBody, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    exit;
}

// ===============================
// EXTRAER CAMPOS
// ===============================
$statusBlock = $data['status'] ?? null;
$requestId   = $data['requestId'] ?? null;
$reference   = $data['reference'] ?? null;
$signature   = $data['signature'] ?? null;

if (!$statusBlock || !$requestId || !$signature) {
    http_response_code(400);
    exit;
}

// Campos internos de status
$status  = $statusBlock['status'] ?? '';
$date    = $statusBlock['date'] ?? '';

// ===============================
// LIMPIAR FIRMA
// ===============================
if (strpos($signature, 'sha256:') === 0) {
    $signature = substr($signature, 7);
}

// ===============================
// GENERAR FIRMA LOCAL
// Fórmula oficial:
// sha256(requestId + status.status + status.date + secretKey)
// ===============================
$stringToSign = $requestId . $status . $date . PLACETOPAY_SECRET_KEY;
$localSignature = hash('sha256', $stringToSign);

// ===============================
// VALIDAR FIRMA
// ===============================
if (!hash_equals($localSignature, $signature)) {
    http_response_code(401);
    exit;
}

// ===============================
// FIRMA VÁLIDA → PROCESAR
// ===============================

// Ejemplo de datos listos para guardar
$estadoFinal = $statusBlock['status'];
$codigoRazon = $statusBlock['reason'];
$mensaje     = $statusBlock['message'];
$fecha       = $statusBlock['date'];

// AQUÍ:
// - actualizar factura
// - marcar pago aprobado / rechazado
// - ejecutar stored procedure
// - registrar auditoría

// ===============================
// RESPUESTA OBLIGATORIA 200
// ===============================
http_response_code(200);
echo json_encode([
    'result' => 'OK'
]);
exit;
