<?php
/**
 * Export Products to XML
 *
 * Structure should be:
 * bankingcheck
 * 	check-image
 *  product
 * 		@id:financeAdds-Id
 * 		@name:Title der entsprechenden Produktseite
 * 		@url:Product URL aus BankingCheck
 * 		bank
 * 			@id
 * 			Title der Bankseite
 * 		benchmark_number
 * 			3.7
 * 		benchmark_logo
 * 			http://www.bankingcheck.de/sites/default/files/bcgrafiken/BankingCheck_Siegel_Usr_37.gif
 *
 * General ToDo:
 * @todo move classcode to lib/php/class.BCXMLExport.php, when ready
 *
 *
 */
class BCXMLExport extends DOMFI {
	/**
	 *
	 * @var string
	 */
	private static $LOGO = "/sites/default/files/pixture_reloaded_logo.png";
	/**
	 * Instance of BCImport
	 *
	 * @var BCImport
	 */
	private $bcimport;
	/**
	 * Cache for the node_load
	 *
	 * @var array
	 */
	private $nodeCache = array();


	private $timespan;
	/**
	 * Constructor, inits XML
	 *
	 * @return BCXMLExport
	 */
	public function BCXMLExport($timespan = null, $withBanks = false, $filterByProducts = array()) {
		$this->timespan = $timespan;
		$this->loadXML("<bankingcheck/>");
		$this->select("/bankingcheck");
		array_push($this->nodeStack, $this->documentElement);
		$this->currentNode = $this->documentElement;
		$this->node("check_image")->innerText($this->uriToUrl(self::$LOGO));
		$this->bcimport = new BCImport();
		$this->initProducts($filterByProducts);
		if($withBanks) {
			$this->initBanks();
		}
	}
	/**
	 * Appends product - nodes
	 */
	private function initProducts($filterByProducts) {
		$bankindex = array_flip($this->bcimport->getBankIndex());
		$votingIndex = BCVotings::getIndex();
		$bcvotings = new BCVotings("","");

		// possibly filter by products
		$filterProductNids = array();
		if (count($filterByProducts) > 0) {
			// load nids of products
			foreach ($filterByProducts as $key => $name) {
				#var_dump($name);
				$productNode = node_load(array("type" => 'product', "title"=>$name));
				#var_dump($productNode);
				$filterProductNids[] = $productNode->nid;
			}
		}

		foreach($this->bcimport->getProductItemIndex() as $fid=>$nid) {

			$nodeData = $this->getDrupalNode($nid);
			//Why can this happen? Keine Ahnung..
			if($nodeData === false) {
				continue;
			}

			// if filterProductNids set, filter by them
			if (count($filterProductNids) > 0) {
				// if not in the filter array, ignore this node and continue
				if (!in_array($nodeData->field_prodmyproduct[0]["nid"], $filterProductNids)) {
					continue;
				}
			}

			$bankid = $bankindex[$nodeData->field_proditemmybank[0]["nid"]];
			$bankdata = $this->getDrupalNode($nodeData->field_proditemmybank[0]["nid"]);
			
			if(is_null($votingIndex[$nid]["average"])) {
				$votingIndex[$nid]["average"] = 0;
			}

			if($this->timespan != null) { // Nur votings in der angebenen Zeitspanne berücksichtigen:
				//ProduktTyp ermitteln auf besondere Art:
				preg_match('#^produkte/([^/]+)/.*#', $nodeData->path, $matches);
				$productType = $matches[1];
				//Voting erstellen und daten holen
				$voting = new BCVotings($nid, $productType);
				$votings = $voting->getVotingsForTimespan($this->timespan);

				$votingIndex[$nid]["average"] = str_replace(',', '.', $votings['average']);
				$count = $voting->getVotingCountInTimespan($this->timespan);
			}



			$xmlNode = $this->node("product")
				->attribute("id", $fid)
				->attribute("name", $nodeData->title)
				->attribute("url", $this->uriToUrl("/".$nodeData->path))
				->within();


			
			$xmlNode
				->node("bank")
					->attribute("id", $bankid)
					->innerText($bankdata->title)
				->node("benchmark_number")
					->innerText($votingIndex[$nid]["average"]);

			if(isset($count)) {
				$xmlNode->node("benchmark_count")
						->innerText($count);
			}

			$xmlNode->node("benchmark_logo")
				->innerText(
					$this->uriToUrl(
						$bcvotings->getSiegelUrl(
							true,
							explode(
								".",
								$votingIndex[$nid]["average"]
								)
							)
						)
					);
			$this->endWithin();
		}
	}

