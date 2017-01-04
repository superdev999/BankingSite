<%%beginform%%>
<h2 class="rechner-head">Kostenrechner Girokonto</h2>
<div class="rechner-form girokonto-form">
	<script type="text/javascript">
		function CheckTage (elem) {
			var MT = 30 - elem.value;
			document.kontenrechner.minustage.value = MT;
		}
	</script>

	<form name="kontenrechner" action="/produkte/girokonto" method="post" enctype="multipart/form-data" style="border:0px;margin:0px;padding:0px;display:inline;">

            <table width="500" cellspacing="2" cellpadding="3">
              <tr class="fa_zeile">

                <td valign="top" width="50%" class="border-right">     <!-- *1* oben-links -->

                  <table class="zahlungseingang bluefield" width="100%" cellspacing="2" cellpadding="3" border="0">     <!-- ZAHLUNGSEINGANG -->
                    <tr>
                      <td width="20" valign="middle"><img src="http://www.financeads.net/tools/img/nr_1.gif" alt="Schritt 1"/></td>
                      <td class="fa_zeile" valign="top">Zahlungseingang/Monat:<br /><small><i>nur Zahl z.B. 2000</i></small></td>
                      <td align="right" valign="top" class="fa_zeile">
                        <div style="white-space:nowrap;">{feld:zahlungseingang}</div>
                      </td>
                    </tr>
                  </table>

                </td>
                <td width="50%" valign="top">     <!-- *4* oben-rechts -->

                  <table width="100%" cellspacing="2" cellpadding="3" border="0">     <!-- GUTHABEN -->
                    <tr>
                      <td width="20" valign="middle"><img src="http://www.financeads.net/tools/img/nr_4.gif" alt="Schritt 4"/></td>

                      <td class="fa_zeile">Durchschnittl. Guthaben:<br /><small><i>nur Zahl z.B. 2000</i></small></td>
                      <td align="right" valign="top" class="fa_zeile">
                        <div style="white-space:nowrap;">{feld:guthaben}</div>
                      </td>
                    </tr>
                  </table>

                </td>

              </tr>

              <tr class="fa_zeile">
                <td class="border-right" valign="top">     <!-- *2* mitte-links -->

                  <table class="kreditkarte bluefield" width="100%" cellspacing="0" cellpadding="3" border="0">     <!-- KREDITKARTE -->
                   <tr>
                      <td width="20" valign="middle" rowspan="2"><img src="http://www.financeads.net/tools/img/nr_2.gif" alt="Schritt 2"/></td>
                      <td class="fa_zeile">EC-Karte:<br /></td>

                      <td align="right" valign="top" class="fa_zeile">
                        {feld:eckarte}
                      </td>
                    </tr>
				   <tr>

                      <td class="fa_zeile">Kreditkarte:<br /></td>

                      <td align="right" valign="top" class="fa_zeile">
                        {feld:kreditkarte}
                      </td>
                    </tr>

                  </table>

                </td>
                <td width="50%" valign="top">     <!-- *5* mitte-rechts -->


                  <table class="minus bluefield" width="100%" cellspacing="0" cellpadding="3" border="0">     <!-- MINUS BETRAG -->
                    <tr>
                      <td width="20" valign="middle"><img src="http://www.financeads.net/tools/img/nr_5.gif" alt="Schritt 5"/></td>
                      <td class="fa_zeile">Durchschnittl. Minus:<br /><small><i>nur Zahl z.B. 2000</i></small></td>
                      <td align="right" valign="top" class="fa_zeile">
                        <div style="white-space:nowrap;">{feld:minus}</div>
                      </td>

                    </tr>
                  </table>

                </td>
              </tr>
              <tr class="fa_zeile">
                <td width="50%" class="border-right" valign="top">     <!-- *3* unten-links -->

                  <table class="berufsgruppe bluefield" width="100%" cellspacing="0" cellpadding="3" border="0">     <!-- BERUFSGRUPPE -->
                    <tr>

                      <td width="20" valign="middle" rowspan="2"><img src="http://www.financeads.net/tools/img/nr_3.gif" alt="Schritt 3"/></td>
                      <td class="fa_zeile">Spezielle Gruppe:</td>
					 </tr>
					 <tr>
                      <td align="right" valign="top" class="fa_zeile">
                        {feld:berufsgruppe}
                      </td>
                    </tr>
                  </table>

                </td>
                <td width="50%" valign="top">     <!-- *6* unten-rechts -->

                  <table class="guthabentage bluefield" width="100%" cellspacing="0" cellpadding="3" border="0">     <!-- TAGE -->
                    <tr>
                      <td width="20" valign="middle" rowspan="2"><img src="http://www.financeads.net/tools/img/nr_6.gif" alt="Schritt 6"/></td>

                      <td class="fa_zeile" align="left">Tage mit Guthaben je Monat:</td>
                      <td align="right" valign="top" class="fa_zeile">
                        {feld:guthabentage}
                      </td>
                    </tr>
                    <tr>

                      <td class="fa_zeile" align="left">Tage im Minus je Monat:</td>
                      <td align="right" valign="top" class="fa_zeile">
                        {feld:minustage}
					  </td>
                    </tr>
                  </table>

                </td>
              </tr>

              <tr>
                <td colspan="2" align="right" class="submit-giro">
