<?php
// $Id: views-view-row-rss.tpl.php,v 1.1 2008/12/02 22:17:42 merlinofchaos Exp $
/**
 * @file views-view-row-rss.tpl.php
 * Default view template to display a item in an RSS feed.
 *
 * @ingroup views_templates
 */
?>
<?php
if (!function_exists("makeImageLinksAbsolute")) {
	function makeImageLinksAbsolute($string) {
		# sample: img src=&quot;/sites/default/files/News/Lendico.png&quot; alt=&quot;Lendico&quot; title=&quot;Lendico&quot;
		# find image links and prepend $HOST
		$newString = preg_replace("/img src=&quot;/i", "img src=&quot;http://".$_SERVER['HTTP_HOST'], $string, -1, &$count);
		
		# 2. Durchlauf für Bank - geht nicht?
		$newString2 = preg_replace("/a href=&quot;\/banken/i", "a href=&quot;http://".$_SERVER['HTTP_HOST']."/banken", $newString, -1, &$count);
		
		# 3. Durchlauf für Produkt - geht nicht?
		$newString3 = preg_replace("/a href=&quot;\/produkte/i", "a href=&quot;http://".$_SERVER['HTTP_HOST']."/produkte", $newString2, -1, &$count);
		return $newString3;
	}
}
?>
<?php
if (!function_exists("makeBankProductLinksAbsolute")) {
	function makeBankProductLinksAbsolute($string) {
		# find bank and product links and prepend $HOST
		$newString = preg_replace("/a href=&quot;/i", "a href=&quot;http://".$_SERVER['HTTP_HOST'], $string, -1, &$count);
		return $newString;
	}
}
?>
<item>
	<title><?php print $title; ?></title>
	<link><?php print $link; ?></link>
	<description><?php print makeImageLinksAbsolute($description); ?></description>
	<?php print $item_elements; ?>
</item>
