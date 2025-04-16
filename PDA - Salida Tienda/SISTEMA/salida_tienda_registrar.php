<?php
include "config.php";

class SalidaTienda
{

    var $info_control;
    var $cod_user;

    function insertar_salida_tienda($info_control)
    {
        $bd = "DESARROLLO";
        $this->info_control = $info_control;

        //////////// OBTENIENDO NUEVO CODIGO DE SALIDA HILO
        $sql_NuevoCod = " select max(codsal_tienda)as codUltimo from $bd.alm.cab_salidas_tienda ";
        $dsl_NuevoCod = db_fetch_all($sql_NuevoCod);
        foreach ($dsl_NuevoCod as $valor => $a) {
            $result = $a['codUltimo'];
        }

        if (is_null($result)) {
            $codsal_tienda = 1;
        } else {
            $codsal_tienda = $result + 1;
        }

        $codtipomov = "2"; // 1 ES INGRESO DE ALMACEN , 2 ES SALIDA DE ALMACEN
        $fecha_tienda = "";
        $hora_t = date('h:i:s A');
        $fecha_tienda = trim($this->info_control['fecha_tienda']) . 'T' . $hora_t;

        ///////////// REGISTRANDO CABECERA SALIDA HILO ////////////
        $insert_cab_salida = "insert into $bd.alm.cab_salidas_tienda(codsal_tienda, codalmacen_origen, 
			codalmacen_destino, codemp_origen, codemp_destino, cod_traslado, obs, 
			total_rollos, total_pesokg, codtipomov, usureg, fechareg, estado) 
			values( 
			" . $codsal_tienda . ", 
			" . trim($this->info_control['codalmacen_origen']) . ", 
			" . trim($this->info_control['codalmacen_destino']) . ", 
			" . trim($this->info_control['codemp_origen']) . ", 
			" . trim($this->info_control['codemp_destino']) . ", 
			" . trim($this->info_control['codtraslado']) . ", 
			'" . trim($this->info_control['obs']) . "', 
			" . trim($this->info_control['total_rollos']) . ", 
			" . trim($this->info_control['total_kg']) . ", 
			" . $codtipomov . ", 
			" . trim($this->info_control['usuario']) . ", 
			'" . $fecha_tienda . "', 
			'I' )";

        db_query($insert_cab_salida);
        // echo $insert_cab_salida;
        //Inserción del detalle
        $columnas = 16;
        $matriz = explode(",", trim($this->info_control['arreglo']));
        $grupos = count($matriz) / $columnas;
        $arr = array_chunk($matriz,  count($matriz) / $grupos);
        foreach ($arr as $valor => $det) {
            $cdgart         = $det[0];
            $codbarra         = $det[1];
            $partida         = $det[2];
            $desprod         = $det[3];
            $proceso         = $det[4];
            $descolor         = $det[5];
            $rollos         = $det[6];
            $peso             = $det[7];
            $codemp         = $det[8];
            $codalmacen     = $det[9];
            $cdgcolor          = $det[10];
            $grem1             = $det[11];
            $coddet_ingtda  = $det[12];
            $coding_tda     = $det[13];
            $numordped         = $det[14];
            $numot             = $det[15];

            /////// REGISTRANDO DETALLE SALIDA TIENDA ///////////////
            $insert_det_salida = "";
            $insert_det_salida = " insert into $bd.alm.det_salidas_tienda(codsal_tienda, voucher, 
				descrip, descolor, proceso, correl, cant_rollos_salida, kneto_salida, cdgcolor, 
				cdgart, numordped, numot, orden, grem, codalmacen_origen, 
				estado, coddet_ingtda, coding_tda, usureg, fechareg) 
				values( 
				" . $codsal_tienda . ", 
				'" . $partida . "', 
				'" . $desprod . "', 
				'" . $descolor . "', 
				'" . $proceso . "', 
				'" . $codbarra . "', 
				" . $rollos . ", 
				" . $peso . ", 
				'" . $cdgcolor . "', 
				'" . $cdgart . "',
				'" . $numordped . "',
				'" . $numot . "',
				'V',
				'" . $grem1 . "',
				" . $codalmacen . ",
				'I',
				" . $coddet_ingtda . ",
				" . $coding_tda . ",
				" . trim($this->info_control['usuario']) . ", 
				'" . $fecha_tienda . "' ) ";

            // echo $insert_det_salida;

            db_query($insert_det_salida);

