<?php

class ReportOrdProforma
{

    private $negocio;
    private $fecha_ini;
    private $fecha_fin;
    private $tipo;
    private $pagina;
    private $pag;
    ////	private $gproforma;
    ////  $gproforma=0,

    function __construct($tipo, $negocio, $pagina, $codCliente, $codEmpresa, $codProf)
    {
        $this->negocio    = $negocio;
        $this->pagina    = $pagina;
        $this->tipo        = $tipo;
        //agregado
        $this->codCliente = $codCliente;
        $this->codEmpresa = $codEmpresa;
        $this->codProf     = $codProf;
    }

    public function list_con_fech($fech_ini, $fech_fi)
    {
        $this->fecha_ini = $fech_ini;
        $this->fecha_fin = $fech_fi;
        $pagina = $this->pagina;
        $tipo = $this->tipo;
        //$this->detallado($fech_ini,$fech_fi,$pagina);
        if ($tipo == 1)
            $this->proforma($fech_ini, $fech_fi, $pagina);
        else
	    	if ($tipo == 2)
            $this->detallado($fech_ini, $fech_fi);
        /*else
				if($tipo==3)
					$this->totalizado($fech_ini,$fech_fi);*/
    }

    public function list_tipo()
    {

        $tipo = $this->tipo;
        if ($tipo == 1)
            $this->proforma();
        else
	    	if ($tipo == 2)
            $this->detallado();
    }

    public function proforma($fe_ini = 0, $fe_fin = 0)
    {
?>
        <table width="100%" border="0" cellpadding="0" cellspacing="1" class="borderTabla">
            <tr>
                <td width="83" height="27" class="smaltext" align="center" valign="middle" background="images/bg_topbar.gif"><strong>TIPO DOC.</strong></td>
                <td width="123" class="smaltext" align="center" valign="middle" background="images/bg_topbar.gif"><strong>CODIGO</strong></td>
                <td width="287" class="smaltext" align="center" valign="middle" background="images/bg_topbar.gif"><strong>CLIENTE</strong></td>
                <td width="269" class="smaltext" align="center" valign="middle" background="images/bg_topbar.gif"><strong>EMPRESA</strong></td>
                <td width="106" class="smaltext" align="center" valign="middle" background="images/bg_topbar.gif"><strong>FECHA</strong></td>
                <td width="111" align="center" valign="middle" background="images/bg_topbar.gif" class="smaltext"><strong>IMPORTE</strong></td>
            </tr>
        </table>
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="borderTabla">
            <tr>
                <td width="50%" height="100%" valign="top">
                    <div id="listOrds" style="height:290;width:100%;overflow:auto">
                        <table width="100%" border="0" cellpadding="2" cellspacing="0">

                            <?php
                            $Profcod = $this->codProf;

                            $COD_NEGO = $this->negocio;
                            $COD_EMP =     $this->codEmpresa;
                            $COD_CLI =    $this->codCliente;

                            if ($fe_ini == 0) $fe_ini = '';
                            if ($fe_fin == 0) $fe_fin = '';

                            $suma = 0;
                            $sql_cabecera = "	select  n.negcne, n.negdes								
					from	negocio n
					where	n.negcod=" . $COD_NEGO . " ";
                            $dsl_cab = $_SESSION['dbmssql']->getAll($sql_cabecera);
                            foreach ($dsl_cab as $row => $fila) {
                                $negdes = $fila['negdes'];
                            }

                            $texto_sql = " execute dbo.BF_Exportar_Proforma_Detallado  '" . $COD_NEGO . "','" . $COD_CLI . "','" . $COD_EMP . "','" . $fe_ini . "','" . $fe_fin . "'";
                            // echo $texto_sql;
                            $in = 1;
                            $conexion = $_SESSION['dbmssql']->getAll($texto_sql);
                            foreach ($conexion as $row  => $filas) {
                                $cliraz         =    $filas['cliraz'];
                                $empraz         =    $filas['empraz'];
                                $tipodocumento     =    strtoupper(trim($filas['tipodoc']));
                                $importe1         =    $filas['importe1'];
                                $importe2       =   $filas['importe2'];
                                $fecha          =   $filas['Fecreg'];

                                if ($tipodocumento == 'PROF') {
                                    $CODIGO         =     $filas['codigo'];
                                    //$importe1 	=	$filas['importe1'];
                                    $IMPORTE_REAL    =    $importe1;
                                    $tipo_cobranza     =     $filas['tipodoc'];
                                }

                                if ($tipodocumento == 'DSCTO') {
                                    $CODIGO         =     $filas['codigo'];
                                    //$importe1 		=	$filas['importe1'];
                                    $IMPORTE_REAL    =    (-1) * $importe1;
                                    $tipo_cobranza     =     $filas['tipodoc'];
                                }

                                if ($tipodocumento == 'RP') {
                                    //Como hay dos tipos de pago.
                                    ///$importe1 =	$filas['importe1'];
                                    ///$importe2 = $filas['importe2'];	
                                    $IMPORTE_REAL = (-1) * $importe1;

                                    if ((trim($importe1)) == 0)
                                        $IMPORTE_REAL = (-1) * $importe2;

                                    $sql_recibo = "	select 	recibo, codordpag 
                            from 	cabregpago 
                            where 	 CodRegCli='" . $COD_CLI . "' and  codregpag=" . $filas['codigo'] . " and  
                                    tipoCob ='C' and estado='I' and
                                    codregneg='" . $COD_NEGO . "'";
                                    $dsl_recib = $_SESSION['dbmssql']->getAll($sql_recibo);
                                    foreach ($dsl_recib as $row => $data) {
                                        $recibo     = $data['recibo'];
                                        $codordpag     = $data['codordpag'];
                                    }

                                    $CODIGO = $codordpag . '-' . $filas['codigo'] . '-' . $recibo;
                                    $tipo_cobranza     = 'OP-' . $filas['tipodoc'] . '-R';
                                }

                                if ($tipodocumento == 'NC') {
                                    $CODIGO         =     $filas['codigo'];
                                    $IMPORTE_REAL    =    (-1) * $importe2;
                                    $tipo_cobranza     =     $filas['tipodoc'];
                                }

                                if ($tipodocumento == 'ND') {
                                    $CODIGO         =     $filas['codigo'];
                                    $IMPORTE_REAL    =    $importe2;
                                    $tipo_cobranza     =     $filas['tipodoc'];
                                }

                            ?>
                                <tr onMouseOver="color1(this,'#dee7ec')" onMouseOut="color2(this,'#ffffff');">
                                    <td width="9%" height="17" align="center"><?php echo $tipo_cobranza ?></td>
                                    <td width="12%" align="center"><?php echo $CODIGO ?></td>
                                    <td width="29%" align="center"><?php echo $cliraz ?></td>
                                    <td width="28%" align="center"><?php echo $empraz ?></td>
                                    <td width="11%" align="center"><?php echo $fecha ?></td>
                                    <td width="11%" align="right"><?php echo number_format($IMPORTE_REAL, 2); ?></td>
                                </tr>
                            <?php $suma = $suma + ($IMPORTE_REAL);
                                $in = $in + 1;
                                settype($suma, 'float');
                            }
                            ?>
                        </table>
                    </div>
                </td>
            </tr>
            <tr bgcolor="#EEEEEE">
                <td colspan=4 height="20" valign="middle">OP: Codigo de Orden de Pago.&nbsp;|&nbsp;RP: Codigo de Registro de Pago.&nbsp;|&nbsp;R: Recibo Fisico.&nbsp;|&nbsp;NC: Codigo de Nota de Credito.&nbsp;|&nbsp;ND: Codigo de Nota de Debito.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong> SALDO AL MOMENTO (S/.): <?php echo number_format($suma, 2) ?>
                    </strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            </tr>
        </table>
    <?php
    }

