<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    // 1. Ejecutamos el archivo cambio.sql para las tablas nuevas
    $sql = file_get_contents(__DIR__.'/../cambio.sql');
    DB::unprepared($sql);
    
    echo "<h2 style='color: green;'>¡Éxito!</h2>";
    echo "<p>Las actualizaciones de la base de datos (Acuerdos de Pago) se aplicaron correctamente.</p>";
    
    // Opcional: Mostrar las tablas como confirmación
    $tables = DB::select("SELECT name FROM sys.tables WHERE name LIKE '%acuerdos%'");
    echo "<h3>Tablas de acuerdos encontradas:</h3><ul>";
    foreach ($tables as $table) {
        echo "<li>" . $table->name . "</li>";
    }
    echo "</ul>";

} catch (\Exception $e) {
    echo "<h2 style='color: red;'>Error al ejecutar los cambios</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
}
