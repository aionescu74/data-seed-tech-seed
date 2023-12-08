SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `fast_client` (
  `fast_client` int NOT NULL,
  `fast_client_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Clienti fastinvoice.ro';

INSERT INTO `fast_client` (`fast_client`, `fast_client_name`) VALUES
(1, 'eu');

CREATE TABLE `fast_country` (
  `fast_country` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Countries';

INSERT INTO `fast_country` (`fast_country`) VALUES
('RO');

CREATE TABLE `fast_countrysubentity` (
  `CountrySubentity` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Subentity` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `fast_countrysubentity` (`CountrySubentity`, `Subentity`) VALUES
('RO-AR', 'Argeș'),
('RO-BU', 'București'),
('RO-CJ', 'Cluj');

CREATE TABLE `fast_currency` (
  `currency` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `fast_currency` (`currency`) VALUES
('EUR'),
('RON');

CREATE TABLE `fast_customerparty` (
  `fast_id` int NOT NULL,
  `fast_client` int NOT NULL,
  `PartyTaxSchemeCompanyID` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'CUI / CIF?',
  `PartyTaxSchemeTaxScheme` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'VAT' COMMENT 'Tip identificator client',
  `PartyName` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Denumire client',
  `PostalAddressStreetName` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Adresă client',
  `PostalAddressCityName` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Localitate',
  `PostalAddressPostalZone` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Cod poștal',
  `PostalAddressCountrySubentity` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Judet',
  `PostalAddressCountry` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tara',
  `PartyLegalEntityRegistrationName` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Denumire client la Reg. Com.',
  `PartyLegalEntityCompanyID` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Reg. Com.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Cumpărător';

INSERT INTO `fast_customerparty` (`fast_id`, `fast_client`, `PartyTaxSchemeCompanyID`, `PartyTaxSchemeTaxScheme`, `PartyName`, `PostalAddressStreetName`, `PostalAddressCityName`, `PostalAddressPostalZone`, `PostalAddressCountrySubentity`, `PostalAddressCountry`, `PartyLegalEntityRegistrationName`, `PartyLegalEntityCompanyID`) VALUES
(1, 1, 'RO46365218', 'VAT', 'WEASWEB DEVELOPMENT S.R.L', 'str. Fagului, nr. 26B', 'Floresti', '', 'RO-CJ', 'RO', 'WEASWEB DEVELOPMENT S.R.L', 'J12/3809/2022');

CREATE TABLE `fast_invoice` (
  `fast_id` int NOT NULL,
  `fast_client` int NOT NULL COMMENT 'FastInvoice Client',
  `ID` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'UBL Invoice ID',
  `IssueDate` date NOT NULL,
  `DueDate` date NOT NULL,
  `Note` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Aici se poate pune cursul valutar',
  `DocumentCurrencyCode` varchar(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `InvoicePeriodEndDate` date DEFAULT NULL,
  `TaxAmount` decimal(10,2) NOT NULL COMMENT 'Valoarea totală a TVA a facturii/ Valoarea TVA totală a facturii în moneda de contabilizare',
  `LineExtensionAmount` decimal(10,2) NOT NULL COMMENT 'Suma valorilor nete ale liniilor facturii',
  `TaxExclusiveAmount` decimal(10,2) NOT NULL COMMENT 'Valoarea totală a facturii fără TVA',
  `TaxInclusiveAmount` decimal(10,2) NOT NULL COMMENT 'Valoarea totală a facturii cu TVA',
  `PayableAmount` decimal(10,2) NOT NULL COMMENT 'Suma de plată'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='e-factura';

INSERT INTO `fast_invoice` (`fast_id`, `fast_client`, `ID`, `IssueDate`, `DueDate`, `Note`, `DocumentCurrencyCode`, `InvoicePeriodEndDate`, `TaxAmount`, `LineExtensionAmount`, `TaxExclusiveAmount`, `TaxInclusiveAmount`, `PayableAmount`) VALUES
(1, 1, '2', '2023-09-29', '2023-10-10', '1 EURO = 4,9746 Lei', 'RON', '0000-00-00', '0.00', '26743.45', '26743.45', '26743.45', '26743.45');

CREATE TABLE `fast_invoiceline` (
  `fast_id` int NOT NULL,
  `fast_invoice_id` int NOT NULL,
  `ID` int NOT NULL COMMENT 'Invoice line number',
  `InvoicedQuantity` decimal(10,2) NOT NULL,
  `LineExtensionAmount` decimal(10,2) NOT NULL,
  `ItemName` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `SellersItemIdentificationID` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ItemClassificationCode` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ItemClassificationCodeListID` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ClassifiedTaxCategoryID` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ClassifiedTaxCategoryPercent` decimal(10,2) DEFAULT NULL,
  `ClassifiedTaxCategoryTaxSchemeID` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `PriceAmount` decimal(10,4) NOT NULL COMMENT 'Preț unitar',
  `PriceAmountCurrencyID` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `PriceBaseQuantity` decimal(10,2) DEFAULT NULL,
  `PriceBaseQuantityUnitCode` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Detalii factură';

INSERT INTO `fast_invoiceline` (`fast_id`, `fast_invoice_id`, `ID`, `InvoicedQuantity`, `LineExtensionAmount`, `ItemName`, `SellersItemIdentificationID`, `ItemClassificationCode`, `ItemClassificationCodeListID`, `ClassifiedTaxCategoryID`, `ClassifiedTaxCategoryPercent`, `ClassifiedTaxCategoryTaxSchemeID`, `PriceAmount`, `PriceAmountCurrencyID`, `PriceBaseQuantity`, `PriceBaseQuantityUnitCode`) VALUES
(1, 1, 1, '168.00', '26743.45', 'Prestari servicii luna septembrie 2023 conform contract nr. WDB54/ 03.08.2023', '', '', '', '', NULL, '', '159.1900', 'RON', NULL, '');

CREATE TABLE `fast_supplierparty` (
  `fast_id` int NOT NULL,
  `fast_client` int NOT NULL,
  `PartyTaxSchemeCompanyID` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'CUI / CIF?',
  `PartyTaxSchemeTaxScheme` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'VAT' COMMENT 'Tip identificator client',
  `PartyName` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Denumire client',
  `PostalAddressStreetName` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Adresă client',
  `PostalAddressCityName` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Localitate',
  `PostalAddressPostalZone` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Cod poștal',
  `PostalAddressCountrySubentity` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Judet',
  `PostalAddressCountry` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tara',
  `PartyLegalEntityRegistrationName` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Denumire client la Reg. Com.',
  `PartyLegalEntityCompanyID` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Reg. Com.',
  `CompanyLegalForm` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Forma de organizare',
  `ElectronicMail` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Email'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Vânzător';

INSERT INTO `fast_supplierparty` (`fast_id`, `fast_client`, `PartyTaxSchemeCompanyID`, `PartyTaxSchemeTaxScheme`, `PartyName`, `PostalAddressStreetName`, `PostalAddressCityName`, `PostalAddressPostalZone`, `PostalAddressCountrySubentity`, `PostalAddressCountry`, `PartyLegalEntityRegistrationName`, `PartyLegalEntityCompanyID`, `CompanyLegalForm`, `ElectronicMail`) VALUES
(1, 1, '48538048', 'VAT', 'IONESCU F. ADRIAN PFA', 'Aleea Fizicienilor nr. 14 bl. 1G sc. 2 et. 2 ap. 65', 'Sector 3', '032111', 'RO-BU', 'RO', 'IONESCU F. ADRIAN PFA', 'F40/5498/2023', 'PFA', 'aionescu74@yahoo.com');

CREATE TABLE `fast_taxscheme` (
  `TaxScheme` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `fast_taxscheme` (`TaxScheme`) VALUES
('VAT');


ALTER TABLE `fast_client`
  ADD PRIMARY KEY (`fast_client`);

ALTER TABLE `fast_country`
  ADD PRIMARY KEY (`fast_country`);

ALTER TABLE `fast_countrysubentity`
  ADD PRIMARY KEY (`CountrySubentity`);

ALTER TABLE `fast_currency`
  ADD PRIMARY KEY (`currency`);

ALTER TABLE `fast_customerparty`
  ADD PRIMARY KEY (`fast_id`),
  ADD UNIQUE KEY `PartyName` (`PartyName`),
  ADD UNIQUE KEY `PartyLegalEntityRegistrationName` (`PartyLegalEntityRegistrationName`),
  ADD UNIQUE KEY `PartyLegalEntityCompanyID` (`PartyLegalEntityCompanyID`),
  ADD KEY `fast_client` (`fast_client`),
  ADD KEY `PartyTaxSchemeTaxScheme` (`PartyTaxSchemeTaxScheme`),
  ADD KEY `PostalAddressCountry` (`PostalAddressCountry`),
  ADD KEY `fast_customerparty_ibfk_3` (`PostalAddressCountrySubentity`);

ALTER TABLE `fast_invoice`
  ADD PRIMARY KEY (`fast_id`),
  ADD KEY `fast_client` (`fast_client`),
  ADD KEY `DocumentCurrencyCode` (`DocumentCurrencyCode`);

ALTER TABLE `fast_invoiceline`
  ADD PRIMARY KEY (`fast_id`),
  ADD KEY `ubl_Invoice` (`fast_invoice_id`),
  ADD KEY `fast_invoice_id` (`fast_invoice_id`),
  ADD KEY `PriceAmountCurrencyID` (`PriceAmountCurrencyID`);

ALTER TABLE `fast_supplierparty`
  ADD PRIMARY KEY (`fast_id`),
  ADD UNIQUE KEY `PartyName` (`PartyName`),
  ADD UNIQUE KEY `PartyLegalEntityRegistrationName` (`PartyLegalEntityRegistrationName`),
  ADD UNIQUE KEY `PartyLegalEntityCompanyID` (`PartyLegalEntityCompanyID`),
  ADD KEY `fast_client` (`fast_client`),
  ADD KEY `PartyTaxSchemeTaxScheme` (`PartyTaxSchemeTaxScheme`),
  ADD KEY `PostalAddressCountry` (`PostalAddressCountry`),
  ADD KEY `fast_supplierparty_ibfk_3` (`PostalAddressCountrySubentity`);

ALTER TABLE `fast_taxscheme`
  ADD PRIMARY KEY (`TaxScheme`);


ALTER TABLE `fast_client`
  MODIFY `fast_client` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `fast_customerparty`
  MODIFY `fast_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `fast_invoice`
  MODIFY `fast_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `fast_invoiceline`
  MODIFY `fast_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `fast_supplierparty`
  MODIFY `fast_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;


ALTER TABLE `fast_customerparty`
  ADD CONSTRAINT `fast_customerparty_ibfk_1` FOREIGN KEY (`fast_client`) REFERENCES `fast_client` (`fast_client`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fast_customerparty_ibfk_2` FOREIGN KEY (`PartyTaxSchemeTaxScheme`) REFERENCES `fast_taxscheme` (`TaxScheme`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fast_customerparty_ibfk_3` FOREIGN KEY (`PostalAddressCountrySubentity`) REFERENCES `fast_countrysubentity` (`CountrySubentity`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fast_customerparty_ibfk_4` FOREIGN KEY (`PostalAddressCountry`) REFERENCES `fast_country` (`fast_country`) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `fast_invoice`
  ADD CONSTRAINT `fast_invoice_ibfk_1` FOREIGN KEY (`fast_client`) REFERENCES `fast_client` (`fast_client`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fast_invoice_ibfk_2` FOREIGN KEY (`DocumentCurrencyCode`) REFERENCES `fast_currency` (`currency`) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `fast_invoiceline`
  ADD CONSTRAINT `fast_invoiceline_ibfk_1` FOREIGN KEY (`fast_invoice_id`) REFERENCES `fast_invoice` (`fast_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fast_invoiceline_ibfk_2` FOREIGN KEY (`PriceAmountCurrencyID`) REFERENCES `fast_currency` (`currency`) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `fast_supplierparty`
  ADD CONSTRAINT `fast_supplierparty_ibfk_1` FOREIGN KEY (`fast_client`) REFERENCES `fast_client` (`fast_client`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fast_supplierparty_ibfk_2` FOREIGN KEY (`PartyTaxSchemeTaxScheme`) REFERENCES `fast_taxscheme` (`TaxScheme`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fast_supplierparty_ibfk_3` FOREIGN KEY (`PostalAddressCountrySubentity`) REFERENCES `fast_countrysubentity` (`CountrySubentity`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fast_supplierparty_ibfk_4` FOREIGN KEY (`PostalAddressCountry`) REFERENCES `fast_country` (`fast_country`) ON DELETE RESTRICT ON UPDATE CASCADE;