	/**
	 * Extend all products by ratings with respect to the criteria
	 * @param  Timespan
	 * @param  integer
	 * @param  float
	 * @param  integer
	 * @return Array
	 */
	public function extendGirokonten($timespan, $number, $limit, $minCommentLength) {		
		// get all products
		foreach ($this->getNodes('/bankingcheck/product') as $node) {

			// load node
			$drupalNode = node_load(array('title' => $node->getAttribute("name")));
			$nid = $drupalNode->nid;			
			
			// load ratings as array of NovaBCVotings with settings
			$votings = BCVotings::getLastGoodRatings($nid, $timespan, $number, $limit, $minCommentLength);
			
			// append element to XML tree
			foreach ($votings as $key => $voting) {				
				$ratingNode = $this->createElement("customer-review");
				$ratingNode->appendChild($this->createElement('id', $voting->getNid()));
				$ratingNode->appendChild($this->createElement('date', $voting->getNode()->created));
				$ratingNode->appendChild($this->createElement('benchmark_number', $voting->getAverage()));
				$ratingNode->appendChild($this->createElement('recommend', $voting->getPromoteValue() ? "true" : "false"));
				$ratingNode->appendChild($this->createElement('comment', $voting->getComment()));	
				
				$node->appendChild($ratingNode);
			}
		}
	}

	/**
   * Appends product - nodes
   */
	private function initBanks() {

		foreach($this->bcimport->getBankIndex() as $fid=>$nid) {
			$nodeData = $this->getDrupalNode($nid);
			//Why can this happen? Keine Ahnung..
			if($nodeData === false) {
				continue;
			}

			
			if($this->timespan != null) { // Nur votings in der angebenen Zeitspanne berücksichtigen:
				//ProduktTyp ermitteln auf besondere Art:

				$productType = $matches[1];
				//Voting erstellen und daten holen
				$voting = new BCVotings($nid, 'bank');
				$votings = $voting->getVotingsForTimespan($this->timespan);

				$average = str_replace(',', '.', $votings['average']);
				$count = $voting->getVotingCountInTimespan($this->timespan);
			}else {
				die('Für withBank=true muss ein timestamp übergeben werden!');
			}



			$xmlNode = $this->node("bank")
				->attribute("id", $fid)
				->attribute("name", $nodeData->title)
				->attribute("url", $this->uriToUrl("/".$nodeData->path))
				->within();
			$xmlNode
						->node("benchmark_number")
							->innerText($average);
					$xmlNode->node("benchmark_count")
							->innerText($count);
					$xmlNode->node("benchmark_logo")
						->innerText(
							$this->uriToUrl(
								$voting->getSiegelUrl(
									true,
									explode(
										".",
										$average
										)
									)
								)
							);
			$this->endWithin();
		}
	}
	/**
	 * Adds http://... to $uri
	 *
	 * @param string $uri
	 * @return string
	 */
	private function uriToUrl($uri) {
		return "http://".$_SERVER['SERVER_NAME'].$uri;
	}
	/**
	 * Get an node (cached)
	 *
	 * @param int $nid
	 * @return stdClass a Drupal-Node
	 */
	private function getDrupalNode($nid) {
		if(!array_key_exists($nid, $this->nodeCache)) {
			$this->nodeCache[$nid] = node_load(array("nid"=>$nid));
		}
		return $this->nodeCache[$nid];
	}
}
?>