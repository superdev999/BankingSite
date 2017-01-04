<?php
class BCDBRelated {
	/**
	 * @var DBToolsDrupal
	 */
	private $db = null;
	/**
	 * @return DBToolsDrupal
	 */
	public function getDB() {
		if(is_null($this->db)) $this->db = new DBToolsDrupal();
		return $this->db;
	}
	protected function getCurrentImportValue($fid, $productType, $pos, $name) {
		return $this->getImportValue($fid, $pos, $name);
	}
	protected function getPreviousImportValue($fid, $productType, $pos, $name) {
		return $this->getImportValue($fid, $pos, $name, true);
	}
	protected function getImportValue($fid, $productType, $pos, $name, $previous=false) {
		if($previous) {
			$limit = "1,1";
		} else {
			$limit = "1";
		}
		return $this->db->simpleQuery(
			$this->db
				->createSelectSQL(
					BCImport::$indexValueTable,
					"value",
					array(
						"fid"=>$fid,
						"productType"=>$productType,
						"pos"=>$pos,
						"name"=>$name
					),
					"AND",
					"timestamp"
				)." DESC LIMIT $limit");

	}
}
?>