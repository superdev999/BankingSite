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
$url = $fields["path"]->content;
$node = node_load(array("nid" => $fields["nid"]->raw));
$author = $node->name;
$user = user_load(array("uid" => $node->uid));
?>
<article>
	<header>
		<figure><img src="<?=$user->picture ?>" alt="Placeholder" width="90" height="90"></figure>
		<h3><?=$author ?></h3>
		<p>Posted in : <?=$fields["field_mybank_nid"]->content ?> / <?=$fields["field_myproduct_nid"]->content ?></p>
		<p class="scheme-f"><?=$fields["created"]->content  ?></p>
	</header>
	<div>
		<header>
			<figure><img src="http://placehold.it/208x211" alt="Placeholder" width="208" height="211"></figure>
			<ul class="list-f">
				<li><i class="icon-heart"></i> 298</li>
				<li><i class="icon-eye"></i> 6578</li>
				<li><i class="icon-comment"></i> <?=$fields["comment_count"]->content ?></li>
			</ul>
			<h4><?=$fields["title"]->content  ?></h4>
		</header>
		<p><?=$fields["teaser"]->content ?></p>
		<p class="link-c"><?=$fields["view_node"]->content ?></p>
	</div>
</article>
