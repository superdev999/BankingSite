<?php
class BCImportFormats {
	public static function formatValue($nodeName, $value, $nid="0")
	{
		if ($nid == 0)
		{
			//$nid = 0;
			if ($node = menu_get_object())
			{
				// Get the nid
				# Raute entfernen zum aktivieren
				$nid = $node->nid;
			}
		}
		// first choose currency
		// später wird dies ein Feld aus den Daten sein
		if($nid == 34182){
			$currency = 'CHF';}
		else if($nid == 32407){
			$currency = 'NOK';}
		else{
			$currency = "€";}

		switch($nodeName)
		{
			case "Art der Bank":
			case "bankType":
			/* bei FinanceAds gibt es:
			Internetbank
			Filialbank
			Versicherung
			Bausparkasse
			Paymentdienstleister
			Onlinebroker
			Kreditmarktplatz
			Geldanlagemarktplatz
			Finanzdienstleister
			Vermittler
			Sonstiges*/
			
				if($value == "internetbank") {
					return "Direktbank"; }
				elseif($value == "filialbank")
				{
					// nur bei PSD HT
					if ($nid == 3417)
					{
						return "Regionalbank / Genossenschaftsbank";
					}
					// nur bei Berliner Sparkasse
					else if ($nid == 4493) {
						return "Regionalbank";	}
					else {
						return "Filialbank"; }
					
				}
				elseif($value == "versicherung") {
					return "Versicherung"; }
				elseif ($value == "Finanzdienstleister")
				{
					// nur bei VexCash & Cashper & Xpresscredit & KreditUp
					if ($nid == 24431 || $nid == 33501 || $nid == 33485 || $nid == 33569) {
						return "Kurzzeitkredit-Anbieter";
					}
					// nur bei Payango & Payback & VIABUY & Miles & More
					else if ($nid == 236 || $nid == 17672 || $nid == 33519 || $nid == 52190) {
						return "Kreditkarten-Anbieter";	}
					else {
						return "Finanzdienstleister"; }
				}
				elseif ($value == "")
				{
					// KreditUp - Wert noch leer
					if ($nid == 33569) {
						return "Kurzzeitkredit-Anbieter"; }
					else {
						return "Finanzdienstleister"; }
				}
				else {
					return $value; }
				break;

			case "depositprotection":
			case "Höhe der Einlagensicherung":
				if($value == "999999") {
					return "unbegrenzt"; }
				elseif($value == "0") {
					return "nicht vorhanden"; }
				elseif($value == "") {
					return "nicht vorhanden"; }
				else {
					return number_format($value, 0, ",", ".")." €"; }
				break;

			case "depositprotection_name":
			case "Einlagensicherungssystem":
				if($value == "-") {
					return "nicht vorhanden"; }
				elseif($value == "") {
					return "nicht vorhanden"; }
				else {
					return $value; }
				break;

			case "depositprotection_country":
			case "Land der Einlagensicherung":
				if($value == "DE") {
					return "Deutschland - DE"; }
				elseif($value == "NL") {
					return "Niederlande - NL"; }
				elseif($value == "AT") {
					return "Österreich - AT"; }
				elseif($value == "EE") {
					return "Estland - EE"; }
				elseif($value == "LV") {
					return "Lettland - LV"; }
				elseif($value == "BG") {
					return "Bulgarien - BG"; }
				elseif($value == "PT") {
					return "Portugal - PT"; }
				elseif($value == "NO") {
					return "Norwegen - NO"; }
				elseif($value == "IT") {
					return "Italien - IT"; }
				elseif($value == "PL") {
					return "Polen - PL"; }
				elseif($value == "FR") {
					return "Frankreich - FR"; }
				elseif($value == "GB") {
					return "Großbritannien - GB"; }
				elseif($value == "MT") {
					return "Malta - MT"; }
				elseif($value == "IR") {
					return "Irland - IR"; }
				elseif($value == "CZ") {
					return "Tschechien - CZ"; }
				elseif($value == "") {
					return "nicht vorhanden"; }
				elseif($value == "-") {
					return "nicht vorhanden"; }
				else {
					return $value; }
				break;

			case "depositprotection_desc":
				if($value == "") {
					return "nicht vorhanden"; }
				elseif($value == "-") {
					return "nicht vorhanden"; }
				else {
					return $value; }
				break;

			// == depositprotection_desc
			case "Beschreibung Einlagensicherung":
				if($value == "") {
					return "nicht vorhanden"; }
				elseif($value == "-") {
					return "nicht vorhanden"; }
				else {
					return $value; }
				break;


			case "interest_frequency":
				if($value == "1") {
					return "jährlich"; }
				elseif($value == "2") {
					return "halbjährlich"; }
				elseif($value == "4") {
					return "quartalsweise"; }
				elseif($value == "6") {
					return "alle 2 Monate"; }
				elseif($value == "12") {
					return "monatlich"; }
				elseif($value == "360") {
					return "täglich"; }
				break;
			
			// Sonderfall für degussa bank TG, bis 7 stellige Werte von FA geliefert werden
			case "balance_max":
				if($value == "999999" && $nid == 391) {
					return "1 Million Euro"; }
				elseif($value == "999999" ) {
					return "unbegrenzt"; }
				elseif($value == "99" && $nodeName == "balance_max") {
					return "unbegrenzt"; }
				else {
					return number_format($value, 0, ",", ".")." ".$currency; }
				break;
			case "balance_min":
			//case "depositprotection":
			case "incoming_pm_min":
			case "incoming_pm_max":
			case "transaction_min":
			case "transaction_max":
			case "amount_min":
			case "loans_amount_min":
			case "carloans_amount_min":
			case "amount_max":
			case "loans_amount_max":
			case "carloans_amount_max":
			//case "Höhe der Einlagensicherung":
			case "trade_volume_min":
			case "trade_volume_max":
				if($value == "999999") {
					return "unbegrenzt"; }
				elseif($value == "99" && $nodeName == "balance_max") {
					return "unbegrenzt"; }
				else {
					return number_format($value, 0, ",", ".")." ".$currency; }
				break;
			case "trades_pa_min":
			case "trades_pa_max":
				if($value == "999999") {
					return "unbegrenzt";
				} else {
					return number_format($value, 0, ",", ".");
				}
				break;
			case "cost":
			case "ec_cost":
			case "visa_cost":
			case "mastercard_cost":
			case "incentive":
			case "trade_cost_fixed":
			case "trade_cost_min":
			case "trade_cost_max":
			case "trade_cost_add":
			case "account_cost_fixed":
			case "account_cost_variable":
			case "account_cost_min":
			case "account_cost_max":
			case "account_cost_add":
				return number_format($value, 2, ",", ".")." ".$currency;
				break;
			case "interest_nom":
			case "loans_interest_nom":
			case "carloans_interest_nom":
			case "interest_eff":
			case "loans_interest_eff":
			case "carloans_interest_eff":
			case "trade_cost_variable":
			case "cost_add":
			case "loans_cost_add":
			case "carloans_cost_add":
			case "cost_transaction_euro":
			case "cost_transaction_noeuro":
				return number_format($value, 2, ",", ".")." %";
				break;
			case "ec":
			case "visa":
			case "visa_freeyear":
			case "mastercard":
			case "mastercard_freeyear":
			case "currentaccount_necessary":
			case "bonusprogram":
			case "otctrading":
			case "intradaytrading":
			case "solvency_dep":
			case "carloans_solvency_dep":
			case "loans_solvency_dep":
			case "zweck_modernisierung":
			case "purpose_other":
			case "purpose_car":
				if($value == "1") {
					return "ja";
				} else {
					return "nein";
				}
				break;
			case "provider":
				if($value == "visa") {
					return "Visa Card";
				} elseif($value == "mastercard") {
					return "Mastercard";
				} elseif($value == "american express") {
					return "American Express";
				}
				break;
			case "cardtype":
				if($value == "credit") {
					return "Echte Kreditkarte mit Teilzahlungsfunktion";
				} elseif($value == "charge") {
					return "Monatliche Abrechnung";
				} elseif($value == "debit") {
					return "Taggenaue Belastung";
				} elseif($value == "prepaid") {
					return "Aufladung (vor der Nutzung)";
				}
				break;
			case "banking":
				if($value == "telefon") {
					return "Telefon";
				} elseif($value == "internet") {
					return "Internet";
				}
				break;
			case "solvency_lev":
			case "loans_solvency_lev":
			case "carloans_solvency_lev":
				if($value == "0") {
					return "Alle (Bonitätsunabhängig)";
				} elseif($value == "1") {
					return "Bestes (Niedrigster Zinssatz)";
				} elseif($value == "5") {
					return "ZweiDrittel (Zinssatz für repräsentatives 2/3 Beispiel)";
				} elseif($value == "9") {
					return "Schlechtestes (Höchster Zinssatz)";
				}
				break;
			case "zusage_zeitraum":
			case "commitment_period":
				if($value == "0") {
					return "Keine Angabe";
				} elseif($value == "1") {
					return "30 Tage";
				} elseif($value == "2") {
					return "3 Wochen";
				} elseif($value == "3") {
					return "2 Wochen";
				}elseif($value == "4") {
					return "10 Tage";
				}elseif($value == "5") {
					return "1 Woche";
				}elseif($value == "6") {
					return "5 Tage";
				}elseif($value == "7") {
					return "2 Tage";
				}elseif($value == "8") {
					return "1 Tag";
				}elseif($value == "9") {
					return "noch am selben Tag";
				}elseif($value == "10") {
					return "sofort";
				}
				break;
			case "commitment_probability":
				if($value == "0") {
					return "Keine Angabe";
				} elseif($value == "1") {
					return "sehr unterdurchschnittlich";
				} elseif($value == "2") {
					return "unterdurchschnittlich";
				} elseif($value == "3") {
					return "weniger durchschnittlich";
				}elseif($value == "4") {
					return "durchschnittlich";
				}elseif($value == "5") {
					return "weniger überdurchschnittlich";
				}elseif($value == "6") {
					return "überdurchschnittlich";
				}elseif($value == "7") {
					return "sehr überdurchschnittlich";
				}elseif($value == "8") {
					return "weniger hoch";
				}elseif($value == "9") {
					return "hoch";
				}elseif($value == "10") {
					return "über durchschnittlich hoch";
				}
				break;
			case "period_max":
			case "carloans_period_max":
			case "loans_period_max":
				if($value == "99") {
					return "unbegrenzt";
				} else {
					return $value;
				}
				break;
			case "Webseite":
				return '<a href="'.$value.'" target="_blank" title="'.$value.'">Zur Website</a>';
				break;
			case "cost_variable":
				if ($value == 0) return "-";
				return $value.' %';
				break;
			case "cost_fixed":
				if ($value == 0) return "-";
				return $value.' '.$currency;
				break;
			case "rentalbonds_cost_fixed":
				return number_format($value, 2, ",", ".").' '.$currency;
				break;
			case "mortgages_ltv":
				return number_format($value, 0, ",", ".").'%';
				break;
			case "rentalbonds_cost_variable":
			case "mortgages_interest_nom":
			case "mortgages_interest_eff":
				return number_format($value, 2, ",", ".").'%';
				break;
			case "rentalbonds_period_min":
			case "rentalbonds_period_max":
			case "mortgages_period_min":
			case "mortgages_period_max":
				if ($value == 1) return $value.' Jahr';
				return $value.' Jahre';
				break;
			case "rentalbonds_amount_min":
			case "rentalbonds_amount_max":
			case "mortgages_amount_min":
			case "mortgages_amount_max":
				return number_format($value, 0, ",", ".").' '.$currency;
				break;
			default:
				#drupal_set_message($nodeName. " returned without alteration.", "status");
				return $value;
				break;
		}
	}
}
?>
