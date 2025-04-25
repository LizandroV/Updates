<?php
class NotaDebitoProforma
{

    var $info_oserv;

    function insert_nota_debito_cabecera_detalle_prof($info_oserv)
    {
        $this->info_oserv = $info_oserv;

        //codigoServicio
        $sql_NuevoCodNota = "select max(CodOrdNotaDeb)as codUltimo from CABNOTADEBITO_PROF   
                            where CodNotaNeg ='" . trim($this->info_oserv['codNegocio']) . "' ";
        $dsl_NuevoCodNota = $_SESSION['dbmssql']->getAll($sql_NuevoCodNota);
        foreach ($dsl_NuevoCodNota as $valor => $a) {
            $result = $a['codUltimo'];
        }

        if (is_null($result)) {
            $NewCodigo = 1;
        } else {
            $NewCodigo = $result + 1;
        }

        ///Calculando la Empresa
        $sql_EmpCod = "select	CodProfEmp 
                from CABORDPROF 
                where CodOrdProf='" . trim($this->info_oserv['order_factura']) . "' and 
                    CodProfNeg='" . trim($this->info_oserv['codNegocio']) . "'  ";
        $dsl_EmpCod = $_SESSION['dbmssql']->getAll($sql_EmpCod);
        foreach ($dsl_EmpCod as $valor => $ok) {
            $CodFacEmp = trim($ok['CodProfEmp']);
        }

        ///Calculando la Cliente
        $sql_CliCod = "select	c.CodProfCli, m.LetMon, c.CodProfServ 
                from CABORDPROF c INNER JOIN MONEDA m
                ON m.LetMon = c.TipMoneda
                where c.CodOrdProf='" . trim($this->info_oserv['order_factura']) . "' and 
                    c.CodProfNeg='" . trim($this->info_oserv['codNegocio']) . "'  ";
        $dsl_CliCod = $_SESSION['dbmssql']->getAll($sql_CliCod);
        foreach ($dsl_CliCod as $valor => $ok) {
            $CodFacCli  =   trim($ok['CodProfCli']);
            $Moneda     =   trim($ok['LetMon']);
            $CodFacServ =   trim($ok['CodProfServ']);
        }

        //Insertando en(cabecera)
        $sql_insert_cab_nota = " execute BF_Insertar_CabNotaDebitoProf " . $NewCodigo . ",
                                        " . trim($this->info_oserv['codNegocio']) . ",
                                        " . trim($this->info_oserv['order_factura']) . ",
                                        " . trim($CodFacEmp) . " ,
                                        " . trim($CodFacCli) . " ,
                                        '" . trim($this->info_oserv['dateInicio']) . "',
                                        " . trim($this->info_oserv['cod_motivo']) . ",
                                        " . trim($this->info_oserv['txt_subtotal']) . " ,
                                        " . trim($this->info_oserv['monto_total']) . ",
                                        " . trim($this->info_oserv['monto_al_cambio']) . ",																			
                                        " . trim($this->info_oserv['usuario']) . ",
                                        '" . trim($this->info_oserv['numero_nota']) . "',
                                        '" . strtoupper(trim($this->info_oserv['comentario'])) . "',
                                        '" . trim($this->info_oserv['tipo_almacen']) . "' ";
        $_SESSION['dbmssql']->query($sql_insert_cab_nota);

        //Insercion del detalle
        $matriz = explode(",", trim($this->info_oserv['arreglo']));
        $grupos = count($matriz) / 6;
        $arr = array_chunk($matriz,  count($matriz) / $grupos);
        foreach ($arr as $valor => $especifico) {
            $txt_cantidad       =    $especifico[0];
            $txt_und1           =    $especifico[1];
            $txt_descripcion    =    $especifico[2];
            $txt_punitario      =    $especifico[3];
            $txt_costo          =    $especifico[4];
            $cod                =    $especifico[5];  //no se usa en insert

            $sql_insert_det_servicio = "";
            $sql_insert_det_servicio = " execute BF_Insertar_DetNotaDebitoProf 
                                        " . $NewCodigo . ",
                                        " . trim($this->info_oserv['codNegocio']) . ",
                                        " . trim($this->info_oserv['order_factura']) . ",
                                        " . $txt_cantidad . ",
                                        " . $txt_und1 . ",
                                        '" . strtoupper(trim($txt_descripcion)) . "',
                                        " . $txt_punitario . ",
                                        " . $txt_costo . ",
                                        " . trim($this->info_oserv['usuario']) . " ";
            $_SESSION['dbmssql']->query($sql_insert_det_servicio);
            // echo $sql_insert_det_servicio . " <br>";
        }
    }

    function update_nota_debito_cabecera_detalle_prof($info_oserv)
    {
        $this->info_oserv = $info_oserv;
        $sql_EmpCod = "select	C.CodNotaEmp, C.CodNotaCli from CABNOTADEBITO_PROF C 
            where C.CodOrdNotaDeb='" . trim($this->info_oserv['codigoNota']) . "' and 
                C.CodNotaNeg='" . trim($this->info_oserv['codNegocio']) . "' ";
        $dsl_EmpCod = $_SESSION['dbmssql']->getAll($sql_EmpCod);
        foreach ($dsl_EmpCod as $valor => $ok) {
            $CodNotaEmp = trim($ok['CodNotaEmp']);
            $CodNotaCli = trim($ok['CodNotaCli']);
        }

        $sql_update_cab_nota = " execute BF_Actualizar_CabNotaDebito_Prof 
                                " . trim($this->info_oserv['codigoNota']) . ",
                                " . trim($this->info_oserv['codNegocio']) . ",
                                " . trim($CodNotaEmp) . ",
                                " . trim($CodNotaCli) . ",
                                '" . trim($this->info_oserv['dateInicio']) . "',
                                " . trim($this->info_oserv['cod_motivo']) . ",
                                " . trim($this->info_oserv['txt_subtotal']) . ",
                                " . trim($this->info_oserv['monto_total']) . ",
                                " . trim($this->info_oserv['monto_al_cambio']) . ",																			
                                " . trim($this->info_oserv['usuario']) . ",
                                '" . strtoupper(trim($this->info_oserv['comentario'])) . "',
                                '" . trim($this->info_oserv['tipo_almacen']) . "' ";
        $_SESSION['dbmssql']->query($sql_update_cab_nota);

        //Insercion del detalle
        $matriz = explode(",", trim($this->info_oserv['arreglo']));
        $grupos = count($matriz) / 6;
        $arr = array_chunk($matriz,  count($matriz) / $grupos);
        foreach ($arr as $valor => $especifico) {
            $txt_cantidad        =    $especifico[0];
            $txt_und1            =    $especifico[1];
            $txt_descripcion    =    $especifico[2];
            $txt_punitario        =    $especifico[3];
            $txt_costo            =    $especifico[4];
            $cod_Det_Nota_Deb    =    $especifico[5];  // se usa en update

            $sql_insert_det_servicio = "";
            $sql_insert_det_servicio = " execute BF_Actualizar_DetNotaDebitoProf 
                                        " . $cod_Det_Nota_Deb . ",
                                        " . trim($this->info_oserv['codNegocio']) . ",
                                        " . $txt_cantidad . ",
                                        " . $txt_und1 . ",
                                        '" . strtoupper(trim($txt_descripcion)) . "',
                                        " . $txt_punitario . ",
                                        " . $txt_costo . ",
                                        " . trim($this->info_oserv['usuario']) . " ";
            $_SESSION['dbmssql']->query($sql_insert_det_servicio);
        }
    }
}
