<?php
class BCVotings extends BCDBRelated {
	/**
	 * nid of the productitem be rated
	 *
	 * @var int
	 */
	private $nid;
	/**
	 * @var string
	 */
	private $typeProduct;
	/**
	 * @var float
	 */
	private $average;
	/**
	 * @var int
	 */
	private $promotingRate;
	/**
	 * @var array
	 */
	private $ratingSums;
	/**
	 * True if average etc.. has beed loaded from db
	 *
	 * @var unknown_type
	 */
	private $loaded = false;
	const votingBase = 5;
	private static $votingIndexTable = "BCVotingsIndex";
	private static $fieldDefinition = array(
		"tagesgeld"=>array(
			"ratings"         =>array(
				"Zinssatz"    =>"field_dailyallowances_interest_rating",
				"Zinsgutschrift"    =>"field_dailyallowances_credit_rating",
				"Sicherheit"    =>"field_dailyallowances_security_rating",
				"Service"    =>"field_dailyallowances_service_rating",
				"Beantragung"    =>"field_dailyallowances_apply_rating",
			),
			"promotion"       =>"content_type_bewertung_tagesgeld.field_call_promote_value",
			"productitemfield"=>"content_type_bewertung_tagesgeld.field_call_myproductitem_nid",
			"from"            =>"content_type_bewertung_tagesgeld"
		),
		"festgeld"=>array(
			"ratings"         =>array(
				"Zinssatz"    =>"field_fixeddeposits_interest_rating",
				"Zinsgutschrift"    =>"field_fixeddeposits_credit_rating",
				"Sicherheit"    =>"field_fixeddeposits_security_rating",
				"Service"    =>"field_fixeddeposits_service_rating",
				"Beantragung"    =>"field_fixeddeposits_apply_rating",
			),
			"promotion"       =>"content_type_bewertung_festgeld.field_promote_value",
			"productitemfield"=>"content_type_bewertung_festgeld.field_myproductitem_nid",
			"from"            =>"content_type_bewertung_festgeld"
		),
		"girokonto"=>array(
			"ratings"         =>array(
				"Kosten"    =>"field_currentaccount_costs_rating",
				"Dispozinsen"    =>"field_currentaccount_mrpinterest_rating",
				"Guthabenzinsen"    =>"field_currentaccount_credinteres_rating",
				"Karten"    =>"field_currentaccount_cards_rating",
				"Bargeldversorgung"    =>"field_currentaccount_cash_rating",
				"Service"    =>"field_currentaccount_service_rating",
				"Beantragung"    =>"field_currentaccount_apply_rating",
			),
			"promotion"       =>"content_type_bewertung_girokonto.field_currentaccount_promote_value",
			"productitemfield"=>"content_type_bewertung_girokonto.field_currentaccount_myproductim_nid",
			"from"            =>"content_type_bewertung_girokonto"
		),
		"kreditkarte"=>array(
			"ratings"         =>array(
				"Kosten"    =>"field_creditcard_costs_rating",
				"Bargeldversorgung"    =>"field_creditcard_cash_rating",
				"Zahlungsbedingungen"    =>"field_creditcard_payment_rating",
				"Zusatzleistungen"    =>"field_creditcard_specials_rating",
				"Service"    =>"field_creditcard_service_rating",
				"Beantragung"    =>"field_creditcard_apply_rating",
			),
			"promotion"       =>"content_type_bewertung_kreditkarte.field_creditcard_promote_value",
			"productitemfield"=>"content_type_bewertung_kreditkarte.field_creditcard_myproductitem_nid",
			"from"            =>"content_type_bewertung_kreditkarte"
		),
		"depot"=>array(
			"ratings"         =>array(
				"Kosten"    =>"field_brokerage_costs_rating",
				"Wertpapierhandel"    =>"field_brokerage_trading_rating",
				"Fondshandel"    =>"field_brokerage_fund_rating",
				"Zusatzleistungen"    =>"field_brokerage_specials_rating",
				"Service"    =>"field_brokerage_service_rating",
				"Beantragung"    =>"field_brokerage_apply_rating",
			),
			"promotion"       =>"content_type_bewertung_depot.field_brokerage_promote_value",
			"productitemfield"=>"content_type_bewertung_depot.field_brokerage_myproductitem_nid",
			"from"            =>"content_type_bewertung_depot"
		),
		"ratenkredit"=>array(
			"ratings"         =>array(
				"Kreditkosten"    =>"field_loans_costs_rating",
				"Kreditzusage"    =>"field_loans_commitment_rating",
				"Leistungen"    =>"field_loans_features_rating",
				"Service"    =>"field_loans_service_rating",
				"Beantragung"    =>"field_loans_apply_rating",
			),
			"promotion"       =>"content_type_bewertung_ratenkredit.field_loans_promote_value",
			"productitemfield"=>"content_type_bewertung_ratenkredit.field_loans_myproductitem_nid",
			"from"            =>"content_type_bewertung_ratenkredit"
		),
		"autokredit"=>array(
			"ratings"         =>array(
				"Kreditkosten"    =>"field_carloans_costs_rating",
				"Kreditzusage"    =>"field_carloans_commitment_rating",
				"Leistungen"    =>"field_carloans_features_rating",
				"Service"    =>"field_carloans_service_rating",
				"Beantragung"    =>"field_carloans_apply_rating",
			),
			"promotion"       =>"content_type_bewertung_autokredit.field_carloans_promote_value",
			"productitemfield"=>"content_type_bewertung_autokredit.field_carloans_myproductitem_nid",
			"from"            =>"content_type_bewertung_autokredit"
		),
			"mietkaution" =>array(
					"ratings" => array(
							"Kautionskosten" => "field_rentalbonds_costs_rating",
							"Kautionszusage" => "field_rentalbonds_commitment_rating",
							"Leistungen" => "field_rentalbonds_features_rating",
							"Service" => "field_rentalbonds_service_rating",
							"Beantragung" => "field_rentalbonds_apply_rating",
							),
					"promotion" => "content_type_bewertung_mietkaution.field_rentalbonds_promote_value",
					"productitemfield" => "content_type_bewertung_mietkaution.field_rentalbonds_myproductitem_nid",
					"from" => "content_type_bewertung_mietkaution"
					),
			"baufinanzierung" => array(
					"ratings" => array(
							"Finanzierungskosten" => "field_mortgages_costs_rating",
							"Finanzierungszusage" => "field_mortgages_commitment_rating",
							"Leistungen" => "field_mortgages_features_rating",
							"Service" => "field_mortgages_service_rating",
							"Beantragung" => "field_mortgages_apply_rating",
							),
					"promotion" => "content_type_bewertung_baufinanzierung.field_mortgages_promote_value",
					"productitemfield" => "content_type_bewertung_baufinanzierung.field_mortgages_myproductitem_nid",
					"from" => "content_type_bewertung_baufinanzierung"),
			"bank" => array(
					"ratings" => array(
							"Service" => "field_bank_service_rating",
							"Beantragung" => "field_bank_apply_rating",
							),
					"promotion" => "content_type_bewertung_bank.field_bank_promote_value",
					"productitemfield" => "content_type_bewertung_bank.field_bank_bankitem_nid",
					"from" => "content_type_bewertung_bank"),
	);
	public static $myProductField = array(
		"tagesgeld"=>"field_call_myproductitem",
		"festgeld"=>"field_myproductitem",
		"girokonto"=>"field_currentaccount_myproductim",
		"kreditkarte"=>"field_creditcard_myproductitem",
		"depot"=>"field_brokerage_myproductitem",
		"ratenkredit"=>"field_loans_myproductitem",
		"autokredit"=>"field_carloans_myproductitem",
		"mietkaution"=>"field_rentalbonds_myproductitem",
		"baufinanzierung"=>"field_mortgages_myproductitem",
		"bank"=>"field_bank_bankitem",
	);
	/**
	 * Constructor, sets the nid and the type of product,
	 * by setting type of product an lowercase - action is
	 * performed
	 *
	 * @param string $nidProductItem Drupal Node Id of the voted item
	 * @param string $typeProduct something like "tagesgeld"
	 * @return BCVotings
	 */
	public function BCVotings($nidProductItem, $typeProduct) {
		$this->nid = $nidProductItem;
		$this->typeProduct = strtolower($typeProduct);
	}
	/**
	 * Creates an instance of BCVotings by a rating
	 *
	 * @param stdClass $node
	 * @return BCVotings
	 */
	public static function byRatingNode($node) {
		$typeProduct = str_replace("bewertung_", "", $node->type);
		$field = self::$myProductField[$typeProduct];
		if (!is_null($field)) {
			$fieldArr = $node->$field;
			$nidProductItem = $fieldArr[0]["nid"];
		}
		return new BCVotings($nidProductItem, $typeProduct);
	}
	/**
	 * Return an sorted array [nid] => Average
	 *
	 * @param int $cnt
	 * @return string
	 */
	public static function getBestRated($cnt = 10) {
		$instance = new BCDBRelated();
		$sql = $sql = self::getBaseRatedSQL()." AND `count`>0 ORDER BY average DESC LIMIT ".$cnt;
		$r = array();
		foreach($instance->getDB()->fullTableArray($sql) as $line) {
			$r[$line["nid"]] = $line["average"];
		}
		return $r;
	}
	/**
	 * Return an sorted array [nid] => Average
	 *
	 * @param int $cnt
	 * @return string
	 */
	public static function getBestRatedMin20($cnt = 5) {
		$instance = new BCDBRelated();
		$sql = self::getBaseRatedSQL()." AND `count`>20 ORDER BY average DESC LIMIT ".$cnt;
		$r = array();
		foreach($instance->getDB()->fullTableArray($sql) as $line) {
			$r[$line["nid"]] = $line["average"];
		}
		return $r;
	}
	/**
	 * Return an sorted array [nid] => Average
	 *
	 * @param int $cnt
	 * @return string
	 */
	public static function getBestRatedMin50($cnt = 5) {
		$instance = new BCDBRelated();
		$sql = self::getBaseRatedSQL()." AND `count`>50 ORDER BY average DESC LIMIT ".$cnt;
		$r = array();
		foreach($instance->getDB()->fullTableArray($sql) as $line) {
			$r[$line["nid"]] = $line["average"];
		}
		return $r;
	}
	/**
	 * Return an sorted array [nid] => Average
	 *
	 * @param int $cnt
	 * @return string
	 */
	public static function getBestRatedMin100($cnt = 5) {
		$instance = new BCDBRelated();
		$sql = self::getBaseRatedSQL()." AND `count`>100 ORDER BY average DESC LIMIT ".$cnt;
		$r = array();
		foreach($instance->getDB()->fullTableArray($sql) as $line) {
			$r[$line["nid"]] = $line["average"];
		}
		return $r;
	}
	
