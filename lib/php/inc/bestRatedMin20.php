<ol>
<?php
if(!function_exists("__autoload")) {
	function __autoload($cn) {
		$fn = $_SERVER['DOCUMENT_ROOT']."/lib/php/class.$cn.php";
		if(is_file($fn)) {
			require_once($fn);
		}
	}
}
$i = 0;
foreach(BCVotings::getBestRatedMin20() as $nid=>$average) {
	$node = node_load(array("nid"=>$nid));
	?>
	<li>
		<a href="/<?=$node->path?>" title="<?=$node->title?>"><?=$node->title?></a>
	</li>
	<?php
	$i++;
}
?>
</ol>
<?php
if($i==0) {
	echo "<div style='background-color:#fff;padding:5px 10px;'>Es wurden noch keine Produkte mit mindestens 20 Bewertungen vorhanden.</div>";
}
?>

