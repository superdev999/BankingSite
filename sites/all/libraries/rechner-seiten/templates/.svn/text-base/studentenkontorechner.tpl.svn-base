<%%beginform%%>
	<script type="text/javascript">
		function CheckTage (elem) {
			var MT = 30 - elem.value;
			document.kontenrechner.minustage.value = MT;
		}
	</script>

	<form name="kontenrechner" action="" method="post" enctype="multipart/form-data" style="border:0px;margin:0px;padding:0px;display:inline;">
			            
            <table width="500" cellspacing="2" cellpadding="3">
              <tr><th colspan="2" class="fa_rahmen">Kostenrechner Studentenkonto</th></tr>
              <tr class="fa_zeile">

                <td valign="top" width="50%">     <!-- *1* oben-links -->
                
                  <table width="100%" cellspacing="0" cellpadding="3" border="0">     <!-- ZAHLUNGSEINGANG -->
                    <tr>
                      <td width="20" valign="top"><img src="http://www.financeads.net/tools/img/nr_1.gif" alt="Schritt 1" /></td>
                      <td class="fa_zeile" valign="top">Zahlungseingang/Monat:<br /><small><i>nur Zahl z.B. 2000</i></small></td>
                      <td align="right" valign="top" class="fa_zeile">
                        <div style="white-space:nowrap;">{feld:zahlungseingang} &euro;</div>

                      </td>
                    </tr>
                  </table>
                  
                </td>
                <td width="50%">     <!-- *4* oben-rechts -->
                  
                  <table width="100%" cellspacing="0" cellpadding="3" border="0">     <!-- GUTHABEN -->
                    <tr>
                      <td width="20" valign="top"><img src="http://www.financeads.net/tools/img/nr_4.gif" alt="Schritt 4" /></td>

                      <td class="fa_zeile">Durchschnittl. Guthaben:<br /><small><i>nur Zahl z.B. 2000</i></small></td>
                      <td align="right" valign="top" class="fa_zeile">
                        <div style="white-space:nowrap;">{feld:guthaben} &euro;</div>
                      </td>
                    </tr>
                  </table>
                  
                </td>

              </tr>
              
              <tr class="fa_zeile">
                <td>     <!-- *2* mitte-links -->
                
                  <table width="100%" cellspacing="0" cellpadding="3" border="0">     <!-- KREDITKARTE -->
                   <tr>
                      <td width="20" valign="top"><img src="http://www.financeads.net/tools/img/nr_2.gif" alt="Schritt 2" /></td>
                      <td class="fa_zeile">EC-Karte:<br /></td>

                      <td align="right" valign="top" class="fa_zeile">
                        {feld:eckarte}
                      </td>
                    </tr>
				   <tr>
                      <td width="20" valign="top"></td>
                      <td class="fa_zeile">Kreditkarte:<br /></td>

                      <td align="right" valign="top" class="fa_zeile">
                        {feld:kreditkarte}
                      </td>
                    </tr>
                   
                  </table>
                  
                </td>
                <td width="50%">     <!-- *5* mitte-rechts -->

                
                  <table width="100%" cellspacing="0" cellpadding="3" border="0">     <!-- MINUS BETRAG -->
                    <tr>
                      <td width="20" valign="top"><img src="http://www.financeads.net/tools/img/nr_5.gif" alt="Schritt 5" /></td>
                      <td class="fa_zeile">Durchschnittl. Minus:<br /><small><i>nur Zahl z.B. 2000</i></small></td>
                      <td align="right" valign="top" class="fa_zeile">
                        <div style="white-space:nowrap;">{feld:minus} &euro;</div>
                      </td>

                    </tr>
                  </table>
                  
                </td>
              </tr>
              <tr class="fa_zeile">
                <td width="50%">     <!-- *3* unten-links -->
                
                  <table width="100%" cellspacing="0" cellpadding="3" border="0">     <!-- BERUFSGRUPPE -->
                    <tr>

                      <td width="20" valign="top" rowspan="2"><img src="http://www.financeads.net/tools/img/nr_3.gif" alt="Schritt 3" /></td>
						  <td class="fa_zeile">Hochschulstadt:
							{feld:hochschulstadt}
						  </td>
                    </tr>
					<tr>
						 <td class="fa_zeile">Nur Filialbanken:
							{feld:hochschulstadt_filialbanken}
						</td>
					</tr>
                  </table>
                  
                </td>
                <td width="50%">     <!-- *6* unten-rechts -->
                
                  <table width="100%" cellspacing="0" cellpadding="3" border="0">     <!-- TAGE -->
                    <tr>
                      <td width="20" valign="top"><img src="http://www.financeads.net/tools/img/nr_6.gif" alt="Schritt 6" /></td>

                      <td class="fa_zeile" align="left">Tage mit Guthaben je Monat:</td>
                      <td align="right" valign="top" class="fa_zeile">
                        {feld:guthabentage}                      
                      </td>
                    </tr>
                    <tr>
                      <td width="20" valign="top">&nbsp;</td>

                      <td class="fa_zeile" align="left">Tage im Minus je Monat:</td>
                      <td align="right" valign="top" class="fa_zeile">
                        {feld:minustage}
					  </td>
                    </tr>
                  </table>
                  
                </td>
              </tr>

              <tr>
                <td colspan="2" align="right" class="fa_zeile">    			
				
                  <input type="submit" class="fa_button" value="Girokonten vergleichen" name="submit" />
				  <input type="hidden" name="usersubmit" value="true" />
                
                </td>
              </tr>
            </table>
            
		</form>

