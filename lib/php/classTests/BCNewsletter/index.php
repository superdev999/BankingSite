<?php
ini_set("include_path", $_SERVER['DOCUMENT_ROOT']."/");
include($_SERVER['DOCUMENT_ROOT']."/lib/php/inc/fullBootstrap.php");


$test = new BCNodeSender(array(60,8));

if(array_key_exists("send", $_GET)) {
	$test->send();
} else {
	if(array_key_exists("sendOnlyTo", $_GET)) {
		$test->send($_GET["sendOnlyTo"]);
	}
	echo $test->preview();
}
?>