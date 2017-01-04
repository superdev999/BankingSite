<?php
chdir($_SERVER['DOCUMENT_ROOT']);
require_once $_SERVER['DOCUMENT_ROOT'].'/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
$item = $_POST["currentprodukt"];

$productitems = db_query('SELECT distinct(content_type_productitem.field_proditemmybank_nid) FROM `content_type_productitem` JOIN node using(nid) WHERE node.type="%s" AND content_type_productitem.field_prodmyproduct_nid=%d AND node.status=1 ORDER BY title', "productitem", $item);
$returnValue = array();

while($productitem = db_fetch_object ($productitems)) {

	$element=new StdClass;
	$element->bank=$productitem->field_proditemmybank_nid;

	$element->groupedBy=$item;

	$nd=node_load($element->bank);
	$element->url=$nd->field_banklogo[0]["filepath"];
	$element->title=$nd->title;


	$returnValue[] = $element;

}

echo json_encode($returnValue);
?>