	/**
	 * SQL for newest Product
	 *
	 * @return String
	 */
	 public static function getNewestProduct() {
		 $instance = new BCDBRelated();
		 $sql = "Select n.title FROM node n \n"
		. "INNER JOIN content_type_bank cb ON n.nid = cb.nid \n"
		. "ORDER BY n.created DESC Limit 10";
				
		$r = array();
		//while ($row = mysql_fetch_array($sql)
		foreach($instance->getDB()->fullTableArray($sql) as $line) {
			$r[$line["nid"]] = $line["title"];
			
		}
		return $r;
	 }
	 
	 /**
	  *SQL check if BankingCheck customer YES / NO
	  *@ return number
	  */
	 public static function getBCCustomer($node){
		 $sql = db_query("SELECT field_bccustomer_value FROM `content_field_bccustomer` WHERE nid like '".$node."' and delta LIKE 0 LIMIT 0, 30 ");
		 $result = mysql_result($sql);
		 return $result;
	 }
	 	 
	 
	/**
	 * Return an sorted array [nid] => Average
	 *
	 * @param int $cnt
	 * @return string
	 */
	public static function getBestRatedMin100_10($cnt = 10) {
		$instance = new BCDBRelated();
		$sql = self::getBaseRatedSQL()." AND `count`>100 ORDER BY average DESC LIMIT ".$cnt;
		$r = array();
		foreach($instance->getDB()->fullTableArray($sql) as $line) {
			$r[$line["nid"]] = $line["average"];
		}
		return $r;
	}
	/**
	 * Return an sorted array [nid] => Count
	 *
	 * @param int $cnt
	 * @return string
	 */
	public static function getMostRated($cnt = 10) {
		$instance = new BCDBRelated();
		$sql = self::getBaseRatedSQL()." AND `count`>0 ORDER BY `count` DESC LIMIT ".$cnt;
		$r = array();
		foreach($instance->getDB()->fullTableArray($sql) as $line) {
			$r[$line["nid"]] = $line["count"];
		}
		return $r;
	}
	/**
	 * Creates an array
	 * [nid]
	 * 		[nid][timestamp][average][promote][ratingSums][count]
	 * @return array
	 *
	 */
	public static function getIndex() {
		$r = array();
		$instance = new BCVotings("","");
		foreach($instance->getDB()->fullTableArray("SELECT * FROM ".self::$votingIndexTable) as $line) {
			$r[$line["nid"]] = $line;
		}
		return $r;
	}
	/**
	 * SQL for select (online) averages
	 *
	 * @return string
	 */
	private static function getBaseRatedSQL() {
		return "SELECT ".self::$votingIndexTable.".nid, average, count FROM ".self::$votingIndexTable."
		LEFT JOIN node on ".self::$votingIndexTable.".nid = node.nid
		WHERE node.status = 1";
	}
	
