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
?>
<li>
	<?php
		$nid = $row->nid;
		$node = node_load(array("nid"=>$nid));
		$name = $node->title;
		$url = "/".$node->path;
		$bewertenUrl = "";
		$logoUrl = "";
		$novaBCProductitem = new NovaBCProductitem($node);
		$bank = $novaBCProductitem->getAnbieter();
		$product = $novaBCProductitem->getProduct();
		$bewertenUrl = "/node/add/bewertung-".strtolower($product->title)."?itemId=".$node->nid;
		$logoUrl = $bank->field_banklogo[0]["filepath"];
	?>
	<a href="<?php echo $url; ?>">
		<span class="img">
			<img src="<?php echo $logoUrl; ?>" alt="<?php echo $name; ?>" width="167" height="53">
		</span> <?php echo $name; ?>
	</a>
	<ul class="list-b">
		<li><a href="<?php echo $bewertenUrl; ?>"><i class="icon-star"></i> <span class="tip left">Jetzt bewerten</span></a></li>
		<li><?php echo $count; ?></li>
	</ul>
</li>
