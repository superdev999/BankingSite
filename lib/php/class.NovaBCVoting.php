<?php
class NovaBCVoting {
	private $_node;
	private $_ratings;
	private $_promote;
	private $_promote_bank;
	private $_comment;
	private $_notifyOnPublish;
	public function __construct($nid) {
		if (is_integer($nid) || is_string($nid)) $this->_node = node_load(array("nid"=>$nid));
		else if (is_object($nid)) {
			#var_dump($nid);
			if (preg_match("/^bewertung_/", $nid->type)) $this->_node = $nid;
			else {
				echo $this->_node->nid." is not a voting: ".$this->_node->type;
				return false;
			}
		} else throw new Exception($nid." is not a valid type.");

		if (preg_match("/^bewertung_/", $this->_node->type) === 0) {
			echo $this->_node->nid." is not a voting: ".$this->_node->type;
			return false;
		}


		$ratingNamesArray = array();
		$BCVotingNode = BCVotings::byRatingNode($this->_node);

		// strip names from the trailing "_rating"
		foreach ($BCVotingNode->getRatingFields(substr($this->_node->type, 10)) as $key => $fullFieldName) {
			#echo $key." | ".$fullFieldName;
			$ratingNamesArray[$key] = substr($fullFieldName, 0, -7);
			#echo "From ".$fullFieldName. " to ".substr($fullFieldName, 0, -7)."<br>";
		}

		// Copy keys for later reference
		$this->_ratings = array();
		foreach($ratingNamesArray as $key => $val) $this->_ratings[$key] = NULL;

		// Flip array that we have the fieldname => speaking name association (e.g. "field_fixeddeposits_credit" => "Zinsgutschrift"
		$fieldnameToSpeakingName = array_flip($ratingNamesArray);
		#print_r($fieldnameToSpeakingName);

		foreach($this->_node as $key => $val) {
			// If the key is found in the names of the ratingNames of the type
			if (in_array($key, $ratingNamesArray)) {
				$speakingName = $fieldnameToSpeakingName[$key];
				#echo "Rating found in ".$key.": Is ".$speakingName."<br>";
				$valtmp = $val[0];
				$this->_ratings[$speakingName] = $valtmp["rating"];
			}
			// If it is the promoting value
			elseif (preg_match('/_promote$/', $key)) {
				$this->_promote = $val[0]["value"] == 1 ? "Ja" : "Nein";
				if (empty($this->_promote_bank))
					$this->_promote_bank = $val[0]["value"] == 1 ? "Ja" : "Nein";
			}
			// if it is the comment
			elseif (preg_match('/_comment$/', $key)) {
				$valtmp = $val[0];
				$this->_comment = $valtmp["value"];
				#echo "Kommentar gefunden in ".$key.": ".$comment.".<br>";
			}
			// if it it the notifyOnPublish value
			elseif (preg_match('/_notify_publish/', $key)) {
				$this->_notifyOnPublish = $val[0]["value"] == 1 ? "Ja" : "Nein";
			}
			// if it is the promote bank value
			elseif (preg_match('/_promote_b/', $key)) {
				if (!is_null($val[0]["value"]))
					$this->_promote_bank = $val[0]["value"] == 1 ? "Ja" : "Nein";
			}
		}
	}

	public function getNode() {
		return $this->_node;
	}

	public function getNid() {
		return $this->getNode()->nid;
	}

	public static function isVoting($arg) {
		if (is_integer($arg)) $node = node_load(array("nid" => $arg));
		else                  $node = $arg;
		#var_dump($node);
		return (preg_match("/^bewertung_/", $node->type) === 1);
	}

	/**
	 * Return field name for NID of rated productitem
	 * @return String field name for NID of rated productitem
	 */
	public function getRatedProductItemNidFieldName() {
		switch ($this->_node->type) {
			case "bewertung_autokredit":
				return "field_carloans_myproductitem";
			case "bewertung_baufinanzierung":
				return "field_mortgages_myproductitem";
			case "bewertung_depot":
				return "field_brokerage_myproductitem";
			case "bewertung_festgeld":
				return "field_myproductitem";
			case "bewertung_girokonto":
				return "field_currentaccount_myproductim";
			case "bewertung_kreditkarte":
				return "field_creditcard_myproductitem";
			case "bewertung_mietkaution":
				return "field_rentalbonds_myproductitem";
			case "bewertung_ratenkredit":
				return "field_loans_myproductitem";
			case "bewertung_tagesgeld":
				return "field_call_myproductitem";
			case "bewertung_bank":
				return "field_bank_bankitem";
			default:
				throw new Exception("Undefined rating type: ".$this->_node->type);
		}
	}