	private static function getBaseRatedSQL365() {
		return "SELECT b.nid, b.average, b.count, FROM_UNIXTIME(n.created) FROM BCVotingsIndex b
		JOIN node n on b.nid = n.nid
		WHERE DATE_ADD(FROM_UNIXTIME(n.created), INTERVAL 365 DAY) > NOW() AND n.status = 1";
	}
	 
	/**
	 * returns the fieldnames by type of productitem
	 *
	 * @throws Exception
	 * @param string $typeProduct
	 * @return array
	 */
	public static function getRatingFields($typeProduct) {
		if(array_key_exists($typeProduct, self::$fieldDefinition)) {
			return self::$fieldDefinition[$typeProduct]["ratings"];
		} else {
			throw new Exception($typeProduct. " is not configured");
		}
	}
	/**
	 * returns the field name of link to (main)product by
	 * type of productitem
	 *
	 * @param string $typeProduct
	 * @return string
	 */
	public static function getMyProductFieldName($typeProduct) {
		if(array_key_exists($typeProduct, self::$fieldDefinition)) {
			$raw = preg_replace("#.*\.#", "", self::$fieldDefinition[$typeProduct]["productitemfield"]);
			return str_replace("_nid", "", $raw);
		} else {
			throw new Exception($typeProduct. " is not configured");
		}
	}

