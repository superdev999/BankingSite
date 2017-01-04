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



$bank = $node->field_shortnewsbrokername[0];
require_once($_SERVER['DOCUMENT_ROOT']."/sites/all/libraries/onvista/xmlprocess.php");
$xmlprocess = new nova_xmlprocess();
$product = $node->field_shortnewsbrokerlink[0];
$brokerlink = new URL($xmlprocess->getProductURLForProductId($product["nid"]));

$bankNode = node_load(array("nid"=>$bank["nid"]));
$productNode = node_load(array("nid"=>$product["nid"]));
$einzelprodukt = node_load($productNode->field_prodmyproduct[0]["nid"]);
$productName = strtolower($einzelprodukt->title);
$bcVotings = new BCVotings($productNode->nid, $einzelprodukt->title);

$authorNid = $node->uid;
#echo $authorNid;
$author = user_load(array("uid" => $authorNid));
switch ($authorNid) {
	case '3': // bankingcheck
		$gPlusProfile = "";
		break;	
	case '21': // bankingcheck-alex
		$gPlusProfile = "https://plus.google.com/107041505248638512767";
		break;	
	case '894': // bankingcheck-melanie
		$gPlusProfile = "https://plus.google.com/107041505248638512767";
		break;	
	case '28': // bankingcheck-anika
		$gPlusProfile = "https://plus.google.com/107041505248638512767";
		break;	
	case '909': // bankingcheck-gerry
		$gPlusProfile = "https://plus.google.com/107041505248638512767";
		break;	
	default:
		break;
}

require_once($_SERVER['DOCUMENT_ROOT']."/sites/all/libraries/onvista/addTextAsTitle.php");
?>
<div id="node-<?php print $node->nid; ?>" class="node <?php print $node_classes; ?>">
  <div class="shortnewsLeft">
    <div><?php echo format_date($node->created); ?> / <?php echo addTextAsTitle($bank["view"]); ?> / 
    	<?php
    		echo addTextAsTitle($product["view"]). " - Autor: ";
    		if (strlen($gPlusProfile)>0) echo "<a href='".$gPlusProfile."?rel=author'>".$author->name."</a>";
    		else                         echo $author->name;
    		?>
    	</div>
		<h2><a href="<?php print $node_url; ?>"><?php print $title; ?></a></h2>
		<?php if ($unpublished): ?>
		  <div class="unpublished"><?php print t('Unpublished'); ?></div>
		<?php endif; ?>
		<?php if ($terms): ?>
		  <div class="taxonomy"><?php print t('Posted in ') . $terms; ?></div>
		<?php endif; ?>
	</div>
	<div style="clear:both"></div>
	<div class="shortnewsLeft">
    <div class="content clearfix" style="min-height:150px;">
		  <div class="newImgContainer"><img src="<?php echo "http://www.bankingcheck.de/".$bankNode->field_banklogo[0]["filepath"]; ?>" title="<?php echo $bank["safe"]["title"]; ?>" alt="<?php echo $bank["safe"]["title"]; ?>" /></div>  
			<?php	print $content;	?>							
			<a href="<?php echo BCFARechner::$linkBase.$brokerlink->query; ?>" rel="nofollow" target="_blank" style="clear:both">
		    <div class="transactButton">&gt;&gt; Jetzt abschließen</div>
			</a>																  
		</div>
	</div>						
	<div class="shortnewsRight" style="min-height:150px">
    <?php
		$bcVotings->showSiegel();
		if ($bcVotings->calcVotingCount()>=10) $num = " (".$bcVotings->calcVotingCount().")";
		else                                   $num = "";
		?>
	</div>
	<div style="clear:both"></div>
	<div class="shortnewsLeft">
		<?php
		echo 'Mehr Details: <a href="'.$node->field_promotionlink[0]["value"].'" rel="nofollow" target="_blank" style="font-weight:normal">'.$node->field_promotionlink[0]["value"].'</a>';
		$fields["comments_link"]->content = '<ul class="links inline"><li class="comment_add first last"><a href="'.$node->links["comment_add"]["href"].'" title="Teilen Sie Ihre Gedanken und Meinungen zu diesem Beitrag mit.">Kommentieren</a></li></ul>';
		$fields["comment_count"]->content = $node->comment_count;
		BCTemplate::showSingleLineRatingCommentRow($node, $url, $fields);		
		if ($links && !$node->type == "story"): ?>
		  <div class="actions clearfix"><?php print $links; ?></div>
		<?php endif; ?>
	</div>
	<div class="shortnewsRight">
		<div style="position:relative;top:0px;">
		  <a href="/node/add/bewertung-<?=$productName."?itemId=".$productNode->nid?>">
        <div class="rateNowShortnews" style="text-align:center;vertical-align:middle">
				  <img src="http://www.bankingcheck.de/themes/pixture_reloaded/images/bg-rate-now_star.png" style="margin:3px 4px 4px 0px;" alt="Stern" />
          <span style="position:relative;top:-5px;">Jetzt bewerten<?php echo $num; ?></span><span style="clear:both"></span>
				</div>
			</a>
			<?php
			$bi = new BCImport();
			$bankUrl = $bi->getBankUrl($productNode->field_prodfinanceaddsid[0]["value"]);
			$urlObject = new URL($bankUrl);
		  ?>	
			<div class="banklinkShortnews" style="margin-top:2px;">
        <a href="<?=BCFARechner::$linkBase.$urlObject->query?>" rel="nofollow" title="Direkt zur Bank" target="_blank">Direkt zur Bank</a>
			</div>
		</div>
	</div>
	<div style="clear:both"></div>
</div>