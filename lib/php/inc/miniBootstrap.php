<?php
include($_SERVER['DOCUMENT_ROOT']."/sites/default/settings.php");
function navAutoload($cn) {
	$fn = $_SERVER['DOCUMENT_ROOT']."/lib/php/class.$cn.php";
	if(is_file($fn)) {
		require_once($fn);
	}
}
spl_autoload_register("navAutoload");
?>