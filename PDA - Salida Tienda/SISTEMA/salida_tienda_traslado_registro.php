<?php
include "config.php";

class IngresoTrasladoTienda
{

    var $info_tienda;

    function insertar_ingreso_traslado_tienda($info_tienda)
    {
        $bd = "DESARROLLO";
        $this->info_tienda = $info_tienda;

        //////////// OBTENIENDO NUEVO CODIGO DE SALIDA TIENDA
        $sql_codsal_tda = "select isnull(max(codsal_tienda),0) as codUltimo from $bd.alm.cab_salidas_tienda ";
        $dsl_codsal_tda = db_fetch_all($sql_codsal_tda);
        $codsal_tienda = $dsl_codsal_tda[0]['codUltimo'];
        // echo "$codsal_tienda<br><br>";
        //////////// OBTENIENDO NUEVO CODIGO DE INGRESO TIENDA
        $sql_coding_tda = "select max(coding_tda)as codUltimo from $bd.alm.cab_ingresos_tienda ";
        $dsl_coding_tda = db_fetch_all($sql_coding_tda);
        foreach ($dsl_coding_tda as $valor => $a) {
            $result = $a['codUltimo'];
        }

        if (is_null($result)) {
            $coding_tienda = 1;
        } else {
            $coding_tienda = $result + 1;
        }

        $codcategoria = 3; // 3 ES PRODUCTOS TERMINADOS
        $codtipo_almacen = 3; // 3 ES TELA ACABADA
        $codtipo_movimiento = 1; // 1 ES INGRESO DE ALMACEN

        $fecha_tienda = "";
        $hora_t = date('h:i:s A');
        $fecha_tienda = trim($this->info_tienda['fecha_ing']) . 'T' . $hora_t;

        ////INSERTANDO LA CABECERA INGRESO TIENDA
        $sql_insertar = "insert into $bd.alm.cab_ingresos_tienda(coding_tda, codemp, obs, 
			codcategoria, codalmacen, codtipoalmacen, codtipomov, total_rollos, 
			total_pesokg, usureg, fechareg, estado, cod_traslado, codsal_tienda)
			values(
                " . $coding_tienda . ",
                " . trim($this->info_tienda['codemp_origen']) . ",
                '" . trim($this->info_tienda['obs_ing']) . "',
                " . $codcategoria . ",
                " . trim($this->info_tienda['codalmacen_origen']) . ",
                " . $codtipo_almacen . ",
                " . $codtipo_movimiento . ",
                " . trim($this->info_tienda['total_rollos']) . ",
                " . trim($this->info_tienda['total_peso']) . ",
                " . trim($this->info_tienda['usuario']) . ",
                '" . $fecha_tienda . "',
                'I', 
                " . trim($this->info_tienda['codtraslado']) . ",
                " . $codsal_tienda . " )";

        $resultadoCIT = db_query($sql_insertar);
        // echo "<br> -------------TRASLADO-----------";
        // echo "<br> $sql_insertar";
        /////////// REGISTRANDO DETALLE
        $matriz = explode(",", trim($this->info_tienda['arreglo']));
        $grupos = count($matriz) / 16;
        $arr = array_chunk($matriz,  count($matriz) / $grupos);
        foreach ($arr as $valor => $det) {
            $cdgart       = $det[0];
            $codbarra    = $det[1];
            $partida    = $det[2];
            $desprod    = $det[3];
            $proceso     = $det[4];
            $descolor     = $det[5];
            $rollos     = $det[6];
            $peso         = $det[7];
            $codemp     = $det[8];
            $codalmacen = $det[9];
            $cdgcolor     = $det[10];
            $grem1         = $det[11];
            //$coddet_ingtda= $det[12];
            //$coding_tda = $det[13];
            $numordped     = $det[14];
            $numot         = $det[15];

            $insert_det_ingreso_tienda = "";
            $insert_det_ingreso_tienda = " insert into $bd.alm.det_ingresos_tienda(coding_tda, voucher, descrip, 
				descolor, proceso, correl, cant_rollos, kneto_recibido, cdgcolor, kbruto, cdgart, 
				numordped, numot, orden, grem, liqacab, usureg, fechareg, estado, codsal_tienda) 
				values(
				" . $coding_tienda . ", 
				'" . $partida . "', 
				'" . $desprod . "',
				'" . $descolor . "',
				'" . $proceso . "',
				'" . $codbarra . "',
				" . $rollos . ",
				" . $peso . ",
				" . $cdgcolor . ",
				'0.00',
				'" . $cdgart . "',
				'" . $numordped . "',
				'" . $numot . "',
				'V',
				'" . $grem1 . "',
				'0',
				" . trim($this->info_tienda['usuario']) . ",
				'" . $fecha_tienda . "',
				'I',
				" . $codsal_tienda . " ) ";
            $resultadoDIT = db_query($insert_det_ingreso_tienda);
            // echo "<br><br> $insert_det_ingreso_tienda";
        }
        if ($resultadoCIT === false || $resultadoDIT === false) {
            echo "ERROR AL REGISTRAR";
        } else {
            echo "REGISTRADO";
        }
    }
}/*fin de la clase*/
