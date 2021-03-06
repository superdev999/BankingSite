<?php // $Id: node.tpl.php,v 1.5 2009/05/04 20:52:54 jmburnz Exp $
/**
 * @file
 *  node.tpl.php
 *
 * Theme implementation to display a node.
 *
 * @see template_preprocess()
 * @see template_preprocess_node()
 */
?>
<h3>node-anbieterkategorie.tpl.php</h3>
<?php
if(!function_exists("__autoload")) {
	function __autoload($cn) {
		$fn = $_SERVER['DOCUMENT_ROOT']."/lib/php/class.$cn.php";
		if(is_file($fn)) {
			require_once($fn);
		}
	}
}
// get current catNid
$catNid = $node->nid;

// load all anbieter having this catNid
$query = db_query('SELECT nid FROM `content_field_anbieterkategorien` JOIN node using(nid) JOIN content_type_bank using(nid) WHERE content_field_anbieterkategorien.field_anbieterkategorien_nid=%d AND node.status=1 ORDER BY title', $catNid);

?>
<div id="node-<?php print $node->nid; ?>" class="node <?php print $node_classes; ?>">
	<div class="node node-type-page">
		<div class="content clearfix">
			<h1><?php echo $node->title; ?></h1>
			<?php echo $node->body; ?>
		</div>
	</div>
	<div class="node-inner-0">
		<div class="view-banken">
			<ul>
				<?php
				while ($anbieter = db_fetch_object ($query)) {
					// build list
					$anbieterNode = node_load(intval($anbieter->nid));
					$imagepath = $anbieterNode->field_banklogo[0]["filepath"];
					$nodeLink = $anbieterNode->path;

					echo "<li class='views-row'><a href='/".$nodeLink."'><img src='/".$imagepath."' /></a>";
				}
				?>
			</ul>
		</div>
	</div>
</div>