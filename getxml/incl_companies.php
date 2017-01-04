<?
function collectCompanies($werbeflaeche)
{
	$xml = "";
	
	$import = new BCImport();

	$sql = db_query("SELECT nid FROM node WHERE type='bank' AND status='1' ORDER BY nid DESC");
	while ($node_object = db_fetch_object ($sql)) {
		$node = node_load(array("nid" => $node_object->nid));
		
		// angepasste Logos der Banken aus DB verwenden
		$query = "SELECT * FROM content_type_bank WHERE nid='" . $node->nid . "' LIMIT 1";
		$result1 = db_query($query);
		$banknode = db_fetch_object($result1);		
		$query = "SELECT * FROM files WHERE fid='" . $banknode->field_banklogo_fid . "' LIMIT 1";
		$result2 = db_query($query);
		$filepaths = db_fetch_object($result2);
			

		$xml .= createOpenXMLTagA("bank", "id", $node->field_financeaddsid[0]["value"]);
		$xml .= createXMLTags("name", xmlEncode($node->title));
		$xml .= createXMLTags("body", xmlEncode(strip_tags($node->body)));
		$xml .= createXMLTags("logo", xmlEncode("http://" . $_SERVER["SERVER_NAME"] . "/" . $filepaths->filepath));
		
		//$xml .= createOpenXMLTagA("bank", "id", $node->field_financeaddsid[0]["value"]);
		//$xml .= createXMLTags("name", xmlEncode($node->title));
		//$xml .= createXMLTags("body", xmlEncode(strip_tags($node->body)));
		//$xml .= createXMLTags("logo", xmlEncode("http://" . $_SERVER["SERVER_NAME"] . "/" . $node->field_banklogo[0]["filepath"]));
		
		
		// Konditionen
		$i=0;
		foreach($import->getBankDataUnformatted($node->field_financeaddsid[0]["value"]) as $key=>$value) {
			if($key == "bankUrl")
			{
				$xml .= createXMLTags("url", xmlEncode("http://" . $_SERVER["SERVER_NAME"] . "/zum_Anbieter.php?t=" . $werbeflaeche . "C" . $node->field_financeaddsid[0]["value"] . "08000D"));
			}
			else
			{
				$xml .= createXMLTags($key, xmlEncode($value));
			}
		}


		
		// Verfügbare Produkte und deren Bewertung
		$prods = $import->getBankProductsData($node->field_financeaddsid[0]["value"]);
		if(count($prods)>0) {
			$i = 0;
			foreach($prods as $line) {
				
				$xml .= createOpenXMLTag("product");
				$xml .= createXMLTags("name", xmlEncode($line["title"]));
						
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
				
				

				$xml .= createCloseXMLTag("product");
			}
		}
		$xml .= createCloseXMLTag("bank");
		
		// Debugging
		//break;
		
	} // While Schleife

	return $xml;
}
?>