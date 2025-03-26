<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "config.php";
$bd="DESARROLLO";

header('Content-Type: application/json');
$notificaciones = [];
$contador = 0;

$sql = "SELECT cod_notificacion, descrip, 
               convert(varchar,fecha_doc,103) AS fecha, 
               CONVERT(varchar(5), fecha_doc,108) AS hora, 
               tipo_doc, numero_doc, cod_ordserv, estado 
        FROM ".$bd.".dbo.notificacion_vigilancia order by cod_notificacion desc";
$sq0 = db_query($sql);

if (!$sq0) {
    echo json_encode(["error" => "Error en la consulta SQL"]);
    exit;
}

while ($rows = db_fetch_array($sq0)) {
    $contador++;
    $notificaciones[] = [
        "cod_notificacion" => $rows['cod_notificacion'],
        "descrip" => str_replace('?', '°', utf8_decode($rows['descrip'])),
        "fecha" => $rows['fecha'],
        "hora" => $rows['hora'],
        "tipo_doc" => $rows['tipo_doc'],
        "numero_doc" => $rows['numero_doc'],
        "cod_ord" => $rows['cod_ordserv'],
        "estado" => $rows['estado']
    ];
}

echo json_encode(["contador" => $contador, "notificaciones" => $notificaciones]);
?>
