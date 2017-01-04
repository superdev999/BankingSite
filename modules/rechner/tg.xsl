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

          font-size: 30px;
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
          font-size: 12px;
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
          font-size: 30px;
          padding-left: 2px;
          padding-right: 2px;
          padding-top: 2px;
          padding-bottom: 2px;
          }
        </style>
      </head>
      <BODY class = "mainbody">
        <TABLE border="1" width="100%" cellspacing="0" cellpadding="2">
          <THEAD class="stdHeader">
            <TR>
              <TH colspan="2">Anbieter / Tagesgeldkonto</TH>
              <!--<TH>Testsiegel</TH>-->
              <TH width="2">Rendite</TH>
              <TH>Zinsertrag</TH>
              <!-- <TH>Einlagensicherung</TH>-->
              <!-- <TH>Bewertung BC</TH>-->
              <TH>Infos / Antrag</TH>
            </TR>
          </THEAD>
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

    <TR bgcolor="{$switchingColor}">

      <xsl:apply-templates select="logo" />
      <xsl:apply-templates select="bank"/>
      <!--<xsl:apply-templates select="testsiegel"/>-->
      <xsl:apply-templates select="rendite"/>
      <xsl:apply-templates select="zinsertrag"/>
      <!-- <xsl:apply-templates select="einlagensicherung"/> -->
      <!--xsl:apply-templates select="bewertung-bc"/-->
      <xsl:apply-templates select="link"/>
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
    <TD align="right">
      <xsl:apply-templates /><xsl:text disable-output-escaping="yes">&amp;nbsp;</xsl:text>%
    </TD>
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

  <xsl:template match="link">
    <TD align="center">
      <a>
        <xsl:attribute name="href">
          <xsl:apply-templates />
        </xsl:attribute>
        zur Bank
      </a>
    </TD>
  </xsl:template>

</xsl:stylesheet>