	/**
	 * get array of $number last "good ratings" in $timespan, with at least $limit average rating and more than $commentLength characters as comment
	 * @param  integer  nid for productitem
	 * @param  Timespan Timespan for comments
	 * @param  integer  max Number of resulting ratings
	 * @param  float    minimum average rating
	 * @param  integer  maximal comment length
	 * @return Array    array with NovaBCVotings adhering to the criteria
	 */
	public static function getLastGoodRatings($nid, $timespan, $number, $limit, $commentLength) {
		//
		$node = node_load(array("nid" => $nid));
		$type = node_load(array("nid" => $node->field_prodmyproduct[0]["nid"]));

		$filteredVotings = array();

		$sql = db_query('SELECT nid FROM `content_type_bewertung_%s` JOIN `node` USING (nid) WHERE %s = %d AND ('.$timespan->getMySQLCondition("node.created").')', strtolower($type->title), self::$fieldDefinition[strtolower($type->title)]["productitemfield"], $nid);

		// cycle through results
		while ($voting = db_fetch_object($sql)) {
			// construct $novaBCVoting
			$novaBCVoting = new NovaBCVoting($voting->nid);

			// check comment length
			if (strlen($novaBCVoting->getComment()) < $commentLength) {
				continue;
			}

			// check minimum rating
			if ($novaBCVoting->getAverage() < $limit) {
				continue;
			}

			// if valid voting: add to array
			$filteredVotings[] = $novaBCVoting;

		}

		// select $number random elements
		shuffle($filteredVotings);
		return array_slice($filteredVotings, 0, $number);
	}

