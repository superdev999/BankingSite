<?php
ini_set("include_path", $_SERVER['DOCUMENT_ROOT']."/");
chdir($_SERVER['DOCUMENT_ROOT']);
require_once $_SERVER['DOCUMENT_ROOT'].'/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

# all types
$productTypes = array("tagesgeld", "festgeld", "girokonto", "kreditkarte", "depot", "ratenkredit", "autokredit", "mietkaution", "baufinanzierung", "bank");
$convert = array(84 => 80, 67 => 60, 50 => 60, 34 => 40, 17 => 20);


echo "<ul>";
foreach ($productTypes as $key => $productType) {
	# get ratings
	echo "<li>Handle ".$productType."<ul>";
	$ratings = BCVotings::getRatingFields($productType);
	foreach ($ratings as $key => $rating) {
		echo "<li>Handle ".$rating."<ul>";
		foreach ($convert as $oldValue => $newValue) {
			echo "<li>Handle ".$oldValue." => ".$newValue."<br>";
			$sql = "UPDATE `content_type_bewertung_".$productType."` SET `".$rating."`='".$newValue."' WHERE `".$rating."` = '".$oldValue."';";
			echo $sql."</li>";
			var_dump(db_query($sql));
		}

		$shortRating = substr($rating, 0, -7);
		echo $shortRating;

		$fieldSettings = db_fetch_object(db_query('SELECT * FROM `content_node_field` WHERE `field_name` = "%s"', $shortRating));
		$globalSettings = unserialize($fieldSettings->global_settings);
		$globalSettings["stars"] = (String)BCVotings::votingBase;
		var_dump($globalSettings["stars"]);
		$serialized = serialize($globalSettings);
		var_dump($serialized);
		db_query('UPDATE `content_node_field` SET `global_settings` = "%s" WHERE `field_name` = "%s"', $serialized, $shortRating);

		echo "</ul></li>";
	}
	echo "</ul></li>";
}
echo "</ul>";
?>