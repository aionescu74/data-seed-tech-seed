<?php
require_once("../connection.inc");
require_once("../json_functions.inc");
require_once("../xml_functions.inc");
require_once("./ubl_functions.inc");


$invoiceId = @$_POST['id'];
//print $invoiceId;
if($invoiceId == "")
{
    $invoiceId = @$_GET['id'];
}
if($invoiceId == "")
{
    $invoiceId = @$_POST['Id'];
}
if($invoiceId == "")
{
    $invoiceId = @$_GET['Id'];
}



$xml = GetUBLInvoice($invoiceId);


//$filename = "invoice_". $invoiceId . " " . date('Ymd') . ".csv";
//header("Content-Disposition: attachment; filename=\"$filename\"");
header("Content-Type: text/xml");
header("Pragma: no-cache");
header("Expires: 0");

print($xml);