    function depurar($texto_malo)
    {
        $texto_malo = str_replace("Ñ", "�", $texto_malo);
        $texto_malo = str_replace("á", "�", $texto_malo);
        $texto_malo = str_replace("é", "�", $texto_malo);
        $texto_malo = str_replace("í", "�", $texto_malo);
        $texto_malo = str_replace("ó", "�", $texto_malo);
        $texto_malo = str_replace("ú", "�", $texto_malo);
        $texto_malo = str_replace("�", "", $texto_malo);
        $texto_malo = str_replace("Ó", "�", $texto_malo);
        $texto_malo = str_replace("ñ", "�", $texto_malo);
        return $texto_malo;
    }

    public function detallado($fe_ini = 0, $fe_fin = 0)
    {
    ?>
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="borderTabla">
            <tr>
                <td height="100%" valign="top">
                    <div id="listOrds" style="height:290;width:100%;overflow:auto">
                        <table width="100%" border="0" cellpadding="0" cellspacing="1" class="borderTabla">
                            <tr>
                                <td width="97" height="27" class="smaltext" align="center" valign="middle" background="images/bg_topbar.gif"><strong>COD. DOC.</strong></td>
                                <td width="113" class="smaltext" align="center" valign="middle" background="images/bg_topbar.gif"><strong>FECHA DOC.</strong></td>
                                <td width="270" class="smaltext" align="center" valign="middle" background="images/bg_topbar.gif"><strong>EMPRESA</strong></td>
                                <td width="252" class="smaltext" align="center" valign="middle" background="images/bg_topbar.gif"><strong>CLIENTE</strong></td>
                                <td width="88" align="center" valign="middle" background="images/bg_topbar.gif" class="smaltext"><strong>MONEDA</strong></td>
                                <td width="131" align="center" valign="middle" background="images/bg_topbar.gif" class="smaltext"><strong>DOCUMENTO</strong></td>
                            </tr>
                        </table>
                        <table width="100%" border="0" cellpadding="2" cellspacing="0">

                            <?php
                            if ($fe_ini == 0 and $fe_fin == 0) {
                                //////////////////////$GuiaProforma	= $this->gproforma;
                                $cod_negocio    = $this->negocio;
                                $Clientecod        = $this->codCliente;
                                $Empresacod        = $this->codEmpresa;

                                $sql_det  = "	select	p.codordprof as coddoc , p.fecreg as fecdoc,
                                    e.empraz, c.cliraz,
                                    p.tipmoneda, 'PROFORMA' as docu, 'OP' as clave, 'P' as sigla
                            from	cabordprof p, empresa e, cliente c
                            where	p.codprofemp=e.empcod and p.codprofcli=c.clicod and p.estado in ('P')
                            
                                    and p.codprofneg = '" . $cod_negocio . "' 	";
                                if ($Clientecod != '0') {
                                    $sql_det  .= "			and p.codprofcli = '" . $Clientecod . "' ";
                                }
                                if ($Empresacod != '0') {
                                    $sql_det  .= "			and p.codprofemp = '" . $Empresacod . "' ";
                                }
                                $sql_det  .= "	union ";
                                $sql_det  .= "	select	ds.codorddscto as coddoc, ds.fecreg as fecdoc,
                                    e.empraz, c.cliraz,
                                    ds.tipmoneda, 'DESCUENTO' as docu, 'OD' as clave, ds.tipoDscto as sigla
                            from	caborddscto ds, empresa e, cliente c
                            where	ds.coddsctoemp=e.empcod and ds.coddsctocli=c.clicod and 
                                    ds.tipodoc in ('P') and ds.estado not in ('C','E')
                                    
                                    and ds.coddsctoneg = '" . $cod_negocio . "'	";
                                if ($Clientecod != '0') {
                                    $sql_det  .= "			and ds.coddsctocli = '" . $Clientecod . "' ";
                                }
                                if ($Empresacod != '0') {
                                    $sql_det  .= "			and ds.coddsctoemp = '" . $Empresacod . "' ";
                                }

                                $sql_det  .= "	union ";
                                $sql_det  .= "	select	re.codregpag as coddoc, re.fecreg as fecdoc,
                                    e.empraz, c.cliraz,
                                    '' as tipmoneda, 'REG. PAGO' as docu, 'RP' as clave, re.tipoCob as sigla
                            from	cabregpago re		, empresa e, cliente c  
                            where	re.codregemp=e.empcod and re.codregcli=c.clicod and 
                                    re.estado  in ('I')     /*and re.tipoCob ='C'*/
                                    
                                    and re.codregneg = '" . $cod_negocio . "'	";
                                if ($Clientecod != '0') {
                                    $sql_det  .= "			and re.codregcli = '" . $Clientecod . "' ";
                                }
                                if ($Empresacod != '0') {
                                    $sql_det  .= "			and re.codregemp = '" . $Empresacod . "' ";
                                }

                                // NOTA DE CREDITO PROFORMA
                                $sql_det  .= "	union ";
                                $sql_det  .= "	select	cp.CodOrdNotaCre as coddoc, cp.fecreg as fecdoc,
                                    e.empraz, c.cliraz,
                                    '' as tipmoneda, 'NC' as docu, 'NC' as clave, 'H' as sigla
                            from	CABNOTACREDITO_PROF cp, empresa e, cliente c
                            where	cp.CodNotaEmp=e.empcod and cp.CodNotaCli=c.clicod  and 
                                    cp.estado in('A') 
                                    and cp.CodNotaNeg = '" . $cod_negocio . "'	";
                                if ($Clientecod != '0') {
                                    $sql_det  .= "			and cp.CodNotaCli = '" . $Clientecod . "' ";
                                }
                                if ($Empresacod != '0') {
                                    $sql_det  .= "			and cp.CodNotaEmp = '" . $Empresacod . "' ";
                                }
                                // NOTA DE DEBITO PROFORMA
                                $sql_det  .= "	union ";
                                $sql_det  .= "	select	cp.CodOrdNotaDeb as coddoc, cp.fecreg as fecdoc,
                                    e.empraz, c.cliraz,
                                    '' as tipmoneda, 'ND' as docu, 'ND' as clave, 'I' as sigla
                            from	CABNOTADEBITO_PROF cp, empresa e, cliente c
                            where	cp.CodNotaEmp=e.empcod and cp.CodNotaCli=c.clicod  and 
                                    cp.estado in('A') 
                                    and cp.CodNotaNeg = '" . $cod_negocio . "'	";
                                if ($Clientecod != '0') {
                                    $sql_det  .= "			and cp.CodNotaCli = '" . $Clientecod . "' ";
                                }
                                if ($Empresacod != '0') {
                                    $sql_det  .= "			and cp.CodNotaEmp = '" . $Empresacod . "' ";
                                }

                                $sql_det  .= "	order by 2 	desc";
                            } else  //consulta con fechas
                            {
                                ///////////////////$GuiaProforma	= $this->gproforma;
                                $cod_negocio    = $this->negocio;
                                $Clientecod        = $this->codCliente;
                                $Empresacod        = $this->codEmpresa;

                                $sql_det = " set dateformat dmy ";
                                $sql_det .= "	select	p.codordprof as coddoc , p.fecreg as fecdoc,
                                    e.empraz, c.cliraz,
                                    p.tipmoneda, 'PROF' as docu, 'OP' as clave, 'P' as sigla
                            from	cabordprof p, empresa e, cliente c
                            where	p.codprofemp=e.empcod and p.codprofcli=c.clicod and p.estado in ('P')
                                    and p.codprofneg = '" . $cod_negocio . "' 	";
                                if ($Clientecod != '0') {
                                    $sql_det  .= "			and p.codprofcli = '" . $Clientecod . "' ";
                                }
                                if ($Empresacod != '0') {
                                    $sql_det  .= "			and p.codprofemp = '" . $Empresacod . "' ";
                                }

                                $sql_det  .= "			and convert(datetime,convert(varchar(10),p.Fecreg,103)) 
                                    between '" . $fe_ini . "' and '" . $fe_fin . "' ";

                                $sql_det  .= "	union ";
                                $sql_det  .= "	select	ds.codorddscto as coddoc, ds.fecreg as fecdoc,
                                    e.empraz, c.cliraz,
                                    ds.tipmoneda, 'DSCTO' as docu, 'OD' as clave, ds.tipoDscto as sigla
                            from	caborddscto ds, empresa e, cliente c
                            where	ds.coddsctoemp=e.empcod and ds.coddsctocli=c.clicod and 
                                    ds.tipodoc in ('P') and ds.estado not in ('C','E')
                                    and ds.coddsctoneg = '" . $cod_negocio . "'	";
                                if ($Clientecod != '0') {
                                    $sql_det  .= "			and ds.coddsctocli = '" . $Clientecod . "' ";
                                }
                                if ($Empresacod != '0') {
                                    $sql_det  .= "			and ds.coddsctoemp = '" . $Empresacod . "' ";
                                }

                                $sql_det  .= "			and convert(datetime,convert(varchar(10),ds.Fecreg,103)) 
                                    between '" . $fe_ini . "' and '" . $fe_fin . "' ";

                                $sql_det  .= "	union ";
                                $sql_det  .= "	select	re.codregpag as coddoc, re.fecreg as fecdoc,
                                    e.empraz, c.cliraz,
                                    '' as tipmoneda, 'RP' as docu, 'RP' as clave, re.tipoCob as sigla
                            from	cabregpago re, empresa e, cliente c
                            where	re.codregemp=e.empcod and re.codregcli=c.clicod  and 
                                    re.estado in ('I') 
                                    and re.codregneg = '" . $cod_negocio . "'	";
                                if ($Clientecod != '0') {
                                    $sql_det  .= "			and re.codregcli = '" . $Clientecod . "' ";
                                }
                                if ($Empresacod != '0') {
                                    $sql_det  .= "			and re.codregemp = '" . $Empresacod . "' ";
                                }

                                $sql_det  .= "		and convert(datetime,convert(varchar(10),re.Fecreg,103)) 
                                between '" . $fe_ini . "' and '" . $fe_fin . "' ";


                                // NOTA DE CREDITO PROFORMA
                                $sql_det  .= "	union ";
                                $sql_det  .= "	select	cp.CodOrdNotaCre as coddoc, cp.fecreg as fecdoc,
                                    e.empraz, c.cliraz,
                                    '' as tipmoneda, 'NC' as docu, 'NC' as clave, 'H' as sigla
                            from	CABNOTACREDITO_PROF cp, empresa e, cliente c
                            where	cp.CodNotaEmp=e.empcod and cp.CodNotaCli=c.clicod  and 
                                    cp.estado in('A') 
                                    and cp.CodNotaNeg = '" . $cod_negocio . "'	";
                                if ($Clientecod != '0') {
                                    $sql_det  .= "			and cp.CodNotaCli = '" . $Clientecod . "' ";
                                }
                                if ($Empresacod != '0') {
                                    $sql_det  .= "			and cp.CodNotaEmp = '" . $Empresacod . "' ";
                                }

                                $sql_det  .= "		and convert(datetime,convert(varchar(10),cp.Fecreg,103)) 
                                between '" . $fe_ini . "' and '" . $fe_fin . "' ";
                                // NOTA DE DEBITO PROFORMA
                                $sql_det  .= "	union ";
                                $sql_det  .= "	select	cp.CodOrdNotaDeb as coddoc, cp.fecreg as fecdoc,
                                                e.empraz, c.cliraz,
                                                '' as tipmoneda, 'ND' as docu, 'ND' as clave, 'I' as sigla
                                                from	CABNOTADEBITO_PROF cp, empresa e, cliente c
                                                where	cp.CodNotaEmp=e.empcod and cp.CodNotaCli=c.clicod  and 
                                                cp.estado in('A') 
                                                and cp.CodNotaNeg = '" . $cod_negocio . "'	";
                                if ($Clientecod != '0') {
                                    $sql_det  .= "			and cp.CodNotaCli = '" . $Clientecod . "' ";
                                }
                                if ($Empresacod != '0') {
                                    $sql_det  .= "			and cp.CodNotaEmp = '" . $Empresacod . "' ";
                                }

                                $sql_det  .= "		and convert(datetime,convert(varchar(10),cp.Fecreg,103)) 
                                                    between '" . $fe_ini . "' and '" . $fe_fin . "' ";
                                $sql_det  .= "      order by 2 desc";
                            }

                            // echo $sql_det;
                            $query_det = $_SESSION['dbmssql']->getAll($sql_det);

                            $total_comp_sol = 0;
                            $total_comp_dol = 0;

                            $SUMA_PROF = 0;
                            $SUMA_REGPAG = 0;
                            $SUMA_DSCTO = 0;
                            $plus = 0;
                            $contador = 0;
                            $SUMA_CONTINUA = 0;
                            foreach ($query_det as $pro => $valores) {
                                $coddoc        = $valores['coddoc'];
                                $sigla        = $valores['sigla'];
                                switch ($sigla) {
                                    case 'P': {
                                            $abrev = "-PROF";
                                        }
                                        break;
                                    case 'D': {
                                            $abrev = "-DVOL";
                                        }
                                        break;
                                    case 'S': {
                                            $abrev = "-SLDO";
                                        }
                                        break;
                                    case 'O': {
                                            $abrev = "-OTRO";
                                        }
                                        break;
                                    case 'Q': {
                                            $abrev = "-CHQ";
                                        }
                                        break;
                                    case 'C': {
                                            $abrev = "-CSH";
                                        }
                                        break;
                                    case 'L': {
                                            $abrev = "-LTR";
                                        }
                                        break;
                                    case 'H': {
                                            $abrev = "-NC";
                                        }
                                        break;
                                    case 'I': {
                                            $abrev = "-ND";
                                        }
                                        break;
                                }
                                $Fecha        = $valores['fecdoc'];
                                $empraz        = $valores['empraz'];
                                $cliraz        = $valores['cliraz'];
                                $tipmoneda    = $valores['tipmoneda'];
                                switch ($tipmoneda) {
                                    case 'S': {
                                            $moneda = "SOLES";
                                        }
                                        break;
                                    case 'D': {
                                            $moneda = "DOLARES";
                                        }
                                        break;
                                }
                                $docu        = $valores['docu'];
                                $clave        = $valores['clave'];
                                //$sigla	= $valores['sigla'];

                                $NewCodigoComp = (string)str_pad($coddoc, 7, '0', STR_PAD_LEFT);

                            ?>
                                <tr onMouseOver="color1(this,'#dee7ec')" onMouseOut="color2(this,'#ffffff');">
                                    <td class="small_fuente" width="95" height="27" align="center">&nbsp;<?= $coddoc . $abrev ?></td>
                                    <td class="small_fuente" width="110" align="center"><?= $Fecha ?></td>
                                    <td class="small_fuente" width="266" align="center" valign="middle"><?= $empraz ?></td>
                                    <td class="small_fuente" width="250" align="center" valign="middle"><?= $cliraz ?></td>
                                    <td class="small_fuente" width="85" align="center" valign="middle"><?= $moneda ?></td>
                                    <td width="128" align="center" valign="middle" class="small_fuente"><?= $docu ?></td>
                                </tr>
                                <tr width="100%">
                                    <td colspan="6">
                                        <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td colspan="4" align="center">
                                                    <table width="100%" height="50" border="0" cellpadding="0" cellspacing="0" align="center">
                                                        <tr>
                                                            <td valign="top">
                                                                <fieldset>
                                                                    <legend>Detalle</legend>
                                                                    <?php
                                                                    if ($clave == "OP") {
                                                                        $SUMA = 0;
                                                                    ?>
                                                                        <table width="100%" border="0" cellpadding="3" cellspacing="0" class="borderTabla1">
                                                                            <tr>
                                                                                <td class="smalltext" width="220" height="22" valign="middle" background="images/prof.jpg"><strong>&nbsp;PRODUCTO</strong></td>
                                                                                <td class="smalltext" width="221" align="center" valign="middle" background="images/prof.jpg"><strong>&nbsp;ESPECIFICACION</strong></td>
                                                                                <td width="245" align="center" valign="middle" background="images/prof.jpg" class="smalltext"><strong>SERVICIO</strong></td>
                                                                                <td width="80" align="center" valign="middle" background="images/prof.jpg" class="smalltext"><strong>CANT.</strong></td>
                                                                                <td width="57" align="center" valign="middle" background="images/prof.jpg" class="smalltext"><strong>P. UNIT</strong></td>
                                                                                <td width="79" align="center" valign="middle" background="images/prof.jpg" class="smalltext"><strong>&nbsp;IMPORTE</strong></td>
                                                                            </tr>
                                                                            <?php
                                                                            $sql  = "select	p.ProDes,	
                                (	select	especificacion from detordserv 
                                    where	CodServPro=d.CodprofPro and 
                                            CodServNeg=d.codprofneg and
                                            CodOrdServ=d.CodprofServ and
                                            ServDes=d.ServDes and 
                                                estado not in ('C','E','N')
                                ) as especificacion, 
                                (	select	servdes from detordserv 
                                    where	CodServPro=d.CodprofPro and 
                                            CodServNeg=d.codprofneg and
                                            CodOrdServ=d.CodprofServ and
                                            ServDes=d.ServDes and 
                                                estado not in ('C','E','N')
                                ) as servdes,
                                d.cantrecep,
                                (select MedAbrev from medida where medcod=d.medida) as abrev_medida,
                                d.preciounit, d.preciototal, c.codprofserv
                        from	cabordprof c, detordprof d, producto p
                        where	c.codordprof=d.codordprof and c.codprofneg=d.codprofneg and 
                                c.codordprof = '" . $coddoc . "' and d.CodprofPro=p.ProCod and
                                d.codprofneg = '" . $cod_negocio . "' ";
                                                                            $query  = $_SESSION['dbmssql']->getAll($sql);
                                                                            foreach ($query as $item => $value) {
                                                                                $ProDes          =  $value['ProDes'];
                                                                                $especificacion =  $value['especificacion'];
                                                                                $servdes          =  $value['servdes'];
                                                                                $cantrecep        =  $value['cantrecep'];
                                                                                $abrev_medida   =  $value['abrev_medida'];
                                                                                $preciounit     =  $value['preciounit'];
                                                                                $preciototal     =  $value['preciototal'];
                                                                                $codprofserv     =  $value['codprofserv'];
                                                                                $ProDes            = $this->depurar($ProDes);
                                                                                $especificacion    = $this->depurar($especificacion);
                                                                                $servdes        = $this->depurar($servdes);

                                                                                $SUMA = $SUMA + $preciototal;

                                                                                settype($preciototal, 'float');
                                                                                settype($cantrecep, 'float');
                                                                                settype($preciounit, 'float');

                                                                            ?>
                                                                                <tr>
                                                                                    <td class="smalltext" width="220" height="25" valign="middle"> &nbsp;<?= $ProDes ?></td>
                                                                                    <td class="smalltext" width="221" align="center" valign="middle">&nbsp;<?= $especificacion ?></td>
                                                                                    <td class="smalltext" align="center" valign="middle"> &nbsp;<?= $servdes ?></td>
                                                                                    <td class="smalltext" align="center" valign="middle"><?= $cantrecep . " " . $abrev_medida ?></td>
                                                                                    <td class="smalltext" align="center" valign="middle"><?= $preciounit ?></td>
                                                                                    <td class="smalltext" width="79" height="25" valign="middle" align="center"><?= $preciototal ?></td>
                                                                                </tr>
                                                                            <?php
                                                                            }
                                                                            settype($SUMA, 'float');

                                                                            $SUMA_PROF = $SUMA_PROF + $SUMA;

                                                                            ///Gu�as despachos
                                                                            $con2 = " ";
                                                                            $sql_GuiaDespacho = " select 	CodOrdDesp 
                                        from 	CabOrdDesp 	
                                        where 	CodOrdServ='" . $codprofserv . "' and 
                                                CodDespNeg='" . $cod_negocio . "' and 
                                                estado not in ('E','C') 
                                        order by CodOrdDesp desc";
                                                                            $dsl_GuiaDespacho = $_SESSION['dbmssql']->getAll($sql_GuiaDespacho);
                                                                            foreach ($dsl_GuiaDespacho as $val => $value) {
                                                                                $codorddesp    = trim($value['CodOrdDesp']);
                                                                                $con2 = trim($codorddesp . "-" . $con2);
                                                                            }
                                                                            $CodOrdDesp = substr($con2, 0, strlen($con2) - 1);

                                                                            /////LOTE
                                                                            $sql_Lote = "	select 	Lote from CabOrdServ 
                                where 	CodOrdServ='" . $codprofserv . "' and 
                                        CodServNeg='" . $cod_negocio . "'  and 
                                        estado not in ('E','C')";
                                                                            $dsl_Lote = $_SESSION['dbmssql']->getAll($sql_Lote);
                                                                            foreach ($dsl_Lote as $val => $value) {
                                                                                $Lote = trim($value['Lote']);
                                                                            }
                                                                            if ($Lote == 0) $Lote = "No Definido";


                                                                            $sql_Lote = "	select 	Lote from CabOrdServ 
                                where 	CodOrdServ='" . $codprofserv . "' and 
                                        CodServNeg='" . $cod_negocio . "' and 						
                                        Estado  in ('T','P')";
                                                                            $dsl_Lote = $_SESSION['dbmssql']->getAll($sql_Lote);
                                                                            foreach ($dsl_Lote as $val => $inf) {
                                                                                $Lote = trim($inf['Lote']);
                                                                            }

                                                                            $sql_suma_ing = "  	SELECT  isnull(sum(cantidadrecep),0) as suma_ing
                                        FROM    DETORDDESP
                                        WHERE   CodDespNeg = '" . $cod_negocio . "' and 
                                                CodOrdServ = '" . $codprofserv . "' and
                                                Estado in ('I') ";

                                                                            $dsl_suma_ing = $_SESSION['dbmssql']->getAll($sql_suma_ing);
                                                                            foreach ($dsl_suma_ing as $val => $value) {
                                                                                $sum_ing    = trim($value['suma_ing']);
                                                                            }

                                                                            $SALDO = $Lote - $sum_ing;


                                                                            ?>
                                                                            <tr>
                                                                                <td class="smalltext" width="220" height="25" valign="middle">&nbsp;O. S. : <?= $codprofserv ?></td>
                                                                                <td class="smalltext" width="221" valign="middle">&nbsp;Lote: <?= $Lote ?></td>
                                                                                <td class="smalltext" valign="middle"> &nbsp;Saldo: <?= $SALDO ?></td>
                                                                                <td colspan="2" align="right" valign="middle" class="smalltext2"><strong>Total S/.:</strong></td>
                                                                                <td class="smalltext" width="79" height="25" valign="middle" align="center"><?= round($SUMA, 2) ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="smalltext" width="220" height="25" valign="middle">&nbsp;O. D. : <?= $CodOrdDesp ?></td>
                                                                                <td class="smalltext" width="221" valign="middle">&nbsp;</td>
                                                                                <td class="smalltext" align="center" valign="middle">&nbsp; </td>
                                                                                <td class="smalltext" align="center" valign="middle">&nbsp;</td>
                                                                                <td class="smalltext" align="center" valign="middle">&nbsp;</td>
                                                                                <td class="smalltext" width="79" height="25" valign="middle" align="center">&nbsp;</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align="right" valign="top">&nbsp;</td>
                                                                                <td align="right" valign="top">&nbsp;</td>
                                                                                <td align="right" valign="top">&nbsp;</td>
                                                                                <td height="23" colspan="2" align="right" valign="middle">&nbsp;</td>
                                                                                <td align="right" width="79" valign="middle">&nbsp;</td>
                                                                            </tr>
                                                                        </table>
                                                                    <?php
                                                                        $SUMA_CONTINUA = $SUMA_CONTINUA + $SUMA;
                                                                        settype($SUMA_CONTINUA, 'float');
                                                                    }
                                                                    if ($clave == "OD") {
                                                                    ?>

                                                                        <table width="100%" border="0" cellpadding="3" cellspacing="0" class="borderTabla1">
                                                                            <tr>
                                                                                <td height="22" colspan="3" valign="middle" background="images/postee.gif" class="smalltext"><strong>&nbsp;PRODUCTO</strong><strong>&nbsp;</strong></td>
                                                                                <td width="80" align="center" valign="middle" background="images/postee.gif" class="smalltext"><strong>CANT.</strong></td>
                                                                                <td width="57" align="center" valign="middle" background="images/postee.gif" class="smalltext"><strong>P. UNIT</strong></td>
                                                                                <td width="79" align="center" valign="middle" background="images/postee.gif" class="smalltext"><strong>&nbsp;IMPORTE</strong></td>
                                                                            </tr>
                                                                            <?php
                                                                            $SUMA_OD = 0;
                                                                            $sql  = "select	p.prodes, d.cantidad, d.preciounit, d.importe
                     from	detorddscto d, producto p
                     where	d.coddsctoprod = p.procod and 
                            d.coddsctoneg = '" . $cod_negocio . "' and
                            d.codorddscto = '" . $coddoc . "' and 
                            d.estado not in ('C','E') ";
                                                                            $query  = $_SESSION['dbmssql']->getAll($sql);
                                                                            foreach ($query as $item => $value) {
                                                                                $prodes      =  $value['prodes'];
                                                                                $cantidad     =  $value['cantidad'];
                                                                                $preciounit =  $value['preciounit'];
                                                                                $importe    =  $value['importe'];

                                                                                $prodes    = $this->depurar($prodes);

                                                                                $SUMA_OD = $SUMA_OD + $importe;

                                                                                settype($cantidad, 'float');
                                                                                settype($preciounit, 'float');
                                                                                settype($SUMA_OD, 'float');
                                                                            ?>
                                                                                <tr>
                                                                                    <td height="25" colspan="3" valign="middle" class="smalltext">&nbsp;<?= $prodes ?>&nbsp;&nbsp;</td>
                                                                                    <td class="smalltext" align="center" valign="middle"><?= $cantidad ?></td>
                                                                                    <td class="smalltext" align="center" valign="middle"><?= $preciounit ?></td>
                                                                                    <td class="smalltext" width="79" height="25" valign="middle" align="center"><?= $importe ?></td>
                                                                                </tr>
                                                                            <?php
                                                                            }
                                                                            $sql_co  = "select 	comentario 
                                from 	caborddscto 
                                where 	codorddscto='" . $coddoc . "' and 
                                        coddsctoneg= '" . $cod_negocio . "' and
                                        estado not in ('C','E') ";
                                                                            $query_co  = $_SESSION['dbmssql']->getAll($sql_co);
                                                                            foreach ($query_co as $item => $co) {
                                                                                $comentario_co  =  $co['comentario'];
                                                                            }
                                                                            ?>
                                                                            <tr>
                                                                                <td colspan="2" rowspan="2" valign="top" class="smalltext">&nbsp;Comentario: </td>
                                                                                <td width="624" rowspan="2" valign="top" class="smalltext"><?= $comentario_co ?></td>
                                                                                <td colspan="2" align="right" valign="middle" class="smalltext2"><strong>Total S/.:</strong></td>
                                                                                <td class="smalltext" width="79" height="25" valign="middle" align="center"><?= round($SUMA_OD, 2) ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td height="15" colspan="2" align="right" valign="middle">&nbsp;</td>
                                                                                <td align="right" width="79" valign="middle">&nbsp;</td>
                                                                            </tr>
                                                                        </table>

                                                                    <?php

                                                                        $SUMA_DSCTO = $SUMA_DSCTO + $SUMA_OD;
                                                                        $SUMA_CONTINUA = $SUMA_CONTINUA - $SUMA_OD;
                                                                    }

                                                                    settype($SUMA_DSCTO, 'float');
                                                                    settype($SUMA_CONTINUA, 'float');

                                                                    if ($clave == "RP") {
                                                                    ?>
                                                                        <table width="100%" border="0" cellpadding="3" cellspacing="0" class="borderTabla1">
                                                                            <tr>
                                                                                <td height="22" valign="middle" background="images/ft.gif" class="smalltext"><strong>&nbsp;PRODUCTO</strong><strong>&nbsp;</strong></td>
                                                                                <td width="132" height="22" align="center" valign="middle" background="images/ft.gif" class="smalltext"><strong>ORD. PAGO</strong></td>
                                                                                <td width="157" align="center" valign="middle" background="images/ft.gif" class="smalltext"><strong>RECIBO</strong></td>
                                                                                <td width="229" align="center" valign="middle" background="images/ft.gif" class="smalltext"><strong>COBRADOR</strong></td>
                                                                                <td width="79" align="center" valign="middle" background="images/ft.gif" class="smalltext"><strong>IMPORTE</strong></td>
                                                                            </tr>
                                                                            <?php
                                                                            $sql  = "select	re.comentario, re.codordpag, re.recibo, re.cobrador, re.SumAbon,re.TotalRecib
                     from	cabregpago re, empresa e, cliente c
                     where	re.codregemp = e.empcod and re.codregcli = c.clicod and 
                            re.estado not in ('C','E') and               re.tipoCob ='C' and
                            re.codregneg = '" . $cod_negocio . "' and 
                            re.codregpag = '" . $coddoc . "'";
                                                                            $query  = $_SESSION['dbmssql']->getAll($sql);
                                                                            foreach ($query as $item => $value) {
                                                                                $comentario =  $value['comentario'];
                                                                                $codordpag     =  $value['codordpag'];
                                                                                $recibo     =  $value['recibo'];
                                                                                $cobrador   =  $value['cobrador'];
                                                                                $SumAbon     =  trim($value['SumAbon']);
                                                                                $TotalRecib    =  trim($value['TotalRecib']);

                                                                                $recib_reg_dinero = $TotalRecib;
                                                                                if ($TotalRecib == "0")
                                                                                    $recib_reg_dinero = (float)$SumAbon;


                                                                                $comentario    = $this->depurar($comentario);
                                                                                $cobrador    = $this->depurar($cobrador);


                                                                                $SUMA_REGPAG = $SUMA_REGPAG + $recib_reg_dinero;

                                                                                settype($SumAbon, 'float');
                                                                                settype($SUMA_REGPAG, 'float');

                                                                            ?>
                                                                                <tr>
                                                                                    <td height="25" valign="middle" class="smalltext">&nbsp;<?= $comentario ?></td>
                                                                                    <td height="25" align="center" valign="middle" class="smalltext"><?= $codordpag ?></td>
                                                                                    <td class="smalltext" align="center" valign="middle"><?= $recibo ?></td>
                                                                                    <td class="smalltext" align="center" valign="middle"><?= $cobrador ?></td>
                                                                                    <td class="smalltext" width="79" height="25" valign="middle" align="center"><?= $recib_reg_dinero ?></td>
                                                                                </tr>
                                                                            <?php } ?>
                                                                            <tr>
                                                                                <td height="15" width="331" align="right" valign="top">&nbsp;</td>
                                                                                <td align="right" valign="top">&nbsp;</td>
                                                                                <td colspan="2" align="right" valign="middle">&nbsp;</td>
                                                                                <td align="right" width="79" valign="middle">&nbsp;</td>
                                                                            </tr>
                                                                        </table>


                                                                        <?php

                                                                        if ($Clientecod != 0 && $Empresacod != 0) {
                                                                            $SUPER_SALDO = round($SUMA_PROF + $plus - $SUMA_REGPAG - $SUMA_DSCTO, 2);

                                                                            //setear	
                                                                            if ($contador == 0) {
                                                                                /*if($Clientecod==105 && $Empresacod==4 && $cod_negocio==3)$SUPER_SALDO=3408.6;
                    if($Clientecod==30 && $Empresacod==4 && $cod_negocio==3)$SUPER_SALDO=42688.02;
                    if($Clientecod==35 && $Empresacod==4 && $cod_negocio==3)$SUPER_SALDO=39508.2;
                    if($Clientecod==28 && $Empresacod==4 && $cod_negocio==3)$SUPER_SALDO=3391.2;
                    if($Clientecod==99 && $Empresacod==4 && $cod_negocio==3)$SUPER_SALDO=266.2;
                    if($Clientecod==25 && $Empresacod==4 && $cod_negocio==3)$SUPER_SALDO=12131;
                    if($Clientecod==23 && $Empresacod==4 && $cod_negocio==3)$SUPER_SALDO=30902.2;
                    if($Clientecod==82 && $Empresacod==4 && $cod_negocio==3)$SUPER_SALDO=513.6;
                    if($Clientecod==67 && $Empresacod==4 && $cod_negocio==3)$SUPER_SALDO=3347.5;
                    if($Clientecod==78 && $Empresacod==4 && $cod_negocio==3)$SUPER_SALDO=1335.5;
                    if($Clientecod==48 && $Empresacod==4 && $cod_negocio==3)$SUPER_SALDO=1892.7;
                    if($Clientecod==129 && $Empresacod==4 && $cod_negocio==3)$SUPER_SALDO=594;*/
                                                                            }

                                                                            $plus = $plus + $SUMA_PROF - $SUMA_REGPAG - $SUMA_DSCTO;
                                                                            $SUMA_PROF = 0;

                                                                            /////////////////si es seteado, se suman los seteados. Sino suma_prof es cero.///////////////
                                                                            if ($contador == 0) {
                                                                                /*if($Clientecod==105 && $Empresacod==4 && $cod_negocio==3)$SUMA_PROF=0+$SUPER_SALDO;
                    if($Clientecod==30 && $Empresacod==4 && $cod_negocio==3)$SUMA_PROF=0+$SUPER_SALDO;
                    if($Clientecod==35 && $Empresacod==4 && $cod_negocio==3)$SUMA_PROF=0+$SUPER_SALDO;
                    if($Clientecod==28 && $Empresacod==4 && $cod_negocio==3)$SUMA_PROF=0+$SUPER_SALDO;
                    if($Clientecod==99 && $Empresacod==4 && $cod_negocio==3)$SUMA_PROF=0+$SUPER_SALDO;
                    if($Clientecod==25 && $Empresacod==4 && $cod_negocio==3)$SUMA_PROF=0+$SUPER_SALDO;
                    if($Clientecod==23 && $Empresacod==4 && $cod_negocio==3)$SUMA_PROF=0+$SUPER_SALDO;
                    if($Clientecod==82 && $Empresacod==4 && $cod_negocio==3)$SUMA_PROF=0+$SUPER_SALDO;
                    if($Clientecod==67 && $Empresacod==4 && $cod_negocio==3)$SUMA_PROF=0+$SUPER_SALDO;
                    if($Clientecod==78 && $Empresacod==4 && $cod_negocio==3)$SUMA_PROF=0+$SUPER_SALDO;
                    if($Clientecod==48 && $Empresacod==4 && $cod_negocio==3)$SUMA_PROF=0+$SUPER_SALDO;
                    if($Clientecod==129 && $Empresacod==4 && $cod_negocio==3)$SUMA_PROF=0+$SUPER_SALDO;*/
                                                                            }
                                                                            /////////////////////////////////////////////////////////////////////////////////////////////
                                                                            $SUMA_REGPAG = 0;
                                                                            $SUMA_DSCTO = 0;
                                                                            $contador++;
                                                                        }

                                                                        ///despues de un registro de pago capturo su valor
                                                                        $SUMA_CONTINUA = $SUPER_SALDO;
                                                                    }
                                                                    settype($SUMA_REGPAG, 'float');
                                                                    settype($SUMA_DSCTO, 'float');
                                                                    settype($SUMA_CONTINUA, 'float');
                                                                    settype($SUPER_SALDO, 'float');

                                                                    if ($clave == "NC") {
                                                                        ?>
                                                                        <table width="100%" border="0" cellpadding="3" cellspacing="0" class="borderTabla1">
                                                                            <tr>
                                                                                <td colspan="2" height="22" valign="middle" background="images/ft.gif" class="smalltext"><strong>&nbsp;DESCRIPCION</strong><strong>&nbsp;</strong></td>
                                                                                <td width="132" height="22" align="center" valign="middle" background="images/ft.gif" class="smalltext"><strong>CANT</strong></td>
                                                                                <td width="157" align="center" valign="middle" background="images/ft.gif" class="smalltext"><strong>P. UNITARIO</strong></td>
                                                                                <!--<td width="229" align="center" valign="middle" background="images/ft.gif" class="smalltext"><strong>COBRADOR</strong></td>-->
                                                                                <td width="79" align="center" valign="middle" background="images/ft.gif" class="smalltext"><strong>IMPORTE</strong></td>
                                                                            </tr>
                                                                            <?php
                                                                            $sql = " select	de.Glosa, de.Cantidad, m.MedAbrev as medida, de.Punitario, de.Monto 
                    from DETNOTACREDITO_PROF de left join MEDIDA m on de.MedCod=m.MedCod
                    where de.estado not in('C','E') and
                        de.CodNotaNeg='" . $cod_negocio . "' and 
                        de.CodOrdNotaCre='" . $coddoc . "' ";
                                                                            $query  = $_SESSION['dbmssql']->getAll($sql);
                                                                            foreach ($query as $item => $value) {
                                                                                $glosa    = $value['Glosa'];
                                                                                $cantidad = $value['Cantidad'];
                                                                                $medida   = $value['medida'];
                                                                                $p_unit   = $value['Punitario'];
                                                                                $monto      = trim($value['Monto']);
                                                                                $glosa      = $this->depurar($glosa);
                                                                            ?>
                                                                                <tr>
                                                                                    <td colspan="2" height="25" valign="middle" class="smalltext">&nbsp;<?= $glosa ?></td>
                                                                                    <td class="smalltext" align="center" valign="middle"><?= $cantidad . ' ' . $medida ?></td>
                                                                                    <td class="smalltext" align="center" valign="middle"><?= $p_unit ?></td>
                                                                                    <td class="smalltext" width="79" height="25" valign="middle" align="center"><?= $monto ?></td>
                                                                                </tr>
                                                                            <?php } ?>
                                                                            <tr>
                                                                                <td height="15" width="331" align="right" valign="top">&nbsp;</td>
                                                                                <td align="right" valign="top">&nbsp;</td>
                                                                                <td colspan="2" align="right" valign="middle">&nbsp;</td>
                                                                                <td align="right" width="79" valign="middle">&nbsp;</td>
                                                                            </tr>
                                                                        </table>

                                                                    <?php
                                                                        ///despues de un registro de pago capturo su valor
                                                                        $SUMA_CONTINUA = $SUPER_SALDO;
                                                                    }
                                                                    settype($SUMA_REGPAG, 'float');
                                                                    settype($SUMA_DSCTO, 'float');
                                                                    settype($SUMA_CONTINUA, 'float');
                                                                    settype($SUPER_SALDO, 'float');

                                                                    // ✅ HECHO: NOTA DE DEBITO
                                                                    if ($clave == "ND") {
                                                                    ?>
                                                                        <table width="100%" border="0" cellpadding="3" cellspacing="0" class="borderTabla1">
                                                                            <tr>
                                                                                <td colspan="2" height="22" valign="middle" background="images/ft.gif" class="smalltext"><strong>&nbsp;DESCRIPCION</strong><strong>&nbsp;</strong></td>
                                                                                <td width="132" height="22" align="center" valign="middle" background="images/ft.gif" class="smalltext"><strong>CANT</strong></td>
                                                                                <td width="157" align="center" valign="middle" background="images/ft.gif" class="smalltext"><strong>P. UNITARIO</strong></td>
                                                                                <!--<td width="229" align="center" valign="middle" background="images/ft.gif" class="smalltext"><strong>COBRADOR</strong></td>-->
                                                                                <td width="79" align="center" valign="middle" background="images/ft.gif" class="smalltext"><strong>IMPORTE</strong></td>
                                                                            </tr>
                                                                            <?php
                                                                            $sql = " select	de.Glosa, de.Cantidad, m.MedAbrev as medida, de.Punitario, de.Monto 
                                                                                    from DETNOTADEBITO_PROF de left join MEDIDA m on de.MedCod=m.MedCod
                                                                                    where de.estado not in('C','E') and
                                                                                        de.CodNotaNeg='" . $cod_negocio . "' and 
                                                                                        de.CodOrdNotaDeb='" . $coddoc . "' ";
                                                                            $query  = $_SESSION['dbmssql']->getAll($sql);
                                                                            foreach ($query as $item => $value) {
                                                                                $glosa    = $value['Glosa'];
                                                                                $cantidad = $value['Cantidad'];
                                                                                $medida   = $value['medida'];
                                                                                $p_unit   = $value['Punitario'];
                                                                                $monto      = trim($value['Monto']);
                                                                                $glosa      = $this->depurar($glosa);
                                                                            ?>
                                                                                <tr>
                                                                                    <td colspan="2" height="25" valign="middle" class="smalltext">&nbsp;<?= $glosa ?></td>
                                                                                    <td class="smalltext" align="center" valign="middle"><?= $cantidad . ' ' . $medida ?></td>
                                                                                    <td class="smalltext" align="center" valign="middle"><?= $p_unit ?></td>
                                                                                    <td class="smalltext" width="79" height="25" valign="middle" align="center"><?= $monto ?></td>
                                                                                </tr>
                                                                            <?php } ?>
                                                                            <tr>
                                                                                <td height="15" width="331" align="right" valign="top">&nbsp;</td>
                                                                                <td align="right" valign="top">&nbsp;</td>
                                                                                <td colspan="2" align="right" valign="middle">&nbsp;</td>
                                                                                <td align="right" width="79" valign="middle">&nbsp;</td>
                                                                            </tr>
                                                                        </table>

                                                                    <?php
                                                                        ///despues de un registro de pago capturo su valor
                                                                        $SUMA_CONTINUA = $SUPER_SALDO;
                                                                    }
                                                                    settype($SUMA_REGPAG, 'float');
                                                                    settype($SUMA_DSCTO, 'float');
                                                                    settype($SUMA_CONTINUA, 'float');
                                                                    settype($SUPER_SALDO, 'float');
                                                                    ?>
                                                                </fieldset>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    <?php      } ?>
                                    </td>
                                </tr>
                        </table>
                    </div>
                </td>
            </tr>
            <tr bgcolor="#EEEEEE">
                <td width="95%" colspan=4 height="25" align="right" valign="middle">
                    <?php
                    /*=============A G R E G A D O ( Junio 2014 ) ==========*/
                    $COD_NEGO = $cod_negocio; /////	=base64_decode($_REQUEST['codNegocio']);
                    $COD_EMP  = $Empresacod; ///	=base64_decode($_REQUEST['empcod']);
                    $COD_CLI  = $Clientecod; ////	=base64_decode($_REQUEST['clicod']);
                    $suma_x = 0;
                    if ($fe_ini == 0) $fe_ini = '';
                    if ($fe_fin == 0) $fe_fin = '';
                    $Sql2 = " execute BF_Exportar_Proforma_Detallado '" . $COD_NEGO . "','" . $COD_CLI . "','" . $COD_EMP . "','" . $fe_ini . "','" . $fe_fin . "' ";
                    $dsl_Sql2 = $_SESSION['dbmssql']->getAll($Sql2);
                    foreach ($dsl_Sql2 as $item => $info) //calculando el total de las cantidades
                    {
                        $tipodoc    = $info['tipodoc'];
                        $codigo        = $info['codigo'];
                        $cliraz        = $info['cliraz'];
                        $empraz        = $info['empraz'];
                        $fecha        = $info['fecha'];
                        $importe1    = $info['importe1'];
                        $importe2    = $info['importe2'];

                        if ($info['tipodoc'] == 'PROF') {
                            $CODIGO         =     $info['codigo'];
                            $importe1         =    $info['importe1'];
                            $IMPORTE_REAL    =    $importe1;
                            $tipo_cobranza     =     $info['tipodoc'];
                        }
                        if ($info['tipodoc'] == 'DSCTO') {
                            $CODIGO         =     $info['codigo'];
                            $importe1         =    $info['importe1'];
                            $IMPORTE_REAL    =    (-1) * $importe1;
                            $tipo_cobranza     =     $info['tipodoc'];
                        }
                        if ($info['tipodoc'] == 'RP') {
                            //Como hay dos tipos de pago.
                            $importe1 =    $info['importe1'];
                            $importe2 = $info['importe2'];
                            $IMPORTE_REAL = (-1) * $importe1;

                            if ($importe1 == 0)
                                $IMPORTE_REAL = (-1) * $importe2;

                            $sql_recibo = "	select 	recibo, codordpag 
									from 	cabregpago 
									where 	codregpag=" . $info['codigo'] . " and  
											tipoCob ='C' and 
											codregneg='" . $COD_NEGO . "'";
                            $dsl_recib = $_SESSION['dbmssql']->getAll($sql_recibo);
                            foreach ($dsl_recib as $row => $data) {
                                $recibo     = $data['recibo'];
                                $codordpag     = $data['codordpag'];
                            }

                            $codigo = $codordpag . '-' . $info['codigo'] . '-' . $recibo;
                            $tipo_cobranza     = 'OP-' . $info['tipodoc'] . '-R';
                        }
                        if ($info['tipodoc'] == 'NC') {
                            $CODIGO         = $info['codigo'];
                            $importe1         = $info['importe1'];
                            $IMPORTE_REAL    = (-1) * $importe2;
                            $tipo_cobranza     =  $info['tipodoc'];
                        }
                        if ($info['tipodoc'] == 'ND') {
                            $CODIGO         = $info['codigo'];
                            $importe1         = $info['importe1'];
                            $IMPORTE_REAL    = $importe2;
                            $tipo_cobranza     =  $info['tipodoc'];
                        }
                        $suma_x = $suma_x + $IMPORTE_REAL;
                    }
                    $SUMA_CONTINUA = $suma_x;

                    settype($SUMA_CONTINUA, 'float');

                    /*==================================*/
                    ?>
                    <strong>SALDO AL MOMENTO (S/.):<?= "  " . number_format($SUMA_CONTINUA, 2) ?></strong>
                </td>
            </tr>
        </table>
<?php

    }
}



?>