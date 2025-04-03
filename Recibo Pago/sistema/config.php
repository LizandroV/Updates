<?php
//error_reporting(E_ALL);
define('SERVER_MYSQL', '192.168.1.207');
define('DATABASE',   'ENTERPRISETEXTIL');
define('BD_USUARIO', 'lizandro');
define('BD_CLAVE',   'Fir25?de');
$xtbl = 'ENTERPRISETEXTIL_';
$_SESSION['tbl'] = 'ENTERPRISETEXTIL_';
$TIPOmodulos = "123";
$igvnum1 = '1.18';

$MODULOcreditolibre = 'Si'; // INCLUIR opcion credito (saldra delsde cotizacion)-  post facturacion solo sirve para servicios   Si / No
$mostarCOMISION = 'No';
$req = "<font color=red>(*)</font>";
$conexion = db_data(SERVER_MYSQL, BD_USUARIO, BD_CLAVE, DATABASE);
function db_data($server, $user, $password, $database = DATABASE, $link = 'link_db')
{
    global $$link;
    $connectionInfo = array(
        "UID" => $user,
        "PWD" => $password,
        "Database" => $database
    );
    $$link = sqlsrv_connect($server, $connectionInfo);
    // print_r($connectionInfo);

    if (!$$link) {
        //print_r($$link);
        die('Estamos en mantenimiento para brindar un mejor servicio (BD)');
    }
    if ($$link) {
        return $$link;
    }
}

function db_query($query, $link = 'link_db')
{
    global $$link;
    $result = sqlsrv_query($$link, $query);
    return $result;
}
function db_fetch_array($query)
{
    return sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
}
function db_fetch_all($query)
{
    $result = db_query($query);

    if ($result === false) {
        die("Error en la consulta SQL: " . print_r(sqlsrv_errors(), true));
    }

    $data = [];

    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
        foreach ($row as $key => $value) {
            if (is_string($value)) {
                // Detectar la codificación original
                $encoding = mb_detect_encoding($value, ["UTF-8", "ISO-8859-1", "Windows-1252"], true);

                // Convertir a UTF-8 si es necesario
                if ($encoding && $encoding !== "UTF-8") {
                    $row[$key] = mb_convert_encoding($value, "UTF-8", $encoding);
                }
            }
        }
        $data[] = $row;
    }

    sqlsrv_free_stmt($result);
    return $data;
}
function db_exec_sp($query, $params = array(), $database = 'DESARROLLO', $link = 'link_db')
{
    global $$link;

    // Cambiar la base de datos
    sqlsrv_query($$link, "USE $database;");

    $stmt = sqlsrv_prepare($$link, $query, $params);

    if (!$stmt) {
        return "Error al preparar la consulta: " . print_r(sqlsrv_errors(), true);
    }

    $result = sqlsrv_execute($stmt);

    if (!$result) {
        return "Error en la ejecución: " . print_r(sqlsrv_errors(), true);
    }

    return "Procedimiento ejecutado correctamente.";
}

function db_num_rows($query)
{
    return sqlsrv_num_rows($query);
}
function db_close()
{
    global $$link;
    return sqlsrv_close($$link);
}
function db_error()
{
    return sqlsrv_errors();
}
