<?php
set_time_limit(60 * 60);
define("DB_SERVER", "localhost");
define("DB_USERNAME", "erpsofts_predial");
define("DB_PASSWORD", "predial123.");
define("DB_PORT", "1433");
define("DB_DATABASE", "erpsofts_predial");

try
{
    $conn = new PDO("sqlsrv:Server=" . DB_SERVER . "," . DB_PORT . ";Database=" . DB_DATABASE, DB_USERNAME, DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sth = $conn->prepare("SET NOCOUNT ON; EXEC SP_CALCULO_PREDIAL_BATCH ?,?,?,?;");
    $anio = $argv[1];
    $predio_inicial = $argv[2];
    $predio_final = $argv[3];
    $id_usuario = $argv[4];
    $sth->bindParam(1, $anio);
    $sth->bindParam(2, $predio_inicial);
    $sth->bindParam(3, $predio_final);
    $sth->bindParam(4, $id_usuario);
    $sth->execute();
    $sth->nextRowset();

    $conn = null;
}
catch(PDOException $e)
{
    echo $e->getMessage();
}
?>
