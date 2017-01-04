<?php

include 'incl_zugriffs_id_array.php';
include 'incl_vars.php';

// Einlesen der Parameter aus URL
$URLId = $_GET['id'];
$URLKategorie = $_GET['cat'];
$URLWerbeflaeche = $_GET['wf'];


// Zugelassene IDs werden in der Datei "incl_zugriffs_id_array.php" definiert
// Das hier verwendete Array heißt "zugelasseneIDs"

// Suche nach angegebener ID in Array
$isValidID = false;
for ($i = 0; $i < sizeof($zugelasseneIDs); $i++)
{
	if ($zugelasseneIDs[$i] === $URLId)
	{
		$isValidID = true;
		break;
	}
}

$isValidCat = false;
for ($i = 0; $i < sizeof($kategorienArray); $i++)
{
	if ($kategorienArray[$i][0] === $URLKategorie)
	{
		$isValidCat = true;
		break;
	}
}



// ID wird akzeptiert
if ($isValidID)
{
	if ($isValidCat)
	{
		if ($URLWerbeflaeche != "") // Prüfe ob Werbefläche in der URL vorhanden ist
		{
			$werbeflaeche = $URLWerbeflaeche;
		}
		else
		{
			$werbeflaeche = $defaultWerbeflaeche;
		}
		
		// Öffne Datei
		$myFile = realpath('cached_xml_4873434/') . '/cached_' . $URLKategorie . '.xml';
		if (is_readable($myFile))
		{
			$fh = fopen($myFile, 'r');
			
			//XML Kopf
			header("Content-Type: application/xml");
			
			$xml = "";
			
			while (!feof($fh)) {
				$line = fgets($fh);
				
				if (strpos($line,"<url>") !== false)
				{
					$line = str_replace($werbeflaecheKey, $werbeflaeche, $line);
				}
				
				$xml .= $line;
			}
			
			fclose($fh);
			
			echo $xml;
		}
		else
		{
			die("Kann Datei nicht öffnen: " . $myFile);
		}
	}
	else
	{
		die("Die in der URL angegebene Kategorie kann nicht gefunden werden");
	}
}
else
{
	header('HTTP/1.1 403 Forbidden');
}

?>
