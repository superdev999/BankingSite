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

$authorNid = $node->uid;
#echo $authorNid;
$author = user_load(array("uid" => $authorNid));

switch ($authorNid) {
  case '3': // bankingcheck Daniel Bödger
    $gPlusProfile = "https://plus.google.com/100769994796879195233";
    break;  
  case '21': // bankingcheck-alex
    $gPlusProfile = "https://plus.google.com/107041505248638512767";
    break;  
  case '894': // bankingcheck-melanie
    $gPlusProfile = "https://plus.google.com/111586395137440136804";
    break;  
  case '28': // bankingcheck-anika
    $gPlusProfile = "https://plus.google.com/115548828195941773358";
    break;  
  case '1805': // bankingcheck-tobias
    $gPlusProfile = "https://plus.google.com/117345733446619256418";
    break;
  default: // bankingcheck Daniel Bödger
	$gPlusProfile = "https://plus.google.com/100769994796879195233";
	break;
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
        <div class="taxonomy">
          <?php print t('Posted in ') . $terms; ?><br>
          Autor:
          <?php
            if (strlen($gPlusProfile)>0) echo "<a target='_blank' href='".$gPlusProfile."?rel=author'>".$author->name."</a>";
            else                         echo $author->name;
          ?>
        </div>
      <?php endif; ?>

      <div class="content clearfix">
        <?php print $content; ?>
      </div>
	  <?php
if($node->type=="story") {
/**
 * @todo use drupal techniques
 */
	$fields["comments_link"]->content = '<ul class="links inline"><li class="comment_add first last"><a href="'.$node->links["comment_add"]["href"].'" title="Teilen Sie Ihre Gedanken und Meinungen zu diesem Beitrag mit.">Kommentieren</a></li></ul>';
	$fields["comment_count"]->content = $node->comment_count;
  BCTemplate::showSingleLineRatingCommentRow($node, $url, $fields);	
}
	  ?>

      <?php if ($links && !$node->type == "story"): ?>
        <div class="actions clearfix"><?php print $links; ?></div>
      <?php endif; ?>

    </div></div>
  </div></div>
</div> <!-- /node -->
