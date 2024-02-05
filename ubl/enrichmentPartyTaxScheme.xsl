<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output omit-xml-declaration="yes" indent="yes"/>
<xsl:variable name="replace">xxx</xsl:variable>
 
 <xsl:strip-space elements="*"/>

 <xsl:template match="node()|@*">
    <xsl:copy>
        <xsl:value-of disable-output-escaping="yes" select="$replace" />
    </xsl:copy>
 </xsl:template>
 
 
 <xsl:template match="node()|@*">
      <xsl:copy>
        <xsl:choose>
            <!--xsl:when test="local-name()='PartyTaxScheme'"-->
            <xsl:when test="local-name()='TaxScheme'">
                <!--xsl:value-of disable-output-escaping="yes" select="$replace" /-->
                <xsl:choose>
                    <!--xsl:when test="(count(descendant-or-self::*/*[name() = 'cac:TaxScheme']) = 1)"-->
                    <xsl:when test="ancestor::*/*[name() = 'cac:PartyTaxScheme']">
                        
                      <!--xsl:value-of select="descendant-or-self::*/*[name() = 'cbc:CompanyID']"/-->  
                        
                        
                      <!--xsl:value-of disable-output-escaping="yes" select="$replace" />
                      <xsl:value-of select="descendant-or-self::*[name() = 'cac:TaxScheme']" />
                      <xsl:value-of select="$myNodeSetVar/*[name() = 'cac:TaxScheme']" /--> 
                    </xsl:when>
                    <xsl:otherwise>
                        <xsl:apply-templates select="node()[boolean(normalize-space())]|@*"/>
                        <!--xsl:apply-templates select="@* | node()"/-->
                    </xsl:otherwise>
                </xsl:choose>
            </xsl:when>
            <xsl:otherwise>
               <!--xsl:apply-templates select="@*|*|processing-instruction()|comment()|text()"/-->
               <xsl:apply-templates select="node()[boolean(normalize-space())]|@*"/>
               <!--xsl:apply-templates select="@* | node()"/-->
            </xsl:otherwise>
         </xsl:choose>
      </xsl:copy>
   </xsl:template>
 

</xsl:stylesheet>