            $sql_revision = "select det.voucher, det.descrip, det.liqacab, det.correl, 
			sum(det.kneto_recibido) as peso_recibido, 
			
			(sum(det.kneto_recibido) - 
			
			isnull((select sum(isnull(dp.kneto,0)) from $bd.DES.PLIST_DET_TIENDA dp  
			inner join $bd.des.PLIST_CAB_TIENDA cp on dp.CodPL=cp.CodPL 
			where dp.voucher=det.voucher and dp.cdgcolor=det.cdgcolor and dp.cdgart=det.cdgart and dp.correl=det.correl 
			and cp.codalmacen_origen=cab.codalmacen and cp.codemp_origen=cab.codemp 
			and dp.coding_tda=det.coding_tda and dp.coddet_ingtda=det.coddet_ingtda 
			and cp.EstadoGeneral<>'1'),0) - 
			
			isnull((select sum(isnull(dst.kneto_salida,0)) from $bd.alm.det_salidas_tienda dst  
			inner join $bd.alm.cab_salidas_tienda cst on dst.codsal_tienda=cst.codsal_tienda 
			where dst.voucher=det.voucher and dst.cdgcolor=det.cdgcolor and dst.cdgart=det.cdgart and dst.correl=det.correl 
			and cst.codalmacen_origen=cab.codalmacen and cst.codemp_origen=cab.codemp 
			and dst.coding_tda=det.coding_tda and dst.coddet_ingtda=det.coddet_ingtda 
			and dst.estado<>'C' and cst.Estado<>'C'),0) + 
			
			isnull((select sum(isnull(detdev.kneto,0)) from $bd.alm.det_devolucion_tienda detdev 
			inner join $bd.alm.cab_devolucion_tienda cabdev 
			on detdev.coddevol_tda=cabdev.coddevol_tda 
			where detdev.voucher=det.voucher and detdev.cdgcolor=det.cdgcolor and 
			detdev.cdgart=det.cdgart and detdev.correl=det.correl and 
			cabdev.codalmacen_destino=cab.codalmacen and cabdev.codemp_destino=cab.codemp 
			and detdev.coding_tda=det.coding_tda and detdev.coddet_ingtda=det.coddet_ingtda 
			and detdev.estado<>'C' and cabdev.Estado<>'C'),0) )as peso_stock
			
			from $bd.alm.det_ingresos_tienda det left join $bd.alm.cab_ingresos_tienda cab on 
			det.coding_tda=cab.coding_tda 
			where cab.estado<>'C' and det.estado<>'C' and det.liqacab=0 and det.voucher='" . $partida . "' 
			and det.correl='" . $codbarra . "' and det.cdgcolor='" . $cdgcolor . "' and det.cdgart='" . $cdgart . "'
			and cab.codalmacen=" . trim($this->info_control['codalmacen_origen']) . " 
			and cab.codemp=" . trim($this->info_control['codemp_origen']) . " 
			and det.coding_tda=" . $coding_tda . " and det.coddet_ingtda=" . $coddet_ingtda . " 
			
			group by det.voucher, det.descrip, det.liqacab, det.correl, 
			det.cdgcolor, det.cdgart, cab.codalmacen, cab.codemp, 
			det.coding_tda, det.coddet_ingtda 
			
			order by det.voucher, det.correl";

            // echo $sql_revision;

            $dsl_revision = db_fetch_all($sql_revision);
            foreach ($dsl_revision as $valor => $val) {
                $peso_stock = trim($val['peso_stock']);
                if ($peso_stock <= "0") {
                    // ACTUALIZAR ESTADO DE LIQACAB DE LA TABLA INGRESOS 
                    // PARA QUE NO SE MUESTRE EN LA BUSQUEDA DE ROLLOS 
                    $sql_tda = " UPDATE DET 
					SET DET.LIQACAB='1' 
					FROM $bd.alm.det_ingresos_tienda DET INNER JOIN $bd.alm.cab_ingresos_tienda CAB 
					ON DET.coding_tda=CAB.coding_tda 
					where CAB.estado<>'C' and DET.estado<>'C' 
						and CAB.codalmacen=" . trim($this->info_control['codalmacen_origen']) . " 
						and CAB.codemp=" . trim($this->info_control['codemp_origen']) . " 
						and DET.voucher='" . $partida . "'
						and DET.correl='" . $codbarra . "' and DET.cdgcolor='" . $cdgcolor . "'
						and DET.cdgart='" . $cdgart . "' ";
                    db_query($sql_tda);
                }
            }
        }
    }
}
