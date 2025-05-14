<?php
session_start();
require('../../../includes/dbmssql_cfg.php');
// Datos
$token = 'apis-token-5005.Uc8IaqYX0unv1qW0N7HXi1JbViOsisSX';

$ruc = $_REQUEST['consultaruc'];
// $ruc = '20472498305';

// Iniciar llamada a API
$curl = curl_init();

// Buscar ruc sunat
curl_setopt_array($curl, array(
  // para usar la versión 2
  CURLOPT_URL => 'https://api.apis.net.pe/v2/sunat/ruc?numero=' . $ruc,
  // para usar la versión 1
  // CURLOPT_URL => 'https://api.apis.net.pe/v1/ruc?numero=' . $ruc,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_SSL_VERIFYPEER => 0,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Referer: http://apis.net.pe/api-ruc',
    'Authorization: Bearer ' . $token
  ),
));

$response = curl_exec($curl);

curl_close($curl);

$empresa = json_decode($response, true);
//  echo '<pre>';
// var_dump($empresa);
// echo '</pre>';
$datos = array(
  'razonSocial' => $empresa['razonSocial'],
  'tipoDocumento' => $empresa['tipoDocumento'],
  'numeroDocumento' => $empresa['numeroDocumento'],
  'estado' => $empresa['estado'],
  'condicion' => $empresa['condicion'],
  'direccion' => $empresa['direccion'],
  'ubigeo' => $empresa['ubigeo'],
  'viaTipo' => $empresa['viaTipo'],
  'viaNombre' => $empresa['viaNombre'],
  'zonaCodigo' => $empresa['zonaCodigo'],
  'zonaTipo' => $empresa['zonaTipo'],
  'numero' => $empresa['numero'],
  'interior' => $empresa['interior'],
  'lote' => $empresa['lote'],
  'dpto' => $empresa['dpto'],
  'manzana' => $empresa['manzana'],
  'kilometro' => $empresa['kilometro'],
  'distrito' => $empresa['distrito'],
  'provincia' => $empresa['provincia'],
  'departamento' => $empresa['departamento'],
);


echo json_encode($datos, JSON_FORCE_OBJECT);
