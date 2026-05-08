<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\Usuario::first();
if (!$user) {
    $user = new App\Models\Usuario();
}
$user->correo_electronico = 'administrador@erpsoftsas.com';
$user->password = md5('Password123!');
$user->id_tipo_usuario = 1; // Administrador
$user->save();

echo "\n========================================\n";
echo "CORREO DE ACCESO: " . $user->correo_electronico . "\n";
echo "CONTRASEÑA:       Password123!\n";
echo "Hashed (MD5):     " . $user->password . "\n";
echo "URL:              http://localhost:8000\n";
echo "========================================\n";
