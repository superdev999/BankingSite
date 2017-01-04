<?php
class BCProductdetailsOrder {
	// Sortierungsreihenfolge und optionale alternative Übersetzung nach Produkten
	
	// !!!
	// Vorsicht: letzte Zeile im Array darf kein Komma am Ende haben.
	// Die zweite Spalte dient zur Eingabe eines optionalen, alternativen Ausgabetextes.
	// !!!

	// Autokredit
	public static $carloansOrderArray = array(
		array("period_min",				""),		// Mindestanlagedauer in Monaten
		array("period_max",				""),		// Maximalanlagedauer in Monaten
		array("amount_min",				""),		// Kreditbetrag von
		array("amount_max",				""),		// Kreditbetrag bis
		array("interest_eff",			""),		// Effektiver Jahreszins
		array("interest_nom",			""),		// Sollzinssatz
		array("solvency_dep",			""),		// Bonitätsabhängige Konditionen
		array("cost_add",				""),		// Bearbeitungsgebühren
		array("solvency_lev",			""),		// Bonitätslevel Voraussetzung
		array("productname",			""), 		// Produktname
		array("legaldata",				""), 		// Pflichtangaben
		array("commitment_probability",	""), 		// Zusagewahrscheinlickeit
		array("commitment_period",		""), 		// Zusagezeitraum
		array("purpose_other",			""), 		// Ratenkredit
		array("purpose_car",			""), 		// Autokredit
		array("incentive",				""),		// Bonus Höhe
		array("incentive_description",	""), 		// Art des Bonus
		array("features",				"")			// Zusätzliche Infos
	);


	// Ratenkredit
	public static $loansOrderArray = array(
		array("period_min",				""),		// Mindestanlagedauer in Monaten
		array("period_max",				""),		// Maximalanlagedauer in Monaten
		array("amount_min",				""),		// Kreditbetrag von
		array("amount_max",				""),		// Kreditbetrag bis
		array("interest_eff",			""),		// Effektiver Jahreszins
		array("interest_nom",			""),		// Sollzinssatz
		array("solvency_dep",			""),		// Bonitätsabhängige Konditionen
		array("cost_add",				""),		// Bearbeitungsgebühren
		array("solvency_lev",			""),		// Bonitätslevel Voraussetzung
		array("productname",			""), 		// Produktname
		array("legaldata",				""), 		// Pflichtangaben
		array("commitment_probability",	""), 		// Zusagewahrscheinlickeit
		array("commitment_period",		""), 		// Zusagezeitraum
		array("purpose_other",			""), 		// Ratenkredit
		array("purpose_car",			""), 		// Autokredit
		array("zweck_modernisierung",	""), 		// Modernisierung
		array("incentive",				""),		// Bonus Höhe
		array("incentive_description",	""), 		// Art des Bonus
		array("features",				"")			// Zusätzliche Infos
	);
	
	// Mietkaution
	public static $rentalbondsOrderArray = array(
		array("period_min",				""),		// Laufzeit von
		array("period_max",				""),		// Laufzeit bis
		array("amount_min",				""),		// Höhe Mietkaution von
		array("amount_max",				""),		// Höhe Mietkaution bis
		array("cost_fixed",				""),		// Fixe jährliche Kosten
		array("cost_variable",			""),		// Variable jährliche Kosten
		array("productname",			""),		// Produktname
		array("features",				"")			// Zusätzliche Infos
	);

	// Tagesgeld
	public static $dailyallowancesOrderArray = array(
		array("cost",						""),		// Kosten
		array("balance_min",				"Mindestanlage"),		// Mindestguthaben (def.) - Mindestanlage
		array("balance_max",				"Maximalanlage"),		// Maximalguthaben (def.) - Maximalanlage
		array("period_min",					""),		// Mindestanlagedauer in Monaten
		array("period_max",					""),		// Maximalanlagedauer in Monaten
		array("interest_frequency",			""),		// Zinsgutschrift
		array("interest_nom",				""),		// Aktueller Zinssatz
		array("productname",				""),		// Produktname
		array("depositprotection",			""),		// Höhe der Einlagensicherung
		array("depositprotection_name",		""),		// Einlagensicherungssystem
		array("depositprotection_country",	""),		// Land der Einlagensicherung
		array("depositprotection_desc",		""),		// Beschreibung Einlagensicherung
		array("incentive",					""),		// Bonus Höhe
		array("incentive_description",		""), 		// Art des Bonus
		array("features",					"")		// Zusätzliche Infos
	);
	
	// Festgeld
	public static $fixeddepositsOrderArray = array(
		array("cost",						""),		// Kosten
		array("balance_min",				"Mindestanlage"),		// Mindestguthaben (def.) - Mindestanlage
		array("balance_max",				"Maximalanlage"),		// Maximalguthaben (def.) - Maximalanlage
		array("period_min",					""),		// Mindestanlagedauer in Monaten
		array("period_max",					""),		// Maximalanlagedauer in Monaten
		array("interest_frequency",			""),		// Zinsgutschrift
		array("interest_nom",				""),		// Aktueller Zinssatz
		array("productname",				""),		// Produktname
		array("depositprotection",			""),		// Höhe der Einlagensicherung
		array("depositprotection_country",	""),		// Land der Einlagensicherung
		array("depositprotection_name",		""),		// Einlagensicherungssystem
		array("depositprotection_desc",		""),		// Beschreibung Einlagensicherung
		array("incentive",					""),		// Bonus Höhe
		array("incentive_description",		""), 		// Art des Bonus
		array("features",					"")			// Zusätzliche Infos
	);
	
