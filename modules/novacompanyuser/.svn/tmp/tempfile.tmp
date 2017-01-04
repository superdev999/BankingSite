<?php
function novacompanyuser_reviewsInner()
{
	$step = 10;

	$html = '<h1>Bewertungen</h1>
	<p>Hier können Sie alle freigegebenen Anbieter- und Produktbewertungen auswählen und einsehen. Sie können jede Bewertung kommentieren und so in den direkten Kundenkontakt treten.</p>
	<h2>Bitte wählen sie das gewünschte Einzelprodukt bzw. Anbieter aus</h2>';

	// Get all my product and bank id
	# load bankId and its products
	$novaBCUser = NovaBCUser::buildUser($GLOBALS['user']->uid);
	$myProducts = $novaBCUser->getMyProducts();
	$myProducts[] = $novaBCUser->getMyBankNode();

	// build form
	$html .= "<form action='' method='get'><table style='margin-top:5px'><tr><td>Einzelprodukt oder Anbieter:  <select name='einzelprodukt'>";
	// output selection box
	$selected = intval($_GET["einzelprodukt"]);
	foreach ($myProducts as $key => $node) {
		$html .= '<option value="'.$node->nid.'"';
		if ($selected == $node->nid) $html .= ' selected="selected"';
		$html .= '>'.$node->title.'</option>';
	}
	$html .= "</select></td></tr></table><input type='submit' value='Bewertungen anzeigen'></form>";	

	if (isset($_GET["einzelprodukt"]))
	{
		// get selected item
		$nid = (int) $_GET["einzelprodukt"];
		$node = node_load(array("nid" => $nid));

		$type = NULL;
		$view = NULL;
		$body = NULL;
		if ($node->type == "bank") {
			// for bank load view "Bankbewertungen"
			$view = views_get_view("Bankbewertungen");
			$body = $view->preview("page_1", array(0 => $node->nid));
			#dpm($view);
		} else {
			$type = node_load(array("nid" => $node->field_prodmyproduct[0]['nid']));

			#dpm($type->title);

			// get right view for product type
			// see http://www.testsystem.de.bankingcheck.nova-web.de/admin/build/views/edit/Bewertungen
			$viewAssociation = array("Tagesgeld" => "page_1", "Festgeld" => "page_2", "Girokonto" => "page_3", "Kreditkarte" => "page_4", "Depot" => "page_5", "Ratenkredit" => "page_6", "Autokredit" => "page_7", "Mietkaution" => "page_8", "Baufinanzierung" => "page_9");
			$view = views_get_view("Bewertungen");
			$body = $view->preview($viewAssociation[$type->title], array(0 => $node->nid));
		
		}
		$html .= "<h3 style='margin-top:160px'><a href='/".$node->path."'>".$node->title."</a></h3>";

		$count = $view->total_rows;
		
		if($count == 0) {
			$verb = "sind";
			$count = "keine";
			$name = "Bewertungen";
		} elseif($count == 1) {
			$name = "Bewertung";
			$verb = "ist";
			$count = "eine";
		} else {
			$verb = "sind";
			$name = "Bewertungen";
		}
		$html .= "<p>Es $verb zu diesem Produkt $count $name vorhanden.</p><div style='width:1015px;'>".$body."</div>";

		#$html .= views_embed_view("Bewertungen", $viewAssociation[$type->title], $node->nid);
		#dpm(views_get_current_view());
		#$html .= "</div>";
	}
	return $html;
}


function getRatingsForIdArray($idArray) {
	$ratingIds = array("");
	$idString = join(',',$idArray);

	$tableColumnArray = array("content_type_bewertung_autokredit" => "field_carloans_myproductitem_nid", "content_type_bewertung_bank" => "field_bank_bankitem_nid", "content_type_bewertung_baufinanzierung" => "field_mortgages_myproductitem_nid", "content_type_bewertung_depot" => "field_brokerage_myproductitem_nid", "content_type_bewertung_festgeld" => "field_myproductitem_nid", "content_type_bewertung_girokonto" => "field_currentaccount_myproductim_nid", "content_type_bewertung_kreditkarte" => "field_creditcard_myproductitem_nid", "content_type_bewertung_mietkaution" => "field_rentalbonds_myproductitem_nid", "content_type_bewertung_ratenkredit" => "field_loans_myproductitem_nid", "content_type_bewertung_tagesgeld" => "field_call_myproductitem_nid");
	foreach ($tableColumnArray as $table => $column) {
		# code...
	
		$query = db_query("SELECT nid FROM {%s} WHERE %s IN (%s)",$table, $column, $idString);
		while ($result = db_result($query)) {
			$ratingIds[] = $result;
		  
			#var_dump(node_load(array("nid" => $result))->title);
			#echo "<br>";
		}
	}
	return $ratingIds;
}
?>