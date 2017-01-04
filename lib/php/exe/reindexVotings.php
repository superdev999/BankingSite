<?php
ini_set("include_path", $_SERVER['DOCUMENT_ROOT']."/");
chdir($_SERVER['DOCUMENT_ROOT']);
require_once $_SERVER['DOCUMENT_ROOT'].'/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
#$du = new DebugUtilities();
#$du->startDuration();

$sql = db_query("SELECT node.nid as nid, LOWER(node2.title) AS productType FROM node
LEFT JOIN content_type_productitem ON  content_type_productitem.nid = node.nid
LEFT JOIN node AS node2 ON field_prodmyproduct_nid = node2.nid
WHERE node.type = 'productitem'");
while ($ratingObject = db_fetch_object ($sql)) {
	if($ratingObject->productType != "") {
		$vo = new BCVotings($ratingObject->nid, $ratingObject->productType);
		#echo $ratingObject->nid."<br>";
		$vo->reindex();
	}
}

$sql = db_query("SELECT nid FROM content_type_bank");

while ($ratingObject = db_fetch_object ($sql)) {
	$vo = new BCVotings($ratingObject->nid, 'bank');
	echo $ratingObject->nid."<br>";
	$vo->reindex();
}
?>