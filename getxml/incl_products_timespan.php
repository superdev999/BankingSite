<?
function collectProducts($werbeflaeche, $productType)
{
	$xml = "";
	
	$import = new BCImport();

	$result = db_query("SELECT nid FROM node WHERE type='productitem' AND status='1' ORDER BY nid ASC");
	while ($node_object = db_fetch_object($result)) {
		$node = node_load(array("nid" => $node_object->nid));//120));
		
		$foundType = $node->field_prodmyproduct[0]["nid"];
		
		if ($foundType === $productType) // Prüfe ob der gefundene Produkttyp der Anfrage entspricht
		{
			// Produktbewertungen abholen (wird erst später angefügt)
			$query = "SELECT * FROM content_type_bank WHERE nid='" . $node->field_proditemmybank[0]["nid"] . "' LIMIT 1";
			$result3 = db_query($query);
			$banknode = db_fetch_object($result3);
		
			$BCImportID = $node->field_prodfinanceaddsid[0]["value"];
			
			// Abrufen des XML in der Tabelle BCImportXML
			$query = "SELECT * FROM BCImportXML WHERE fid='" . $BCImportID . "' ORDER BY timestamp DESC LIMIT 1";
			$result2 = db_query($query);
			$BCImportXML = db_fetch_object($result2);
			
			// Jede Zeile im XML in Array-Elementen speichern
			$xmlArray = explode("\n", $BCImportXML->xml);
			
			// Ausgabe
			for ($i = 0; $i < sizeof($xmlArray); $i++)
			{
				if (strpos($xmlArray[$i],"<?xml") !== false)
				{
					continue; // Überspringe Zeile mit XML Header
				}
				else if (strpos($xmlArray[$i],"<url>") !== false)
				{
					// überschreibe/erstelle XML Tag <url>
					$xmlArray[$i] = createXMLTags("url", xmlEncode("http://" . $_SERVER["SERVER_NAME"] . "/zum_Anbieter.php?t=" . $werbeflaeche . "C" . $banknode->field_financeaddsid_value . "09000D&product=" . $BCImportID));

				}
				else if (strpos($xmlArray[$i],"<logo>") !== false)
				{
					// überschreibe/erstelle XML Tag <logo>
					$xmlArray[$i] = createXMLTags("logo", xmlEncode("http://" . $_SERVER["SERVER_NAME"] . "/sites/default/files/imports/" . $banknode->field_financeaddsid_value . ".gif"));
				}
				else if (strpos($xmlArray[$i],"</product>") !== false)
				{
					break; // Breche ab bei Schließung des Product-Tags
				}
				
				$xml .= $xmlArray[$i] . "\n"; // 1:1 Kopie des Inhalts der Tabelle BCImportXML (Feld: xml)
			}

			// Produktbewertung anhängen
			$prods = $import->getBankProductsData($banknode->field_financeaddsid_value);
			
			if(count($prods)>0) {
				foreach($prods as $line) {
					
					if ($line["nidProductItem"] == $node_object->nid)
					{
						$votings = new BCVotings($line["nidProductItem"], $line["typeProduct"]);
						$benchmark_number = $votings->getAverage();
						
						if($benchmark_number != "0" && $benchmark_number != "") // Bewertung vorhanden
						{
							$benchmark_logo = str_replace(".", "", $benchmark_number);

							// Ganzzahlige Bewertungen anpassen
							if (strlen($benchmark_logo) == 1)
							{
								$benchmark_logo .= "0";
							}

							$benchmark_logo = "http://" . $_SERVER["SERVER_NAME"] . "/sites/default/files/bcgrafiken/BankingCheck_Siegel_Usr_" . $benchmark_logo . ".gif";
							
						}
						else // Keine Bewertung vorhanden
						{
							$benchmark_number = "";
							$benchmark_logo = "http://" . $_SERVER["SERVER_NAME"] . "/sites/default/files/bcgrafiken/BankingCheck_Siegel_Usr_not.gif";
						}
						
						$xml .= createXMLTags("benchmark_number", xmlEncode($benchmark_number));
						$xml .= createXMLTags("benchmark_logo", xmlEncode($benchmark_logo));
						
					}

				}
			}
			
			$xml .= createCloseXMLTag("product");
			
		} // IF: Tageskonto
		
		// Debugging
		//break;

	} // While Schleife
	
	return $xml;

}
?>