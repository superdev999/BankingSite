<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:msxsl="urn:schemas-microsoft-com:xslt" exclude-result-prefixes="msxsl">
  <xsl:output method="html" indent="yes"/>

  <xsl:template match="/">
    <HTML>
      <head>
        <style type="text/css">
          .mainbody
          {
          font-family: Arial, Helvetica, Sans-Serif;
          color: #000040;
          background-color: #ffffff;//e8edfe;
          }
          .stdHeader
          {

          font-size: 10px;
          font-weight: bold;
          /*color: #8080ff;*/
          margin-bottom:2px;

          }
          .stdHeadingCell
          {
          font-size: 12px;
          text-align:left;
          font-weight: bold;
          background-color: #d6d6ff;
          padding-left: 2px;
          padding-right: 2px;
          padding-top: 2px;
          padding-bottom: 2px;
          }
          table
          {
          font-size: 8px;
          border-color: #000000;
          border-spacing: 0px;
          border-style: solid;
          border-width: 2px;
          empty-cells:show;
          margin-bottom:20px;
          }
          TD
          {
          /*border:solid 1px #ffffff;*/
          font-size: 10px;
          padding-left: 2px;
          padding-right: 2px;
          padding-top: 2px;
          padding-bottom: 2px;
          }
        </style>
      </head>
      <BODY class = "mainbody">
        <TABLE border="1" width="300px" cellspacing="0" cellpadding="2">
          <xsl:apply-templates select="//product"/>
        </TABLE>
      </BODY>
    </HTML>
  </xsl:template>

  <xsl:template match="//product">
    <xsl:variable name="switchingColor" >
      <xsl:choose>
        <xsl:when test="position() mod 2 = 0">
          <xsl:text>#BEBEBE</xsl:text>
        </xsl:when>
        <xsl:otherwise>
          <xsl:text>#EEEEEE</xsl:text>
        </xsl:otherwise>
      </xsl:choose>
    </xsl:variable>

    <TR>
      <xsl:apply-templates select="bank"/>
      <TD align="right">
        <a>
          <xsl:attribute name="href">
            <xsl:apply-templates select="link"/>
          </xsl:attribute>
          <xsl:apply-templates select="rendite"/>
        </a>
      </TD>
    </TR>
  </xsl:template>


  <xsl:template match="logo">
    <TD width="2">
      <xsl:element name="img">
        <xsl:attribute name="src">
          <xsl:value-of select="."/>
        </xsl:attribute>
        <xsl:attribute name="alt"></xsl:attribute>
      </xsl:element>
    </TD>
  </xsl:template>

  <xsl:template match="bank">
    <TD>
      <xsl:apply-templates />
      <!--br/>
      <xsl:value-of select="../produkt"/-->
    </TD>
  </xsl:template>

  <!--<xsl:template match="testsiegel">
    <TD>
      <xsl:apply-templates />
    </TD>
  </xsl:template>-->


  <xsl:template match="rendite">
      <xsl:apply-templates /><xsl:text disable-output-escaping="yes">&amp;nbsp;</xsl:text>%
  </xsl:template>

  <xsl:template match="zinsertrag">
    <TD align="right">
      <xsl:apply-templates /><xsl:text disable-output-escaping="yes">&amp;nbsp;</xsl:text>€
    </TD>
  </xsl:template>

  <!--<xsl:template match="einlagensicherung">
    <TD>
      <xsl:apply-templates />%
    </TD>
  </xsl:template>-->

  <!--<xsl:template match="bewertung-bc">
    <TD>
      <xsl:apply-templates />%
    </TD>
  </xsl:template>-->

  <xsl:template name="replace-string">
    <xsl:param name="text"/>
    <xsl:param name="replace"/>
    <xsl:param name="with"/>
    <xsl:choose>
      <xsl:when test="contains($text,$replace)">
        <xsl:value-of select="substring-before($text,$replace)"/>
        <xsl:value-of select="$with"/>
        <xsl:call-template name="replace-string">
          <xsl:with-param name="text" select="substring-after($text,$replace)"/>
          <xsl:with-param name="replace" select="$replace"/>
          <xsl:with-param name="with" select="$with"/>
        </xsl:call-template>
      </xsl:when>
      <xsl:otherwise>
        <xsl:value-of select="$text"/>
      </xsl:otherwise>
    </xsl:choose>
  </xsl:template>
  
  <xsl:template match="link">
    <xsl:call-template name="replace-string">
      <!-- imported template -->
      <xsl:with-param name="text" select="."/>
      <xsl:with-param name="replace" select="'http://www.financeads.net/de/tc.php?t='"/>
      <xsl:with-param name="with" select="'http://www.bankingcheck.de/zum_Anbieter.php?t='"/>
    </xsl:call-template>
  </xsl:template>
  
</xsl:stylesheet>