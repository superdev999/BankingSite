


<%%beginform%%>
<h2 class="rechner-head">Zinsrechner Festgeldkonto</h2>
<div class="rechner-form">
	<script src="http://www.financeads.net/javascript/boxover.js" type="text/javascript"></script>

	<form name="kontenrechner" action="/produkte/festgeld" method="post" enctype="multipart/form-data" style="border:0px;margin:0px;padding:0px;display:inline;">
	<table width="500" cellspacing="2" cellpadding="3">
	<tr class="fa_zeile">
	  <td valign="top" width="50%">
		  <table width="100%" cellspacing="0" cellpadding="3">
		  <tr>
			  <td width="20" valign="middle">
          <img src="http://www.financeads.net/tools/img/nr_1.gif" alt="Schritt 1" />
        </td>
			  <td valign="top" class="fa_zeile">Anlagebetrag:<br /><small><i>nur Zahl z.B. 10000</i></small></td>
        <td width="100" class="fa_zeile" align="right" style="white-space:nowrap;">{feld:anlagebetrag} &euro;</td>
		  </tr>
		  </table>
	  </td>
	  <td width="50%">
		  <table width="100%" cellspacing="0" cellpadding="3">
		  <tr>
        <td width="20" valign="middle">
          <img src="http://www.financeads.net/tools/img/nr_2.gif" alt="Schritt 2" />
        </td>
		    <td class="fa_zeile">Anlagedauer:<br /><small><i>bitte w&auml;hlen</i></small></td>
		    <td align="right" valign="middle">{feld:laufzeit}</td>
		  </tr>
		  </table>
	  </td>
  </tr>
    
	<tr>
    <td colspan="2" align="center">
      <input type="submit" value="Festgeldkonten vergleichen" name="submit" class="fa_button" />
      <input type="hidden" name="usersubmit" value="true" />
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
		<th colspan="8" class="fa_rahmen">Festgeldkonten nach Zinsen*</th>
	</tr>
	<tr class="fa_zeile">
		<th></th>
		<th colspan="2">Anbieter / Festgeldkonto</th>
		<th>Zinssatz</th>
		<th>Zinsertrag</th>
		<th>Einlagen-<br />sicherung</th>
		<th>Bewertung</th>
		<th class="last">Mehr Infos /<br />Antrag</th>
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
		<td align="center" valign="middle">{zinssatz}%</td>
		<td align="center" valign="middle">{zinsertrag} &euro;</td>
		<td align="center" valign="middle"><div style="white-space:nowrap;">{einlagensicherung}% {es_land}</div></td>
		<td align="center" valign="middle">{freitext2}</td>
		<td align="center" valign="middle" class="last"><a href="{link}" rel="nofollow" onclick="blur()" target="_blank" class="fa_link">zum Anbieter</a></td>
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
      <a href="{freitext1}" rel="follow" title="{produkt}" onclick="blur()" target="_self" style="text-decoration:none;">
        <font class="fa_zeile fa_anbieter">{bank}</font>
        <br/>
        <font class="fa_zeile fa_produkt">{produkt}</font>
      </a>
    </td>
		<td align="center" valign="middle">{zinssatz}%</td>
		<td align="center" valign="middle">{zinsertrag} &euro;</td>
		<td align="center" valign="middle"><div style="white-space:nowrap;">{einlagensicherung}% {es_land}</div></td>
		<td align="center" valign="middle">{freitext2}</td>
		<td align="center" valign="middle" class="last"><a href="{link}" rel="nofollow" onclick="blur()" target="_blank" class="fa_link">zum Anbieter</a></td>
	</tr>

	<%%enddata2%%>

<%%endtable_start%%>
	<tr>
		<td colspan="8" class="fa_footnotes">
			* Werte f&uuml;r gew&auml;hlten Zeitraum. Alle Angaben ohne Gew&auml;hr
		</td>
	</tr>
	</table>

<%%endtable_ende%%>
