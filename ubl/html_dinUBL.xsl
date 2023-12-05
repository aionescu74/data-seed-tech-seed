<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0" xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">
    <xsl:output method="html" version="4.0" encoding="UTF-8"/>

    <xsl:template match="/">
        
        
        <html>
            <link rel="stylesheet" type="text/css" href="./ubl.css"/>
            <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <body>
        
        <table class="invoice">
            <tr>
                <td colspan="2">
                    <h2>Factura</h2>
                    <hr/>
                </td>
            </tr>
          <tr>
            <td>Invoice number</td>
            <td>
                <xsl:value-of select="/Invoice/cbc:ID"/>
            </td>
         </tr>
          <tr>
            <td>Invoice date</td>
            <td>
                <xsl:value-of select="/Invoice/cbc:IssueDate"/>
            </td>
          </tr>
        </table>
        </body>
        </html>
        
        
    </xsl:template>
</xsl:stylesheet>