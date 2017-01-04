<?php
// $Id: views-view-row-rss.tpl.php 3296 2009-05-27 23:08:21Z tim $
/**
* @file views-view-row-rss.tpl.php
* Default view template to display a item in an RSS feed.
*
* @ingroup views_templates
*/
header("Content-type: text/xml");
echo '<?xml version="1.0" encoding="UTF-8"?>';
require_once($_SERVER['DOCUMENT_ROOT']."/lib/php/class.BCFARechner.php");
require_once($_SERVER['DOCUMENT_ROOT']."/lib/php/class.BCImport.php");
require_once($_SERVER['DOCUMENT_ROOT']."/sites/all/libraries/onvista/addTextAsTitle.php");
#$stylesheet = "/".path_to_theme()."/custom.css";
?>
<?php #echo __FILE__ ?>
<rss version="2.0" xml:base="http://www.bankingcheck.de/shortnews" xmlns:dc="http://purl.org/dc/elements/1.1/">
	<channel>
		<title>Aktuelle BankingCheck Short News / Ticker News</title>
		<link>http://www.bankingcheck.de/shortnews</link>
<?php
foreach ($variables["view"]->result as $entry) {
$node = node_load($entry->nid);

$newslink = "http://www.bankingcheck.de/".$node->path;

$broker = node_load($node->field_shortnewsbrokername[0]["nid"]);
$category = node_load($node->field_shortnewscategory[0]["nid"]);

$productid = $node->field_shortnewsbrokerlink[0]["nid"];
$productNode = node_load(array("nid"=>$productid));

$bankid = $node->field_shortnewsbrokername[0]["nid"];
$bankNode = node_load(array("nid"=>$bankid));

$bi = new BCImport();
$bankUrl = $bi->getBankUrl($productNode->field_prodfinanceaddsid[0]["value"]);
$bankUrl = str_replace("&", "&amp;", $bankUrl);
$brokerlink = new URL($bankUrl);

$description = "<div>".format_date($node->created)." / <a href='http://www.bankingcheck.de/".$broker->path."' title='".$broker->title."'>".$broker->title."</a> / <a href='http://www.bankingcheck.de/".$productNode->path."' title='".$productNode->title."'>".$productNode->title."</a></div>
		<h2>".$node->title."</h2>
		<div class='newImgContainer'><img src='http://www.bankingcheck.de/".$bankNode->field_banklogo[0]["filepath"]."' title='".$bankNode->title."' alt='".$bankNode->title."' /></div>
		<div>".$node->teaser."	</div>
		<div style='clear:both'>
			<a href='http://www.bankingcheck.de".BCFARechner::$linkBase.$brokerlink->query."' rel='nofollow' target='_blank'>
				<div class='transactButton'>&gt;&gt; Zur Bank</div>
			</a>
			<br />
			<div class='moreLinkArrow'>
				<a href='http://www.bankingcheck.de/".$productNode->path."'>Mehr Details zum - $productNode->title</a>
			</div>
			<!--div class='moreLinkArrow'>
				<a href='http://www.bankingcheck.de/".$node->path."'>Beitrag auf der Website lesen</a>
			</div-->
		</div>";
?>
		<item>
			<title><?php echo $node->title; ?></title>
			<link><?php echo $newslink; ?></link>
			<description><![CDATA[<?php echo $description; ?>]]></description>
			<comments><?php echo $newslink."#comments"; ?></comments>
			<pubDate><?php echo date("D, d M Y H:i:s +0000", (($node->created)-3600)); ?></pubDate>
			<dc:creator><?php echo $node->name; ?></dc:creator>
			<text><![CDATA[<?php echo $node->body; ?>]]></text>
			<broker><?php echo $broker->title; ?></broker>
			<brokerproduct><?php echo $productNode->title; ?></brokerproduct>
			<brokerlink>http://www.bankingcheck.de<?php echo BCFARechner::$linkBase.$brokerlink->query; ?></brokerlink>
			<promotion_link><?php echo $node->field_promotionlink[0]["value"]; ?></promotion_link>
			<news_link><?php echo news_link; ?></news_link>
			<type><?php echo $node->field_shortnewstype[0]["value"]; ?></type>
			<issuer><?php echo $node->field_shortnewsissuer[0]["value"]; ?></issuer>
			<category><?php echo $category->title; ?></category>
		</item>
<?php
}
?>
	</channel>
</rss>
