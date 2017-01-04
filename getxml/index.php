<?php

chdir("..");
require_once("lib/php/inc/fullBootstrap.php");

// FA_3678HDRFT776BM = FinanceAds (old 4243545435353) - Banken und Produkte Gesamtbewertung und Siegel
// NE_749876554793MR = NetzeffektSchnittstelle - Girokonto Banken und Produkte Gesamtbewertung und Siegel
// UMT_87HKLR6676TBH = ubermetrics (old 6621221456554) - Banken und Produkte Gesamtbewertung und Siegel
// GG_0567MTVG776VTG = Google (old 7562215421236) - Banken und Produkte Gesamtbewertung und Siegel (war nur mal zum Test an Navid gesendet)
// BCALL_5689JGRDTBC = BC Export ALL (all data, all time) (old 6621251254125)
// BCA13_7765HBGT6CM = BankingCheck Award 2013 Daten (old 6621221456888)
// BCA14_758JKZTGHFF = BankingCheck Award 2014 Daten
// BCA15_7512HTFKITG = BankingCheck Award 2015 Daten

if( isset($_GET["FA_3678HDRFT776BM"]) || isset($_GET["UMT_87HKLR6676TBH"]) || isset($_GET["GG_000001228884"]) )
{
	// ab sofort aus Cache laden (File wird in der Nacht neu erzeugt)
	$cacheFile = "http://www.bankingcheck.de/getxml/cached_xml_4873434/bankingcheck.xml";
	$ch = curl_init();
	header("Content-Type:text/xml");
	$timeout = 10; // timeout in secondes set to zero for no timeout
	curl_setopt ($ch, CURLOPT_URL, $cacheFile);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$xml = curl_exec($ch);
	curl_close($ch);
	
	/*$bcxmlexport = new BCXMLExport();
	header("Content-Type:text/xml");
	$xml = $bcxmlexport->saveXML();
	$xml = str_replace("<benchmark_number>0</benchmark_number>","<benchmark_number>-</benchmark_number>",$xml);
	$xml = str_replace("http://www.bankingcheck.de/sites/default/files/bcgrafiken/BankingCheck_Siegel_Usr_00.gif",
		"http://www.bankingcheck.de/sites/default/files/bcgrafiken/BankingCheck_Siegel_Usr_not.gif",$xml);
	$xml = str_replace("http://www.bankingcheck.de/sites/default/files/bcgrafiken/BankingCheck_Anbietersiegel_Usr_00.gif",
		"http://www.bankingcheck.de/sites/default/files/bcgrafiken/BankingCheck_Anbietersiegel_Usr_not.gif",$xml);
	*/
	echo $xml ;
	die();
}
if( isset($_GET["NE_749876554793MR"]) )
{
	// nur Netzeffekt Schnittstellenausgabe
	// Girokonto Daten + 15 Kunden-Bewertungen mit mind. 4.5 und mind. 15 Zeichen Text
	// ab sofort aus Cache laden (File wird in der Nacht neu erzeugt)
	$cacheFile = "http://www.bankingcheck.de/getxml/cached_xml_5154423/bankingcheck.xml";
	$ch = curl_init();
	header("Content-Type:text/xml");
	$timeout = 10; // timeout in secondes set to zero for no timeout
	curl_setopt ($ch, CURLOPT_URL, $cacheFile);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$xml = curl_exec($ch);
	curl_close($ch);
	
	echo $xml ;
	die();
}
else if (isset($_GET["BCA13_7765HBGT6CM"]))
{
  // Export für den BC Award 2013 nur vom 01.01.2013 bis 30.09.2013
  // Datum am besten einstellbar machen + Timespan + Anzahl Bewertungen
  
  // Export mit Timespan
  // \\Lg-nas\BMS\Webseiten\5_bankingcheck.de\Aktuell\lib\php\inc\bcVotingsInTimespan.php
	//Stunde - Minute - Sekunde -- Monat - Tag - Jahr!
	$ts = new Timespan(new Date(mktime(0, 0, 0, 1, 1, 2013)), new Date(mktime(23, 59, 59, 9, 30, 2013)));
  	$bcxmlexport = new BCXMLExport($ts, true);
	header("Content-Type:text/xml");
	$xml = $bcxmlexport->saveXML();
	$xml = str_replace("<benchmark_number>0</benchmark_number>","<benchmark_number>-</benchmark_number>",$xml);
	$xml = str_replace("http://www.bankingcheck.de/sites/default/files/bcgrafiken/BankingCheck_Siegel_Usr_00.gif",
		"http://www.bankingcheck.de/sites/default/files/bcgrafiken/BankingCheck_Siegel_Usr_not.gif",$xml);
	$xml = str_replace("http://www.bankingcheck.de/sites/default/files/bcgrafiken/BankingCheck_Anbietersiegel_Usr_00.gif",
		"http://www.bankingcheck.de/sites/default/files/bcgrafiken/BankingCheck_Anbietersiegel_Usr_not.gif",$xml);
	//echo $bcxmlexport->saveXML();
	echo $xml ;
	die();
}
else if (isset($_GET["BCA14_758JKZTGHFF"]))
{
  // Export für den BC Award 2014 nur vom 01.02.2014 bis 31.05.2014
  // Datum am besten einstellbar machen + Timespan + Anzahl Bewertungen
  
  // Export mit Timespan
  // \\Lg-nas\BMS\Webseiten\5_bankingcheck.de\Aktuell\lib\php\inc\bcVotingsInTimespan.php
	//Stunde - Minute - Sekunde -- Monat - Tag - Jahr!
	$ts = new Timespan(new Date(mktime(0, 0, 0, 1, 1, 2014)), new Date(mktime(23, 59, 59, 5, 31, 2014)));
  	$bcxmlexport = new BCXMLExport($ts, true);
	header("Content-Type:text/xml");
	$xml = $bcxmlexport->saveXML();
	$xml = str_replace("<benchmark_number>0</benchmark_number>","<benchmark_number>-</benchmark_number>",$xml);
	$xml = str_replace("http://www.bankingcheck.de/sites/default/files/bcgrafiken/BankingCheck_Siegel_Usr_00.gif",
		"http://www.bankingcheck.de/sites/default/files/bcgrafiken/BankingCheck_Siegel_Usr_not.gif",$xml);
	$xml = str_replace("http://www.bankingcheck.de/sites/default/files/bcgrafiken/BankingCheck_Anbietersiegel_Usr_00.gif",
		"http://www.bankingcheck.de/sites/default/files/bcgrafiken/BankingCheck_Anbietersiegel_Usr_not.gif",$xml);
	//echo $bcxmlexport->saveXML();
	echo $xml ;
	die();
}
else if (isset($_GET["BCA15_7512HTFKITG"]))
{
  // Export für den BC Award 2015 nur vom 01.01.2015 bis 30.04.2015
  // Datum am besten einstellbar machen + Timespan + Anzahl Bewertungen
  
  // Export mit Timespan
  // \\Lg-nas\BMS\Webseiten\5_bankingcheck.de\Aktuell\lib\php\inc\bcVotingsInTimespan.php
	//Stunde - Minute - Sekunde -- Monat - Tag - Jahr!
	$ts = new Timespan(new Date(mktime(0, 0, 0, 1, 1, 2015)), new Date(mktime(23, 59, 59, 4, 30, 2015)));
  	$bcxmlexport = new BCXMLExport($ts, true);
	header("Content-Type:text/xml");
	$xml = $bcxmlexport->saveXML();
	$xml = str_replace("<benchmark_number>0</benchmark_number>","<benchmark_number>-</benchmark_number>",$xml);
	$xml = str_replace("http://www.bankingcheck.de/sites/default/files/bcgrafiken/BankingCheck_Siegel_Usr_00.gif",
		"http://www.bankingcheck.de/sites/default/files/bcgrafiken/BankingCheck_Siegel_Usr_not.gif",$xml);
	$xml = str_replace("http://www.bankingcheck.de/sites/default/files/bcgrafiken/BankingCheck_Anbietersiegel_Usr_00.gif",
		"http://www.bankingcheck.de/sites/default/files/bcgrafiken/BankingCheck_Anbietersiegel_Usr_not.gif",$xml);
	//echo $bcxmlexport->saveXML();
	echo $xml ;
	die();
}
else if (isset($_GET["BCALL_5689JGRDTBC"]))
{
  // Alle Daten (bank + produkt) und alle Zeiten exportieren
	//Stunde - Minute - Sekunde -- Monat - Tag - Jahr!
	$ts = new Timespan(new Date(mktime(0, 0, 0, 1, 1, 2011)), new Date(mktime(23, 59, 59, 12, 31, 2015)));
  	$bcxmlexport = new BCXMLExport($ts, true);
	header("Content-Type:text/xml");
	$xml = $bcxmlexport->saveXML();
	$xml = str_replace("<benchmark_number>0</benchmark_number>","<benchmark_number>-</benchmark_number>",$xml);
	$xml = str_replace("http://www.bankingcheck.de/sites/default/files/bcgrafiken/BankingCheck_Siegel_Usr_00.gif",
		"http://www.bankingcheck.de/sites/default/files/bcgrafiken/BankingCheck_Siegel_Usr_not.gif",$xml);
	$xml = str_replace("http://www.bankingcheck.de/sites/default/files/bcgrafiken/BankingCheck_Anbietersiegel_Usr_00.gif",
		"http://www.bankingcheck.de/sites/default/files/bcgrafiken/BankingCheck_Anbietersiegel_Usr_not.gif",$xml);
	//echo $bcxmlexport->saveXML();
	echo $xml ;
	die();
}
else
{
  header('HTTP/1.1 403 Forbidden');
	die();
}
?>