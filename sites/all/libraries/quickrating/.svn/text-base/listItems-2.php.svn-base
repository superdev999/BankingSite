<?php
chdir($_SERVER['DOCUMENT_ROOT']);
require_once $_SERVER['DOCUMENT_ROOT'].'/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
$item = $_POST["currentbank"];

if($item == '0') {
	$banks = db_query("SELECT * FROM {node} WHERE type='%s' AND title REGEXP '^[^a-z]' AND status=1 ORDER BY title", "bank");
} else {
	$banks = db_query("SELECT * FROM {node} WHERE type='%s' AND title like '%s' AND status=1 ORDER BY title", "bank", $item.'%');
}


$returnValue = array();

while($bankNode = db_fetch_object ($banks)) {

	$element=new StdClass;
	$element->item=$item;
	$element->title=$bankNode->title;
	$element->bank=$bankNode->nid;
	$element->groupedBy=$item;

	$nd=node_load($element->bank);
	$element->url=$nd->field_banklogo[0]["filepath"];

	$returnValue[] = $element;
}

echo json_encode($returnValue);
?>
