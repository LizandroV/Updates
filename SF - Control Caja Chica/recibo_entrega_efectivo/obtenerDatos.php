<?php
error_reporting(E_ALL);
session_start();
require('../../../includes/dbmssql_cfg.php');
require_once('consultarDniAPI.php');
header('Content-Type: text/html; charset=UTF-8');


// ✅ HECHO: VER SALDOS
if ($_REQUEST['versaldos']) {

    $empresa = $_POST['codemp'];

    echo "<table border-collapse: collapse;'><tr>";
    $sql_saldos   = " SELECT Mov.CodMon, M.NomMon, M.SimMon, SUM(Monto) AS Saldo
                        FROM (SELECT CodMon, Monto FROM ree.CAJACHICA_ING WHERE (CodEmp = $empresa) AND (Estado != 'C')
                            UNION ALL	
                            SELECT CodMon, (Monto*-1) FROM ree.CAJACHICA_SAL WHERE (CodEmp = $empresa) AND (Estado != 'C')) AS Mov 
                        LEFT OUTER JOIN MONEDA AS M ON Mov.CodMon = M.CodMon
                        GROUP BY Mov.CodMon, M.NomMon, M.SimMon
                        ORDER BY Mov.CodMon ";
    $query_saldos = $_SESSION['dbmssql']->getAll($sql_saldos);

    if (empty($query_saldos)) {
        echo "<td align='center' class='Estilo100' style='padding: 10px; font-size: 13px;'><strong>Sin Saldo Disponible</strong></td>";
    } else {
        foreach ($query_saldos as $id_mov => $res) {
            $CodMon = $res['CodMon'];
            $NomMon = $res['NomMon'];
            $SimMon = $res['SimMon'];
            $Saldo = $res['Saldo'];
            $Saldo = number_format($Saldo, 2);

            if ($CodMon == 2) {
                echo "<td align='center' style='padding: 10px; font-size: 13px;'><strong>DOLARES:</strong> $SimMon $Saldo</td>";
            } else {
                echo "<td align='center' style='padding: 10px; font-size: 13px;'><strong>$NomMon:</strong> $SimMon $Saldo</td>";
            }
        }
    }

    echo "</tr></table>";
}

if (isset($_POST['versaldounico'])) {
    $empresa = intval($_POST['codemp']);
    $codMon = intval($_POST['codmon']);

    $sql = "SELECT SUM(Monto) AS Saldo
        FROM (
            SELECT Monto
            FROM ree.CAJACHICA_ING
            WHERE CodMon = {$codMon} AND CodEmp = {$empresa}  AND Estado != 'C'

            UNION ALL

            SELECT (Monto*-1)
            FROM ree.CAJACHICA_SAL
            WHERE CodMon = {$codMon} AND CodEmp = {$empresa}  AND Estado != 'C'
        ) AS Movimientos ";

    $res = $_SESSION['dbmssql']->getAll($sql);
    echo floatval($res[0]['Saldo']);
    exit;
}

// ✅ HECHO: CONSULTAR DNI
if ($_REQUEST['consultarDni']) {
    $dni = $_POST['dni'];
    $tipo = $_POST['tipo'];

    $sql = "SELECT cod_resp, tipo_resp, Nombre, Estado FROM ree.REE_RESPONSABLES WHERE Dni = '$dni'";
    $res = $_SESSION['dbmssql']->getAll($sql);

    if ($res[0]['Estado'] == "C") {
        echo "ERROR: Este usuario esta Deshabilitado.";
    } else if (count($res) > 0 && !empty($res[0]['cod_resp']) && $res[0]['tipo_resp'] == $tipo) {
        echo $res[0]['cod_resp'] . '|' . utf8_encode($res[0]['Nombre']);
    } else if (count($res) > 0 && !empty($res[0]['cod_resp']) && $res[0]['tipo_resp'] != $tipo) {
        if ($tipo == 1) {
            echo "ERROR: El responsable ya existe como tipo Externo.";
        } else if ($tipo == 2) {
            echo "ERROR: El responsable ya existe como tipo Interno.";
        }
    } else {
        if ($tipo == 2) {
            $apiResponse = consultarDniReniec($dni);

            if ($apiResponse !== false) {
                $nombre = utf8_decode($apiResponse['nombreCompleto']);

                // Insertar nuevo responsable
                $insertSql = "INSERT INTO ree.REE_RESPONSABLES (Dni, tipo_resp, Nombre, Estado) 
                                VALUES ('$dni', $tipo, '$nombre', 'I')";

                $insert = $_SESSION['dbmssql']->query($insertSql);

                if ($insert) {
                    // Obtener el nuevo ID
                    $idSql = "SELECT cod_resp FROM ree.REE_RESPONSABLES WHERE Dni = '$dni'";
                    $nuevoResp = $_SESSION['dbmssql']->getAll($idSql);
                    echo $nuevoResp[0]['cod_resp'] . '|' . utf8_encode($nombre);
                } else {
                    echo "ERROR: No se pudo registrar al responsable.";
                }
            } else {
                echo "CREAR: No existe el usuario con DNI $dni en RENIEC.";
            }
        } else {
            echo "CREAR: No existe el usuario.";
        }
    }
}

