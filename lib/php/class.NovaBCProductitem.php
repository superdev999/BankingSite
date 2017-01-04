<?php
class NovaBCProductitem {
  private $_node;
  public function __construct($nid) {
    if (is_integer($nid) || is_string($nid)) $this->_node = node_load(array("nid"=>$nid));
    else if (is_object($nid)) {
      if ($nid->type == "productitem") $this->_node = $nid;
      else {
        echo $this->_node->nid." is not a productitem: ".$this->_node->type;
        return false;
      }
    } else throw new Exception($nid." is not a valid type: ".get_class($nid));
  }


  /**
   * Checks if this productitem has an agent
   * @return boolean [description]
   */
  public function hasAgent() {
    return (!is_null($this->_node->field_agent[0]["nid"]));
  }

  /**
   * Returns the productitems agent NID
   * @return Integer agent nid
   */
  public function getAgentNid() {
    if ($this->hasAgent()) {
      return $this->_node->field_agent[0]["nid"];
    }
    return NULL;
  }

  public function getAnbieter() {
    return node_load(array("nid" => $this->getAnbieterNid()));
  }

  public function getAnbieterNid() {
    return $this->_node->field_proditemmybank[0]["nid"];
  }

  public function getProduct() {
    return node_load(array("nid" => $this->_node->field_prodmyproduct[0]["nid"]));
  }

  public function getManuallySetBankUrl() {
		return $this->_node->field_banklink[0]["value"];
	}

  public function getBankUrl() {
		if (!is_null($this->getManuallySetBankUrl())) {
			return $this->getManuallySetBankUrl();
		}
    $import = new BCImport();
    $bankUrl = $import->getBankUrl($this->_node->field_prodfinanceaddsid[0]["value"], 'bank');

    if($bankUrl != "") {
      $url = new URL($bankUrl);
      return BCFARechner::$linkBase.$url->query;
    }
    return NULL;
  }

  public static function getByFinanceAdsId($financeAdsId) {
    $query = db_query("SELECT nid FROM {content_type_productitem} WHERE field_prodfinanceaddsid_value = %d",$financeAdsId);
    $result = db_fetch_array($query);
    $nid = $result["nid"];

    $node = new NovaBCProductitem(intval($nid));
    return $node;
  }
}
