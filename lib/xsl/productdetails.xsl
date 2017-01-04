<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet
    version="1.0"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:html="http://www.w3.org/1999/xhtml"
>

    <xsl:output
        method="xml"
    />
    <xsl:param name="$fid" />
    <xsl:template match="//product[@fid=$fid]">
    	<table>
    		<thead colspan="2">
    			<tr>
    				<th>Produktdetails</th>
    			</tr>
    		</thead>
    		<tbody>
    			<tr>
    				<th>Zinsen nach Anlagenhöhe</th>
    			</tr>
    			<tr>
    				<td>
    					<xsl:value-of select="balance_min" /> € bis <xsl:value-of select="balance_max" /> €
    				</td>
    				<td>
    					<xsl:value-of select="interest_nom" /> % <xsl:value-of select="balance_max" /> €
    				</td>
    			</tr>
    		</tbody>
    	</table>
    </xsl:template>
</xsl:stylesheet>