<center>
                  <input type="submit" class="fa_button" value="Girokonten vergleichen" name="submit" />
				  <input type="hidden" name="usersubmit" value="true" />
</center>
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
		<th colspan="8" class="fa_rahmen">Girokonten nach Kosten / Ertrag*</th>
	</tr>
	<tr class="fa_zeile">
		<th></th>
		<th colspan="2">Anbieter / Girokonto</th>
		<th>Haben- / <br>Dispozins p.a.</th>
		<th>Kosten p.a.</th>
		<th>Ertrag p.a.</th>
		<th>Bewertung</th>
		<th class="last">Mehr Infos /<br />Antrag</th>
	</tr>

<%%begintable_ende%%>


	<%%begindata%%>

		<tr class="fa_zeile">
			  <td align="center" valign="middle">{nr}.</td>
			  <td valign="middle" align="center" style="padding:0px;width:79px;">
				<a href="{freitext1}" rel="follow" onclick="blur()"><img src="{logo}" alt="" width="79" height="28" style="vertical-align:middle;" border="0" /></a></td>
			  <td width="150">
				<a href="{freitext1}" rel="follow" onclick="blur()" style="text-decoration:none;">
					<font class="fa_zeile fa_anbieter">{bank}</font>
					<br />
					<font class="fa_zeile fa_produkt">{produkt}</font></a>  </td>
			  <td align="center" valign="middle">{zinsen_haben}&nbsp;&euro;<br />{zinsen_dispo}&nbsp;&euro;</td>
			  <td align="center" valign="middle">{kosten}&nbsp;&euro;</td>
			  <td align="center" valign="middle"><b>{gesamt}&nbsp;&euro;</b></td>
			  <td align="center" valign="middle">{freitext2}</td>
			  <td align="center" valign="middle" class="last">
				<a href="{link}" rel="nofollow" target="_blank" class="fa_link">zum Anbieter</a></td>
		</tr>

	<%%enddata%%>

	<%%begindata2%%>

		<tr class="fa_zeile2">
			  <td align="center" valign="middle">{nr}.</td>
			  <td valign="middle" align="center" style="padding:0px;width:79px;">
				<a href="{freitext1}" rel="follow" onclick="blur()"><img src="{logo}" alt="" width="79" height="28" style="vertical-align:middle;" border="0" /></a></td>
			  <td width="150">
				<a href="{freitext1}" rel="follow" onclick="blur()" style="text-decoration:none;">
					<font class="fa_zeile2 fa_anbieter">{bank}</font>
					<br />
					<font class="fa_zeile2 fa_produkt">{produkt}</font></a>  </td>
			  <td align="center" valign="middle">{zinsen_haben}&nbsp;&euro;<br />{zinsen_dispo}&nbsp;&euro;</td>
			  <td align="center" valign="middle">{kosten}&nbsp;&euro;</td>
			  <td align="center" valign="middle"><b>{gesamt}&nbsp;&euro;</b></td>
			  <td align="center" valign="middle">{freitext2}</td>
			  <td align="center" valign="middle" class="last">
				<a href="{link}" rel="nofollow" target="_blank" class="fa_link">zum Anbieter</a></td>
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