	public static function getLastCommentedRatings($num, $commentLength) {
		$filteredVotings = array();
		$sql = db_query('SELECT nid FROM `node` WHERE `status` = 1 AND `type` LIKE "bewertung_%" ORDER BY `created` DESC LIMIT 100');
		// cycle through results
		while ($voting = db_fetch_object($sql)) {
			// construct $novaBCVoting
			$novaBCVoting = new NovaBCVoting($voting->nid);

			// check comment length
			if (strlen($novaBCVoting->getComment()) < $commentLength) {
				continue;
			}

			if ($novaBCVoting->isCopiedBankVoting()) {
				continue;
			}

			// if valid voting: add to array
			$filteredVotings[] = $novaBCVoting;
		}
		return $filteredVotings;
	}

	/**
	 * Returns the average or "-" by using loadValues
	 *
	 * @return string
	 */
	public function getAverage() {
		if($this->loadValues()) {
			return $this->average;
		} else {
			return "-";
		}
	}
	/**
	 * Prints out an image in
	 * /sites/default/files/bcgrafiken/BankingCheck_Siegel_
	 *
	 * @param boolean $user True if use User-Siegel
	 */
	public function showSiegel($user=true, $customAverage = null) {
		if(is_null($customAverage)) {
			if($this->loadValues()) {
				$this->innerShowSiegel(explode(".",$this->average));
			}
		} else {
			$this->innerShowSiegel(explode(".",$customAverage));
		}
	}

	public function getSiegelHtml($user=true, $customAverage = null) {
		if(is_null($customAverage)) {
			if($this->loadValues()) {
				return $this->innerGetSiegelHtml(explode(".",$this->average));
			}
		} else {
			return $this->innerGetSiegelHtml(explode(".",$customAverage));
		}
	}

	public function getSiegelUrl($user=true, $nums=null) {
		if($this->loadValues() || !is_null($nums)) {
			if(is_null($nums)) {
				$nums = explode(".",$this->average);
			}
			$appendix = $nums[0];
			if($nums[1] == "") {
				$appendix .= "0";
			} else {
				$appendix .= $nums[1];
			}
			if($user) {
				$prefix = "Usr";
			} else {
				$prefix = "";
			}
			$siegel = ($this->typeProduct == 'bank' ?  'Anbietersiegel' :  'Siegel');
			return "/sites/default/files/bcgrafiken/BankingCheck_".$siegel."_".$prefix."_".$appendix.".gif";
		}
	}

	private function innerShowSiegel(array $nums) {
		echo $this->innerGetSiegelHtml($nums);
	}

	private function innerGetSiegelHtml(array $nums) {
		return '<img src="'.$this->getSiegelUrl(true, $nums).'" alt="BankingCheck User-Siegel: '.$nums[0].','.$nums[1].'" width="150" height="150" />';
	}

