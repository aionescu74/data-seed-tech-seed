<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
    <xsl:output method="html" version="4.0" encoding="UTF-8"/>

    <xsl:template match="/">
        
        
        <html>
            <link rel="stylesheet" type="text/css" href="./ubl.css"/>
            <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <body>
        
        <table class="invoice">
            <tr>
                <td colspan="5">
                    <h2>Factura</h2>
                    <hr/>
                </td>
            </tr>
        <tr>
            <td> </td>
            <td>Număr</td>
            <td> </td>
            <td>
                <xsl:element name="input">
                    <xsl:attribute name="type">text</xsl:attribute>
                    <xsl:attribute name="name">ID</xsl:attribute>
                    <!--xsl:attribute name="defaultvalue">0</xsl:attribute-->
                    <xsl:attribute name="value">
                        <xsl:value-of select="/records/record/ID"/>
                    </xsl:attribute>
                    <xsl:attribute name="maxlength">100</xsl:attribute>
                </xsl:element>
            </td>
            <td> </td>
         </tr>
         <tr>
             <td> </td>
            <td>Data emiterii</td>
            <td> </td>
            <td>
                <xsl:element name="input">
                    <xsl:attribute name="type">date</xsl:attribute>
                    <xsl:attribute name="name">IssueDate</xsl:attribute>
                    <xsl:attribute name="value"><xsl:value-of select="/records/record/IssueDate"/></xsl:attribute>
                    <xsl:attribute name="maxlength">100</xsl:attribute>
                </xsl:element>
                
            </td>
            <td> </td>
        </tr>
        <tr>
            <td> </td>
            <td>Data scadenței</td>
            <td> </td>
            <td>
                <xsl:element name="input">
                    <xsl:attribute name="type">date</xsl:attribute>
                    <xsl:attribute name="name">DueDate</xsl:attribute>
                    <xsl:attribute name="value"><xsl:value-of select="/records/record/DueDate"/></xsl:attribute>
                    <xsl:attribute name="maxlength">100</xsl:attribute>
                </xsl:element>
                
            </td>
            <td> </td>
        </tr>
        <tr>
            <td colspan="5">
                <hr/>
            </td>
        </tr>
        <tr>
            <td colspan="2"><b>PRESTATOR</b></td>
            <td> </td>
            <td colspan="2"><b>CLIENT</b></td>
        </tr>
        <tr>
            <td>CIF</td>
            <td>
                <xsl:element name="input">
                    <xsl:attribute name="type">text</xsl:attribute>
                    <xsl:attribute name="name">ID</xsl:attribute>
                    <!--xsl:attribute name="defaultvalue">0</xsl:attribute-->
                    <xsl:attribute name="value">
                        <xsl:value-of select="/records/record/AccountingCustomerParty/PartyTaxSchemeCompanyID"/>
                    </xsl:attribute>
                    <xsl:attribute name="maxlength">100</xsl:attribute>
                </xsl:element>
            </td>
            <td> </td>
            <td>CIF</td>
            <td>CIF Client</td>
        </tr>
        </table>
        </body>
        </html>
        
        
    </xsl:template>
</xsl:stylesheet>