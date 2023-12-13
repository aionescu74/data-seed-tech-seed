<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" version="1.0">
    <xsl:output omit-xml-declaration="yes" indent="yes" encoding="UTF-8"/>

    <xsl:template match="/">
        <InvoiceLines>
            <xsl:for-each select="/records/record">
                <xsl:element name="cac:InvoiceLine">
                    <xsl:element name="cbc:ID">
                        <xsl:value-of select="ID"/>
                    </xsl:element>

                    <cbc:InvoicedQuantity>
                        <xsl:attribute name="unitCode">
                            <xsl:value-of select="InvoicedQuantityUnitCode"/>
                        </xsl:attribute>
                        <xsl:value-of select="InvoicedQuantity"/>
                    </cbc:InvoicedQuantity>
                    
                    <cbc:LineExtensionAmount>
                        <xsl:attribute name="currencyID">
                            <xsl:value-of select="PriceAmountCurrencyID"/>
                        </xsl:attribute>
                        <!--xsl:value-of select="PriceAmount * InvoicedQuantity"/-->
                        <xsl:value-of select="LineExtensionAmount"/>
                    </cbc:LineExtensionAmount>
                    
                    
                    <cac:Item>
                        <cbc:Name>
                            <xsl:value-of select="ItemName"/>
                        </cbc:Name>
                        
                        <cac:SellersItemIdentification>
                          <cbc:ID>
                              <xsl:value-of select="SellersItemIdentificationID"/>
                          </cbc:ID>
                        </cac:SellersItemIdentification>
                        
                        <cac:ClassifiedTaxCategory>
                          <cbc:ID>
                              <xsl:value-of select="ClassifiedTaxCategoryID"/>
                          </cbc:ID>
                          <cbc:Percent>
                              <xsl:value-of select="ClassifiedTaxCategoryPercent"/>
                          </cbc:Percent>
                          <cac:TaxScheme>
                            <cbc:ID>
                                <xsl:value-of select="ClassifiedTaxCategoryTaxSchemeID"/>
                            </cbc:ID>
                          </cac:TaxScheme>
                        </cac:ClassifiedTaxCategory>
                        
                      </cac:Item>
                      <cac:Price>
                            <cbc:PriceAmount>
                                <xsl:attribute name="currencyID">
                                      <xsl:value-of select="PriceAmountCurrencyID"/>
                                  </xsl:attribute>
                                  <xsl:value-of select="PriceAmount"/>
                            </cbc:PriceAmount>
                            <cbc:BaseQuantity>
                                <xsl:attribute name="unitCode">
                                    <xsl:value-of select="PriceBaseQuantityUnitCode"/>
                                </xsl:attribute>
                                <xsl:value-of select="PriceBaseQuantity"/>
                            </cbc:BaseQuantity>
                      </cac:Price>
                    
                </xsl:element>
            </xsl:for-each>
        </InvoiceLines>
    </xsl:template>

</xsl:stylesheet>