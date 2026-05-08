<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    $tables = DB::select("SELECT name FROM sys.tables ORDER BY name");
} catch (\Exception $e) {
    die("Error de conexión: " . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Explorador de Base de Datos</title>
    <style>
        body { font-family: sans-serif; background: #f4f4f4; padding: 20px; }
        .container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .menu { margin-bottom: 20px; padding: 10px; background: #eee; }
        a { color: #0066cc; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Explorador de Tablas - Guateque</h1>
        
        <div class="menu">
            <strong>Tablas disponibles:</strong><br>
            <?php foreach ($tables as $table): ?>
                <a href="?tabla=<?php echo $table->name; ?>"><?php echo $table->name; ?></a> | 
            <?php endforeach; ?>
        </div>

        <?php
        if (isset($_GET['tabla'])) {
            $tableName = $_GET['tabla'];
            echo "<h2>Datos de la tabla: $tableName (Top 50)</h2>";
            try {
                $data = DB::select("SELECT TOP 50 * FROM $tableName");
                if (count($data) > 0) {
                    echo "<table><thead><tr>";
                    foreach (get_object_vars($data[0]) as $col => $val) {
                        echo "<th>$col</th>";
                    }
                    echo "</tr></thead><tbody>";
                    foreach ($data as $row) {
                        echo "<tr>";
                        foreach (get_object_vars($row) as $val) {
                            echo "<td>" . htmlspecialchars($val) . "</td>";
                        }
                        echo "</tr>";
                    }
                    echo "</tbody></table>";
                } else {
                    echo "<p>La tabla está vacía.</p>";
                }
            } catch (\Exception $e) {
                echo "<p style='color:red;'>Error al leer la tabla: " . $e->getMessage() . "</p>";
            }
        }
        ?>
    </div>
</body>
</html>