	/**
	 * Return NID of rated productitem
	 * @return int NID of rated productitem
	 */
	public function getRatedProductItemNid() {
		$fieldName = $this->getRatedProductItemNidFieldName();
		$array = $this->_node->$fieldName;
		return $array[0]["nid"];
	}

	public function getRatedProductItemNode() {
		return node_load(array("nid"=>$this->getRatedProductItemNid()));
	}

	public function getRatedBankNid() {
		$productNode = $this->getRatedProductItemNode();
		return $productNode->field_proditemmybank[0]["nid"];
	}

	public function getRatingValues() {
		return $this->_ratings;
	}
	public function getPromote() {
		return $this->_promote;
	}
	public function getPromoteBank() {
		return $this->_promote_bank;
	}
	public function getComment() {
		return $this->_comment;
	}
	public function getNotifyOnPublish() {
		return $this->_notifyOnPublish;
	}

	public function isAnonymousVote() {
		return ($this->_node->uid == 0);
	}

	public function getStatus() {
		return $this->_node->status;
	}

	public function saveNode() {
		node_save($this->_node);
	}

	public function getUser() {
		return user_load(array("uid" => $this->_node->uid));
	}

	public function getUsername() {
		return strlen($this->getUser()->name) == 0 ? "Anonym" : $this->getUser()->name;
	}

	public function isRatingForMe() {
		global $user;
		#var_dump($user);

		if (!in_array('Unternehmen', array_values($user->roles))) return false;
		profile_load_profile($user);
		if ($this->_node->type == "bewertung_bank") $bankId = $this->getRatedProductItemNid();
		else                                        $bankId = $this->getRatedBankNid();
		#echo "My ID: ".$user->profile_bank_id;
		#echo "BankId: ".$bankId;

		return $user->profile_company_id == $bankId;

	}

	public function getRatingForMe() {
		if ($this->isRatingForMe()) return 'Ja';
		return 'Nein';
	}

	/*
	 * Return true if the Voting was created automatically on creation of another voting
	 */
	public function isCopiedBankVoting() {
		# if this is not a bewertung_bank it cannot be copied
		if ($this->_node->type != "bewertung_bank") return false;

		# if the previous node is not a rating it cannot be copied
		if (!NovaBCVoting::isVoting($this->_node->nid-1)) return false;

		// load previous node
		$previousNode = new NovaBCVoting($this->_node->nid-1);

		# easier check for anonymous votes
		if ($this->isAnonymousVote()) {
			// if token and mail are the same, this node was created automatically
			if ($previousNode->getEmail() == $this->getEmail() && $previousNode->getToken() == $this->getToken()) return true;
		} else {
			# if owner is different
			if ($previousNode->getNode()->uid != $this->getNode()->uid) return false;

			// get comment
			if ($previousNode->getComment() != $this->getComment()) return false;

			// get rated bank
			if ($previousNode->getRatedBankNid() != $this->getRatedBankNid()) return false;

			// save Service, Beantragung, Weiterempfehlen
			if ($previousNode->getServiceField() != $this->getServiceField()) return false;
			if ($previousNode->getApplyField() != $this->getNode()->getApplyField()) return false;
			if ($previousNode->getBankPromoteField() != $this->getBankPromoteField()) return false;

			# all equal
			return true;
		}
		return false;
	}

	/*
	 * Return an array of copied bank votings with relation to this one
	 */
	public function getCopiedBankVotings() {
		if ($this->_node->type == "bewertung_bank") return false;
		$checkNext = 2;
		$nodeArray = array();
		for ($i=1; $i <= $checkNext; $i++) {
			$iNode = new NovaBCVoting($this->_node->nid+$i);
			if ($iNode->isCopiedBankVoting()) {
				$nodeArray[] = $iNode;
			}
		}
		return $nodeArray;
	}

