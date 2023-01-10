<?php

// making request to the api
function curl($url, $method, $postfields = NULL, $assoc = false) {

    $curl = curl_init();

    curl_setopt_array($curl, [
    CURLOPT_URL => "https://perfi.hu/api/index.php?". $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_2TLS,
    CURLOPT_CUSTOMREQUEST => $method,
    CURLOPT_POSTFIELDS => $postfields,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $_SESSION['token'],
    ],
    ]);

    $response = curl_exec($curl);
    $data = json_decode($response, $assoc);
    $err = curl_error($curl);

    curl_close($curl);
    
    if ($err) {
        return "cURL Error #:" . $err;
    } else return $data;
}

?>