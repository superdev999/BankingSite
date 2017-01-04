<?php
// $Id: views-view.tpl.php,v 1.13.2.2 2010/03/25 20:25:28 merlinofchaos Exp $
/**
 * @file views-view.tpl.php
 * Main view template
 *
 * Variables available:
 * - $classes_array: An array of classes determined in
 *   template_preprocess_views_view(). Default classes are:
 *     .view
 *     .view-[css_name]
 *     .view-id-[view_name]
 *     .view-display-id-[display_name]
 *     .view-dom-id-[dom_id]
 * - $classes: A string version of $classes_array for use in the class attribute
 * - $css_name: A css-safe version of the view name.
 * - $css_class: The user-specified classes names, if any
 * - $header: The view header
 * - $footer: The view footer
 * - $rows: The results of the view query, if any
 * - $empty: The empty text to display if the view is empty
 * - $pager: The pager next/prev links to display, if any
 * - $exposd: Exposed widget form/info to display
 * - $feed_icon: Feed icon to display, if any
 * - $more: A link to view more, if any
 * - $admin_links: A rendered list of administrative links
 * - $admin_links_raw: A list of administrative links suitable for theme('links')
 *
 * @ingroup views_templates
 */
require_once($_SERVER['DOCUMENT_ROOT']."/".path_to_theme()."/autoload.php");
//require_once '';
if(ereg("/banken/bewertungen/(.*)/", $_SERVER['REQUEST_URI'], $m)) {
	$bank = node_load(array("nid"=>$m[1]));
	BCTemplate::showBankTabs($bank);
	// $bank = node_load(array("nid"=>$productItem->field_proditemmybank[0]["nid"]));
	$bcVotings = new BCVotings($bank->nid, "bank");
?>
<div class="node node-mine node-type-productitem greyGradient">
<h2 class="title"><a href="<?php print url("node/".$bank->nid); ?>"><?php print $bank->title; ?></a></h2>
<div class="content clearfix">
	<div class="content">
		<div class="col2_1">
			<div class="col1">
<?php
$count = $bcVotings->calcVotingCount();
if($count == 0) {
	$verb = "sind";
	$count = "keine";
	$name = "Bewertungen";
} elseif($count == 1) {
	$verb = "ist";
	$count = "eine";
	$name = "Bewertung";
} else {
	$verb = "sind";
	$name = "Bewertungen";
}
echo "<p>Es $verb zu dieser Bank $count $name vorhanden</p>";
?>
			</div>			
		</div>
	</div>
</div>
<?php
}
?>
<div class="<?php print $classes; ?>">
  <?php if ($admin_links): ?>
    <div class="views-admin-links views-hide">
      <?php print $admin_links; ?>
    </div>
  <?php endif; ?>
  <?php if ($header): ?>
    <div class="view-header">
      <?php print $header; ?>
    </div>
  <?php endif; ?>

  <?php if ($exposed): ?>
    <div class="view-filters">
      <?php print $exposed; ?>
    </div>
  <?php endif; ?>

  <?php if ($attachment_before): ?>
    <div class="attachment attachment-before">
      <?php print $attachment_before; ?>
    </div>
  <?php endif; ?>

  <?php if ($rows): ?>
    <div class="view-content">
      <?php print $rows; ?>
    </div>
  <?php elseif ($empty): ?>
    <div class="view-empty">
      <?php print $empty; ?>
    </div>
  <?php endif; ?>

  <?php if ($pager): ?>
    <?php print $pager; ?>
  <?php endif; ?>

  <?php if ($attachment_after): ?>
    <div class="attachment attachment-after">
      <?php print $attachment_after; ?>
    </div>
  <?php endif; ?>

  <?php if ($more): ?>
    <?php print $more; ?>
  <?php endif; ?>

  <?php if ($footer): ?>
    <div class="view-footer">
      <?php print $footer; ?>
    </div>
  <?php endif; ?>

  <?php if ($feed_icon): ?>
    <div class="feed-icon">
      <?php print $feed_icon; ?>
    </div>
  <?php endif; ?>

</div>
</div>
 <?php /* class view */ ?>
