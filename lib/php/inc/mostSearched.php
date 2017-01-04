<?php
if(!function_exists("__autoload")) {
	function __autoload($cn) {
		$fn = $_SERVER['DOCUMENT_ROOT']."/lib/php/class.$cn.php";
		if(is_file($fn)) {
			require_once($fn);
		}
	}
}
foreach(SaveSearchQuery::getTagadelicLinks() as $query=>$weight) {
	echo '<a class="tagadelic level'.$weight.'" href="/search/node?keys='.urlencode($query).'" title="Suche nach `'.$query.'`">'.$query.'</a> ';
}
?>