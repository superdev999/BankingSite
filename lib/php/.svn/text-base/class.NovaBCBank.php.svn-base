<?php
class NovaBCBank {
  private $_node;
  public function __construct($nid) {
    if (is_integer($nid) || is_string($nid)) $this->_node = node_load(array("nid"=>$nid));
    else if (is_object($nid)) {
      #var_dump($nid);
      if ($nid->type == "bank") $this->_node = $nid;
      else {
        echo $this->_node->nid." is not a bank: ".$this->_node->type;
        return false;
      }
    } else throw new Exception($nid." is not a valid type.");
  }

  public function isAgent() {
    dpm($this->_node);
    return (!is_null($this->_node->field_isagent[0]["value"]));
  }
  
  public function getManuallySetBankUrl() {
		return $this->_node->field_banklink[0]["value"];
	}

  public function getBankUrl() {
		if (!is_null($this->getManuallySetBankUrl())) {
			return $this->getManuallySetBankUrl();
		}
    $import = new BCImport();
    $bankUrl = $import->getBusinessUrl($this->_node->field_financeaddsid[0]["value"], 'bank');

    if($bankUrl != "") {
      $url = new URL($bankUrl);
      return BCFARechner::$linkBase.$url->query;
    }
    return NULL;
  }
}
