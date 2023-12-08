<?php
require_once("../connection.inc");
require_once("../json_functions.inc");
require_once("../xml_functions.inc");
//require_once("../ubl_functions.inc");


//require_once("./ubl21.xsl");


$urlSrc    = "http://localhost/seed/";
$idSrc     = 1; 


$query = "SELECT *
    FROM `fast_invoiceline` INVL
    WHERE INVL.fast_invoice_id = '" . $idSrc . "';";
//print($query);


// executam queriul:
$xml_object = GetXMLfromQuery($conn, $query);   // xml_functions.inc
//print_r($xml_object);
/*
try 
{
    $result = $conn -> query($query);
}
catch(Exception $e)
{
    print ($e->getMessage());
} 
//$result = $conn -> query($query);
$row = $result -> fetch_object();
*/


$proc = new XSLTProcessor();
//$xslUBL = GetXSL_UBL();
$XSL = new DOMDocument();
$XSL->load( './invoiceline.xsl' );
//$XSL->load( './html_dinUBL.xsl' );

$proc->importStylesheet($XSL);

//$proc->importStyleSheet($xslUBL);
$UBL = $proc->transformToXML($xml_object);
print_r($UBL);

