<?php

require('salida_tienda_traslado_registro.php');

$info_tienda = array();
$info_tienda = array(
    'arreglo'           => trim($_REQUEST['arreglo']),
    'codemp_origen'       => trim($_REQUEST['codemp_origen']),
    'codalmacen_origen' => trim($_REQUEST['codalmacen_origen']),
    'fecha_ing'           => trim($_REQUEST['fecha_ing']),
    'usuario'           => trim($_REQUEST['usuario']),
    'obs_ing'           => trim($_REQUEST['obs_ing']),
    'total_rollos'       => trim($_REQUEST['total_rollos']),
    'total_peso'       => trim($_REQUEST['total_peso']),
    'codtraslado'        => trim($_REQUEST['codtraslado'])
);

$traslado_tienda = new IngresoTrasladoTienda();
if ($_REQUEST['registrar_traslado']) {
    $traslado_tienda->insertar_ingreso_traslado_tienda($info_tienda);
}
