<?php
require_once("./connection.inc");
require_once("./json_functions.inc");


//$resp = execRestPipe($conn, $rest_pipe_code, $token, $responseContentType);
//print("<br>");
//print("HTTP_RESPONSE: " . $resp);
//print("<br>");
//print("RESPONSE_CONTENT_TYPE: " . $responseContentType);



$method = "GET";

//$url = "https://tennisapi1.p.rapidapi.com/api/tennis/category/6/events/18/08/2023";
//$url = "https://tennisapi1.p.rapidapi.com/api/tennis/calendar/8/2023";

//$arr = array();
$arr = "";

//$headers = "X-RapidAPI-Key: 5231d0501dmsh73885372882be7cp1f0260jsn0938d9b33c26
//X-RapidAPI-Host: tennisapi1.p.rapidapi.com";

$headers = array(
            'X-RapidAPI-Key: 5231d0501dmsh73885372882be7cp1f0260jsn0938d9b33c26',
            'X-RapidAPI-Host: tennisapi1.p.rapidapi.com'
        );


$body = "";
$token = "";
$responseContentType = "";
$httpCode = "";

//$jsonORxml = CallAPI($method, $url, $arr, $headers, $body, $token, $responseContentType, $httpCode);
//$jsonORxml = CallAPI($method, $url, $arr, $headers);
//$jsonORxml = file_get_contents("./category_schedules.json");
$jsonORxml = file_get_contents("./calendar.json");
//print_r($jsonORxml);

$arr = json_decode($jsonORxml, true);

/*
foreach ($arr as $event => $person_a) {
    echo $person_a['status'];
}
 * 
 */

//$pointer = ".i[indicator='I4'].val_den_indicator";
//$pointer = ".i[val_den_indicator='CHELTUIELI TOTALE'].val_indicator";
//$pointer = ".j[1].a";
//$pointer = ".an";
//$pointer = "records[0].okNok";


//$pointer = ".events[0].tournament.category.id";
//$pointer = ".events[0].tournament.category[id='6'].name";
//.events[4].tournament.category.id
$pointer = ".dailyUniqueTournaments[date='2023-08-01'].uniqueTournamentIds[0]";


$xxx = -1;
$xxx = getPointerValue($arr, $pointer, 0);
print($xxx);