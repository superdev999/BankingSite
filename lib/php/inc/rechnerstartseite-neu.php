<!-- ************ TAGESGELDRECHNER ************ -->
<article id="search" class="a">
	<script type="text/javascript" src="http://www.financeads.net/javascript/boxover.js"></script>
	<h2>Bewertungen von über 500 Banken in ganz Europa! </h2>
	<div class="tabs-a">
		<ul>
			<li class="a"><a href="./"><i class="icon-money"></i> Tagesgeld</a></li>
			<li class="b"><a href="./"><i class="icon-lock"></i> Festgeld</a></li>
			<li class="c"><a href="./"><i class="icon-wallet"></i> Girokonto</a></li>
			<li class="d"><a href="./"><i class="icon-credit-card"></i> Kreditkarte</a></li>
			<li class="e"><a href="./"><i class="icon-graph"></i> Depot</a></li>
			<li class="f"><a href="./"><i class="icon-calendar"></i> ratenKredit</a></li>
			<li class="g"><a href="./"><i class="icon-car"></i> AutoKredit</a></li>
			<li class="h"><a href="./"><i class="icon-home"></i> Baufi</a></li>
			<li class="i"><a href="./"><i class="icon-key"></i> mehr</a></li>
		</ul>
		<div>
			<form enctype="multipart/form-data" method="post" action="/produkte/tagesgeld" class="form-a">
				<p>
					<span>
						<label for="anlagebetrag">Anlagebetrag <span>(nur Zahl z.B. 10000)</span></label>
						<input type="text" placeholder="10 000" maxlength="6" name="anlagebetrag" data-icon="icon-euro-circle"></input>
					</span>
					<span>
						<label for="laufzeit">Anlagedauer <span>(bitte wählen)</span></label>
						<select name="laufzeit" size="1" >
							<option value="1">1 Monat</option>
							<option selected="selected" value="3">3 Monate</option>
							<option value="6">6 Monate</option>
							<option value="9">9 Monate</option>
							<option value="12">12 Monate</option>
						</select>
					</span>
					<button type="submit">Vergleichen</button>
				</p>
			</form>
			<form style="border:0px;margin:0px;padding:0px;display:inline;" enctype="multipart/form-data" method="post" action="/produkte/festgeld" name="kontenrechner">
				<table width="500" cellspacing="2" cellpadding="3">
					<tbody>
						<tr class="fa_zeile">
							<td width="50%" valign="top">
								<table width="100%" cellspacing="0" cellpadding="3">
									<tbody>
										<tr>
											<td width="20" valign="middle">
												<img alt="Schritt 1 - Anlagebetrag eingeben" src="http://www.financeads.net/tools/img/nr_1.gif" />
											</td>
											<td valign="top" class="fa_zeile">Anlagebetrag:<br/>
												<small><i>nur Zahl z.B. 10000</i></small>
											</td>
											<td valign="top" align="right" class="fa_zeile">
												<div style="white-space:nowrap;">
													<input type="text" class="festgeldrechner_anlagebetrag" value="20000" maxlength="6" name="anlagebetrag"> €</input>
												</div>
											</td>
										</tr>
									</tbody>
								</table>
							</td>
							<td width="50%">
								<table width="100%" cellspacing="0" cellpadding="3">
									<tbody>
										<tr>
											<td width="20" valign="middle">
												<img alt="Schritt 2 - Anlagedauer auswählen" src="http://www.financeads.net/tools/img/nr_2.gif" />
											</td>
											<td class="fa_zeile">Anlagedauer:<br/>
												<small><i>bitte wählen</i></small>
											</td>
											<td valign="top" align="right">
												<select size="1" class="festgeldrechner_laufzeit" name="laufzeit">
													<option value="1">1 Monat</option>
													<option value="3">3 Monate</option>
													<option value="6">6 Monate</option>
													<option value="9">9 Monate</option>
													<option selected="selected" value="12">12 Monate</option>
													<option value="24">24 Monate</option>
													<option value="36">36 Monate</option>
													<option value="48">48 Monate</option>
													<option value="60">60 Monate</option>
													<option value="72">72 Monate</option>
													<option value="84">84 Monate</option>
													<option value="120">120 Monate</option>
												</select>
											</td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
						<tr class="fa_zeile">
							<td align="right" colspan="2">
								<center>
									<input type="submit" class="fa_button" name="submit" value="Festgeldkonten vergleichen" />
									<input type="hidden" value="true" name="usersubmit" />
								</center>
							</td>
						</tr>
					</tbody>
				</table>
			</form>
			<form style="border:0px;margin:0px;padding:0px;display:inline;" enctype="multipart/form-data" method="post" action="/produkte/girokonto" name="kontenrechner">
				<script type="text/javascript">
					function CheckTage (elem) {
						var MT = 30 - elem.value;
						document.kontenrechner.minustage.value = MT;
					}
				</script>
				<table width="500" cellspacing="2" cellpadding="3">
					<tbody><tr class="fa_zeile">
						<td width="50%" valign="top" class="border-right">
							<!-- *1* oben-links -->
							<table width="100%" cellspacing="2" cellpadding="3" border="0" class="zahlungseingang bluefield">
								<!-- ZAHLUNGSEINGANG -->
								<tbody>
									<tr>
									<td width="20" valign="middle">
										<img alt="Schritt 1 - Zahlungseingang/Monat eingeben" src="http://www.financeads.net/tools/img/nr_1.gif" />
									</td>
									<td valign="top" class="fa_zeile">Zahlungseingang/Monat:<br/>
										 <small><i>nur Zahl z.B. 2000</i></small>
									</td>
										<td valign="top" align="right" class="fa_zeile">
											<div style="white-space:nowrap;">
												<input type="text" class="girokontorechner_zahlungseingang" value="1200" maxlength="6" name="zahlungseingang" />
											</div>
										</td>
									</tr>
								</tbody>
							</table>
						</td>
						<td width="50%" valign="top">
							<!-- *4* oben-rechts -->
							<table width="100%" cellspacing="2" cellpadding="3" border="0">
								<!-- GUTHABEN -->
								<tbody>
									<tr>
										<td width="20" valign="middle">
											<img alt="Schritt 4 - Durchschnittl. Guthaben eingeben" src="http://www.financeads.net/tools/img/nr_4.gif" />
										</td>
										<td class="fa_zeile">Durchschnittl. Guthaben:<br/>
										<small>
											<i>nur Zahl z.B. 2000</i>
										</small>
										</td>
										<td valign="top" align="right" class="fa_zeile">
											<div style="white-space:nowrap;">
												<input type="text" class="girokontorechner_guthaben" value="1000" maxlength="6" name="guthaben" />
											</div>
										</td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>
						<tr class="fa_zeile">
							<td valign="top" class="border-right">
								<!-- *2* mitte-links -->
								<table width="100%" cellspacing="0" cellpadding="3" border="0" class="kreditkarte bluefield">
									<!-- KREDITKARTE -->
									<tbody>
										<tr>
											<td width="20" valign="middle" rowspan="2">
												<img alt="Schritt 2 - Karte(n) wählen" src="http://www.financeads.net/tools/img/nr_2.gif" />
											</td>
											<td class="fa_zeile">EC-Karte:<br/>
											</td>
											<td valign="top" align="right" class="fa_zeile">
												<input type="radio" class="girokontorechner_eckarte" checked="checked" value="1" name="eckarte" />
												&nbsp;ja&nbsp;&nbsp;&nbsp;
												<input type="radio" class="girokontorechner_eckarte" value="0" name="eckarte"/>
												&nbsp;nein
											</td>
										</tr>
										<tr>
											<td class="fa_zeile">Kreditkarte:<br/>
											</td>
											<td valign="top" align="right" class="fa_zeile">
												<input type="radio" class="girokontorechner_kreditkarte" checked="checked" value="1" name="kreditkarte" />
												&nbsp;ja&nbsp;&nbsp;&nbsp;
												<input type="radio" class="girokontorechner_kreditkarte" value="0" name="kreditkarte" />
													&nbsp;nein
												</td>
										</tr>
									</tbody>
								</table>
							</td>
							<td width="50%" valign="top">
								<!-- *5* mitte-rechts -->
								<table width="100%" cellspacing="0" cellpadding="3" border="0" class="minus bluefield">
									<!-- MINUS BETRAG -->
									<tbody><tr>
										<td width="20" valign="middle">
											<img alt="Schritt 5 - Durchschnittl. Minus eingeben" src="http://www.financeads.net/tools/img/nr_5.gif" />
										</td>
										<td class="fa_zeile">Durchschnittl. Minus:<br/>
										<small>
											<i>nur Zahl z.B. 2000</i>
										</small>
										</td>
										<td valign="top" align="right" class="fa_zeile">
											<div style="white-space:nowrap;">
												<input type="text" class="girokontorechner_minus" value="0" maxlength="6" name="minus" />
											</div>
										</td>
									</tr>
									</tbody>
								</table>
							</td>
						</tr>
						<tr class="fa_zeile">
							<td width="50%" valign="top" class="border-right">
								<!-- *3* unten-links -->
								<table width="100%" cellspacing="0" cellpadding="3" border="0" class="berufsgruppe bluefield">
									<!-- BERUFSGRUPPE -->
									<tbody>
										<tr>
											<td width="20" valign="middle" rowspan="2">
												<img alt="Schritt 3" src="http://www.financeads.net/tools/img/nr_3.gif" />
											</td>
											<td class="fa_zeile">Spezielle Gruppe:</td>
										</tr>
										<tr>
											<td valign="top" align="right" class="fa_zeile">
												<select class="girokontorechner_berufsgruppe" size="1" name="berufsgruppe">
													<option selected="selected" value="">Keine</option>
													<option value="Ausbildung">Studenten / Schüler / Azubis</option>
												</select>
											</td>
										</tr>
									</tbody>
								</table>
							</td>
							<td width="50%" valign="top">
								<!-- *6* unten-rechts -->
								<table width="100%" cellspacing="0" cellpadding="3" border="0" class="guthabentage bluefield">
									<!-- TAGE -->
									<tbody>
										<tr>
											<td width="20" valign="middle" rowspan="2">
												<img alt="Schritt 6 - Tage mit Guthaben je Monat auswählen" src="http://www.financeads.net/tools/img/nr_6.gif" />
											</td>
											<td align="left" class="fa_zeile">Tage mit Guthaben je Monat:</td>
											<td valign="top" align="right" class="fa_zeile">
												<select class="girokontorechner_guthabentage" onchange="CheckTage(this)" name="guthabentage">
													<option selected="selected" value="30">30</option>
													<option value="29">29</option>
													<option value="28">28</option>
													<option value="27">27</option>
													<option value="26">26</option>
													<option value="25">25</option>
													<option value="24">24</option>
													<option value="23">23</option>
													<option value="22">22</option>
													<option value="21">21</option>
													<option value="20">20</option>
													<option value="19">19</option>
													<option value="18">18</option>
													<option value="17">17</option>
													<option value="16">16</option>
													<option value="15">15</option>
													<option value="14">14</option>
													<option value="13">13</option>
													<option value="12">12</option>
													<option value="11">11</option>
													<option value="10">10</option>
													<option value="9">9</option>
													<option value="8">8</option>
													<option value="7">7</option>
													<option value="6">6</option>
													<option value="5">5</option>
													<option value="4">4</option>
													<option value="3">3</option>
													<option value="2">2</option>
													<option value="1">1</option>
												</select>
											</td>
										</tr>
										<tr>
											<td align="left" class="fa_zeile">Tage im Minus je Monat:</td>
											<td valign="top" align="right" class="fa_zeile">
												<input type="text" id="minustage_tpl" class="girokontorechner_minustage" value="0" maxlength="2" name="minustage" disabled="disabled" />
											</td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
						<tr>
							<td align="right" class="submit-giro" colspan="2">
								<center>
									<input type="submit" name="submit" value="Girokonten vergleichen" class="fa_button" />
									<input type="hidden" value="true" name="usersubmit" />
								</center>
							</td>
						</tr>
					</tbody>
				</table>
			</form>
			<form style="border:0px;margin:0px;padding:0px;display:inline;" enctype="multipart/form-data" method="post" action="/produkte/kreditkarte" name="kontenrechner">
				<table width="500" cellspacing="2" cellpadding="3">
					<tbody>
						<tr class="fa_zeile">
							<td width="50%" valign="top">
								<table width="100%" cellspacing="0" cellpadding="3">
									<tbody>
										<tr>
											<td width="20" valign="middle">
												<img alt="Schritt 1" src="http://www.financeads.net/tools/img/nr_1.gif" />
											</td>
											<td valign="top" class="fa_zeile">Jahresumsatz im Euroland:<br>
												<small><i>nur Zahl z.B. 20000</i></small></td>
											<td valign="top" align="right" class="fa_zeile">
												<div style="white-space:nowrap;"><input type="text" value="2500" maxlength="6" name="umsatzeuroland" class="kreditkarterechner_umsatzeuroland"> €</div>
											</td>
										</tr>
									</tbody>
								</table>
							</td>
							<td width="50%" valign="top" rowspan="2" style="background-color:#dfe6f0">
								<table width="100%" height="128" cellspacing="0" cellpadding="3">
									<tbody>
										<tr>
											<td width="20" valign="middle" rowspan="4"><img alt="Schritt 3" src="http://www.financeads.net/tools/img/nr_3.gif"></td>
											<td class="fa_zeile" colspan="2">Nur bestimmte Karten anzeigen</td>
										</tr>
							 			<tr>
											<td class="fa_zeile">Kartengesellschaft</td>
											<td valign="top" align="right" class="fa_zeile">
												<select name="kartengesellschaft" size="1" class="kreditkarterechner_kartengesellschaft">
													<option value="0">alle</option>
													<option value="1">American Express</option>
													<option value="2">Visa</option>
													<option value="3">Mastercard</option>
												</select>
											</td>
										</tr>
										<tr>
											<td class="fa_zeile">Ansehen / Status</td>
											<td valign="top" align="right" class="fa_zeile">
												<select name="anzeige" size="1" class="kreditkarterechner_anzeige">
													<option selected="selected" value="0">alle Karten</option>
													<option value="1">Nur Gold- und Platinkarten</option>
													<option value="2">Nur Platinkarten</option>
												</select>
											</td>
										</tr>
										<tr>
											<td class="fa_zeile">Zahlungsart</td>
											<td valign="top" align="right" class="fa_zeile">
												<select name="zahlungsart" size="1" class="kreditkarterechner_zahlungsart">
													<option value="0">alle ohne Prepaid</option>
													<option value="1">sofortiger Einzug vom Konto</option>
													<option value="2">monatliche Rechnung</option>
													<option value="3">Teilzahlungsfunktion</option>
													<option value="4">Prepaid</option>
													<option value="5" selected="selected">alle inkl. Prepaid</option>
												</select>
											</td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
						<tr class="fa_zeile">
							<td width="50%" valign="top">
								<table width="100%" cellspacing="0" cellpadding="3">
									<tbody>
										<tr>
											<td width="20" valign="middle"><img alt="Schritt 2" src="http://www.financeads.net/tools/img/nr_2.gif"></td>
											<td valign="top" class="fa_zeile">Jahresumsatz im Nicht-Euroland:<br>
												<small><i>nur Zahl z.B. 20000</i></small>
											</td>
											<td valign="top" align="right" class="fa_zeile">
												<div style="white-space:nowrap;"><input type="text" value="0" maxlength="6" name="umsatznichteuroland" class="kreditkarterechner_umsatznichteuroland"> €</div>
											</td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
						<tr class="fa_zeile">
							<td align="right" colspan="2">
								<center>
									<input type="submit" name="submit" class="fa_button" value="Kreditkarten vergleichen" alt="Kreditkarten vergleichen">
									<input type="hidden" value="true" name="usersubmit">
								</center>
							</td>
						</tr>
					</tbody>
				</table>
			</form>
			<form style="border:0px;margin:0px;padding:0px;display:inline;" enctype="multipart/form-data" method="post" action="/produkte/depot" name="kontenrechner">
				<table width="500" cellspacing="2" cellpadding="3">
					<tbody>
						<tr class="fa_zeile">
							<td width="50%" valign="top" class="border-right">
								<table width="100%" cellspacing="0" cellpadding="3">
									<tbody>
										<tr>
											<td width="20" valign="middle"><img alt="Schritt 1" src="http://www.financeads.net/tools/img/nr_1.gif"></td>
											<td valign="top" class="fa_zeile">Durchschnittl. Ordervolumen:<br><small><i>nur Zahl z.B. 5000</i></small></td>
											<td valign="top" align="right" class="fa_zeile"><div style="white-space:nowrap;"><input type="text" class="depotrechner_ordervolumen" value="1000" maxlength="6" name="ordervolumen"> €</div></td>
										</tr>
									</tbody>
								</table>
							</td>
							<td width="50%" valign="top">
								<table width="100%" cellspacing="0" cellpadding="3">
									<tbody>
										<tr>
											<td width="20" valign="middle"><img alt="Schritt 3" src="http://www.financeads.net/tools/img/nr_3.gif"></td>
											<td class="fa_zeile">Anteil Order über Internet:<br></td>
											<td valign="top" align="right" class="fa_zeile">
												<select class="depotrechner_orderinternet" size="1" name="orderinternet">
													<option selected="selected" value="100">100%</option>
													<option value="90">90%</option>
													<option value="80">80%</option>
													<option value="70">70%</option>
													<option value="60">60%</option>
													<option value="50">50%</option>
													<option value="40">40%</option>
													<option value="30">30%</option>
													<option value="20">20%</option>
													<option value="10">10%</option>
													<option value="0">0%</option>
												</select>
												&nbsp;&nbsp;&nbsp;
											</td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
						<tr class="fa_zeile">
							<td width="50%" valign="top" class="border-right">
								<table width="100%" cellspacing="0" cellpadding="3">
									<tbody>
										<tr>
											<td width="20" valign="middle"><img alt="Schritt 2" src="http://www.financeads.net/tools/img/nr_2.gif"></td>
											<td class="fa_zeile">Anzahl Order / Jahr:<br><small><i>nur Zahl z.B. 2000</i></small></td>
											<td valign="top" align="right" class="fa_zeile"><input type="text" class="depotrechner_orderanzahl" value="12" maxlength="6" name="orderanzahl">&nbsp;&nbsp;&nbsp;</td>
										</tr>
									</tbody>
								</table>
							</td>
							<td width="50%" valign="top">
								<table width="100%" cellspacing="0" cellpadding="3">
									<tbody>
										<tr>
											<td width="20" valign="middle"><img alt="Schritt 4" src="http://www.financeads.net/tools/img/nr_4.gif"></td>
											<td class="fa_zeile">Durchschnittl. Depotvolumen:<br><small><i>nur Zahl z.B. 20000</i></small></td>
											<td valign="top" align="right" class="fa_zeile"><div style="white-space:nowrap;"><input type="text" class="depotrechner_depotvolumen" value="20000" maxlength="7" name="depotvolumen"> €</div></td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
						<tr>
							<td align="right" class="submit-giro" colspan="2">
								<center>
									<input type="submit" name="submit" class="fa_button" value="Depots vergleichen">
									<input type="hidden" value="true" name="usersubmit">
								</center>
							</td>
						</tr>
					</tbody>
				</table>
			</form>
			<form style="border:0px;margin:0px;padding:0px;display:inline;" enctype="multipart/form-data" method="post" action="/produkte/ratenkredit" name="kontenrechner">
				<table width="500" cellspacing="2" cellpadding="3">
					<tbody>
						<tr class="fa_zeile">
							<td width="50%" valign="top">
								<table width="100%" cellspacing="0" cellpadding="3">
									<tbody>
										<tr>
											<td width="20" valign="middle">
												<img alt="Schritt 1" src="http://www.financeads.net/tools/img/nr_1.gif">
											</td>
											<td width="160" valign="top" class="fa_zeile">Kreditbetrag:<br><small><i>nur Zahl z.B. 10000</i></small></td>
											<td width="100" valign="top" align="right" class="fa_zeile"><div style="white-space:nowrap;"><input type="text" value="10000" maxlength="6" name="kreditbetrag" class="ratenkreditrechner_kreditbetrag"> €</div></td>
										</tr>
									</tbody>
								</table>
							</td>
							<td width="50%" valign="top">
								<table width="100%" cellspacing="0" cellpadding="3">
									<tbody>
										<tr>
											<td width="20" valign="middle"><img alt="Schritt 2" src="http://www.financeads.net/tools/img/nr_2.gif"></td>
											<td width="160" class="fa_zeile">Kreditlaufzeit:<br><small><i>bitte wählen</i></small></td>
											<td width="120" valign="top" align="right">
												<select name="laufzeit" size="1" class="ratenkreditrechner_laufzeit">
													<option value="1">1 Monate</option>
													<option value="12">12 Monate</option>
													<option value="24">24 Monate</option>
													<option selected="selected" value="36">36 Monate</option>
													<option value="48">48 Monate</option>
													<option value="60">60 Monate</option>
													<option value="72">72 Monate</option>
													<option value="84">84 Monate</option>
												</select>
											</td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
						<tr class="fa_zeile">
							<td colspan="2">
								<table width="100%" cellspacing="0" cellpadding="3" border="0">
									<tbody>
										<tr>
											<td width="20" valign="middle"><img alt="Schritt 3" src="http://www.financeads.net/tools/img/nr_3.gif"></td>
											<td align="left" class="fa_zeile">Verwendungszweck:<br><small><i>bitte wählen</i></small></td>
											<td width="120" valign="top" align="right">
												<select name="verwendungszweck" size="1" class="ratenkreditrechner_verwendungszweck"><option selected="selected" value="free">Freie Verwendung</option><option value="car">Neuwagen / Gebrauchtwagen</option></select>
											</td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
						<tr class="fa_zeile">
							<td align="right" colspan="2">
								<center>
									<input type="hidden" value="" name="channel">
									<input type="hidden" value="" name="sub">
									<input type="submit" class="fa_button" name="submit" value="Ratenkredite vergleichen">
									<input type="hidden" value="true" name="usersubmit">
								</center>
							</td>
						</tr>
					</tbody>
				</table>
			</form>
			<form style="border:0px;margin:0px;padding:0px;display:inline;" enctype="multipart/form-data" method="post" action="/produkte/autokredit" name="autokredit">
				<table width="500" cellspacing="2" cellpadding="3">
					<tbody>
						<tr class="fa_zeile">
							<td width="50%" valign="top">
								<table width="100%" cellspacing="0" cellpadding="3">
									<tbody>
										<tr>
											<td width="20" valign="middle">
												<img alt="Schritt 1" src="http://www.financeads.net/tools/img/nr_1.gif">
											</td>
											<td width="160" valign="top" class="fa_zeile">Kreditbetrag:<br><small><i>nur Zahl z.B. 10000</i></small></td>
											<td width="100" valign="top" align="right" class="fa_zeile"><div style="white-space:nowrap;"><input type="text" value="10000" maxlength="6" name="kreditbetrag" class="ratenkreditrechner_kreditbetrag"> €</div></td>
										</tr>
									</tbody>
								</table>
							</td>
							<td width="50%" valign="top">
								<table width="100%" cellspacing="0" cellpadding="3">
									<tbody>
										<tr>
											<td width="20" valign="middle"><img alt="Schritt 2" src="http://www.financeads.net/tools/img/nr_2.gif"></td>
											<td width="160" class="fa_zeile">Kreditlaufzeit:<br><small><i>bitte wählen</i></small></td>
											<td width="120" valign="top" align="right">
												<select name="laufzeit" size="1" class="ratenkreditrechner_laufzeit">
													<option value="1">1 Monate</option>
													<option value="12">12 Monate</option>
													<option value="24">24 Monate</option>
													<option selected="selected" value="36">36 Monate</option>
													<option value="48">48 Monate</option>
													<option value="60">60 Monate</option>
													<option value="72">72 Monate</option>
													<option value="84">84 Monate</option>
													<option value="96">96 Monate</option>
													<option value="120">120 Monate</option>
												</select>
											</td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
						<tr class="fa_zeile">
							<td align="right" colspan="2">
								<center>
									<input type="hidden" value="" name="channel">
									<input type="hidden" value="" name="sub">
									<input type="submit" class="fa_button" name="submit" value="Autokredite vergleichen">
									<input type="hidden" value="true" name="usersubmit">
								</center>
							</td>
						</tr>
					</tbody>
				</table>
			</form>
			<form action="/produkte/baufinanzierung" method="post" enctype="multipart/form-data" style="border:0px;margin:0px;padding:0px;display:inline;">
				<table cellspacing="2" cellpadding="3" width="500">
				<tbody><tr class="fa_zeile">
				<td valign="top" width="50%">
				<table cellspacing="0" cellpadding="3" width="100%">
				<tbody><tr>
				<td valign="middle" width="20">
				<img src="http://www.financeads.net/tools/img/nr_1.gif" alt="Schritt 1">
				</td>
				<td class="fa_zeile" align="left" valign="top" width="160">Kreditbetrag:<br><small><i>nur Zahl z.B. 10000</i></small></td>
				<td class="fa_zeile" style="white-space:nowrap;" align="right" width="100"><input class="ratenkreditrechner_kreditbetrag" name="kreditbetrag" maxlength="6" value="100000" type="text"> €</td>
				</tr>
				</tbody></table>
				</td>
				<td width="50%">
				<table cellspacing="0" cellpadding="3" width="100%">
				<tbody><tr>
				<td class="fa_zeile" valign="middle" width="20">
				<img src="http://www.financeads.net/tools/img/nr_2.gif" alt="Schritt 2">
				</td>
				<td class="fa_zeile" align="top" width="160">
				Kreditlaufzeit:<br><small><i>bitte wählen</i></small>
				</td>
				<td align="right" valign="middle" width="120">
				<select style="visibility: visible;" class="ratenkreditrechner_laufzeit" size="1" name="laufzeit"><option value="5">5 Jahre</option><option value="10" selected="selected">10 Jahre</option><option value="15">15 Jahre</option><option value="20">20 Jahre</option>
				</select>

				</td>
				</tr>
				</tbody></table>
				</td>
				</tr>
				<tr class="fa_zeile">
				<td valign="top" width="50%">
				<table cellspacing="0" cellpadding="3" width="100%">
				<tbody><tr>
				<td valign="middle" width="20">
				<img src="http://www.financeads.net/tools/img/nr_3.gif" alt="Schritt 3">
				</td>
				<td class="fa_zeile" align="left" valign="top">Beleihung:<br><small><i>bitte wählen</i></small></td>
				<td class="fa_zeile" style="white-space:nowrap;" align="right"><select style="visibility: visible;" name="beleihung" class="baufinanzierungrechner_beleihung">
				<option value="60" selected="selected">60 %</option>
				<option value="80">80 %</option>
				</select>
				</td>
				</tr>
				</tbody></table>
				</td>
				<td width="50%">
				<table cellspacing="0" cellpadding="3" width="100%">
				<tbody><tr>
				<td class="fa_zeile" valign="middle" width="20">
				<img src="http://www.financeads.net/tools/img/nr_4.gif" alt="Schritt 4">
				</td>
				<td class="fa_zeile" align="top" width="160">
				Tilgung:<br><small><i>bitte wählen</i></small>
				</td>
				<td style="padding-right:4px;" align="right" valign="middle">
				<select style="visibility: visible;" name="tilgung" class="baufinanzierungrechner_tilgung"><option value="1" selected="selected">1%</option><option value="2">2%</option><option value="3">3%</option><option value="4">4%</option><option value="5">5%</option><option value="6">6%</option><option value="7">7%</option><option value="8">8%</option><option value="9">9%</option><option value="10">10%</option></select>
				<input name="tilgung_voll" value="1" type="checkbox">	voll
				</td>
				</tr>
				</tbody></table>
				</td>
				</tr>
				<tr class="fa_zeile">
				<td colspan="2" valign="top" width="100%">
				<table cellspacing="0" cellpadding="3" width="100%">
				<tbody><tr>
				<td valign="middle" width="20">
				<img src="http://www.financeads.net/tools/img/nr_5.gif" alt="Schritt 5" style="margin-left:2px;">
				</td>
				<td class="fa_zeile" align="left" valign="top">Nur regionale Angebote aus meiner Region berücksichtigen (bitte PLZ angeben): <input name="regional" value="PLZ" size="5" class="baufinanzierungrechner_regional" type="text"></td>
				</tr>
				</tbody></table>
				</td>
				</tr>
				<tr>
				<td colspan="2" align="center">
				<input name="channel" value="" type="hidden">
				<input name="sub" value="" type="hidden">
				<input value="Baufinanzierungen vergleichen" name="submit" class="fa_button" type="submit">
				<input name="usersubmit" value="true" type="hidden">
				</td>
				</tr>
				</tbody></table>
			</form>
			<form action="/produkte/mietkaution" method="post" enctype="multipart/form-data" style="border:0px;margin:0px;padding:0px;display:inline;">
				<table cellspacing="2" cellpadding="3" width="500">
				<tbody><tr class="fa_zeile">
				<td valign="top" width="50%">
				<table cellspacing="0" cellpadding="3" width="100%">
				<tbody><tr>
				<td valign="middle" width="20">
				<img src="http://www.financeads.net/tools/img/nr_1.gif" alt="Schritt 1">
				</td>
				<td class="fa_zeile" align="left" valign="top" width="160">Kautionsbetrag:<br><small><i>nur Zahl z.B. 10000</i></small></td>
				<td class="fa_zeile" style="white-space:nowrap;" align="right" width="100"><input class="ratenkreditrechner_kreditbetrag" name="kreditbetrag" maxlength="6" value="1500" type="text"> €</td>
				</tr>
				</tbody></table>
				</td>
				<td width="50%">
				<table cellspacing="0" cellpadding="3" width="100%">
				<tbody><tr>
				<td class="fa_zeile" valign="middle" width="20">
				<img src="http://www.financeads.net/tools/img/nr_2.gif" alt="Schritt 2">
				</td>
				<td class="fa_zeile" align="top" width="160">
				Kreditlaufzeit:<br><small><i>bitte wählen</i></small></td>
				<td align="right" valign="middle" width="120">
				<select style="visibility: visible;" class="ratenkreditrechner_laufzeit" size="1" name="laufzeit"><option value="1">1 Jahr</option><option value="2">2 Jahre</option><option value="3">3 Jahre</option><option value="4">4 Jahre</option><option value="5" selected="selected">5 Jahre</option><option value="6">6 Jahre</option><option value="7">7 Jahre</option><option value="8">8 Jahre</option><option value="9">9 Jahre</option><option value="10">10 Jahre</option>
				</select>
				</td>
				</tr>
				</tbody></table>
				</td>
				</tr>
				<tr>
				<td colspan="2" align="center">
				<input name="channel" value="" type="hidden">
				<input name="sub" value="" type="hidden">
				<input value="Mietkaution vergleichen" name="submit" class="fa_button" type="submit">
				<input name="usersubmit" value="true" type="hidden">
				</td>
				</tr>
				</tbody>
				</table>
			</form>
		</div>
	</div>
	<p class="scheme-d">Damit sie wissen, wem sie vertrauen</p>
</article>