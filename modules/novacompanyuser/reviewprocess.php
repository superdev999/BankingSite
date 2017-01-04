<?php
function novacompanyuser_reviewprocessInner()
{
	$html = '<h1>Bewertungsprozess</h1>
	<p>Hier k&ouml;nnen Sie Ihre Bewertungslinks f&uuml;r jedes Produkt und f&uuml;r die Anbieterbewertung abrufen. Eine kleine Newslettervorlage f&uuml;r Ihre Kunden ist auch vorhanden - bitte f&uuml;gen Sie dort die entsprechenden Links zur Bewertungsabgabe ein.</p>
	';

	# load bankId and its products
	$novaBCUser = NovaBCUser::buildUser($GLOBALS['user']->uid);
	$myProducts = $novaBCUser->getMyProducts();
	$myProducts[] = $novaBCUser->getMyBankNode();

	drupal_add_js('themes/pixture_reloaded/lib/jquery.zclip.min.js', 'theme');
	// copy zclip over to noConflict jQuery, delete old reference
	drupal_add_js('$jq.fn.zclip = $.fn.zclip;$.fn.zclip = null;', 'inline');

	drupal_add_js('function addCopy(textId, buttonId) {
									$jq("#"+buttonId).zclip({
										path:"/themes/pixture_reloaded/lib/jquery.clipboard.swf",
										copy:function(textId){console.log("#"+textId); return "fh"+$jq("#"+textId).val();}
									});
								}', 'inline');

	$html .= "<h3>Links:</h3><ul>";
	foreach ($myProducts as $key => $product) {
		$productName = strtolower(node_load(array("nid" => $product->field_prodmyproduct[0]["nid"]))->title);
		if (!$productName) $productName = "bank";
		$link = 'http://'.$_SERVER["HTTP_HOST"].'/node/add/bewertung-'.$productName.'?itemId='.$product->nid;
		$html .= '<li>
								<b>'.$product->title.'</b>:<br>
								Link: <input type="text" value="'.$link.'" style="width:90%;cursor: text;" id="'.$product->nid.'_path">
								<!--<a id="'.$product->nid.'_button" style="cursor: pointer;" class="shortnewsbutton" onclick="$jq(\'#'.$product->nid.'_path\').focus().select();">Kopieren</a>-->
							</li>';

		drupal_add_js('$jq(function () {addCopy("'.$product->nid.'_path", "'.$product->nid.'_button");});', 'inline');
	}

	$html .= "<h3>Newslettervorlage</h3>";
	$html .= "<textarea style='width:94%;height:200px'>Sehr geehrter Kunde,
	
Wir m&ouml;chten uns auf diesem Wege herzlich f&uuml;r das bisherige Vertrauen, dass Sie uns geschenkt haben, bedanken. Heute ist Ihre Meinung gefragt! 
	
Bewerten Sie hier unseren Service und unsere Produkte:

<Auflistung Anbieter und Produktlink - siehe oben>
<optional KwK Programm und/oder Gutschein>

Wir freuen uns &uuml;ber ihre Teilnahme!

Ihre Bank / Versicherung</textarea>";

	return $html;
}
?>