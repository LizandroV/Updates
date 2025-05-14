<?php
// error_reporting(E_ALL);
class cajaChica
{

    var $info_cc_s;
    var $texto;
    var $empresa;

    function insert_recibo_entrega($info_cc_s)
    {

        $this->info_cc_s = $info_cc_s;
        $comentario = mb_convert_encoding($info_cc_s['comentario'], 'ISO-8859-1', 'UTF-8');

        $insert_cc_s = "INSERT INTO ree.CAJACHICA_SAL (
            cod_resp, CodMon, Monto, CodEmp, cod_categ, cod_concepto, comentario, usureg, FecReg, Estado
        ) VALUES (
            '" . $info_cc_s['cod_resp'] . "',
            '" . $info_cc_s['CodMon'] . "',
            '" . $info_cc_s['Monto'] . "',
            '" . $info_cc_s['CodEmp'] . "',
            '" . $info_cc_s['cod_categ'] . "',
            '" . $info_cc_s['cod_concepto'] . "',
            '" . $comentario . "',
            '" . $info_cc_s['usureg'] . "',
            GETDATE(),
            'I'
        )";
        $_SESSION['dbmssql']->query($insert_cc_s);
        echo $insert_cc_s;
    }

    function busqueda_movimiento($texto)
    {
        $this->texto = $texto;
    }

    function convertir_fecha($fecha_datetime)
    {
        // Convertir el mes de texto a su formato numérico (Apr -> 04)
        $meses = array(
            "Jan" => "01",
            "Feb" => "02",
            "Mar" => "03",
            "Apr" => "04",
            "May" => "05",
            "Jun" => "06",
            "Jul" => "07",
            "Aug" => "08",
            "Sep" => "09",
            "Oct" => "10",
            "Nov" => "11",
            "Dec" => "12"
        );

        // Dividir la fecha
        $fecha_partes = explode(" ", trim($fecha_datetime));

        // Obtener los valores del mes y día
        $mes_num = $meses[$fecha_partes[0]]; // Ejemplo: "Apr" -> "04"
        $dia = str_pad($fecha_partes[1], 2, "0", STR_PAD_LEFT); // Asegurar dos dígitos

        // Obtener el año, hora y minutos
        $anio = $fecha_partes[2];
        $hora_minuto_segundos = substr($fecha_partes[3], 0, 8); // "04:01:21"
        $periodo = substr($fecha_partes[3], 8); // "PM" o "AM"

        // Crear una fecha en formato "Y-m-d H:i:s" para ser compatible con DateTime
        $fecha_completa = "$anio-$mes_num-$dia $hora_minuto_segundos $periodo";

        // Crear el objeto DateTime
        $date = DateTime::createFromFormat('Y-m-d h:i:s A', $fecha_completa);

        // Verificar si la fecha es válida
        if ($date instanceof DateTime) {
            return $date->format('d/m/Y h:i A'); // Ejemplo: "22/04/2025 04:01 PM"
        } else {
            return 'Fecha inválida';
        }
    }

    function filtar_movimiento($filtro)
    {
        $this->texto     = $filtro;
    }

    // 🔥 CRÍTICO: FALTA AGREGAR FECHAS
    function ver_movimientos($empresa)
    {
        echo '<table width="100%" border="0" cellpadding="0"  cellspacing="0" id="playlist">';

        $sql_ccs   = " SELECT 
                            CCS.FecReg,
                            B.Nombre,
                            CAT.Nombre AS Categoria,
                            CON.Descripcion AS Concepto,
                            M.SimMon,
                            CCS.Monto,
                            CCS.cod_cc_sal AS Codigo,
                            'SALIDA' AS Tipo
                        FROM REE.CAJACHICA_SAL CCS
                        LEFT JOIN REE.REE_RESPONSABLES B ON CCS.cod_resp = B.cod_resp
                        LEFT JOIN MONEDA M ON CCS.CodMon = M.CodMon
                        LEFT JOIN REE.REE_CATEGORIAS CAT ON CCS.cod_categ = CAT.cod_categ
                        LEFT JOIN REE.REE_CONCEPTOS CON ON CCS.cod_concepto = CON.cod_concepto
                        WHERE CCS.Estado != 'C' AND CCS.CodEmp = $empresa AND B.Nombre LIKE '%$this->texto%' 
                        OR  CCS.Estado != 'C' AND CCS.CodEmp = $empresa AND CON.Descripcion LIKE '%$this->texto%'

                        UNION ALL

                        SELECT 
                            CCI.FecReg,
                            CAST('' AS VARCHAR(100)) AS Nombre,
                            CAST('INGRESO A CAJA CHICA' AS VARCHAR(100)) AS Categoria,
                            CON.Descripcion AS Concepto,
                            M.SimMon,
                            CCI.Monto,
                            CCI.cod_cc_ing AS Codigo,
                            'INGRESO' AS Tipo
                        FROM REE.CAJACHICA_ING CCI
                        LEFT JOIN MONEDA M ON CCI.CodMon = M.CodMon
                        LEFT JOIN REE.REE_CONCEPTOS CON ON CCI.cod_concepto = CON.cod_concepto
                        WHERE CCI.Estado != 'C' AND CCI.CodEmp = $empresa  AND CON.Descripcion LIKE '%$this->texto%'

                        ORDER BY FecReg DESC; ";

        $query_ccs = $_SESSION['dbmssql']->getAll($sql_ccs);
        foreach ($query_ccs as $id_mov => $all_mov) {
            $fecha = $all_mov['FecReg'];
            $fecha_formateada = $this->convertir_fecha($fecha);
            $nombre = utf8_encode($all_mov['Nombre']);
            $categoria = utf8_encode($all_mov['Categoria']);
            $concepto = utf8_encode($all_mov['Concepto']);
            $moneda = $all_mov['SimMon'];
            $monto = $all_mov['Monto'];
            $codigo = $all_mov['Codigo'];
            $tipo = $all_mov['Tipo'];


            if (trim($tipo) == "SALIDA") {
                $monto_mov = "<td width='80' style='font-size:11px; color: red; font-weight: bold;' align='center' valign='middle'>- $moneda $monto</td>";
                $ver = "<a style='cursor: pointer;' onClick='verRecibo($codigo, 1)'><img src='images/template.gif' title='Ver' width='20' height='20' align='absmiddle' border='0'></a>";
                $rendicion = "<a style='cursor: pointer;' onClick='rendicionRecibo($codigo)'><img src='images/docs.png' title='Rendición' width='18' height='14' align='absmiddle' border='0'></a>";
                $eliminar = "<a style='cursor: pointer;' onClick='cancelarRecibo($codigo, 1)'><img src='images/publish_x.png' title='Eliminar' width='13' height='12' align='absmiddle' border='0'></a>";
            } else {
                $monto_mov = "<td width='80' style='font-size:11px; color: green; font-weight: bold;' align='center' valign='middle'>+ $moneda $monto</td>";
                $ver = "";
                $rendicion = "";
                $eliminar = "";
            }
?>
            <tr onMouseOver="color1(this,'#dee7ec');" onMouseOut="color2(this,'#ffffff');">
                <!-- <td width="10" height="19" align="center" valign="middle"><img src="images/b_plus.png" width="9" height="9" /></td> -->
                <td width="100" style="font-size:10px;" valign="middle"><?= $fecha_formateada ?></td>
                <td width="150" style="font-size:10px;" align="center" valign="middle"><?= $nombre ?></td>
                <td width="130" style="font-size:10px;" align="center" valign="middle"><?= $categoria ?></td>
                <td width="150" style="font-size:10px;" align="center" valign="middle"><?= $concepto ?></td>
                <?= $monto_mov ?>
                <td width="110" align="center" valign="middle">
                    <?= $ver ?>
                    <?= $rendicion ?>
                    <?= $eliminar ?>
                </td>
            </tr>


<?php }
        echo '</table>';
    }

    function cancelarRecibo($idRecibo, $tipo)
    {
        if ($tipo == 1) {
            $cancelarRecibo = "UPDATE REE.CAJACHICA_SAL SET Estado = 'C' WHERE cod_cc_sal = $idRecibo and Estado = 'I'";
            $_SESSION['dbmssql']->query($cancelarRecibo);
        }
    }

    function actualizarRecibo($info_cc_s)
    {
        $this->info_cc_s = $info_cc_s;
        $comentario = mb_convert_encoding($info_cc_s['comentario'], 'ISO-8859-1', 'UTF-8');
        $update_cc_s = "UPDATE REE.CAJACHICA_SAL
                        SET
                            cod_resp     = '" . $info_cc_s['cod_resp'] . "',
                            CodMon       = '" . $info_cc_s['CodMon'] . "',
                            Monto        = '" . $info_cc_s['Monto'] . "',
                            cod_categ    = '" . $info_cc_s['cod_categ'] . "',
                            cod_concepto = '" . $info_cc_s['cod_concepto'] . "',
                            comentario   = '" . $comentario . "',
                            usumod       = '" . $info_cc_s['usureg'] . "',
                            FecMod       = GETDATE()
                        WHERE
                            Estado = 'I'
                            AND CodEmp = '" . $info_cc_s['CodEmp'] . "'
                            AND cod_cc_sal = '" . $info_cc_s['cod_ccs'] . "';
                            ";
        $_SESSION['dbmssql']->query($update_cc_s);
    }
}

?>