<?php
require('salida_tienda_registrar.php');

$info_control = array();
$info_control = array(
    'arreglo'               => trim($_REQUEST['arreglo']),
    'codtraslado'           => trim($_REQUEST['codtraslado']),
    'codalmacen_origen'     => trim($_REQUEST['codalmacen_origen']),
    'codalmacen_destino'    => trim($_REQUEST['codalmacen_destino']),
    'codemp_origen'         => trim($_REQUEST['codemp_origen']),
    'codemp_destino'        => trim($_REQUEST['codemp_destino']),
    'fecha_tienda'          => trim($_REQUEST['fecha_tienda']),
    'obs'                   => trim($_REQUEST['obs']),
    'usuario'               => trim($_REQUEST['usuario']),
    'total_rollos'          => trim($_REQUEST['total_rollos']),
    'total_kg'              => trim($_REQUEST['total_kg'])
);
// 'codsal_tienda'         => trim($_REQUEST['codsal_tienda']),
// 'mov_tienda'            => trim($_REQUEST['mov_tienda'])

// Incio de la Clase //
$salida = new SalidaTienda();

if ($_REQUEST['registrar_salida_tienda']) {
    $salida->insertar_salida_tienda($info_control);
}
