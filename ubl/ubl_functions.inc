<?php
require_once("../xml_functions.inc");


function GetUBLInvoiceHeader($invoiceId)
{
    global $conn;
    
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
    LEFT JOIN fast_supplier_paymentmeans PAY ON SUP.fast_id = PAY.fast_supplier_id
    WHERE INV.fast_id = '" . $invoiceId . "';";
    
    $xml_object = GetXMLfromQuery($conn, $query);   // xml_functions.inc
    
    $proc = new XSLTProcessor();
    
    $XSL = new DOMDocument();
    $XSL->load( './invoice.xsl' );
    

    $proc->importStylesheet($XSL);

    $UBL = $proc->transformToXML($xml_object);
    $UBL = trim($UBL);
    //print_r($UBL);
    
    
    
    
    
    $dom = new DOMDocument;
    $dom->loadXML($UBL);
    $xml_object_inv = simplexml_import_dom($dom);
    $cleanupXSL = new DOMDocument();
    $cleanupXSL->load( './cleanup.xsl' );
    $proc->importStylesheet($cleanupXSL);
    $UBL = $proc->transformToXML($xml_object_inv);
    $UBL = trim($UBL);
    //return $UBL;
    
    
    
    $inv_taxsubtotal = GetUBLInvoiceTaxSubtotal($invoiceId);
    $inv_taxsubtotal = str_replace('<xxx xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">', '', $inv_taxsubtotal);
    $inv_taxsubtotal = str_replace('</xxx>', '', $inv_taxsubtotal);
    
    $inv_head = str_replace('<cac:TaxSubtotal>to be replaced</cac:TaxSubtotal>', $inv_taxsubtotal, $UBL);
    
    
    
    
    
    return $inv_head;
}






function GetUBLInvoiceTaxSubtotal($invoiceId)
{
    global $conn;
    
    $query = "SELECT ClassifiedTaxCategoryID, ClassifiedTaxCategoryPercent, ClassifiedTaxCategoryTaxSchemeID,
                TaxExemptionReasonCode, sum(LineExtensionAmount) as TaxableAmount, PriceAmountCurrencyID
                FROM `fast_invoiceline`
                WHERE fast_invoice_id = '" . $invoiceId . "'
                GROUP BY TaxExemptionReasonCode";
    
    $xml_object = GetXMLfromQuery($conn, $query);   // xml_functions.inc
    
    $proc = new XSLTProcessor();
    
    $XSL = new DOMDocument();
    $XSL->load( './taxsubtotal.xsl' );
    

    $proc->importStylesheet($XSL);

    $UBL = $proc->transformToXML($xml_object);
    $UBL = trim($UBL);
    //print_r($UBL);
    
    
    
    
    
    $dom = new DOMDocument;
    $dom->loadXML($UBL);
    $xml_object_inv = simplexml_import_dom($dom);
    $cleanupXSL = new DOMDocument();
    $cleanupXSL->load( './cleanup.xsl' );
    $proc->importStylesheet($cleanupXSL);
    $UBL = $proc->transformToXML($xml_object_inv);
    
    
    $UBL = trim($UBL);
    
    
    return $UBL;
}







function GetUBLInvoiceLines($invoiceId)
{
    global $conn;
    
    $query = "SELECT *
        FROM `fast_invoiceline` INVL
        WHERE INVL.fast_invoice_id = '" . $invoiceId . "';";
    
    $xml_object = GetXMLfromQuery($conn, $query);   // xml_functions.inc
    
    $proc = new XSLTProcessor();
    
    $XSL = new DOMDocument();
    $XSL->load( './invoiceline.xsl' );
    
    $proc->importStylesheet($XSL);

    $UBL = $proc->transformToXML($xml_object);
    
    $dom = new DOMDocument;
    $dom->loadXML($UBL);
    $xml_object_inv = simplexml_import_dom($dom);
    $cleanupXSL = new DOMDocument();
    $cleanupXSL->load( './cleanup.xsl' );
    $proc->importStylesheet($cleanupXSL);
    $UBL = $proc->transformToXML($xml_object_inv);
    
    
    $UBL = trim($UBL);
    //print_r($UBL);
    
    return $UBL;
}


function GetUBLInvoice($invoiceId)
{
    $inv = GetUBLInvoiceHeader($invoiceId);
    //print_r($inv);
    
    $xml = substr($inv, 0, strlen($inv)-10);
    
    $inv_lin = GetUBLInvoiceLines($invoiceId);
    $inv_lin = str_replace('<InvoiceLines xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">', '', $inv_lin);
    $inv_lin = str_replace('<?xml version="1.0" encoding="UTF-8"?>', '', $inv_lin);
    $inv_lin = str_replace('</InvoiceLines>', '', $inv_lin);

    $xml = $xml . $inv_lin . '</Invoice>';
    
    $xml = trim($xml);
    return $xml;
   
}