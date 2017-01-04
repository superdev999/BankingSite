<?php
class BCImportFormats {
	public static function formatValue($nodeName, $value) {
		switch($nodeName)
    {
      case "Art der Bank":
        if($value == "internetbank")
        {
          return "Direktbank";
        }
        elseif($value == "filialbank")
        {
          return "Filialbank";
        }
     case "depositprotection_country":
        if($value == "DE")
        {
          return "Deutschland - DE";
        }
        elseif($value == "NL")
        {
          return "Niederlande - NL";
        }
        elseif($value == "AT")
        {
          return "Österreich - AT";
        }
        elseif($value == "EE")
        {
          return "Estland - EE";
        }
        else
        {
          return $value;
        }
        break;
     case "Land der Einlagensicherung":
        if($value == "DE")
        {
          return "Deutschland - DE";
        }
        elseif($value == "NL")
        {
          return "Niederlande - NL";
        }
        elseif($value == "AT")
        {
          return "Österreich - AT";
        }
        elseif($value == "EE")
        {
          return "Estland - EE";
        }
        else
        {
          return $value;
        }
        break;
			case "interest_frequency":
				if($value == "1")
        {
					return "jährlich";
				}
        elseif($value == "2")
        {
					return "halbjährlich";
				}
        elseif($value == "4")
        {
					return "quartalsweise";
				}
        elseif($value == "6")
        {
					return "alle 2 Monate";
				}
        elseif($value == "12")
        {
					return "monatlich";
				}
				break;
			case "balance_min":
			case "balance_max":
			case "depositprotection":
			case "incoming_pm_min":
			case "incoming_pm_max":
			case "transaction_min":
			case "transaction_max":
			case "amount_min":
			case "amount_max":
			case "Höhe der Einlagensicherung":
			case "trade_volume_min":
			case "trade_volume_max":
			if($value == "999999") {
					return "unbegrenzt";
				} elseif($value == "99" && $nodeName == "balance_max") {
					return "unbegrenzt";
				} else {
					return number_format($value, 0, ",", ".")." €";
				}
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
				return number_format($value, 2, ",", ".")." €";
			break;
			case "interest_nom":
			case "interest_eff":
			case "trade_cost_variable":
			case "cost_add":
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
				if($value == "0") {
					return "Alle (Bonitätsunabhängig)";
				} elseif($value == "1") {
					return "Bestes (Niedrigster Zinssatz)";
				} elseif($value == "5") {
					return "ZweiDrittel (Zinssatz für repräsentatives Beispiel)";
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
				if($value == "99") {
					return "unbegrenzt";
				} else {
					return $value;
				}
				break;
			case "Webseite":
				return '<a href="'.$value.'" target="_blank" title="'.$value.'">Zur Website</a>';
				break;
			default:
				return $value;
				break;
		}
	}
}
?>