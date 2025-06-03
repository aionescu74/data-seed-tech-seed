<?php
//$query = "INSERT INTO seed_jobs_log VALUES (default, 'messaging', now(), 'OK', 'messaging');";
//print($query);
//$conn -> query($query);

function sendPushBullet($body)
{
    $url    = "https://api.pushbullet.com/v2/pushes";
    $token  = "...";
    
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS    => $body
      ));
    if($token != "")
    {
        //print('aaa');
        $authorization = "Authorization: Bearer ".$token;
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); // Inject the token into the header
    }
    
    $result = curl_exec($curl);
    $information = curl_getinfo($curl, CURLINFO_HEADER_OUT);
    //print('aaa');
    print_r($information);
    //$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    //print('HTTP_RESPONSE: ');
    //print($httpCode);
    
    return($result);
}


function sendEmail($receiver_email, $subject, $body)
{
    $headers = 'MIME-Version: 1.0' . "\r\n".
    'Content-type: text/html; charset=utf-8' . "\r\n".
    'From: adrian.ionescu@data-seed.tech' . "\r\n" .
    'Reply-To: adrian.ionescu@data-seed.tech' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

    
    mail($receiver_email, $subject, wordwrap($body, 70), $headers);
}
