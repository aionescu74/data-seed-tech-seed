<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
    <xsl:output method="xml" version="1.0" encoding="UTF-8"/>

    <xsl:template match="/">
        <Invoice xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">
            <cbc:UBLVersionID>2.1</cbc:UBLVersionID>
            <cbc:CustomizationID>urn:cen.eu:en16931:2017#compliant#urn:efactura.mfinante.ro:CIUS-RO:1.0.1</cbc:CustomizationID>
            
            <xsl:element name="cbc:ID">
                <xsl:value-of select="/records/record/ID"/>
            </xsl:element>
            
            <cbc:IssueDate><xsl:value-of select="/records/record/IssueDate"/></cbc:IssueDate>
            <cbc:DueDate><xsl:value-of select="/records/record/DueDate"/></cbc:DueDate>
            
            <cbc:InvoiceTypeCode><xsl:value-of select="380"/></cbc:InvoiceTypeCode>
            <cbc:Note><xsl:value-of select="/records/record/Note"/></cbc:Note>
            <cbc:DocumentCurrencyCode><xsl:value-of select="/records/record/DocumentCurrencyCode"/></cbc:DocumentCurrencyCode>
            
            <cac:InvoicePeriod>
                <cbc:EndDate>
                    <xsl:value-of select="/records/record/InvoicePeriodEndDate"/>
                </cbc:EndDate>
            </cac:InvoicePeriod>
            
            <cac:AccountingSupplierParty>
                <cac:Party>
                    <cac:PartyName>
                        <cbc:Name>
                            <xsl:value-of select="/records/record/SUPPartyName"/>
                        </cbc:Name>
                    </cac:PartyName>
                    <cac:PostalAddress>
                        <cbc:StreetName>
                            <xsl:value-of select="/records/record/SUPPostalAddressStreetName"/>
                        </cbc:StreetName>
                        <cbc:CityName>
                            <xsl:value-of select="/records/record/SUPPostalAddressCityName"/>
                        </cbc:CityName>
                        <cbc:PostalZone>
                            <xsl:value-of select="/records/record/SUPPostalAddressPostalZone"/>
                        </cbc:PostalZone>
                        <cbc:CountrySubentity>
                            <xsl:value-of select="/records/record/SUPPostalAddressCountrySubentity"/>
                        </cbc:CountrySubentity>
                        <cac:Country>
                            <cbc:IdentificationCode>
                                <xsl:value-of select="/records/record/SUPPostalAddressCountry"/>
                            </cbc:IdentificationCode>
                        </cac:Country>
                    </cac:PostalAddress>
                    <cac:PartyTaxScheme>
                        <cbc:CompanyID>
                            <xsl:value-of select="/records/record/SUPPartyTaxSchemeCompanyID"/>
                        </cbc:CompanyID>
                        <cac:TaxScheme>
                            <cbc:ID>
                                <xsl:value-of select="/records/record/SUPPartyTaxSchemeTaxScheme"/>
                            </cbc:ID>
                        </cac:TaxScheme>
                    </cac:PartyTaxScheme>
                    <cac:PartyLegalEntity>
                        <cbc:RegistrationName>
                            <xsl:value-of select="/records/record/SUPPartyLegalEntityRegistrationName"/>
                        </cbc:RegistrationName>
                        <cbc:CompanyID>
                            <xsl:value-of select="/records/record/SUPPartyLegalEntityCompanyID"/>
                        </cbc:CompanyID>
                        <cbc:CompanyLegalForm>
                            <xsl:value-of select="/records/record/SUPCompanyLegalForm"/>
                        </cbc:CompanyLegalForm>
                    </cac:PartyLegalEntity>
                    <cac:Contact>
                        <cbc:ElectronicMail>
                            <xsl:value-of select="/records/record/SUPElectronicMail"/>
                        </cbc:ElectronicMail>
                    </cac:Contact>
                </cac:Party>
            </cac:AccountingSupplierParty>
            <cac:AccountingCustomerParty>
                <cac:Party>
                    <cac:PartyName>
                        <cbc:Name>
                            <xsl:value-of select="/records/record/CUSTPartyName"/>
                        </cbc:Name>
                    </cac:PartyName>
                    <cac:PostalAddress>
                        <cbc:StreetName>
                            <xsl:value-of select="/records/record/CUSTPostalAddressStreetName"/>
                        </cbc:StreetName>
                        <cbc:CityName>
                            <xsl:value-of select="/records/record/CUSTPostalAddressCityName"/>
                        </cbc:CityName>
                        <cbc:PostalZone>
                            <xsl:value-of select="/records/record/CUSTPostalAddressPostalZone"/>
                        </cbc:PostalZone>
                        <cbc:CountrySubentity>
                            <xsl:value-of select="/records/record/CUSTPostalAddressCountrySubentity"/>
                        </cbc:CountrySubentity>
                        <cac:Country>
                            <cbc:IdentificationCode>
                                <xsl:value-of select="/records/record/CUSTPostalAddressCountry"/>
                            </cbc:IdentificationCode>
                        </cac:Country>
                    </cac:PostalAddress>
                    <cac:PartyTaxScheme>
                        <cbc:CompanyID>
                            <xsl:value-of select="/records/record/CUSTPartyTaxSchemeCompanyID"/>
                        </cbc:CompanyID>
                        <cac:TaxScheme>
                            <cbc:ID>
                                <xsl:value-of select="/records/record/CUSTPartyTaxSchemeTaxScheme"/>
                            </cbc:ID>
                        </cac:TaxScheme>
                    </cac:PartyTaxScheme>
                    <cac:PartyLegalEntity>
                        <cbc:RegistrationName>
                            <xsl:value-of select="/records/record/CUSTPartyLegalEntityRegistrationName"/>
                        </cbc:RegistrationName>
                        <cbc:CompanyID>
                            <xsl:value-of select="/records/record/CUSTPartyLegalEntityCompanyID"/>
                        </cbc:CompanyID>
                        <cbc:CompanyLegalForm>
                            <xsl:value-of select="/records/record/CUSTCompanyLegalForm"/>
                        </cbc:CompanyLegalForm>
                    </cac:PartyLegalEntity>
                    <cac:Contact>
                        <cbc:ElectronicMail>
                            <xsl:value-of select="/records/record/CUSTElectronicMail"/>
                        </cbc:ElectronicMail>
                    </cac:Contact>
                </cac:Party>
            </cac:AccountingCustomerParty>
            
            <cac:PaymentMeans>
                <cbc:PaymentMeansCode>
                    <xsl:value-of select="/records/record/PaymentMeansCode"/>
                </cbc:PaymentMeansCode>
                <cac:PayeeFinancialAccount>
                    <cbc:ID>
                        <xsl:value-of select="/records/record/PayeeFinancialAccountID"/>
                    </cbc:ID>
                </cac:PayeeFinancialAccount>
            </cac:PaymentMeans>
            
            <cac:TaxTotal>
                <cbc:TaxAmount>
                    <xsl:attribute name="currencyID">
                        <xsl:value-of select="/records/record/DocumentCurrencyCode"/>
                    </xsl:attribute>
                    <xsl:value-of select="/records/record/TaxAmount"/>
                </cbc:TaxAmount>
            </cac:TaxTotal>
            
            <cac:LegalMonetaryTotal>
                <cbc:LineExtensionAmount>
                    <xsl:attribute name="currencyID">
                        <xsl:value-of select="/records/record/DocumentCurrencyCode"/>
                    </xsl:attribute>
                    <xsl:value-of select="/records/record/LineExtensionAmount"/>
                </cbc:LineExtensionAmount>
                
                
                <cbc:TaxExclusiveAmount>
                    <xsl:attribute name="currencyID">
                        <xsl:value-of select="/records/record/DocumentCurrencyCode"/>
                    </xsl:attribute>
                    <xsl:value-of select="/records/record/TaxExclusiveAmount"/>
                </cbc:TaxExclusiveAmount>
                
                <cbc:TaxInclusiveAmount>
                    <xsl:attribute name="currencyID">
                        <xsl:value-of select="/records/record/DocumentCurrencyCode"/>
                    </xsl:attribute>
                    <xsl:value-of select="/records/record/TaxInclusiveAmount"/>
                </cbc:TaxInclusiveAmount>
                
                <cbc:PayableAmount >
                    <xsl:attribute name="currencyID">
                        <xsl:value-of select="/records/record/DocumentCurrencyCode"/>
                    </xsl:attribute>
                    <xsl:value-of select="/records/record/PayableAmount"/>
                </cbc:PayableAmount >
            </cac:LegalMonetaryTotal>
        </Invoice>
    </xsl:template>

</xsl:stylesheet>