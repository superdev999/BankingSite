<style>
.dropdown {
    position: relative;
    display: inline-block;
}

.dropdown-content {
    display: none;
    position: absolute;
    background-color: #f9f9f9;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    padding: 12px 16px;
}

.dropdown:hover .dropdown-content {
    display: block;
}
</style>

<!-- ************ TAGESGELDRECHNER ************ -->
<article id="search" class="a">
	<script type="text/javascript" src="http://www.financeads.net/javascript/boxover.js"></script>
	<h2>Bewertungen von über 500 Banken in ganz Europa! </h2>
	<div class="tabs-a">
		<ul>
			<li class="j"><a href="./"><i class="icon-eye"></i> Suche</a></li>
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
		<!--<span>Begriff eingeben und mit ENTER bestätigen</span>-->
				<div id="search-module" class="form-a">
					<p>
					<?php 
					$block = module_invoke('search', 'block', 'view', 0);
					print $block['content'];
				?>
				</p>
				</div>
				
			<form enctype="multipart/form-data" method="post" action="/produkte/tagesgeld" class="form-a">
				<p>
					<span>
						<label for="anlagebetrag">Anlagebetrag:<span><br>nur Zahl z.B. 10000</span></label>
						<input type="text" value="10000" maxlength="6" name="anlagebetrag" data-icon="icon-euro-circle"/>
					</span>
					<span>
						<label for="laufzeit">Anlagedauer:<span><br>bitte wählen</span></label>
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
			<form enctype="multipart/form-data" method="post" action="/produkte/festgeld" name="kontenrechner" class="form-a">
				<p>
					<span>
						<label for="anlagebetrag">Anlagebetrag:<span><br>nur Zahl z.B. 20000</span></label>
						<input type="text" value="20000" maxlength="6" name="anlagebetrag" data-icon="icon-euro-circle"/>
					</span>
					<span>
					<label for="laufzeit">Anlagedauer:<span><br>bitte wählen</span></label>
						<select name="laufzeit" size="1" >
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
					</span>
					<button type="submit">Vergleichen</button>
				</p>	
			</form>
			<form enctype="multipart/form-data" method="post" action="/produkte/girokonto" name="kontenrechner" class="form-a">
				<script type="text/javascript">
					function CheckTage(elem) {
						var days = elem.value;
						var minusTage = 30 - days;
						document.getElementById("minustage_tpl").value = minusTage;
					}
				</script>
				<p>
					<span>
						<label for="zahlungseingang">Zahlungseingang/Monat: <span><br> nur Zahlen z.B. 1200</span></label>
						<input type="text" maxlength="6" value="1200" name="zahlungseingang" data-icon="icon-euro-circle"></input>		
						<label for="eckarte">EC-Karte:<span><br>auswählen, falls erwünscht</span></label>
						<select name="eckarte">
							<option selected="selected" value="1">Ja</option>
							<option value="0">Nein</option>
						</select>
						<label for="kreditkarte">Kreditkarte:<span><br>auswählen, falls erwünscht</span></label>
						<select name="kreditkarte">
							<option selected="selected" value="1">Ja</option>
							<option value="0">Nein</option>
						</select>
						<label for="berufsgruppe">Spezielle Gruppe:<span><br>Schüler / Student/ Azubi</span></label>
						<select size="1" name="berufsgruppe">
							<option selected="selected" value="">keine</option>
							<option value="Ausbildung">Studenten / Schüler / Azubis</option>
						</select>
					</span>
					<span>
						<label for="durchschnittGuthaben">Durchschnittliches Guthaben:<span><br>nur Zahlen z.B. 2000</span></label>
						<input type="text" placeholder="1000" maxlength="6" value="2000" name="guthaben" data-icon="icon-euro-circle"></input>
						<label for="minus">Durchschnittliches Minus: <span><br> nur Zahl z.B. 2000</span></label>
						<input type="text" placeholder="0" value="0" maxlength="6" name="minus" />
						<label for="guthabentage">Tage mit Guthaben je Monate:<span><br>1 - 30 Tage</span></label>
						<select size="1" onchange="CheckTage(this)" id="tage" name="guthabentage">
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
						<label for="tageMinusJeMonat">Tage im Minus je Monat:<span><br>berechnet sich automatisch</span></label>
						<input type="text" id="minustage_tpl" value="0" maxlength="2" name="minustage" disabled="disabled" />
					</span>
					<button type="submit">Vergleichen</button>
				</p>
			</form>
			<form enctype="multipart/form-data" method="post" action="/produkte/kreditkarte" class="form-a">
				<p>
					<span>
						<label for="umsatzeuroland">Jahresumsatz im Euroland:<span><br>nur Zahl z.B. 20000</span></label>
						<input type="text" value="2000" maxlength="6" name="umsatzeuroland" data-icon="icon-euro-circle" />
						<label for="umsatznichteuroland">Jahresumsatz im Nicht-Euroland:<span><br>nur Zahl z.B. 20000</span></label>
						<input type="text" value="0" maxlength="6" name="umsatznichteuroland" data-icon="icon-euro-circle" />
					</span>
					<span>
						<label for="kartengesellschaft">Kartengestellschaft:<span><br>z.b. Visa oder Mastercard</span></label>
						<select name="kartengesellschaft" size="1">
							<option value="0">alle</option>
							<option value="1">American Express</option>
							<option value="2">Visa</option>
							<option value="3">Mastercard</option>
						</select>
						<label for="anzeige">Ansehen / Status:<span><br>z.b. Gold oder Platin</span></label>
						<select name="anzeige" size="1">
							<option selected="selected" value="0">alle Karten</option>
							<option value="1">Nur Gold- und Platinkarten</option>
							<option value="2">Nur Platinkarten</option>
						</select>
						<label for="zahlungsart">Zahlungsart:<span><br>z.b. Prepaid oder Teilzahlungsfunktion</span></label>
						<select name="zahlungsart" size="1" >
							<option value="0">alle ohne Prepaid</option>
							<option value="1">sofortiger Einzug vom Konto</option>
							<option value="2">monatliche Rechnung</option>
							<option value="3">Teilzahlungsfunktion</option>
							<option value="4">Prepaid</option>
							<option value="5" selected="selected">alle inkl. Prepaid</option>
						</select>
					</span>
					<button type="submit">Vergleichen</button>
				</p>	
			</form>	
			<form enctype="multipart/form-data" method="post" action="/produkte/depot" name="kontenrechner" class="form-a">
				<p>
					<span>
						<label for="ordervolumen">Durchschnittliches Ordervolumen:<span><br>nur Zahl z.B. 1000</span></label>
						<input type="text" value="1000" maxlength="6" name="ordervolumen" data-icon="icon-euro-circle" />
						<label for="orderanzahl">Anzahl Order / Jahr:<span><br>nur Zahl z.B. 12</span></label>
						<input type="text" value="12" maxlength="6" name="orderanzahl" data-icon="icon-euro-circle" />
					</span>
					<span>
						<label for="orderinternet">Anteil Order über das Internet:<span><br>zwischen 0% - 100 % wählen</label>
						<select size="1" name="orderinternet">
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
						<label for="depotvolumen">Durchschnittliches Depotvolumen:<span><br>nur Zahl z.B. 20000</span></label>
						<input type="text" value="20000" maxlength="7" name="depotvolumen" data-icon="icon-euro-circle" />
					</span>
					<button type="submit">Vergleichen</button>
				</p>
			</form>
			<form enctype="multipart/form-data" method="post" action="/produkte/ratenkredit" name="kontenrechner" class="form-a">
				<p>
					<span>
						<label for="kreditbetrag">Kreditbetrag:<span><br>nur Zahl z.B. 10000</span></label>
						<input type="text" value="10000" maxlength="6" name="kreditbetrag" data-icon="icon-euro-circle" />
						<label for="verwendungszweck">Verwendungszweck:<span><br>bitte wählen</span></label>
							<select name="verwendungszweck" size="1" class="ratenkreditrechner_verwendungszweck">
								<option selected="selected" value="free">Freie Verwendung</option>
								<option value="car">Neuwagen / Gebrauchtwagen</option>
							</select> 
					</span>
					<span>
						<label for="laufzeit">Kreditlaufzeit:<span><br>bitte wählen</span></label>
						<select name="laufzeit" size="1">
								<option value="1">1 Monate</option>
								<option value="12">12 Monate</option>
								<option value="24">24 Monate</option>
								<option selected="selected" value="36">36 Monate</option>
								<option value="48">48 Monate</option>
								<option value="60">60 Monate</option>
								<option value="72">72 Monate</option>
								<option value="84">84 Monate</option>
							</select>
					</span>
					<button type="submit">Vergleichen</button>
				</p>
			</form>
			<form enctype="multipart/form-data" method="post" action="/produkte/autokredit" name="autokredit" class="form-a" >
				<p>
					<span>
						<label for="kreditbetrag">Kreditbetrag:<span><br>nur Zahl z.B. 10000</span></label>
						<input type="text" value="10000" maxlength="6" name="kreditbetrag" data-icon="icon-euro-circle" />
					</span>
					<span>
						<label for="laufzeit">Kreditlaufzeit:<span><br>bitte wählen</span></label>
						<select name="laufzeit" size="1">
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
					</span>
					<button type="submit">Vergleichen</button>
				</p>
			</form>		
			<form enctype="multipart/form-data" method="post" action="/produkte/baufinanzierung" class="form-a">
				<p>
					<span>
						<label for="kreditbetrag">Kreditbetrag:<span><br>nur Zahl z.B. 100000</span></label>
						<input name="kreditbetrag" maxlength="6" value="100000" type="text" />
						<label for="beleihung">Beleihung:<span><br>bitte wählen</span></label>
						<select style="visibility: visible;" name="beleihung">
							<option value="60" selected="selected">60 %</option>
							<option value="80">80 %</option>
						</select>
						<label for="regional">Regionale Angebote berücksichtigen:<span><br>bitte PLZ angeben</span></label>
						<input name="regional" placeholder="12345" size="5" type="text">
					</span>
					<span>
						<label for="laufzeit">Kreditlaufzeit:<span><br>bitte wählen</span></label>
						<select style="visibility: visible;" size="1" name="laufzeit">
							<option value="5">5 Jahre</option>
							<option value="10" selected="selected">10 Jahre</option>
							<option value="15">15 Jahre</option>
							<option value="20">20 Jahre</option>
						</select>
						<label for="tilgung">Tilgung:<span><br>bitte wählen</span></label>
						<select style="visibility: visible;" name="tilgung" >
							<option value="1" selected="selected">1%</option>
							<option value="2">2%</option><option value="3">3%</option>
							<option value="4">4%</option><option value="5">5%</option>
							<option value="6">6%</option><option value="7">7%</option>
							<option value="8">8%</option><option value="9">9%</option>
							<option value="10">10%</option>
						</select>
						<label for="tilgung_voll">Volle Tilgung:<span><br>bitte Ja / Nein auswählen</span></label>
						<!--<input name="tilgung_voll" value="1" type="checkbox" />-->
						<select name="tilgung_voll">
							<option value="1">Ja</option>
							<option value="0" selected="selected">Nein</option>
						</select>
					</span>
					<button type="submit">Vergleichen</button> 
				</p>
			</form>	
			<form  method="post" action="/produkte/mietkaution" enctype="multipart/form-data" class="form-a">
				<p>
					<span>
						<label for="kreditbetrag">Kautionsbetrag:<span><br>nur Zahl z.B. 10000</span></label>
						<input name="kreditbetrag" maxlength="6" type="text" value="1500">
					</span>
					<span>
						<label for="laufzeit">Kreditlaufzeit:<span><br>bitte wählen</span></label>
						<select style="visibility: visible;" size="1" name="laufzeit">
							<option value="1">1 Jahr</option><option value="2">2 Jahre</option>
							<option value="3">3 Jahre</option><option value="4">4 Jahre</option>
							<option value="5" selected="selected">5 Jahre</option>
							<option value="6">6 Jahre</option><option value="7">7 Jahre</option>
							<option value="8">8 Jahre</option><option value="9">9 Jahre</option>
							<option value="10">10 Jahre</option>
						</select>
					</span>
					<button id="mietkaution" type="submit">Vergleichen</button>
				</p>
			</form>
			<form  method="post" action="/produkte/geschaeftskonto" enctype="multipart/form-data" class="form-a">
				<p>
					<span>
						<label for="zahlungseingang">Zahlungseingang:<span><br>nur Zahl z.B. 3000</span></label>
						<input name="zahlungseingang" maxlength="6" type="text" value="3000">
						<label for="transaktionen">Beleglose Buchungen/Monat:<span><br> </span></label>
						<input name="transaktionen" maxlength="6" type="text" value="50">
						<label for="eckartecb">Zusatzoptionen:<span><br>ec-Karte</span></label>
						<select name="eckartecb">
							<option value="1">Ja</option>
							<option selected="selected" value="0">Nein</option>
						</select>
						<label for="kreditkartecb">Kreditkarte:<span><br>auswählen, falls erwünscht</span></label>
						<select name="kreditkartecb">
							<option value="1">Ja</option>
							<option selected="selected" value="0">Nein</option>
						</select>
						<label for="filialbanken">Nur Filialbank:<span><br>auswählen, falls erwünscht</span></label>
						<select name="filialbanken">
							<option value="1">Ja</option>
							<option selected="selected" value="0">Nein</option>
						</select>
					</span>
					<span>
						<label for="guthaben">Durchschnittl. Guthaben:<span><br>nur Zahl z.B. 5000</span></label>
						<input name="guthaben" maxlength="6" type="text" value="5000">
						<label for="transaktionenbeleghaft">Beleghafte Buchungen/Monat:<span><br> </span></label>
						<input name="transaktionenbeleghaft" maxlength="6" type="text" value="0">
						<label for="eckartecb">Zusatzoptionen:<span><br>bitte auswählen</span></label>
						<select name="eckartecb">
							<option value="1">Freiberufler</option>
							<option selected="selected" value="2">Einzelunternehmer</option>
							<option value="3">Einzelkaufleute (eK)</option>
							<option value="4">Gesellschaft bürgerlichen Rechts (GbR)</option>
							<option value="5">GmbH / KGaA / AG</option>
							<option value="6">Kommanditgesellschaft (KG)</option>
							<option value="7">Limited (Ltd)</option>
							<option value="8">Offene Handelsgesellschaft (OHG)</option>
							<option value="9">UG (haftungsbeschränkt)</option>
							<option value="10">Sonstige</option>
						</select>
					</span>
					<button type="submit">Vergleichen</button>
				</p>
			</form>
			
				
				
				<script >
				document.getElementById("edit-search-block-form-1").placeholder = "Anbieter oder Produkt suchen...";
				document.getElementById("edit-submit").value = "Los";
				</script>
				
		</div>
	</div>
	<p class="scheme-d">Damit Sie wissen, wem Sie vertrauen</p>
</article>