	/**
	 * Prints out an table of Voting-Results
	 * @return null
	 */
	public function showVotings2() {
		$this->showVotingsForTimespan(null);
	}


	public function showVotings($bBank) {
	//first
		if($this->loadValues()) {
			if ($bBank == true) {
				echo "<div itemscope itemtype='http://schema.org/BankOrCreditUnion'><meta itemprop='name' content='".node_load(array("nid" => $this->nid))->title."' /><div itemprop='aggregateRating' itemscope itemtype='http://schema.org/AggregateRating'><meta itemprop='bestRating' content='".BCVotings::votingBase."' /><table><tbody>";
			} else {
				echo "<div itemscope itemtype='http://schema.org/Product'><meta itemprop='name' content='".node_load(array("nid" => $this->nid))->title."' /><div itemprop='aggregateRating' itemscope itemtype='http://schema.org/AggregateRating'><meta itemprop='bestRating' content='".BCVotings::votingBase."' /><table><tbody>";
			}

			foreach($this->ratingSums as $name=>$value) {
				$value = number_format($value, 1);
				// alternativer Text für Beantragung und Service
				if ($name == "Beantragung")
					echo "<tr><th>Banking &amp; Prozesse</th><td>$value</td></tr>";
				elseif  ($name == "Service")
					echo "<tr><th>Service, Beratung &amp; Support</th><td>$value</td></tr>";
				else
					echo "<tr><th>$name</th><td>$value</td></tr>";
			}
      if ($bBank == true) {
				echo "</tbody><tfoot><tr><th>Gesamtbewertung</th><td><span itemprop='ratingValue'>".number_format($this->average, 1)."</span></td></tr></tbody></table>";
				echo '<meta itemprop="ratingCount" content="'.$this->calcVotingCount().'" /></div></div>';
			} else {
				echo "</tbody><tfoot><tr><th>Gesamtbewertung</th><td><span itemprop='ratingValue'>".number_format($this->average, 1)."</span></td></tr></tbody></table>";
				echo '<meta itemprop="reviewCount" content="'.$this->calcVotingCount().'" /></div></div>';
			}
		}
	}

	/**
	 * Prints out an table of Voting-Results for timespan
	 * @param  Timespan|null $t Timespan for votes
	 * @return void
	 */
	public function showVotingsForTimespan(Timespan $t = null) {
		$vals = array();
		$customAverage = 0;
		$res = $this->getDB()->single_line_assoc($this->buildSQLForTsCreated($t));
		$siegel = ($this->typeProduct == 'bank' ?  'Anbietersiegel' :  'Siegel');
		unset($res["Promote"]);
		foreach($res as $name=>$value) {
			$vals[] = $value;
		}
		if (count($vals) != 0) {
      $customAverage = round(array_sum($vals)/count($vals), 1);
		  echo '<div class="siegel">';
		  echo $this->showSiegel(true, $customAverage);
		} else {

			echo '<div class="siegel">';
      		echo '<img src="/sites/default/files/bcgrafiken/BankingCheck_'.$siegel.'_Usr_not.gif" alt="BankingCheck User-Siegel" width="150" height="150" />';
    }
		echo '</div>';
		echo "<table>";
		if(!is_null($t)) {
			echo "<caption>".$t->toString()."</caption>";
		}
		echo "<tbody>";
		foreach($res as $name=>$value) {
				$value = number_format(round($value, 1), 1);
				// alternativer Text für Beantragung und Service
				if ($name == "Beantragung")
					echo "<tr><th>Banking &amp; Prozesse</th><td>$value</td></tr>";
				elseif  ($name == "Service")
					echo "<tr><th>Service, Beratung &amp; Support</th><td>$value</td></tr>";
				else
					echo "<tr><th>$name</th><td>$value</td></tr>";
		}
		echo "</tbody><tfoot><tr><th>Gesamtbewertung</th><td>".number_format($customAverage,1)."</td></tr></tbody></table>";
	}

