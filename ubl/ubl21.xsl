<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
    <xsl:output method="xml" version="1.0" encoding="UTF-8"/>

    <xsl:template match="/">
        <Invoice xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">
            <cbc:UBLVersionID>2.1</cbc:UBLVersionID>
            <cbc:CustomizationID>urn:cen.eu:en16931:2017#compliant#urn:efactura.mfinante.ro:CIUS-RO:1.0.0</cbc:CustomizationID>
            
            <xsl:element name="cbc:ID">
                <xsl:value-of select="/records/record/invoiceNumber"/>
            </xsl:element>
            
            <cbc:IssueDate><xsl:value-of select="/records/record/InvoiceDate"/></cbc:IssueDate>
        </Invoice>
    </xsl:template>

</xsl:stylesheet>