<?php
require_once("../connection.inc");
require_once("../json_functions.inc");
require_once("../xml_functions.inc");
require_once("./ubl_functions.inc");



$invoiceId = 1; 

$xml = GetUBLInvoice($invoiceId);




print($xml);

