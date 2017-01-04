<table class="views-table cols-10">
<tbody>
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
foreach(BCVotings::getBestRatedMin100_10() as $nid=>$average) {
	$node = node_load(array("nid"=>$nid));
	?>
	<tr class="<?=$i%2==0?"odd":"even"?>">
		<td><a href="/<?=$node->path?>" title="<?=$node->title?>"><?=$node->title?></a></td>
		<td><?=$average?></td>
	</tr>
	<?php
	$i++;
}
?>
</tbody>
</table>