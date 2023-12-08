<?php
require_once("../connection.inc");
require_once("../json_functions.inc");
require_once("../xml_functions.inc");
//require_once("../ubl_functions.inc");


//require_once("./ubl21.xsl");


$urlSrc    = "http://localhost/seed/";
$tableSrc  = "fast_invoice";
$idSrc     = 1; 
//$json = "";
/*
$response = CallSeedAPI($urlSrc, $tableSrc, 'GET', $idSrc, '', $json);   //definit doar LOCAL in connection.inc
$json = json_encode(json_decode($json), JSON_PRETTY_PRINT);
//print($json);
//json2xml();
*/


//$query = "SELECT * 
//            FROM `".$tableSrc."`
//            WHERE fast_id = '" . $idSrc . "';";

$query = "SELECT INV.ID, INV.IssueDate, INV.DueDate, INV.Note, INV.DocumentCurrencyCode,
	SUP.PartyName as SUPPartyName, 
        SUP.PostalAddressStreetName as SUPPostalAddressStreetName,
        SUP.PostalAddressCityName as SUPPostalAddressCityName, 
        SUP.PostalAddressPostalZone as SUPPostalAddressPostalZone, 
        SUP.PostalAddressCountrySubentity as SUPPostalAddressCountrySubentity, 
        SUP.PostalAddressCountry as SUPPostalAddressCountry,
        SUP.PartyTaxSchemeCompanyID as SUPPartyTaxSchemeCompanyID, 
        SUP.PartyTaxSchemeTaxScheme as SUPPartyTaxSchemeTaxScheme,
        SUP.PartyLegalEntityRegistrationName as SUPPartyLegalEntityRegistrationName,
        SUP.PartyLegalEntityCompanyID as SUPPartyLegalEntityCompanyID,
        SUP.CompanyLegalForm as SUPCompanyLegalForm,
        SUP.ElectronicMail as SUPElectronicMail,
        CUST.PartyName as CUSTPartyName, 
        CUST.PostalAddressStreetName as CUSTPostalAddressStreetName,
        CUST.PostalAddressCityName as CUSTPostalAddressCityName, 
        CUST.PostalAddressPostalZone as CUSTPostalAddressPostalZone, 
        CUST.PostalAddressCountrySubentity as CUSTPostalAddressCountrySubentity, 
        CUST.PostalAddressCountry as CUSTPostalAddressCountry,
        CUST.PartyTaxSchemeCompanyID as CUSTPartyTaxSchemeCompanyID, 
        CUST.PartyTaxSchemeTaxScheme as CUSTPartyTaxSchemeTaxScheme,
        CUST.PartyLegalEntityRegistrationName as CUSTPartyLegalEntityRegistrationName,
        CUST.PartyLegalEntityCompanyID as CUSTPartyLegalEntityCompanyID,
        PAY.PaymentMeansCode, PAY.PayeeFinancialAccountID,
        INV.TaxAmount, INV.LineExtensionAmount, INV.TaxExclusiveAmount, INV.TaxInclusiveAmount, INV.PayableAmount
    FROM `fast_invoice` INV
    LEFT JOIN fast_supplierparty SUP ON INV.fast_supplierparty = SUP.fast_id
    LEFT JOIN fast_customerparty CUST ON INV.fast_customerparty = CUST.fast_id
    LEFT JOIN fast_invoice_paymentmeans PAY ON INV.fast_id = PAY.fast_invoice_id
    WHERE INV.fast_id = '" . $idSrc . "';";

//print($query);


// executam queriul:
$xml_object = GetXMLfromQuery($conn, $query);   // xml_functions.inc


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


//$xml  = $row -> xml;
//print_R($xml);

//$xml_object = simplexml_load_string($xml, null, 0, 'cbc', true);
//print_R($xml_object);
//$namespaces = $xml_object->getNamespaces(true);
//var_dump($namespaces);


//$dom= new DOMDocument;
//$dom->loadXML($xml);
//print_r($dom);
//var_dump($dom);



//$xml_object = simplexml_load_file('test.xml', null, 0, 'cbc', true);
//$namespaces = $xml->getNamespaces(true);
//var_dump($namespaces);

//print($namespaces['cbc']);
///$xml->registerXPathNamespace('', 'urn:oasis:names:specification:ubl:schema:xsd:Invoice-2');
//$xml->registerXPathNamespace('cac', 'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');
//$xml->registerXPathNamespace('cbc', 'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2');

//var_dump($xml_object);



//$s = simplexml_import_dom($xml);
//echo $s->Invoice[0]->ID;


//$xmlDOM = new DOMDocument;
//$xmlDOM->loadXML($xml);
//print_R($xmlDOM);

//$xml_object = new SimpleXMLElement($xml); // da mereu empty object
//$xml_object = simplexml_load_string($xml);
//$xml_object = simplexml_import_dom($xmlDOM);
//print_r($xml_object);

$proc = new XSLTProcessor();
//$xslUBL = GetXSL_UBL();
$XSL = new DOMDocument();
$XSL->load( './invoice.xsl' );
//$XSL->load( './html_dinUBL.xsl' );

$proc->importStylesheet( $XSL );

//$proc->importStyleSheet($xslUBL);
$UBL = $proc->transformToXML($xml_object);
print_r($UBL);

//$xml_object = new SimpleXMLElement($UBL);
//print_r($xml_object);











//print("\n\n\n\n");
//PrintXMLfromQuery($conn, $query);   // xml_functions.inc


//$dom = new DOMDocument;
//$dom->loadXML('<books><book><title>blah</title></book></books>');
//if (!$dom) {
//    echo 'Error while parsing the document';
//    exit;
//}

//$s = simplexml_import_dom($dom);

//echo $s->book[0]->title;