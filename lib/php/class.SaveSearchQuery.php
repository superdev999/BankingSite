<?php
class SaveSearchQuery extends BCDBRelated {
	private static $table = "BCSavedSearch";
	public static function record($query) {
		$instance = new SaveSearchQuery();
		$res = $instance->getDB()->query("UPDATE `".self::$table."` SET cnt = cnt +1 WHERE `query`='$query'");
		if(mysql_affected_rows($instance->getDB()->ident) == 0) {
			$instance->getDB()->insert(self::$table, array("query"=>$query, "cnt"=>1));
		}
	}
	public static function getTagadelicLinks() {
		$instance = new SaveSearchQuery();
		$cntSql = 'SELECT query, ROUND( cnt / (SELECT MAX(cnt) FROM `BCSavedSearch`) * 10) as level FROM `BCSavedSearch`
where query != "Bankname oder Produkt ..." ORDER BY `BCSavedSearch`.`cnt`  DESC
LIMIT 20';
		$r = array();
		foreach($instance->getDB()->fullTableArray($cntSql) as $line) {
			$r[$line["query"]] = $line["level"];
		}
		ksort($r);
		return $r;
	}
	public static function createTable() {
		$sql = 'CREATE TABLE  `'.self::$table.'` (
`query` TEXT NOT NULL ,
`cnt` INT( 14 ) NOT NULL
) ENGINE = MYISAM';
	}
}
?>