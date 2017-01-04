<?php

// BCALL Export - für FA etc. nur in der Nacht, Cache erzeugen
if (isset($_GET["BCALL_5689JGRDTBC_CRON"]))
{
	chdir($_SERVER['DOCUMENT_ROOT']);
	require_once $_SERVER['DOCUMENT_ROOT'].'/includes/bootstrap.inc';
	drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
	
	// Alle Daten (bank + produkt) und alle Zeiten exportieren und in den cache schreiben
	// wird dann von TN, FA, Google, Bing etc. abgeholt!!
	//Stunde - Minute - Sekunde -- Monat - Tag - Jahr!
	//echo "Starting ...";
	$ts = new Timespan(new Date(mktime(0, 0, 0, 1, 1, 2011)), new Date(mktime(23, 59, 59, 12, 31, 2015)));
  	$bcxmlexport = new BCXMLExport($ts, true);
	header("Content-Type:text/xml");
	$xml = $bcxmlexport->saveXML();
	$xml = str_replace("<benchmark_number>0</benchmark_number>","<benchmark_number>-</benchmark_number>",$xml);
	$xml = str_replace("http://www.bankingcheck.de/sites/default/files/bcgrafiken/BankingCheck_Siegel_Usr_00.gif",
		"http://www.bankingcheck.de/sites/default/files/bcgrafiken/BankingCheck_Siegel_Usr_not.gif",$xml);
	$xml = str_replace("http://www.bankingcheck.de/sites/default/files/bcgrafiken/BankingCheck_Anbietersiegel_Usr_00.gif",
		"http://www.bankingcheck.de/sites/default/files/bcgrafiken/BankingCheck_Anbietersiegel_Usr_not.gif",$xml);

	// Daten noch in die Datei schreiben
	if (is_readable(realpath('getxml/cached_xml_4873434/')))
	{
		//echo "beim schreiben";
		$BCAllFile = realpath('getxml/cached_xml_4873434/') . "/bankingcheck.xml";
		$fh = fopen($BCAllFile, 'w');
		if ($fh == null)
		{
			echo "datei konnte nicht geoeffnet werden";
		}
		else
		{
			fwrite($fh, $xml);
			fclose($fh);
			echo "writing finished";
		}
	}
	die();
}
// NE_GK_56845584CFG_CRON
else if (isset($_GET["NE_GK_56845584CFG_CRON"]))
{
	chdir($_SERVER['DOCUMENT_ROOT']);
	require_once $_SERVER['DOCUMENT_ROOT'].'/includes/bootstrap.inc';
	drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
	
	// nur Girokonto Daten + Bewertungen exportieren
	//Stunde - Minute - Sekunde -- Monat - Tag - Jahr!
	//echo "Starting ...";
	$ts = new Timespan(new Date(mktime(0, 0, 0, 1, 1, 2011)), new Date(mktime(23, 59, 59, 12, 31, 2015)));
  $bcxmlexport = new BCXMLExport($ts, true, array("Girokonto"));
	#header("Content-Type:text/xml");
  header("Content-Type:text/plain");
  // only ratings in last year
	$ts = new Timespan(new Date(mktime(0, 0, 0, date("m"), date("d"), date("Y")-2)), new Date(time()));
	$bcxmlexport->extendGirokonten($ts, 15, 3.5, 20);	

	$xml = $bcxmlexport->saveXML();
	$xml = str_replace("<benchmark_number>0</benchmark_number>","<benchmark_number>-</benchmark_number>",$xml);
	$xml = str_replace("http://www.bankingcheck.de/sites/default/files/bcgrafiken/BankingCheck_Siegel_Usr_00.gif",
		"http://www.bankingcheck.de/sites/default/files/bcgrafiken/BankingCheck_Siegel_Usr_not.gif",$xml);
	$xml = str_replace("http://www.bankingcheck.de/sites/default/files/bcgrafiken/BankingCheck_Anbietersiegel_Usr_00.gif",
		"http://www.bankingcheck.de/sites/default/files/bcgrafiken/BankingCheck_Anbietersiegel_Usr_not.gif",$xml);

	// Daten noch in die Datei schreiben
	if (is_readable(realpath('getxml/cached_xml_5154423/')))
	{
		//echo "beim schreiben";
		$BCNEFile = realpath('getxml/cached_xml_5154423/') . "/bankingcheck.xml";
		$fh = fopen($BCNEFile, 'w');
		if ($fh == null)
		{
			echo "datei konnte nicht geoeffnet werden";
		}
		else
		{
			fwrite($fh, $xml);
			fclose($fh);
			echo "writing finished";
		}
	} else echo "Path error: ".realpath('getxml/cached_xml_5154423/');
	die();
}
// Produktdaten Nachtexport + über den Tag verteilt (für Cache)
else if (isset($_GET["5a21ytkj3s2dj45xf4cjg7985"]))
{
	chdir($_SERVER['DOCUMENT_ROOT']);
	require_once $_SERVER['DOCUMENT_ROOT'].'/includes/bootstrap.inc';
	drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

	include 'incl_xml.php';
	include 'incl_companies.php';
	include 'incl_products.php';
	include 'incl_vars.php';
	

	// Lösche alte XML Dateien
	for ($i = 0; $i < sizeof($kategorienArray); $i++)
	{
		$path = realpath('getxml/cached_xml_4873434/') . '/cached_' . $kategorienArray[$i][0] . '.xml';
		if (is_readable($path))
		{
			unlink($path);
		}
	}

	// Initialisiere Zeitmessung
	// Entnommen aus: http://www.devmag.net/php-tricks/233-messung-der-laufzeit.html
	$zeitmessung1=microtime(); 
	$zeittemp=explode(" ",$zeitmessung1); 
	$zeitmessung1=$zeittemp[0]+$zeittemp[1];


	// Erstelle neue XML Dateien
	$erstelleDateien = 0;
	for ($i = 0; $i < sizeof($kategorienArray); $i++)
	{
		$kategorie = $kategorienArray[$i][0];
		
		if (is_readable(realpath('getxml/cached_xml_4873434/')))
		{
			$myFile = realpath('getxml/cached_xml_4873434/') . "/cached_" . $kategorie . ".xml";
			$fh = fopen($myFile, 'w') or die("can't open file");
			
			// Baue XML Dokument
			$xml = createXMLHeader();
			$xml .= createOpenXMLTagA("feed", "name", $kategorie);
			
			if ($kategorie == "companies")
			{
				$xml .= collectCompanies($werbeflaecheKey);
			}
			else
			{
				$xml .= collectProducts($werbeflaecheKey, $kategorienArray[$i][1]);
			}
			
			$xml .= createCloseXMLTag("feed");
			// XML Dokument abgeschlossen
			
			fwrite($fh, $xml);

			fclose($fh);
			
			$erstelleDateien++;
		}
	}

	// Auswertung der Zeitmessung
	$zeitmessung2=microtime(); 
	$zeittemp=explode(" ",$zeitmessung2); 
	$zeitmessung2=$zeittemp[0]+$zeittemp[1]; // Timestamp + Nanosek 
	$zeitmessung=$zeitmessung2-$zeitmessung1; // Differenz der beiden Zeiten 
	$zeitmessung=substr($zeitmessung,0,8); // es wird auf 6 Kommastellen geküzt

	echo $erstelleDateien . " Datei(en) wurden in " . $zeitmessung . " Sekunden erstellt.";	
	die();
}
else //!isset($_GET["5a21ytkj3s2dj45xf4cjg7985"])) ++ (!isset($_GET["BCALL_5689JGRDTBC_CRON"])))
{
	header('HTTP/1.1 403 Forbidden');
	echo "no param";
	die();
}

?>
