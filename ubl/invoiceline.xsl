<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" version="1.0">
    <xsl:output method="xml" version="1.0" encoding="UTF-8"/>

    <xsl:template match="/">
        <InvoiceLines>
            <xsl:for-each select="/records/record">
                <xsl:element name="cac:InvoiceLine">
                    <xsl:element name="cbc:ID">
                        <xsl:value-of select="ID"/>
                    </xsl:element>

                    <cbc:InvoicedQuantity>
                        <xsl:value-of select="InvoicedQuantity"/>
                    </cbc:InvoicedQuantity>
                </xsl:element>
            </xsl:for-each>
        </InvoiceLines>
    </xsl:template>

</xsl:stylesheet>