// ✅ HECHO: CREAR NUEVO RESPOSNABLE
if ($_REQUEST['insertarNuevoDni']) {
    $tipo = $_POST['tipo'];
    $dni = $_POST['dni'];
    $nombre = $_POST['nombre'];

    $nombre = mb_convert_encoding($nombre, 'ISO-8859-1', 'UTF-8');

    $sqlVerificar = "SELECT cod_resp, Estado FROM ree.REE_RESPONSABLES WHERE Dni = '$dni'";
    $resVerif = $_SESSION['dbmssql']->getAll($sqlVerificar);

    if ($resVerif[0]['Estado'] == "C") {
        echo "ERROR: Este usuario esta Deshabilitado.";
        exit;
    } else if (!empty($resVerif[0]['cod_resp'])) {
        echo "ERROR: Este DNI ya está registrado.";
        exit;
    }

    $sqlInsert = "INSERT INTO ree.REE_RESPONSABLES (tipo_resp, Dni, Nombre, Estado) VALUES ('$tipo','$dni', '$nombre', 'I')";
    $result = $_SESSION['dbmssql']->query($sqlInsert);

    $sqlSelect = "SELECT cod_resp FROM ree.REE_RESPONSABLES WHERE tipo_resp = '$tipo' AND Dni = '$dni' AND Estado != 'C'";
    $res = $_SESSION['dbmssql']->getAll($sqlSelect);

    if (count($res) > 0 && !empty($res[0]['cod_resp'])) {
        echo $res[0]['cod_resp'] . '|' . utf8_encode($nombre);
    } else {
        echo "ERROR: No se pudo guardar el beneficiario.";
    }
}

// ✅ HECHO: GUARDAR CATEGORIA
if ($_REQUEST['guardarCategoria']) {
    $nombre = $_POST['nombre'];
    $nombre = mb_convert_encoding($nombre, 'ISO-8859-1', 'UTF-8');
    // Verificar duplicado
    $sql_verificar = "SELECT COUNT(*) AS existe FROM ree.REE_CATEGORIAS WHERE Nombre = '$nombre'";
    $verificar = $_SESSION['dbmssql']->getAll($sql_verificar);
    if ($verificar[0]['existe'] >= 1) {
        echo "ERROR: Ya existe la categoría.";
        exit;
    }

    // Insertar categoria
    $sql = "INSERT INTO ree.REE_CATEGORIAS (Nombre, Estado) VALUES ('$nombre', 'I')";
    $resultado = $_SESSION['dbmssql']->query($sql);
    $sql_id = "SELECT SCOPE_IDENTITY()";
    $id = $_SESSION['dbmssql']->getOne($sql_id);

    // Validar guardar categoria
    $sql_verificar = "SELECT COUNT(*) AS existe FROM ree.REE_CATEGORIAS WHERE Nombre = '$nombre'";
    $verificar = $_SESSION['dbmssql']->getAll($sql_verificar);

    if ($verificar[0]['existe'] == 0) {
        echo "ERROR: No se pudo guardar la categoría.";
        exit;
    }

    echo "$id|" . utf8_encode($nombre);
}

// ✅ HECHO: GUARDAR CONCEPTO
if ($_REQUEST['guardarConcepto']) {
    $descripcion = $_POST['descripcion'];
    $descripcion = mb_convert_encoding($descripcion, 'ISO-8859-1', 'UTF-8');
    // Verificar duplicado
    $sql_verificar = "SELECT COUNT(*) AS existe FROM ree.REE_CONCEPTOS WHERE Descripcion = '$descripcion'";
    $verificar = $_SESSION['dbmssql']->getAll($sql_verificar);
    if ($verificar[0]['existe'] >= 1) {
        echo "ERROR: Ya existe el concepto.";
        exit;
    }

    // Insertar concepto
    $sql = "INSERT INTO ree.REE_CONCEPTOS (Descripcion, Estado) VALUES ('$descripcion', 'I')";
    $resultado = $_SESSION['dbmssql']->query($sql);
    $sql_id = "SELECT SCOPE_IDENTITY()";
    $id = $_SESSION['dbmssql']->getOne($sql_id);

    // Validar guardar categoria
    $sql_verificar = "SELECT COUNT(*) AS existe FROM ree.REE_CONCEPTOS WHERE Descripcion = '$descripcion'";
    $verificar = $_SESSION['dbmssql']->getAll($sql_verificar);

    if ($verificar[0]['existe'] == 0) {
        echo "ERROR: No se pudo guardar el concepto.";
        exit;
    }

    echo "$id|" . utf8_encode($descripcion);
}
