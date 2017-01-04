<?php
class NovaBCCompanyUser extends NovaBCUser {
	private $_user;
	public function __construct($user) {
		$this->_user = user_load(array("uid" => $user->uid));		
	}

	public function getBankId() {
		#var_dump($this->_user);
		return $this->_user->profile_company_id;
	}

	public function getMyBankNode() {
		return node_load(array("nid" => $this->getBankId()));
	}

	public function getMyProductIds() {
		$productIds = array();
		$query = db_query("SELECT nid FROM {content_type_productitem} WHERE field_proditemmybank_nid = %d",$this->getBankId());
		while ($result = db_result($query)) {
			$productIds[] = $result;
		  
			#var_dump(node_load(array("nid" => $result))->title);
			#echo "<br>";
		}
		return $productIds;
	}

	public function getMyProducts() {
		$productIds = $this->getMyProductIds();
		$products = array();
		foreach ($productIds as $key => $productId) {
			$products[] = node_load(array("nid" => $productId));
		}
		return $products;
	}

	public function getApprovedFinanceIdsForWidget() {
		$list = $this->_user->profile_company_widget; 
		$array = explode("\n", $list);

		return array_map(trim, $array);
	}
}