<?php
include($_SERVER['DOCUMENT_ROOT']."/lib/php/inc/fullBootstrap.php");
error_reporting(E_ERROR);
class BCImportTest extends BCImport {
	public function buildSQL() {
		return parent::buildSQL();
	}
}
$test = new BCImportTest();
echo $test->buildSQL();
$test->saveProductValues();
////$test->getDB()->print_q("SELECT * FROM BCImportValueIndex
////WHERE fid = 107
////LIMIT 200 ");
//$test->getDB()->print_q("SELECT COUNT(*) AS cnt, fid, productType, pos, name FROM BCImportValueIndex
//GROUP BY fid, productType, pos, name
//ORDER BY cnt DESC
//LIMIT 20");
//$test->getDB()->print_q("SELECT * FROM BCImportValueIndex
//WHERE fid = 107 AND productType = 'Tagesgeld' AND pos = '1' AND name = 'balance_min'
//LIMIT 200 "
//);
//echo $test->getSrc()->value('//product[@id="25"]/productdetails[1]/trades_pa_min'');
//$test->saveProductValues();
?>