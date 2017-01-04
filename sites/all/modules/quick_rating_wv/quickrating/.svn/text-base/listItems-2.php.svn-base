<?php
chdir($_SERVER['DOCUMENT_ROOT']);
require_once $_SERVER['DOCUMENT_ROOT'].'/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
$item = $_POST["currentB"];
#$productitems = db_query('SELECT * FROM {node} WHERE type="%s" ORDER BY title', "productitem", $bank, $product);
#echo "Bank: ".db_fetch_object(db_query('SELECT * FROM node WHERE type="%s" AND nid="%d"', "bank", $bank))->title."\n";
#echo "Produkt: ".db_fetch_object(db_query('SELECT * FROM node WHERE type="%s" and nid="%d"', "product", $product))->title."\n";


if($item=='0')$banks = db_query("SELECT * FROM {node} WHERE type='%s' AND title REGEXP '^[^a-z]' AND status=1 ORDER BY title", "bank");
else $banks = db_query("SELECT * FROM {node} WHERE type='%s' AND title like '%s' AND status=1 ORDER BY title", "bank", $item.'%');

	
	
$returnValue = array();

	//Falls keine Bank verfügbar, haben wir immerhin eine ID des Buchstabens um eine Fehlermeldung erzeugen zu können.
	$element->item=$item;
	$returnValue[] = $element;

while($bankNode = db_fetch_object ($banks)) {

	$element=new StdClass;
	$element->item=$item;
	$element->title=$bankNode->title;
	$element->id=$bankNode->nid;
	
	$nd=node_load($element->id);
	$element->url=$nd->field_banklogo[0]["filepath"];
	
				


	
	$returnValue[] = $element;
	
}

echo json_encode($returnValue);
#echo "Post:\n";
#print_r($_POST);
?>