	public function getVotingsForTimespan(Timespan $t = null) {

		$vals = array();
		$sql = $this->buildSQLForTsCreated($t);

		$res = $this->getDB()->single_line_assoc($sql);
		unset($res["Promote"]);


		$sum = 0;
		foreach($res as $name=>$value) {
			$sum += floatval($value);
		}
		if(count($res) != 0)
			$customAverage = number_format(round(($sum / count($res)), 1), 1);
		else $customAverage = 0;

    	return array('count' => count($res), 'average' => $customAverage);
	}

	/**
	 * Prints out an DIV with the nodes promoting rate
	 * @return void
	 */
	public function showPromotingRate() {
		if($this->loadValues()) {
			echo '<div class="promotingRate">Weiterempfehlung: '.$this->promotingRate." %".'</div>';
			$lastVote = $this->getDB()->simpleQuery($this->buildSQLLastVote());
			if($lastVote > $this->average) {
				echo '<img class="ratingTrend" src="/sites/default/files/bcgrafiken/trendUp.png" alt="Aufwärtstrend" />';
			} elseif($lastVote < $this->average) {
				echo '<img class="ratingTrend" src="/sites/default/files/bcgrafiken/trendDown.png" alt="Abwärtstrend" />';
			} else {
				echo '<img class="ratingTrend" src="/sites/default/files/bcgrafiken/trendEqual.png" alt="Gleichbleibender Trend" />';
			}
		}
	}

	/**
	 * Echos number of votings for this node from cache
	 * @return void
	 */
	public function showVotingCount() {
		echo $this->getVotingCount();
	}

	/**
	 * Gets number of votings for this node from cache
	 * @return string number of votings
	 */
	public function calcVotingCount() {
		return $this->getVotingCount();
	}

	/**
	 * Gets number of votings for this node from cache
	 *
	 * @return string
	 */
	private function getVotingCount() {
		if($this->configExits()){
			return $this->getDB()->simpleQuery("SELECT COUNT(*) ".$this->getFROMWHERESql(self::$fieldDefinition[$this->typeProduct]));
		} else {
			return "0";
		}
	}


	public function getVotingCountInTimespan($t) {
		if($this->configExits()){
			return $this->getDB()->simpleQuery(
				"SELECT COUNT(*) ".
				$this->getFROMWHERESql(self::$fieldDefinition[$this->typeProduct]) .
				" AND (".$t->getMySQLCondition("node.created").")"
			);
		} else {
			return "0";
		}
	}

	/**
	 * Cached query for votings
	 * @return boolean true, if values could be loaded
	 */
	private function loadValues() {
		if(!$this->loaded) {
			if(!$this->configExits()) {
				return false;
			} else {
				return $this->loadFromDB();
			}
		} else {
			return true;
		}
	}


	public function loadFromDB() {
		$line = $this->getDB()->singleLineAssoc("SELECT * FROM ".self::$votingIndexTable." WHERE nid = ".$this->nid);
		$this->average = number_format($line["average"],1);
		$this->promotingRate = $line["promote"];
		$this->ratingSums = unserialize($line["ratingSums"]);
		return true;
	}


