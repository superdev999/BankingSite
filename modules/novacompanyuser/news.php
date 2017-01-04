<?php
function novacompanyuser_newsInner()
{
	$html = '<h1>News</h1>';
	drupal_goto('node/add/story');
	return $html;
}
?>