	/**
	 * Return the email of the user who created this rating, either for anonymous votings or for registrated users
	 * @return String Email
	 */
	public function getEmailFieldName() {
		if ($this->isAnonymousVote()) {
			switch ($this->_node->type) {
				case "bewertung_autokredit":
					return "field_carloans_mailadress";
				case "bewertung_baufinanzierung":
					return "field_mortgages_mailadress";
				case "bewertung_depot":
					return "field_brokerage_mailadress";
				case "bewertung_festgeld":
					return "field_fixeddeposits_mailadress";
				case "bewertung_girokonto":
					return "field_currentaccount_mailadress";
				case "bewertung_kreditkarte":
					return "field_creditcard_mailadress";
				case "bewertung_mietkaution":
					return "field_rentalbonds_mailadress";
				case "bewertung_ratenkredit":
					return "field_loans_mailadress";
				case "bewertung_tagesgeld":
					return "field_dailyallowances_mailadress";
				case "bewertung_bank":
					return "field_bank_mailadress";
				default:
					throw new Exception("Undefined rating type: ".$this->_node->type);
			}
		}
	}

	/**
	 * Return the email of the user who created this rating, either for anonymous votings or for registrated users
	 * @return String Email
	 */
	public function getEmail() {
		if ($this->isAnonymousVote()) {
			$fieldName = $this->getEmailFieldName();
			$tmpArray = $this->_node->$fieldName;
			return $tmpArray[0]["value"];
		} else {
		  return $this->getUser()->mail;
		}
	}

	public function getMailApproved() {
		if (!$this->isAnonymousVote()) return false;
		switch ($this->_node->type) {
			case "bewertung_autokredit":
				$mailApproved = "field_carloans_mailapproved";
				break;
			case "bewertung_baufinanzierung":
				$mailApproved = "field_mortgages_mailapproved";
				break;
			case "bewertung_depot":
				$mailApproved = "field_brokerage_mailapproved";
				break;
			case "bewertung_festgeld":
				$mailApproved = "field_fixeddeposits_mailapproved";
				break;
			case "bewertung_girokonto":
				$mailApproved = "field_currentaccount_mailapprove";
				break;
			case "bewertung_kreditkarte":
				$mailApproved = "field_creditcard_mailapproved";
				break;
			case "bewertung_mietkaution":
				$mailApproved = "field_rentalbonds_mailapproved";
				break;
			case "bewertung_ratenkredit":
				$mailApproved = "field_loans_mailapproved";
				break;
			case "bewertung_tagesgeld":
				$mailApproved = "field_dailyallowances_mailapprov";
				break;
			case "bewertung_bank":
				$mailApproved = "field_bank_mailapproved";
				break;
			default:
				throw new Exception("Undefined rating type: ".$this->_node->type);
		}
		$mailApprovedTmp = $this->_node->$mailApproved;
		return $mailApprovedTmp[0]["value"];
	}

	public function setMailApproved($value) {
		switch ($this->_node->type) {
			case "bewertung_autokredit":
				$this->_node->field_carloans_mailapproved[0]["value"] = $value;
				break;
			case "bewertung_baufinanzierung":
				$this->_node->field_mortgages_mailapproved[0]["value"] = $value;
				break;
			case "bewertung_depot":
				$this->_node->field_brokerage_mailapproved[0]["value"] = $value;
				break;
			case "bewertung_festgeld":
				$this->_node->field_fixeddeposits_mailapproved[0]["value"] = $value;
				break;
			case "bewertung_girokonto":
				$this->_node->field_currentaccount_mailapprove[0]["value"] = $value;
				break;
			case "bewertung_kreditkarte":
				$this->_node->field_creditcard_mailapproved[0]["value"] = $value;
				break;
			case "bewertung_mietkaution":
				$this->_node->field_rentalbonds_mailapproved[0]["value"] = $value;
				break;
			case "bewertung_ratenkredit":
				$this->_node->field_loans_mailapproved[0]["value"] = $value;
				break;
			case "bewertung_tagesgeld":
				$this->_node->field_dailyallowances_mailapprov[0]["value"] = $value;
				break;
			case "bewertung_bank":
				$this->_node->field_bank_mailapproved[0]["value"] = $value;
				break;
			default:
				throw new Exception("Undefined Node type ".$this->_node->type, 1);
		}
	}

