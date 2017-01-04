<?php
// $Id: views-view-fields.tpl.php,v 1.6 2008/09/24 22:48:21 merlinofchaos Exp $
/**
 * @file views-view-fields.tpl.php
 * Default simple view template to all the fields as a row.
 *
 * - $view: The view in use.
 * - $fields: an array of $field objects. Each one contains:
 *   - $field->content: The output of the field.
 *   - $field->raw: The raw data for the field, if it exists. This is NOT output safe.
 *   - $field->class: The safe class id to use.
 *   - $field->handler: The Views field handler object controlling this field. Do not use
 *     var_export to dump this object, as it can't handle the recursion.
 *   - $field->inline: Whether or not the field should be inline.
 *   - $field->inline_html: either div or span based on the above flag.
 *   - $field->separator: an optional separator that may appear before a field.
 * - $row: The raw result object from the query, with all data it fetched.
 *
 * @ingroup views_templates
 */
$classes = explode("|", "CSV|DOMBuilder|DOMSelection|DOMBuilderTraverse|DOMFI|DBTools|DBToolsDrupal|DebugUtilities|BCDBRelated|BCImport|BCVotings|BCTemplate|BCAlertSender");
 
#$classes = explode("|", "BCImport|BCDBRelated|DOMFI");
foreach($classes as $class) {
	require_once($_SERVER['DOCUMENT_ROOT']."/lib/php/class.$class.php");
}
$url = $fields["path"]->content;
#foreach(get_object_vars($fields["field_shortnewsbrokername_nid"]) as $key => $value) {
#  echo $key."=>".$value."<br>";
#}
#print_r(get_defined_vars());
#print_r(get_class_methods($fields["field_shortnewsbrokername_nid"]));
#print_r(get_object_vars($fields["field_shortnewsbrokername_nid"]));
#print_r($fields["field_shortnewsbrokername_nid"]->content);

$bankid = $fields["field_shortnewsbrokername_nid"]->raw;
$bankNode = node_load(array("nid"=>$bankid));
$productid = $fields["field_shortnewsbrokerlink_nid"]->raw;
$productNode = node_load(array("nid"=>$productid));
$einzelprodukt = node_load($productNode->field_prodmyproduct[0]["nid"]);
$productName = strtolower($einzelprodukt->title);
require_once($_SERVER['DOCUMENT_ROOT']."/sites/all/libraries/onvista/addTextAsTitle.php");
?>


<div class="greyGradient">                     
	<div><?=$fields["created"]->content ?> / <?php echo addTextAsTitle($fields["field_shortnewsbrokername_nid"]->content); ?> / <?php echo addTextAsTitle($fields["field_shortnewsbrokerlink_nid"]->content); ?></div>
		<h2><?=$fields["title"]->content ?></h2>
		<div class="newImgContainer"><img src="<?php echo "/".$bankNode->field_banklogo[0]["filepath"]; ?>" title="<?php echo $bankNode->title; ?>" alt="<?php echo $bankNode->title; ?>" /></div>
		<div>
			<?=$fields["teaser"]->content ?>
		</div>
		<div style="clear:both">
		  <?php
      $bi = new BCImport();
			$bankUrl = $bi->getBankUrl($productNode->field_prodfinanceaddsid[0]["value"]);
			$brokerlink = new URL($bankUrl);
      ?>
			<a href="<?php echo BCFARechner::$linkBase.$brokerlink->query; ?>" rel="nofollow" target="_blank">
				<div class="transactButton">&gt;&gt; Jetzt abschließen</div>
			</a>
			<div class="moreLinkArrow">	<?=$fields["view_node"]->content ?></div>
		</div>
	<?php	BCTemplate::showSingleLineRatingCommentRow($node, $url, $fields);	?>
</div>