<%%endform%%>

<%%begintable_start%%>

	<br />
	<table border="0" cellspacing="2" cellpadding="3" width="500">
	<tr>
		<th colspan="7" class="fa_rahmen">Girokonten nach Gesamtkosten*</th>
	</tr>
	<tr class="fa_zeile">
		<th></th>
		<th colspan="2">Anbieter / Girokonto</th>
		<th>Haben- / Dispozins p.a.</th>
		<th>Kosten p.a.</th>
		<th>Gesamt-<br />kosten p.a.</th>
		<th>Mehr Infos /<br />Antrag</th>	
	</tr>

<%%begintable_ende%%>


	<%%begindata%%>
	
		<tr class="fa_zeile">
			  <td align="center" valign="middle">{nr}.</td>
			  <td valign="middle" align="center" style="padding:0px;width:79px;">
				<a href="{link}" rel="nofollow" target="_blank" onclick="blur()"><img src="{logo}" alt="" width="79" height="28" style="vertical-align:middle;" border="0" /></a></td>
			  <td width="150">
				<a href="{link}" rel="nofollow" target="_blank" onclick="blur()" style="text-decoration:none;">
					<font class="fa_zeile fa_anbieter">{bank}</font>
					<br />
					<font class="fa_zeile fa_produkt">{produkt}</font></a>  </td>
			  <td align="right" valign="middle">{zinsen_haben}&nbsp;&euro;<br />{zinsen_dispo}&nbsp;&euro;</td>
			  <td align="right" valign="middle">{kosten}&nbsp;&euro;</td>
			  <td align="right" valign="middle"><b>{gesamt}&nbsp;&euro;</b></td>
			  <td align="center" valign="middle">
				<a href="{link}" rel="nofollow" target="_blank" class="fa_link">zum Anbieter</a></td>
		</tr>
	
	<%%enddata%%>
	
	<%%begindata2%%>
	
		<tr class="fa_zeile2">
			  <td align="center" valign="middle">{nr}.</td>
			  <td valign="middle" align="center" style="padding:0px;width:79px;">
				<a href="{link}" rel="nofollow" target="_blank" onclick="blur()"><img src="{logo}" alt="" width="79" height="28" style="vertical-align:middle;" border="0" /></a></td>
			  <td width="150">
				<a href="{link}" rel="nofollow" target="_blank" onclick="blur()" style="text-decoration:none;">
					<font class="fa_zeile2 fa_anbieter">{bank}</font>
					<br />
					<font class="fa_zeile2 fa_produkt">{produkt}</font></a>  </td>
			  <td align="right" valign="middle">{zinsen_haben}&nbsp;&euro;<br />{zinsen_dispo}&nbsp;&euro;</td>
			  <td align="right" valign="middle">{kosten}&nbsp;&euro;</td>
			  <td align="right" valign="middle"><b>{gesamt}&nbsp;&euro;</b></td>
			  <td align="center" valign="middle">
				<a href="{link}" rel="nofollow" target="_blank" class="fa_link">zum Anbieter</a></td>
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