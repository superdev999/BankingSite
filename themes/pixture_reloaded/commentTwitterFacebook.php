<?php
if($url == "") {
	$commentLink = '<ul class="links inline"><li class="comment_add first last"><a href="/comment/reply/'.$node->nid.'#comment-form">Kommentieren</a></li></ul>';
	$commentCount = $node->comment_count;
	$url = "http://www.bankingcheck.de".url("node/".$node->nid);
} else {
	$commentLink = $fields["comments_link"]->content;
	$commentCount = $fields["comment_count"]->content;
	$dataText = strip_tags($fields["title"]->content);
}
?>
<div class="greyGradientInvert" style="position: relative;">
	<span class="rightSeperated commentWrapper">
		<span class="commentLink">
			<?=$commentLink ?>
		</span>
		<span class="commentCountBubble">
			<?=$commentCount ?>
		</span>
	</span>

	<?php /* Nova Edit Start 2011-11-20 Neu hinzugefügt, siehe auch page.tpl.php*/?>

	<span class="socialshareprivacy" data-url="<?=$url?>" data-text="<?=rawurlencode($dataText)?>"></span>
</div>