	public function getRealToken() {
		if (!$this->isAnonymousVote()) {
			throw new Exception("Requested token for not anonymous vote with nid ".$this->_node->nid);
			return false;
		}
		switch ($this->_node->type) {
			case "bewertung_autokredit":
				$token = $this->_node->field_carloans_token[0][value];
				break;
			case "bewertung_baufinanzierung":
				$token = $this->_node->field_mortgages_token[0][value];
				break;
			case "bewertung_depot":
				$token = $this->_node->field_brokerage_token[0][value];
				break;
			case "bewertung_festgeld":
				$token = $this->_node->field_fixeddeposits_token[0][value];
				break;
			case "bewertung_girokonto":
				$token = $this->_node->field_currentaccount_token[0][value];
				break;
			case "bewertung_kreditkarte":
				$token = $this->_node->field_creditcard_token[0][value];
				break;
			case "bewertung_mietkaution":
				$token = $this->_node->field_rentalbonds_token[0][value];
				break;
			case "bewertung_ratenkredit":
				$token = $this->_node->field_loans_token[0][value];
				break;
			case "bewertung_tagesgeld":
				$token = $this->_node->field_dailyallowances_token[0][value];
				break;
			case "bewertung_bank":
				$token = $this->_node->field_bank_token[0][value];
				break;
			default:
				throw new Exception("Undefined rating type: ".$this->_node->type);
		}
		#if (strlen($token) < 2) throw new Exception("Token not defined for nid ".$this->_node->nid);
		return $token;
	}

	public function getTokenFieldName() {
		switch ($this->_node->type) {
			case "bewertung_autokredit":
				return "field_carloans_token";
			case "bewertung_baufinanzierung":
				return "field_mortgages_token";
			case "bewertung_depot":
				return "field_brokerage_token";
			case "bewertung_festgeld":
				return "field_fixeddeposits_token";
			case "bewertung_girokonto":
				return "field_currentaccount_token";
			case "bewertung_kreditkarte":
				return "field_creditcard_token";
			case "bewertung_mietkaution":
				return "field_rentalbonds_token";
			case "bewertung_ratenkredit":
				return "field_loans_token";
			case "bewertung_tagesgeld":
				return "field_dailyallowances_token";
			case "bewertung_bank":
				return "field_bank_token";
			default:
				throw new Exception("Undefined rating type: ".$this->_node->type);
		}
	}

	public function getToken() {
		return md5($this->getRealToken()."80uTKSNI6mT68IJuaDgC");
	}

	public function getPromoteFieldName() {
		switch ($this->_node->type) {
			case "bewertung_autokredit":
				return "field_carloans_promote";
			case "bewertung_baufinanzierung":
				return "field_mortgages_promote";
			case "bewertung_depot":
				return "field_brokerage_promote";
			case "bewertung_festgeld":
				return "field_promote";
			case "bewertung_girokonto":
				return "field_currentaccount_promote";
			case "bewertung_kreditkarte":
				return "field_creditcard_promote";
			case "bewertung_mietkaution":
				return "field_rentalbonds_promote";
			case "bewertung_ratenkredit":
				return "field_loans_promote";
			case "bewertung_tagesgeld":
				return "field_call_promote";
			case "bewertung_bank":
				return "field_bank_promote";
			default:
				throw new Exception("Undefined rating type: ".$this->_node->type);
		}
	}

	public function getPromoteField () {
		$fieldName = $this->getPromoteFieldName();
		return $this->_node->$fieldName;
	}

	public function getBankPromoteFieldName() {
		switch ($this->_node->type) {
			case "bewertung_autokredit":
				return "field_carloans_promote_bank";
			case "bewertung_baufinanzierung":
				return "field_mortgages_promote_bank";
			case "bewertung_depot":
				return "field_brokerage_promote_bank";
			case "bewertung_festgeld":
				return "field_promote_bank";
			case "bewertung_girokonto":
				return "field_currentaccount_promote_ban";
			case "bewertung_kreditkarte":
				return "field_creditcard_promote_bank";
			case "bewertung_mietkaution":
				return "field_rentalbonds_promote_bank";
			case "bewertung_ratenkredit":
				return "field_loans_promote_bank";
			case "bewertung_tagesgeld":
				return "field_call_promote_bank";
			default:
				throw new Exception("Undefined rating type: ".$this->_node->type);
		}
	}

	public function getBankPromoteField () {
		// Only works for products, not for banks
		$fieldName = $this->getBankPromoteFieldName();
		return $this->_node->$fieldName;
	}

	public function getPromoteValue() {
		$fieldName = $this->getPromoteFieldName();
		$field = $this->_node->$fieldName;
		return $field[0]["value"];
	}

