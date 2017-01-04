<?php
chdir($_SERVER['DOCUMENT_ROOT']);
require_once $_SERVER['DOCUMENT_ROOT'].'/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
$catNid = $_POST["currentanbieterkategorien"];

$productitems = NULL;
if (isset($_POST["award2015"]) && $_POST["award2015"]) {
	$productitems = db_query('SELECT nid FROM `content_field_anbieterkategorien` JOIN node using(nid) JOIN content_type_bank using(nid) WHERE content_field_anbieterkategorien.field_anbieterkategorien_nid=%d AND node.status=1 AND content_type_bank.field_award2015_value=1 ORDER BY title', $catNid);
} else {
	$productitems = db_query('SELECT nid FROM `content_field_anbieterkategorien` JOIN node using(nid) WHERE content_field_anbieterkategorien.field_anbieterkategorien_nid=%d AND node.status=1 ORDER BY title', $catNid);
}

$returnValue = array();

while($productitem = db_fetch_object ($productitems)) {

	$element=new StdClass;
	$element->bank=$productitem->nid;
	$element->groupedBy=$catNid;

	$nd=node_load($element->bank);

	$element->url=$nd->field_banklogo[0]["filepath"];
	$element->title=$nd->title;

	$returnValue[] = $element;

}

echo json_encode($returnValue);
?>
