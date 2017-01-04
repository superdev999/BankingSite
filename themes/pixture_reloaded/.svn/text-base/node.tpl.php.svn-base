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
if(!function_exists("__autoload")) {

	function __autoload($cn) {
		$fn = $_SERVER['DOCUMENT_ROOT']."/lib/php/class.$cn.php";
		if(is_file($fn)) {

			require_once($fn);
		}
	}
}
?>
<div id="node-<?php print $node->nid; ?>" class="node <?php print $node_classes; ?>">
  <div class="node-inner-0"><div class="node-inner-1">
    <div class="node-inner-2"><div class="node-inner-3">
      <?php if ($page == 0 || $node->type=="story"): ?>
        <h2 class="title"><a href="<?php print $node_url; ?>"><?php print $title; ?></a></h2>
      <?php endif; ?>

      <?php if ($unpublished): ?>
        <div class="unpublished"><?php print t('Unpublished'); ?></div>
      <?php endif; ?>

      <?php print $picture; ?>

      <?php if (!empty($submitted)): ?>
        <div class="submitted"><?php print $submitted; ?></div>
      <?php endif; ?>

      <?php if ($terms): ?>
        <div class="taxonomy"><?php print t('Posted in ') . $terms; ?></div>
      <?php endif; ?>

      <div class="content clearfix">
        <?php print $content; ?>
      </div>
	  <?php
    if($node->type=="story") {
      BCTemplate::showSingleLineRatingCommentRow($node, $url, $fields);	
    }
	  ?>
      <?php if ($links && !$node->type == "story"): ?>
        <div class="actions clearfix"><?php print $links; ?></div>
      <?php endif; ?>

    </div></div>
  </div></div>
</div> <!-- /node -->