	// Girokonto
	public static $currentaccountsOrderArray = array(
		array("cost",					""), 	// Kosten
		array("incoming_pm_min",		""), 	// monat. Geldeingang min.
		array("incoming_pm_max",		""), 	// monat. Geldeingang max.
		array("balance_min",			""), 	// Mindestguthaben
		array("balance_max",			""), 	// Maximalguthaben
		array("interest_nom",			""), 	// Aktueller Zinssatz
		array("interest_frequency",		""), 	// Zinsgutschrift
		array("productname",			""), 	// Produktname
		array("ec",						""), 	// EC-Karte verfügbar
		array("ec_cost",				""), 	// jährliche Kosten EC-Karte
		array("visa",					""), 	// VISA-Card verfügbar
		array("visa_cost",				""), 	// jährliche Kosten VISA-Card
		array("visa_freeyear",			""), 	// VISA-Card im 1.Jahr kostenfrei
		array("mastercard",				""), 	// Mastercard verfügbar
		array("mastercard_cost",		""), 	// jährliche Kosten Mastercard
		array("mastercard_freeyear",	""), 	// Master-Card im 1.Jahr kostenfrei
		array("cashterminals_text",		""), 	// Bargeldversorgung (Anzahl der verfügbaren Automaten)
		array("incentive",				""),	// Bonus Höhe
		array("incentive_description",	""),	// Art des Bonus
		array("features",				"")		// Zusätzliche Infos
	);
	
	// Kreditkarte
	public static $creditcardsOrderArray = array(
		array("cost",						""), 	// Kosten
		array("transaction_min",			""), 	// Jahresumsatz Minimum
		array("transaction_max",			""), 	// Jahresumsatz Maximum
		array("productname",				""), 	// Produktname
		array("cost_transaction_euro",		""), 	// Transaktionskosten Euroraum
		array("cost_transaction_noeuro",	""), 	// Transaktionskosten Nicht-Euroraum
		array("provider",					""), 	// Kreditkartenanbieter
		array("status",						""), 	// Status
		array("currentaccount_necessary",	""), 	// Girokonto notwendig
		array("bonusprogram",				""), 	// Bonusprogramm
		array("cardtype",					""), 	// Art der Kreditkarte
		array("incentive",					""), 	// Bonus Höhe
		array("incentive_description",		""), 	// Art des Bonus
		array("features",					"")		// Zusätzliche Infos
	);
	
	// Depot
	public static $brokerageOrderArray = array(
		array("banking",					""), 	// Banking Verfahren
		array("trades_pa_min",				""), 	// Minimale Anzahl Orders pro Jahr
		array("trades_pa_max",				""), 	// Maximale Anzahl Orders pro Jahr
		array("trade_volume_min",			""), 	// Minimum Ordervolumen
		array("trade_volume_max",			""), 	// Maximum Ordervolumen
		array("trade_cost_fixed",			""), 	// Fixe Orderkosten pro Order
		array("trade_cost_variable",		""), 	// Variabler Orderbestandteil pro Order
		array("trade_cost_min",				""), 	// Mindestbetrag Orderkosten pro Order
		array("trade_cost_max",				""), 	// Maximalbetrag Orderkosten pro Order
		array("trade_cost_add",				""), 	// Zusätzliche fixe Orderkosten
		array("account_cost_fixed",			""), 	// Fixe Depotkosten
		array("account_cost_variable",		""), 	// Variable Depotkosten
		array("account_cost_min",			""), 	// Mindestbetrag Depotkosten
		array("account_cost_max",			""), 	// Maximalbetrag Depotkosten
		array("account_cost_add",			""), 	// Zusätzliche fixe Depotkosten
		array("productname",				""), 	// Produktname
		array("exchanges_text",				""), 	// Börsenplätze
		array("otctrading",					""), 	// Außerbörslicher Handel
		array("intradaytrading",			""), 	// Intradayhandel
		array("realtimeprices",				""), 	// Anzahl der Realtimekurs-Abrufe
		array("funds",						""), 	// Fonds im Angebot
		array("fundsdiscounted",			""), 	// Rabattierte Fonds im Angebot
		array("incentive",					""), 	// Bonus Höhe
		array("incentive_description",		""),	// Art des Bonus
		array("features",					"")		// Zusätzliche Infos
	);
	
	// Baufinanzierung
	public static $mortgagesOrderArray = array(
		array("period_min",			""), 	// Laufzeit von
		array("period_max",			""), 	// Laufzeit bis
		array("amount_min",			""), 	// Kreditbetrag von
		array("amount_max",			""), 	// Kreditbetrag bis
		array("interest_nom",		""), 	// Nominal Jahreszins
		array("interest_eff",		""), 	// Effektiver Jahreszins
		array("ltv",				""), 	// Beleihung
		array("productname",		""), 	// Produktname
		array("legaldata",			""), 	// Pflichtangaben
		array("features",			"")		// Zusätzliche Infos
	);
}

?>