<?php
chdir($_SERVER['DOCUMENT_ROOT']);
require_once $_SERVER['DOCUMENT_ROOT'].'/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
$bank = $_POST["bank"];
$product = $_POST["produkt"];
#$productitems = db_query('SELECT * FROM {node} WHERE type="%s" ORDER BY title', "productitem", $bank, $product);
#echo "Bank: ".db_fetch_object(db_query('SELECT * FROM node WHERE type="%s" AND nid="%d"', "bank", $bank))->title."\n";
#echo "Produkt: ".db_fetch_object(db_query('SELECT * FROM node WHERE type="%s" and nid="%d"', "product", $product))->title."\n";

#$productitems = db_query('SELECT * FROM `content_type_productitem` JOIN node using(nid) WHERE node.type="%s" AND content_type_productitem.field_proditemmybank_nid=%d AND content_type_productitem.field_prodmyproduct_nid=%d', "productitem", $bank, $product);

$bank_name_query = db_fetch_object(db_query('SELECT title FROM `node` WHERE `nid` = "%d"', $bank));
$bank_name = $bank_name_query->title;

$bank_node_url = "node/".$bank;
$bank_url_query = db_fetch_object(db_query('SELECT dst FROM `url_alias` WHERE `src` = "%s"', $bank_node_url));
$bank_alias_url = $bank_url_query->dst;

$productitems = db_query('SELECT node.nid, node.title AS title, productNode.title AS productNodeTitle FROM `content_type_productitem` JOIN node using(nid) JOIN node AS productNode ON content_type_productitem.field_prodmyproduct_nid=productNode.nid WHERE node.type="productitem" AND content_type_productitem.field_proditemmybank_nid=%d AND content_type_productitem.field_prodmyproduct_nid=%d AND node.status=1', $bank, $product);


$returnValue = array();

while ($productitem = db_fetch_object ($productitems)) {
	$originalUrl = "node/".$productitem->nid;
	$urlQuery = db_fetch_object(db_query('SELECT dst FROM `url_alias` WHERE `src` = "%s"', $originalUrl));
	$aliasUrl = $urlQuery->dst;
	$productitem->url = $aliasUrl;
	$productitem->bank = $bank; // für das Feld "Bank allgemein"
	$productitem->bank_alias_url = $bank_alias_url; // für das Feld "Bank allgemein"
	$productitem->bank_name = $bank_name;
	$productitem->isProduct = true;
	
	#echo $aliasUrl;
	#print_r($productitem);

	$returnValue[] = $productitem;

	#echo "<option value='".$bankNode->nid."'>".$bankNode->title."</option>";
}






echo json_encode($returnValue);
#echo "Post:\n";
#print_r($_POST);
?>
