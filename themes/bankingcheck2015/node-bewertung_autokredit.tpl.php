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
<h3>node-bewertung_all.tpl.php</h3>
<div class="fullRating">
<div class="rating-overview node node-type-page">
<?
BCTemplate::showRatingLine($node, false);
echo '</div>';
$fields["comments_link"]->content = '<ul class="links inline"><li class="comment_add first last"><a href="'.$node->links["comment_add"]["href"].'" title="Teilen Sie Ihre Gedanken und Meinungen zu diesem Beitrag mit.">Kommentieren</a></li></ul>';
$fields["comment_count"]->content = $node->comment_count;
BCTemplate::showSingleLineRatingCommentRow($node, $url, $fields);

?>
</div>