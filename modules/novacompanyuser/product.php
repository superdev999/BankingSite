<?php
function novacompanyuser_productInner()
{
	$html = '<h1>Produkte</h1>
	<p>Hier können Sie Ihre einzelnen Produktdetails und Beschreibungen bearbeiten. Die Konditionen der einzelnen Produkte, wie Zinsen, Anlagezeiträume usw. werden automatisch alle 3h aktualisiert.</p>
	<h2>Neues Produkt?</h2>
	<p>Sie möchten ein neues Produkt anlegen - bitte senden Sie uns dazu eine Mail mit Produktnamen und den Konditionen an <a href=\'mailto:kontakt@bankingcheck.de?subject=Neues%20Produkt%20Anlegen&Body=Sehr%20geehrtes%20BankingCheck%20Team,%0D%0A%0D%0Abitte%20legen%20Sie%20uns%20folgendes%20neues%20Produkt%20an:%0D%0A%0D%0A...%20bitte%20Produkt%20eintragen%20...\'>kontakt@bankingcheck.de</a></p>';
	
	$novaBCUser = NovaBCUser::buildUser($GLOBALS['user']->uid);
	$myProducts = $novaBCUser->getMyProducts();

	foreach ($myProducts as $key => $product) {
		#var_dump($product);
		$html .= "<div class='node-type-bank'><h2>".$product->title."</h2>";
		$html .= $product->body;
		$html .= "<a href='/node/".$product->nid."/edit/'><div class='shortnewsbutton'>Bearbeiten</div></a></div>";
	}
	
	return $html;
}
?>