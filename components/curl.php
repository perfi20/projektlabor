<?php
// making request to the api
function curl($url, $method, $postfields = NULL, $assoc = false) {

    if(!isset($_SESSION['token'])) {
        $_SESSION['token'] = '';
    }

    $curl = curl_init();

    curl_setopt_array($curl, [
<<<<<<< HEAD
    CURLOPT_URL => 'https://perfi.hu/api/index.php?' . $url,
=======
    CURLOPT_URL => "https://perfi.hu/api/index.php?". $url,
<<<<<<< HEAD
<<<<<<< HEAD
>>>>>>> f7bb41a4ac79e8a78d2a24c1de5b630fd6e09fa1
=======
>>>>>>> f7bb41a4ac79e8a78d2a24c1de5b630fd6e09fa1
=======
>>>>>>> refs/remotes/origin/2024
    //CURLOPT_URL => "http://localhost/projektlabor/api/index.php?". $url,
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

function weather() {

    $url = "https://api.open-meteo.com/v1/forecast?latitude=47.19&longitude=18.41&current_weather=true&timezone=Europe%2FBerlin";
    $curl = curl_init();
        
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl, CURLOPT_VERBOSE, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($curl);
    $data = json_decode($response);

    curl_close($curl);

    return $data;
}

?>