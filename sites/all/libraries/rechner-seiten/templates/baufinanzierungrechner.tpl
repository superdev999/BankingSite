


<%%beginform%%>
<h2 class="rechner-head">Rechner Baufinanzierung</h2>
<div class="rechner-form">
	<script src="http://www.financeads.net/javascript/boxover.js" type="text/javascript"></script>

		<form name="kontenrechner" action="/produkte/baufinanzierung" method="post" enctype="multipart/form-data" style="border:0px;margin:0px;padding:0px;display:inline;">		
		<table width="500" cellspacing="2" cellpadding="3">
		<tr class="fa_zeile">
			<td valign="top" width="50%">
				<table width="100%" cellspacing="0" cellpadding="3">
				<tr>
					<td width="20" valign="middle">
						<img src="http://www.financeads.net/tools/img/nr_1.gif" alt="Schritt 1" />
					</td>
					<td width="160" class="fa_zeile" align="left" valign="top">Kreditbetrag:<br/><small><i>nur Zahl z.B. 10000</i></small></td>
          <td width="100" class="fa_zeile" align="right" style="white-space:nowrap;">{feld:kreditbetrag} &euro;</td>
				</tr>
				</table>
			</td>
			<td width="50%">
				<table width="100%" cellspacing="0" cellpadding="3">
				<tr>
					<td class="fa_zeile" width="20" valign="middle">
            <img src="http://www.financeads.net/tools/img/nr_2.gif" alt="Schritt 2"/>
          </td>
					<td width="160" class="fa_zeile" align="top">
            Kreditlaufzeit:<br/><small><i>bitte w&auml;hlen</i></small>
          </td>
					<td width="120" align="right" valign="middle">
            {feld:laufzeit}
          </td>
				</tr>
				</table>
			</td>
		</tr>
		<tr class="fa_zeile">
			<td valign="top" width="50%">
				<table width="100%" cellspacing="0" cellpadding="3">
				<tr>
					<td width="20" valign="middle">
						<img src="http://www.financeads.net/tools/img/nr_3.gif" alt="Schritt 3" />
					</td>
					<td class="fa_zeile" align="left" valign="top">Beleihung:<br/><small><i>bitte w&auml;hlen</i></small></td>
					<td class="fa_zeile" align="right" style="white-space:nowrap;">{feld:beleihung}</td>
				</tr>
				</table>
			</td>
			<td width="50%">
				<table width="100%" cellspacing="0" cellpadding="3">
				<tr>
					<td class="fa_zeile" width="20" valign="middle">
            <img src="http://www.financeads.net/tools/img/nr_4.gif" alt="Schritt 4"/>
          </td>
					<td width="160" class="fa_zeile" align="top">
            Tilgung:<br/><small><i>bitte w&auml;hlen</i></small>
          </td>
					<td align="right" valign="middle" style="padding-right:4px;">				
						{feld:tilgung}
						{feld:tilgung_voll}	voll
					</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr class="fa_zeile">
			<td valign="top" colspan="2"  width="100%">
			<table width="100%" cellspacing="0" cellpadding="3">
			<tr>
				<td width="20" valign="middle">
          <img src="http://www.financeads.net/tools/img/nr_5.gif" alt="Schritt 5" style="margin-left:2px;" />
        </td>
				<td class="fa_zeile" align="left" valign="top">Nur regionale Angebote aus meiner Region ber&uuml;cksichtigen (bitte PLZ angeben): {feld:regional}</td>
			</tr>
			</table>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center">
				<input type="hidden" name="channel" value=""/>
        <input type="hidden" name="sub" value=""/>
				<input type="submit" value="Baufinanzierungen vergleichen" name="submit" class="fa_button"/>
				<input type="hidden" name="usersubmit" value="true"/>
			</td>
		</tr>
		</table>
		</form>
</div>
<%%endform%%>

<%%begintable_start%%>

  <br />
  <table border="0" cellspacing="2" cellpadding="3" width="500" class="fa-rechner-results">
    <tr>
      <th colspan="7" class="fa_rahmen">Baufinanzierung nach Sollzins / Restschuld*</th>
    </tr>
	<tr>
		<th></th>
		<th colspan="2">Anbieter / Produktname</th>		
		<th>Sollzins /<br/>Restschuld</th>
		<th>Monatl. Rate</th>		
    <th>Bewertung</th>
		<th class="last">Mehr Infos /<br/>Antrag</th>	
	</tr>

<%%begintable_ende%%>

	<%%begindata%%>
	
		<tr class="fa_zeile">
			<td align="center" valign="middle">{nr}.</td>
			<td align="center" valign="middle">
				<a href="{freitext1}" rel="follow" onclick="blur()" target="_self">
          <img src="{logo}" alt="{produkt}" title="{produkt}" width="79" height="28" border="0" />
        </a>
			</td>
			<td align="left" valign="middle">
				<a href="{freitext1}" rel="follow" onclick="blur()" target="_self" style="text-decoration:none;">
					<font class="fa_zeile fa_anbieter">{bank}</font>
					<br/>
					<font class="fa_zeile fa_produkt">{produkt}</font>
				</a>
			</td>
			<td align="right" valign="middle" style="white-space:nowrap;">
				<b>{sollzins} %</b>
        <br/>
        <small>{restschuld} &euro;</small>
			</td>			
			<td align="center" valign="middle" style="white-space:nowrap;">
				<b>{monatlicherate} &euro;</b>
			</td>
      <td align="center" valign="middle">
        {freitext2}
      </td>
			<td align="center" valign="middle" class="last">
				<a href="{link}" rel="nofollow" onclick="blur()" target="_blank" class="fa_link">zum Anbieter</a>
			</td>
		</tr>
	
	<%%enddata%%>
	
	<%%begindata2%%>
	
		<tr class="fa_zeile2">
			<td align="center" valign="middle">{nr}.</td>
			<td align="center" valign="middle">
				<a href="{freitext1}" rel="follow" onclick="blur()" target="_self">
          <img src="{logo}" alt="{produkt}" title="{produkt}" width="79" height="28" border="0" />
        </a>
			</td>
			<td align="left" valign="middle">
        <a href="{freitext1}" rel="follow" onclick="blur()" target="_self" style="text-decoration:none;">
					<font class="fa_zeile2 fa_anbieter">{bank}</font>
					<br/>
					<font class="fa_zeile2 fa_produkt">{produkt}</font>
				</a>
			</td>
			<td align="right" valign="middle" style="white-space:nowrap;">
        <b>{sollzins} %</b>
				<br/>
        <small>{restschuld} &euro;</small>
			</td>			
			<td align="center" valign="middle" style="white-space:nowrap;">
				<b>{monatlicherate} &euro;</b>
			</td>
      <td align="center" valign="middle">
        {freitext2}
      </td>
			<td align="center" valign="middle" class="last">
				<a href="{link}" rel="nofollow" onclick="blur()" target="_blank" class="fa_link">zum Anbieter</a>
			</td>
		</tr>
	
	<%%enddata2%%>
	
<%%endtable_start%%>

  <tr>
    <td colspan="7" class="fa_footnotes">
      * Werte f&uuml;r gew&auml;hlten Zeitraum. Alle Angaben ohne Gew&auml;hr
    </td>
  </tr>
  </table>

<%%endtable_ende%%>	