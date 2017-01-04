<?php
$oldDir = getcwd();
chdir($_SERVER['DOCUMENT_ROOT']);
include_once $_SERVER['DOCUMENT_ROOT'].'/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
chdir($oldDir);
setlocale(LC_ALL, 'de_DE');
function __autoload($cn) {
	if(class_exists($cn)) {
		return;
	} else {
		$file = $_SERVER['DOCUMENT_ROOT']."/lib/php/class.$cn.php";
		if(!is_file($file)) {
//			throw new Exception($cn." doesn't exist…");
		} else {
			require_once($file);
		}
	}
}