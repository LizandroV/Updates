<?php

function consultarDniReniec($dni)
{
    $token = 'apis-token-5005.Uc8IaqYX0unv1qW0N7HXi1JbViOsisSX';

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.apis.net.pe/v2/reniec/dni?numero=' . $dni,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 2,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Referer: https://apis.net.pe/consulta-dni-api',
            'Authorization: Bearer ' . $token
        ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);

    $persona = json_decode($response, true);

    if (isset($persona['numeroDocumento']) && isset($persona['nombres'])) {
        return array(
            'numeroDocumento' => $persona['numeroDocumento'],
            'nombreCompleto' => $persona['nombres'] . " " . $persona['apellidoPaterno'] . " " . $persona['apellidoMaterno']
        );
    }

    return false; // DNI no encontrado
}
