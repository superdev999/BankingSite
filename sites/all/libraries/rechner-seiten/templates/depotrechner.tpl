
<%%beginform%%>
<h2 class="rechner-head">Kostenrechner Wertpapierdepot</h2>
<div class="rechner-form girokonto-form">
	<form name="kontenrechner" action="/produkte/depot" method="post" enctype="multipart/form-data" style="border:0px;margin:0px;padding:0px;display:inline;">
	<table width="500" cellspacing="2" cellpadding="3">
	<tr class="fa_zeile">

	<td valign="top" width="50%" class="border-right">
		<table width="100%" cellspacing="0" cellpadding="3">
			<tr>
			<td width="20" valign="middle"><img src="http://www.financeads.net/tools/img/nr_1.gif" alt="Schritt 1" /></td>
			<td class="fa_zeile" valign="top">Durchschnittl. Ordervolumen:<br /><small><i>nur Zahl z.B. 5000</i></small></td>
			<td align="right" valign="top" class="fa_zeile"><div style="white-space:nowrap;">{feld:ordervolumen} &euro;</div></td>
			</tr>
		</table>
	</td>

	<td valign="top" width="50%">
		<table width="100%" cellspacing="0" cellpadding="3">
			<tr>
				<td width="20" valign="middle"><img src="http://www.financeads.net/tools/img/nr_3.gif" alt="Schritt 3" /></td>
				<td class="fa_zeile">Anteil Order &uuml;ber Internet:<br /></td>
				<td align="right" valign="top" class="fa_zeile">
					{feld:orderinternet}
				&nbsp;&nbsp;&nbsp;</td>
			</tr>
		</table>
	</td></tr>
	<tr class="fa_zeile">
	<td valign="top" class="border-right" width="50%">
		<table width="100%" cellspacing="0" cellpadding="3">
			<tr>
				<td width="20" valign="middle"><img src="http://www.financeads.net/tools/img/nr_2.gif" alt="Schritt 2" /></td>
				<td class="fa_zeile">Anzahl Order / Jahr:<br /><small><i>nur Zahl z.B. 2000</i></small></td>
				<td align="right" valign="top" class="fa_zeile">{feld:orderanzahl}&nbsp;&nbsp;&nbsp;</td>

			</tr>
		</table>
	</td>
	<td valign="top" width="50%">
		<table width="100%" cellspacing="0" cellpadding="3">
		<tr>
			<td width="20" valign="middle"><img src="http://www.financeads.net/tools/img/nr_4.gif" alt="Schritt 4" /></td>
			<td class="fa_zeile">Durchschnittl. Depotvolumen:<br /><small><i>nur Zahl z.B. 20000</i></small></td>
			<td align="right" valign="top" class="fa_zeile"><div style="white-space:nowrap;">{feld:depotvolumen} &euro;</div></td></tr>
		</table>
	</td></tr>
	<tr><td colspan="2" align="right" class="submit-giro">

	<center><input type="submit" value="Depots vergleichen" class="fa_button" name="submit" />
	<input type="hidden" name="usersubmit" value="true" /></center>

	</td></tr>
	</table>
	</form>
</div>
<%%endform%%>

<%%begintable_start%%>

	<br />
	<table border="0" cellspacing="2" cellpadding="3" width="500" class="fa-rechner-results">
	<tr>
	<th colspan="8" class="fa_rahmen">Wertpapierdepots nach Gesamtkosten*</th>
	</tr>
	<tr class="fa_zeile">
		<th></th>
		<th colspan="2">Anbieter / Wertpapierdepot</th>
		<th>Kosten Depot p.a.</th>
		<th>Kosten/ Order</th>
		<th>Kosten Gesamt p.a.</th>
		<th>Bewertung</th>
		<th class="last">Mehr Infos /<br />Antrag</th>
	</tr>

<%%begintable_ende%%>


	<%%begindata%%>

		<tr class="fa_zeile">
				<td align="center" valign="middle">
					{nr}.
				</td>
				<td valign="middle" align="center" style="text-align:center;margin:0px;padding:0px;">
					<a href="{freitext1}" rel="follow" onclick="blur()">
						<img src="{logo}" alt="" class="fa_logoimg" border="0" />
					</a>
				</td>
				<td>
					<a href="{freitext1}" rel="follow" onclick="blur()" style="text-decoration:none;">
						<font class="fa_zeile fa_anbieter">
							{bank}
						</font>
					<br />
						<font class="fa_zeile fa_produkt">
							{produkt}
						</font>
					</a>
				</td>
				<td align="center" valign="middle">
					{kosten_depot}&nbsp;&euro;&nbsp;
				</td>
				<td align="center" valign="middle">
					{kosten_order}&nbsp;&euro;&nbsp;
				</td>
				<td align="center" valign="middle">
					<b>{kosten_gesamt}&nbsp;&euro;&nbsp;</b>
				</td>
				<td align="center" valign="middle">{freitext2}</td>
				<td align="center" valign="middle" class="last">
					<a href="{link}" rel="nofollow" onclick="blur()" class="fa_link" target="_blank">zum Anbieter</a>
				</td>
		</tr>

	<%%enddata%%>

	<%%begindata2%%>

		<tr class="fa_zeile2">
				<td align="center" valign="middle">
					{nr}.
				</td>
				<td valign="middle" align="center" style="text-align:center;margin:0px;padding:0px;">
					<a href="{freitext1}" rel="follow" onclick="blur()">
						<img src="{logo}" alt="" class="fa_logoimg" border="0" />
					</a>
				</td>
				<td>
					<a href="{freitext1}" rel="follow" onclick="blur()" style="text-decoration:none;">
						<font class="fa_zeile2 fa_anbieter">
							{bank}
						</font>
					<br />
						<font class="fa_zeile2 fa_produkt">
							{produkt}
						</font>
					</a>
				</td>
				<td align="center" valign="middle">
					{kosten_depot}&nbsp;&euro;&nbsp;
				</td>
				<td align="center" valign="middle">
					{kosten_order}&nbsp;&euro;&nbsp;
				</td>
				<td align="center" valign="middle">
					<b>{kosten_gesamt}&nbsp;&euro;&nbsp;</b>
				</td>
				<td align="center" valign="middle">{freitext2}</td>
				<td align="center" valign="middle" class="last">
					<a href="{link}" rel="nofollow" onclick="blur()" class="fa_link" target="_blank">zum Anbieter</a>
				</td>
		</tr>

	<%%enddata2%%>

<%%endtable_start%%>
	<tr>
		<td colspan="8" class="fa_footnotes">
		* Kosten pro Jahr. Bei Wertpapiertransaktionen k&ouml;nnen zus&auml;tzlich zu den Bankgeb&uuml;hren fremde Spesen anfallen. Alle Angaben ohne Gew&auml;hr
		</td>
	</tr>
	</table>

<%%endtable_ende%%>