	public function getApplyFieldName() {
		switch ($this->_node->type) {
			case "bewertung_autokredit":
				return "field_carloans_apply";
			case "bewertung_baufinanzierung":
				return "field_mortgages_apply";
			case "bewertung_depot":
				return "field_brokerage_apply";
			case "bewertung_festgeld":
				return "field_fixeddeposits_apply";
			case "bewertung_girokonto":
				return "field_currentaccount_apply";
			case "bewertung_kreditkarte":
				return "field_creditcard_apply";
			case "bewertung_mietkaution":
				return "field_rentalbonds_apply";
			case "bewertung_ratenkredit":
				return "field_loans_apply";
			case "bewertung_tagesgeld":
				return "field_dailyallowances_apply";
			case "bewertung_bank":
				return "field_bank_apply";
			default:
				throw new Exception("Undefined rating type: ".$this->_node->type);
		}
	}

	public function getApplyField() {
		$fieldName = $this->getApplyFieldName();
		return $this->_node->$fieldName;
	}

	public function getApplyValue() {
		$fieldName = $this->getApplyFieldName();
		$field = $this->_node->$fieldName;
		return $field[0]["rating"];
	}

	public function getServiceFieldName() {
		switch ($this->_node->type) {
			case "bewertung_autokredit":
				return "field_carloans_service";
			case "bewertung_baufinanzierung":
				return "field_mortgages_service";
			case "bewertung_depot":
				return "field_brokerage_service";
			case "bewertung_festgeld":
				return "field_fixeddeposits_service";
			case "bewertung_girokonto":
				return "field_currentaccount_service";
			case "bewertung_kreditkarte":
				return "field_creditcard_service";
			case "bewertung_mietkaution":
				return "field_rentalbonds_service";
			case "bewertung_ratenkredit":
				return "field_loans_service";
			case "bewertung_tagesgeld":
				return "field_dailyallowances_service";
			case "bewertung_bank":
				return "field_bank_service";
			default:
				throw new Exception("Undefined rating type: ".$this->_node->type);
		}
	}

	public function getServiceField() {
		$fieldName = $this->getServiceFieldName();
		return $this->_node->$fieldName;
	}

	public function getServiceValue() {
		$fieldName = $this->getServiceFieldName();
		$field = $this->_node->$fieldName;
		return $field[0]["rating"];
	}

	public function getAverage() {
		$sum = 0;
		foreach($this->getRatingValues() as $name=>$value) {
			$sum += $value;
		}
		$avg = $sum / count($this->getRatingValues())/100*BCVotings::votingBase;
		return number_format($avg, 1);
	}

	public function showGraphicVotings() {
		echo $this->getGraphicVotings();
	}

	public function getGraphicVotings() {
		$html = "<table><tbody>";
		$sum = 0;
		#print_r($this->getRatingValues());
		foreach($this->getRatingValues() as $name=>$value) {
			$sum += $value;

			// alternativer Text für Beantragung und Service
			if ($name == "Beantragung")
				$html .= "<tr><th>Banking &amp; Prozesse</th><td>".self::generateRate($value)."</td></tr>";
			elseif ($name == "Service")
				$html .= "<tr><th>Service, Beratung &amp; Support</th><td>".self::generateRate($value)."</td></tr>";
			else
				$html .= "<tr><th>$name</th><td>".self::generateRate($value)."</td></tr>";
		}
		$avg = $sum / count($this->getRatingValues())/100*BCVotings::votingBase;
		$html .= "</tbody><tfoot><tr><th><b>Gesamtbewertung</b></th><td style=\"padding-left: 6px; vertical-align: middle;\">".number_format($avg, 1)."</td></tr></tfoot></table>";
		return $html;
	}


	/**
	 * Generate HTML for visual display of $value
	 * @param  integer $value Voting value in <= 100
	 * @return String         HTML for visual display
	 */
	public static function generateRate($value) {
		$vals = array();
		for ($count=100/BCVotings::votingBase; $count <= 101; $count += 100/BCVotings::votingBase) {
			# round up
			$vals[] = round($count+0.4);
		}

		$i = 0;
		$html = "";
		while($i<BCVotings::votingBase) {
			if ($vals[$i] <= $value) {
				$html = $html."<div class='star on'></div>";
			} else {
				$html = $html."<div class='star'></div>";
			}
			$i++;
		}
		return $html;
	}
}
