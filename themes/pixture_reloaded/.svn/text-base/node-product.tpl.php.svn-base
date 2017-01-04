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
$classes = explode("|", "CSV|DOMBuilder|DOMSelection|DOMBuilderTraverse|DOMFI|DBTools|DBToolsDrupal|DebugUtilities|BCDBRelated|BCImport|BCFARechner|BCVotings");
foreach($classes as $class) {
	require_once($_SERVER['DOCUMENT_ROOT']."/lib/php/class.$class.php");
}
?>
<div id="node-<?php print $node->nid; ?>" class="node <?php print $node_classes; ?>">
  <div class="node-inner-0"><div class="node-inner-1">
    <div class="node-inner-2"><div class="node-inner-3">

      <?php if ($page == 0): ?>
        <h2 class="title"><a href="<?php print $node_url; ?>"><?php print $title; ?></a></h2>
      <?php endif; ?>

      <div class="content clearfix">
        <?php print $node->content["body"]["#value"]; ?>
      </div>
      <div class="rechner">
<?php
BCFARechner::showRechner($node->field_finaceadsrechner[0]["value"]);
?>
      </div>

      <?php if ($links): ?>
        <div class="actions clearfix"><?php print $links; ?></div>
      <?php endif; ?>

    </div></div>
  </div></div>
</div> <!-- /node -->