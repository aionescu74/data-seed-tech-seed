<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" version="1.0">
    <xsl:output omit-xml-declaration="yes" indent="yes" encoding="UTF-8"/>

    <xsl:template match="/">
        <tax-subtotals>
            <xsl:for-each select="/records/record">
                <cac:TaxSubtotal>
                    <cbc:TaxableAmount>
                        <xsl:attribute name="currencyID">
                            <xsl:value-of select="PriceAmountCurrencyID"/>
                        </xsl:attribute>
                        <xsl:value-of select="TaxableAmount"/>
                    </cbc:TaxableAmount>

                    <cbc:TaxAmount>
                        <xsl:attribute name="currencyID">
                            <xsl:value-of select="PriceAmountCurrencyID"/>
                        </xsl:attribute>
                        <xsl:value-of select="TaxAmount"/>
                    </cbc:TaxAmount>
                    <cac:TaxCategory>
                        <cbc:ID>
                            <xsl:value-of select="ClassifiedTaxCategoryID"/>
                        </cbc:ID>
                        <cbc:Percent>
                            <xsl:value-of select="ClassifiedTaxCategoryPercent"/>
                        </cbc:Percent>
                        <cbc:TaxExemptionReasonCode>
                            <xsl:value-of select="TaxExemptionReasonCode"/>
                        </cbc:TaxExemptionReasonCode>
                        <cac:TaxScheme>
                            <cbc:ID>
                                <xsl:value-of select="ClassifiedTaxCategoryTaxSchemeID"/>
                            </cbc:ID>
                        </cac:TaxScheme>
                    </cac:TaxCategory>
                </cac:TaxSubtotal>
            </xsl:for-each>
        </tax-subtotals>
    </xsl:template>

</xsl:stylesheet>