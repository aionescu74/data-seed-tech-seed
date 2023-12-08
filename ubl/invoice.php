<?php
require_once("../connection.inc");
require_once("../json_functions.inc");
require_once("../xml_functions.inc");
//require_once("../ubl_functions.inc");


//require_once("./ubl21.xsl");


$urlSrc    = "http://localhost/seed/";
$tableSrc  = "invoices";
$idSrc     = 1; 
$json = "";

$response = CallSeedAPI($urlSrc, $tableSrc, 'GET', $idSrc, '', $json, 'XML');   //definit doar LOCAL in connection.inc
//print($response);
$xml_object = new SimpleXMLElement($response);
//$json = json_encode(json_decode($json), JSON_PRETTY_PRINT);
//print($json);
//$array = json_decode ($json, true);



//print_r($array);
//$xml = new SimpleXMLElement('<records/>');
//arrayToXml($array, $xml);
//print_r($xml);
//print("\n\r");





//$query = "SELECT * 
//            FROM `".$tableSrc."`
//            WHERE seed_id = '" . $idSrc . "';";
//print($query);
// executam queriul:
//$xml_object = GetXMLfromQuery($conn, $query);   // xml_functions.inc
//print_r($xml_object);
//print("\n\r");




$proc = new XSLTProcessor();
//$xslUBL = GetXSL_UBL();
$XSL = new DOMDocument();
//$XSL->load( './ubl21.xsl' );
$XSL->load( './html_dinDB.xsl' );

$proc->importStylesheet( $XSL );

//$proc->importStyleSheet($xslUBL);
$UBL = $proc->transformToXML($xml_object);
print_r($UBL);




//print("\n\n\n\n");
//PrintXMLfromQuery($conn, $query);   // xml_functions.inc