	/**
	 * Reindex cache for voting index containing averages
	 * @return boolean success
	 */
	public function reindex() {
		$myConfig = self::$fieldDefinition[$this->typeProduct];
		$ratingNames = array_keys($myConfig["ratings"]);
		$sql = $this->buildSQL();
		try {
			$this->ratingSums = $this->getDB()->singleLineAssoc($sql);
			$this->promotingRate = $this->ratingSums["Promote"];
			unset($this->ratingSums["Promote"]);

			# recalculate average
			$sum = 0;
			foreach($this->ratingSums as $name=>$value) {
				$sum += floatval($value);
			}
			$this->average = number_format($sum / count($ratingNames), 1);

			$this->loaded = true;
			$this->getDB()->delete(self::$votingIndexTable,"nid = ".$this->nid);
			$this->getDB()->insert(
				self::$votingIndexTable,
				array(
					"nid"        => $this->nid,
					"average"    => $this->average,
					"ratingSums" => serialize($this->ratingSums),
					"promote"    => $this->promotingRate,
					"count"      => $this->getVotingCount()
				)
			);
			$sql = "UPDATE content_type_productitem SET field_rating_value = '".$this->average."' WHERE nid = ".$this->nid;
			$this->getDB()->query($sql);
			return true;
		} catch(Exception $e) {
			drupal_set_message("Failed: ".$this->getDB()->curQuery, "error");
			return false;
		}
	}

	/**
	 * Checks, if an config in self::$fieldDefinition for this
	 * type of product exists
	 *
	 * @return boolean
	 */
	private function configExits() {
		return in_array($this->typeProduct, array_keys(self::$fieldDefinition));
	}

	private function buildSQL(Timespan $t = null) {
		$myConfig = self::$fieldDefinition[$this->typeProduct];
		$sql = "SELECT ";
		$fields = array();
		foreach($myConfig["ratings"] as $name=>$value) {
			$fields[] = " AVG(ROUND(`$value` / 100 * ".BCVotings::votingBase.", 1)) AS ".$name;
		}
		$fields[] = "ROUND((SUM(".$myConfig["promotion"].") /  COUNT(*)) * 100) AS Promote";
		$sql .= implode(", ", $fields);
		$sql .= $this->getFROMWHERESql($myConfig);
		if(!is_null($t)) {
			$sql .= " AND (".$t->getMySQLCondition("node.changed").")";
		}
		$sql .= " GROUP BY ".$myConfig["productitemfield"];
		return $sql;
	}

	private function buildSQLForTsCreated(Timespan $t = null) {
		$myConfig = self::$fieldDefinition[$this->typeProduct];
		$sql = "SELECT ";
		$fields = array();
		foreach($myConfig["ratings"] as $name=>$value) {
			$fields[] = " AVG(ROUND(`$value` / 100 * ".BCVotings::votingBase.", 1)) AS ".$name;
		}
		$fields[] = "ROUND((SUM(".$myConfig["promotion"].") /  COUNT(*)) * 100) AS Promote";
		$sql .= implode(", ", $fields);
		$sql .= $this->getFROMWHERESql($myConfig);
		if(!is_null($t)) {
			$sql .= " AND (".$t->getMySQLCondition("node.created").")";
		}
		$sql .= " GROUP BY ".$myConfig["productitemfield"];
		return $sql;
	}

	private function buildSQLLastVote() {
		$myConfig = self::$fieldDefinition[$this->typeProduct];
		$sql = "SELECT ";
		$fields = array();
		foreach($myConfig["ratings"] as $name=>$value) {
			$fields[] = "ROUND(`$value` / 100 * ".BCVotings::votingBase.")";
		}
		$sql .= "ROUND((".implode(" + ", $fields).") / ".count($fields).", 1) as lastVote ";
		$sql .= $this->getFROMWHERESql($myConfig);
		$sql .= " ORDER BY `node`.`changed`  DESC LIMIT 1";
		return $sql;
	}

	/**
	 * Creates a SQL Part like
	 *   FROM   <mytable>
	 *   WHERE  <mnodeidfield> = $this->nid
	 *
	 * @param array $myConfig
	 * @return string
	 */
	private function getFROMWHERESql($myConfig) {
		$sql = " FROM ".$myConfig["from"];
		$sql .= " LEFT JOIN node ON ".$myConfig["from"].".nid = node.nid ";
		$sql .= " WHERE status = 1 AND ".$myConfig["productitemfield"]." = ".$this->nid;
		return $sql;
	}
}
?>
