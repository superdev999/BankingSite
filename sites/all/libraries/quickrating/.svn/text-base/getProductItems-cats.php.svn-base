<?php
chdir($_SERVER['DOCUMENT_ROOT']);
require_once $_SERVER['DOCUMENT_ROOT'].'/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
$bankNid = intval($_POST["bank"]);
$catNid = intval($_POST["currentanbieterkategorien"]); // $catNid Everdeen, the Girl On Fire!

$bankNode = node_load(array("nid" => $bankNid));


$productitems = db_query('SELECT node.nid, node.title AS title, productNode.title AS productNodeTitle FROM `content_type_productitem` JOIN node using(nid) JOIN node AS productNode ON content_type_productitem.field_prodmyproduct_nid=productNode.nid WHERE node.type="productitem" AND content_type_productitem.field_proditemmybank_nid=%d AND node.status=1', $bankNid);

$returnValue = array();

while ($productitem = db_fetch_object ($productitems)) {
	$originalUrl = "node/".$productitem->nid;
	$urlQuery = db_fetch_object(db_query('SELECT dst FROM `url_alias` WHERE `src` = "%s"', $originalUrl));
	$aliasUrl = $urlQuery->dst;
	$productitem->url = $aliasUrl;
	$productitem->bank = $bankNid; // für das Feld "Bank allgemein"
	$productitem->bank_alias_url = $bankNode->path; // für das Feld "Bank allgemein"
	$productitem->bank_name = $bankNode->title;
	$productitem->isProduct = true;

	$returnValue[] = $productitem;
}

// Keine Produkte gefunden, füge Bank hinzu
if (sizeof($returnValue) == 0) {
	$productitem->bank = $bankNid;
	$productitem->bank_alias_url = $bankNode->path;
	$productitem->bank_name = $bankNode->title;
	$productitem->isProduct = false;
	$returnValue[] = $productitem;
}

echo json_encode($returnValue);
?>
