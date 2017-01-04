<?php
class BCTemplate {

	private static function customTranslation($sortArray, $key) {
		$entry = "";

		for ($i = 0; $i < sizeof($sortArray); $i++)
		{
			if ($key == $sortArray[$i][0])
			{
				$entry = $sortArray[$i][1];
				break;
			}
		}

		return $entry;
	}

	public static function showBankTabs($node, $productName=null) {
		self::showTabs(array(
			"Bankdetails"=>url("node/".$node->nid),
			"Bewertungen"=>"/banken/bewertungen/".$node->nid."/".BCImport::propperFileString($node->title),
			"Kommentare"=>url("node/".$node->nid)."?kommentare",
		));
	}
	/**
	 * clear redundance
	 */
	public static function showProductitemTabs($node, $productName=null) {
		if(is_null($productName)) {
			if($node->type == "productitem") {
				$myProduct = node_load(array("nid"=>$node->field_prodmyproduct[0]["nid"]));
				$productName = strtolower($myProduct->title);
			} else {
				$productName = self::getProductNameByNode($node);
			}
		}
		self::showTabs(array(
			"Produktdetails"=>url("node/".$node->nid),
			"Bewertungen"=>"/produkte/".$productName."/bewertungen/".$node->nid."/".BCImport::propperFileString($node->title),
			"Kommentare"=>url("node/".$node->nid)."?kommentare",
			"News"=>"/produkte/news/".$node->nid."/".BCImport::propperFileString($node->title)
		));
	}
	private static function showTabs($functions) {
		echo '<ul class="tabs primary">';
		foreach($functions as $label=>$url) {
			if(ereg("kommentare",  $_SERVER['REQUEST_URI'])) {
				if($label == "Kommentare") {
					$class = 'active';
				} else {
					$class = "";
				}
			} elseif(ereg("^".$url, $_SERVER['REQUEST_URI'])) {
				$class = 'active';
			} else {
				$class = '';
			}
			echo '<li class="'.$class.'"><a href="'.$url.'">'.$label.'</a></li>';
		}
		echo '</ul>';
	}
	public static function getProductNameByNode($node, $toLower=true) {
		$r = $node->field_prodmyproduct[0]["safe"]["title"];
		if($toLower) {
			$r = strtolower($r);
		}
		return $r;
	}

	public static function showRatingLine($ratingNode, $showNotifyOnPublish = true) {
		echo BCTemplate::getRatingLine($ratingNode, $showNotifyOnPublish);
	}

	public static function getRatingLine($ratingNode, $showNotifyOnPublish = true) {
		$html = "";
		// Node: Einzelprodukt
		// ratingNode: Bewertung

		$novaBCVoting = new NovaBCVoting($ratingNode->nid);

		#var_dump($ratingNode->path);

		$node = node_load(array("nid"=>$novaBCVoting->getRatedProductItemNid()));
		$BCVotingNode = BCVotings::byRatingNode($ratingNode);
		$ratingNamesArray = array();
		$originalUrl = "node/".$novaBCVoting->getRatedProductItemNid();
		$urlQuery = db_fetch_object(db_query('SELECT dst FROM `url_alias` WHERE `src` = "%s"', $originalUrl));

		$html .= '<table id="bewertung"><tbody><tr><td style="width: 48%; vertical-align: top">';
		$html .= "<div id='ratings'>";

		$html .= "<div class=\"single_rating\"><a href=\"/".$urlQuery->dst."\"><b>".$node->title."</b></a></div>";
		$html .= '<div class="rating_table">';
		$html .= $novaBCVoting->getGraphicVotings();
		$html .= '</div>';

		$time = "@".$ratingNode->created;
		$html .= "</div></td>";

		$html .= '<td class="middle">';
		// Kommentar
		$html .= '<div>';

		$loc_de = setlocale (LC_ALL, 'de_DE@euro', 'de_DE', 'de', 'ge');
		$date = new DateTime($time);
		$html .= "<div class=\"single_rating rat_abs\" style=\"border-bottom: none; height: auto; font-size:14px;\"><b><a href='/".$ratingNode->path."'>".$date->format('d.m.Y | H:i')." Uhr</a></b><div class=\"commenttext short\">";
		$comment=$novaBCVoting->getComment();
		if(empty($comment)) $html .= 'kein Kommentar'; else $html .= $novaBCVoting->getComment();
		$html .= "</div></div>";
		$html .= "<div style='position:relative;height:0px;'><div class=\"commenttext long\"><div class='commenttextcontent'>";
		$comment=$novaBCVoting->getComment();
		if(empty($comment)) $html .= 'kein Kommentar'; else $html .= $novaBCVoting->getComment();
		$html .= '</div>';

		$html .= '<div style="text-align:right"><a class="bwschliessen" href="#" style="font-size: 12px;">Fenster schließen</a></div></div>';
		$html .= '</div></div>';

		$html .= '<div id="ratings-2">';

		// Weiterempfehlungen
		if ($ratingNode->type == "bewertung_bank") {
			$html .= "<div class=\"single_rating\" style=\"border-top: 1px solid #ccc\"><span class=\"left\">Würde Bank weiterempfehlen</span><div  class=\"right\">".$novaBCVoting->getPromote()."</div></div>";
		}
		else {
			$html .= "<div class=\"single_rating\" style=\"border-top: 1px solid #ccc\"><span class=\"left\">Würde Produkt weiterempfehlen</span><div  class=\"right\">".$novaBCVoting->getPromote()."</div></div>";
			if ($novaBCVoting->getPromoteBank()) $html .= "<div class=\"single_rating\"><span class=\"left\">Würde Bank weiterempfehlen</span><div class=\"right\">".$novaBCVoting->getPromoteBank()."</div></div>";
		}

		if ($showNotifyOnPublish) {
			// Freigabebenachrichtigung
			$html .= "<div class=\"single_rating\"><span  class=\"left\">Freigabebenachrichtigung</span><div  class=\"right\">";
			$freigabemsg=$novaBCVoting->getNotifyOnPublish();
			if (empty($freigabemsg)) $html .= '&nbsp;';
			else $html .= $freigabemsg;
			$html .= "</div></div>";
		}



		$html .= '</div></td>';

		// Siegel
		$html .= '<td style="vertical-align:middle;"><div class="siegel" style="float:right;margin-bottom:0px;">';
		$_GET["node"] = $novaBCVoting->getRatedProductItemNid();
		if ($BCVotingNode->calcVotingCount()>0) {
			$html .= $BCVotingNode->getSiegelHtml();
			$html .= '<div class="bereitsbewertet"><a href="/'.$urlQuery->dst.'">Bereits '.$BCVotingNode->calcVotingCount().' mal bewertet</a></div>';
		} else {
			$html .= '<img src="/sites/default/files/bcgrafiken/BankingCheck_Siegel_Usr_not.gif" alt="BankingCheck User-Siegel" width="150" height="150" />';
			$html .= '<div class="bereitsbewertet"><a href="/'.$urlQuery->dst.'">Noch nicht bewertet</a></div>';
		}
		$html .= "</div><div>&nbsp;</div></td></tr></tbody></table>";

		return $html;
	}

	public static function showRatingLineOldStyle($node) {
		$user = user_load(array("uid"=>$node->uid));

		$db = new BCDBRelated();
		$countComments = $db->getDB()->simpleQuery("SELECT COUNT(*) FROM comments WHERE uid = ".$node->uid);
		$countRatings = $db->getDB()->simpleQuery("SELECT COUNT(*) FROM node WHERE uid = ".$node->uid." AND type LIKE 'bewertung_%' AND status = 1");
		echo '<div class="blueGradientInvert">
		<div class="cols1_1_1">
		<div class="col1 user">';
		if($user->picture != "") {
			echo '<div class="userThumb">
			<img src="/'.$user->picture.'" alt="'.$user->name.'" />
			</div>';
		}
		echo '<div class="userRight"><h3>'.$user->name.'</h3>
		<span>Angemeldet seit: '.date("d.m.Y", $user->created).'<span><br />
		<span>Bewertungen: '.$countRatings.'</span><br />
		<span>Kommentare: '.$contComments.'</span><br />
		</div><br class="clear" /><br />';
		if($countRatings > 0) {
			echo '<a class="allRatings" href="/produktbewertungen/'.$user->name.'" title="Alle Bewertungen des Users">Alle Bewertungen des Users</a>';
		}
		if($countComments > 0) {
			echo '<a class="allCommments" href="/kommentare/'.$user->name.'" title="Alle Kommentare des Users">Alle Kommentare des Users</a>';
		}
		echo '</div>
		<div class="col2">
		<table>';
		$typeProduct = str_replace("bewertung_","", $node->type);
		$fieldName = BCVotings::getMyProductFieldName($typeProduct);
		$myProductValue = $node->$fieldName;
		$productNode = node_load(array("nid"=>$myProductValue[0]["nid"]));
		echo ' <caption><a href="'.url("node/".$productNode->nid).'">'.$productNode->title.'</a></caption>';
		echo '
		<tbody>';
		$cnt = 0;
		$sum = 0;
		$ratingFields = BCVotings::getRatingFields($typeProduct);
		$ratingFieldsFlipped = array_flip($ratingFields);
		$ratingFieldsValues = array();
		foreach(get_object_vars($node) as $key=>$value) {
			if(in_array($key."_rating", $ratingFields)) {
				$stars = round($value[0]["stars"] * ($value[0]["rating"] / 100));
				$ratingFieldsValues[$ratingFieldsFlipped[$key."_rating"]] = $stars;
				$sum += $stars;
				$cnt++;
			}
			elseif(ereg("field_.*_comment", $key)) {
				$comment = $value[0]["value"];
			}
		}
		if($cnt>0) {
			foreach($ratingFields as $label=>$key) {
				echo '<tr><th>'.$label."</th><td>".$ratingFieldsValues[$label]."</td></tr>";
			}
			$gesamt = number_format(($sum / $cnt), 1);
		} else {
			$gesamt = "0.0";
		}
		setlocale(LC_TIME, 'de_DE.utf8');
		//var_dump("second");
		echo '</tbody>
		<tfoot>
		<tr><th>Gesamtbewertung:</th><td>'.$gesamt.'</td></tr>
		</tfoot>
		</table>
		</div>';
		echo '<div class="col3">
		<h4>'.strftime("%d. %B %G | %H:%M", $node->created).' Uhr</h4>';
		echo '<div>'.$comment.'</div>
		</div>
		<br class="clear" />
		</div>
		</div>';

	}
	/**
	 * Enter description here...
	 *
	 * @param DOMFI $data
	 * @return void
	 */
	public static function showProdDataTable(DOMFI $data, $productType) {
		$productdetails = $data->getNodes("/product/productdetails");
		if($productdetails->length()>6) {
			for($i=1;$i<=$productdetails->length();$i=$i+6) {
				$xpath = "/product/productdetails[position() >= '$i' and position()<='".($i+5)."']";
				self::showProdDataTableInner(
					$data,
					$productType,
					$data->getNodes($xpath)
				);
			}
		} else {
			self::showProdDataTableInner($data, $productType, $productdetails);
		}
	}
	public static function getNextRowClass($rowCount) {
		if($rowCount%2 == "0") return "even";
		else return "odd";
	}

	private static function showProdDataTableInner(DOMFI $data, $productType, DOMSelection $productdetails)
	{
		$r = DOMFI::getInstance("<table/>");
		$r->node("thead")
			->within()
				->node("tr")
				->within()
					->node("th")
					->attribute("colspan", $productdetails->length()+1)
					->innerText(t("Productdetails / Conditions of the account"))
				->endWithin();
		$voidName = "";

		if($productType == "Tagesgeld" || $productType == "Festgeld")
		{
			$voidName = "new_customers_only";
			if($productdetails->length() > 1) {

				$r->node("tr")->attribute("class","secondRow")
					->within()
						->node("th");
				$productdetails->rewind();
				$newcustomer = false;
				while($productdetails->valid())
				{
					if($data->value("new_customers_only", $productdetails->current()) == "1")
					{
						//$innerTextValue = t("Neukunden");
						$newcustomer = true;
					}
					else
					{
						//$innerTextValue = t("Bestandskunden");
					}
					//$r->node("th")->innerText($innerTextValue);
					$productdetails->next();
				}
				// nochmal durchlaufen, damit Tabellenköpfe nochmal ersetzt werden können
				if ($newcustomer == false)
				{
					$productdetails->rewind();
					while($productdetails->valid())
					{
						if($data->value("new_customers_only", $productdetails->current()) == "1")
						{
							$innerTextValue = t("Neukunden");
						}
						else
						{
							$innerTextValue = t("Alle Kunden");
						}
						$r->node("th")->innerText($innerTextValue);
						$productdetails->next();
					}
				}
				else
				{
					$productdetails->rewind();
					while($productdetails->valid())
					{
						if($data->value("new_customers_only", $productdetails->current()) == "1")
						{
							$innerTextValue = t("Neukunden");
						}
						else
						{
							$innerTextValue = t("Bestandskunden");
						}
						$r->node("th")->innerText($innerTextValue);
						$productdetails->next();
					}
				}

				$r->endWithin();
			}
		}
		else
		{
			$voidName = "profession";
			if($productdetails->length() > 1) {
				$r->node("tr")->attribute("class","secondRow")
					->within()
						->node("th");
				$productdetails->rewind();
				while($productdetails->valid())
				{
					if($data->value("profession", $productdetails->current()) == "ausbildung")
					{
						$innerTextValue = t("In Ausbildung");
					}
					else
					{
						$innerTextValue = t("Alle Kunden");
					}

					$r->node("th")->innerText($innerTextValue);
					$productdetails->next();
				}
				$r->endWithin();
			}
		}
		$rowCount = 0;
		$r->endWithin()
			->node("tbody")
			->within();

		// Welche Reihenfolge soll angewendet werden "class.BCProductdetailsOrder.php"
		switch ($productType)
		{
		case "Autokredit": 	{ $sortArray = BCProductdetailsOrder::$carloansOrderArray; break; }
		case "Ratenkredit": { $sortArray = BCProductdetailsOrder::$loansOrderArray; break; }
		case "Mietkaution": { $sortArray = BCProductdetailsOrder::$rentalbondsOrderArray; break; }
		case "Tagesgeld": { $sortArray = BCProductdetailsOrder::$dailyallowancesOrderArray; break; }
		case "Festgeld": { $sortArray = BCProductdetailsOrder::$fixeddepositsOrderArray; break; }
		case "Girokonto": { $sortArray = BCProductdetailsOrder::$currentaccountsOrderArray; break; }
		case "Kreditkarte": { $sortArray = BCProductdetailsOrder::$creditcardsOrderArray; break; }
		case "Depot": { $sortArray = BCProductdetailsOrder::$brokerageOrderArray; break; }
		case "Baufinanzierung": { $sortArray = BCProductdetailsOrder::$mortgagesOrderArray; break; }
		}


		// Wird Sortierung angewendet bzw. wurde Reihenfolge gefunden?
		if (sizeof($sortArray) != 0)
		{
			$produktDetailsArray[][] = array();

			// Mehrspaltige Produktdetails zwischenspeichern für Änderung der Reihenfolge
			foreach($data->getNodes("*", $productdetails->item(0)) as $child) {
			  $currentNodeName = $child->nodeName;
				if($currentNodeName == $voidName) {
					continue;
				}

				//Finde richtige Stelle anhand von "sortArray" (Reihenfolge)
				$arrayPosition = -1;
				for ($i = 0; $i < sizeof($sortArray); $i++)
				{
					if ($currentNodeName == $sortArray[$i][0])
					{
						$arrayPosition = $i;
						break;
					}
				}
				if ($arrayPosition > -1) {
					$produktDetailsArray[$arrayPosition][0] = $currentNodeName;
					$produktDetailsArray[$arrayPosition][1] = $child->nodeValue;

					for($j=1;$j<$productdetails->length();$j++) {
							$produktDetailsArray[$arrayPosition][$j+1] = $data->firstValue($currentNodeName, $productdetails->item($j));
					}
				}
				/*else // Debug
					echo '<p style="font-size: 20px;"><strong>!!!!FEHLER: Mehrspaltig !!!!</strong><br />Reihenfolge bei <strong>' . $currentNodeName . '</strong> nicht gefunden.</p>';*/
			}

			// Einspaltige Produktdetails zwischenspeichern für Änderung der Reihenfolge
			foreach($data->getNodes("*", $product) as $addNode) {
				if(!in_array($addNode->nodeName, BCImport::$voidProductImportData)) {

					//Finde richtige Stelle anhand von "sortArray" (Reihenfolge)
					$arrayPosition = -1;
					for ($i = 0; $i < sizeof($sortArray); $i++)
					{
						if ($addNode->nodeName == $sortArray[$i][0])
						{
							$arrayPosition = $i;
							break;
						}
					}
					if ($arrayPosition > -1) {
						$produktDetailsArray[$arrayPosition][0] = $addNode->nodeName;
						$produktDetailsArray[$arrayPosition][1] = $addNode->nodeValue;
					}
					/*else // Debug
						echo '<p style="font-size: 20px;"><strong>!!!!FEHLER: Einspaltig !!!!</strong><br />Reihenfolge bei <strong>' . $addNode->nodeName . '</strong> nicht gefunden.</p>';*/
				}
			}

			// Ausgabe aller Produktdetails mit berücksichtigung der Reihenfolge
			for ($i = 0; $i < sizeof($produktDetailsArray); $i++)
			{
				if (sizeof($produktDetailsArray[$i]) > 2) // Mehrspaltig
				{
					$currentNodeName = $produktDetailsArray[$i][0];

					// Alternativer Text
					$productdetailsLabel = self::customTranslation($sortArray, $currentNodeName);

					if ($productType == "Mietkaution") $outputNodename = "rentalbonds_".$currentNodeName;
					elseif ($productType == "Baufinanzierung")  $outputNodename = "mortgages_".$currentNodeName;
					#elseif ($productType == "Ratenkredit")  $outputNodename = "loans_".$currentNodeName;
					#elseif ($productType == "Autokredit")  $outputNodename = "carloans_".$currentNodeName;
					else $outputNodename = $currentNodeName;

					// Falls kein alternativer Text gefunden wurde, wird die standard Übersetzung angewandt
					if ($productdetailsLabel == "")
						$productdetailsLabel = t($outputNodename);

					$r->node("tr")
						->attribute("class", self::getNextRowClass($rowCount++))
						->within()
							->node("th")->innerText($productdetailsLabel)//t($outputNodename))
							->node("td")->innerText(BCImportFormats::formatValue($outputNodename, $produktDetailsArray[$i][1]));
					for($j=2;$j<sizeof($produktDetailsArray[$i]);$j++) {
							$r->node("td")->innerText(BCImportFormats::formatValue($outputNodename, $produktDetailsArray[$i][$j]));
					}
					$r->endWithin();
				}
				else // Einspaltig
				{
					$currentNodeName = $produktDetailsArray[$i][0];
					$currentNodeValue = $produktDetailsArray[$i][1];


					// Alternativer Text
					$productdetailsLabel = self::customTranslation($sortArray, $currentNodeName);

					if ($productType == "Mietkaution") $currentNodeName = "rentalbonds_".$currentNodeName;
					elseif ($productType == "Baufinanzierung")  $currentNodeName = "mortgages_".$currentNodeName;
					#elseif ($productType == "Ratenkredit")  $currentNodeName = "loans_".$currentNodeName;
					#elseif ($productType == "Autokredit")  $currentNodeName = "carloans_".$currentNodeName;

					// Falls kein alternativer Text gefunden wurde, wird die standard Übersetzung angewandt
					if ($productdetailsLabel == "")
						$productdetailsLabel = t($currentNodeName);


					if($currentNodeValue != "") {
						$r->node("tr")->attribute("class", self::getNextRowClass($rowCount++))->within()
							->node("th")->innerText($productdetailsLabel)//t($currentNodeName))

							->node("td")->attribute("colspan", $productdetails->length());
						if($currentNodeName == "legaldata" || $currentNodeName == "mortgages_legaldata") {
							$html = @DOMDocument::loadHTML(utf8_decode($currentNodeValue));
							$r->xml($html->saveXML());
						} else {
							$r->innerText(BCImportFormats::formatValue($currentNodeName, $currentNodeValue));
						}
						$r->endWithin();
					}
				}
			}
		}
		else // Auslesen ohne Sortierung
		{
			// Alternative (unsortierte) Ausgabe
			foreach($data->getNodes("*", $productdetails->item(0)) as $child) {
			  $currentNodeName = $child->nodeName;
				if($currentNodeName == $voidName) {
					continue;
				}
				if ($productType == "Mietkaution") $outputNodename = "rentalbonds_".$currentNodeName;
				elseif ($productType == "Baufinanzierung")  $outputNodename = "mortgages_".$currentNodeName;
				elseif ($productType == "Ratenkredit")  $outputNodename = "loans_".$currentNodeName;
				elseif ($productType == "Autokredit")  $outputNodename = "carloans_".$currentNodeName;
				else $outputNodename = $currentNodeName;

				$r->node("tr")
					->attribute("class", self::getNextRowClass($rowCount++))
					->within()
						// ->node("th")->innerText($outputNodename)
						->node("th")->innerText(t($outputNodename))
						->node("td")->innerText(BCImportFormats::formatValue($outputNodename, $child->nodeValue));
				for($j=1;$j<$productdetails->length();$j++) {
						$r->node("td")->innerText(BCImportFormats::formatValue($outputNodename, $data->firstValue($currentNodeName, $productdetails->item($j))));
				}
				$r->endWithin();

			}

			foreach($data->getNodes("*", $product) as $addNode) {
				if(!in_array($addNode->nodeName, BCImport::$voidProductImportData)) {
					if($addNode->nodeValue != "") {
						$r->node("tr")->attribute("class", self::getNextRowClass($rowCount++))->within()
							// ->node("th")->innerText($addNode->nodeName)
							->node("th")->innerText(t($addNode->nodeName))
							->node("td")->attribute("colspan", $productdetails->length());
						if($addNode->nodeName == "legaldata") {
							$html = @DOMDocument::loadHTML(utf8_decode($addNode->nodeValue));
							$r->xml($html->saveXML());
						} else {
							$r->innerText(BCImportFormats::formatValue($addNode->nodeName, $addNode->nodeValue));
						}
						$r->endWithin();
					}
				}
			}
		}

		echo $r->innerXML();
	}

	/* NVEDIT OS 18.12.2012
	 * Diese Funktion wird vom theme in der datei template.php aufgerufen.
	 * Sie ergänzt bei Bewertungen entsprechnde open graph tags im html head.
	 * */
	public static function getAdditionalHeaderContent($vars) {
		$tags = '';
		if(ereg("^bewertung_", $vars['node']->type)) {
			//Insert graph ui tags

			//Für Siegel url:
			$BCVotingNode = BCVotings::byRatingNode($vars['node']);

			//Produkt Name:
			$novaBCVoting = new NovaBCVoting($vars['node']->nid);
			$node = node_load(array("nid"=>$novaBCVoting->getRatedProductItemNid()));
			$productName = $node->title;

			//Produkt Kategorie:
			//$nodeMyProduct = node_load(array("nid"=>($node->field_prodmyproduct[0]["nid"])));
			//$productType = $nodeMyProduct->title;


			//Bank:
			$nodeMyBank = node_load(array("nid"=>($node->field_proditemmybank[0]["nid"])));
			$bankName = $nodeMyBank->title;
			//$bankType = ucfirst($nodeMyBank->type);


			$tags = '';
			$tags .= '<meta property="og:title" content="Bewertung von '. $productName  .' der ' . $bankName . ' auf BankingCheck.de" />' . "\n";
			$tags .= '<meta property="og:description" content="Ich habe gerade das ' . $productName  .' der ' . $bankName . ' auf BankingCheck bewertet. Hier könnt Ihr meine Bewertung nachlesen!" />' . "\n";
			$tags .= '<meta property="og:image" content="http://'.$_SERVER['SERVER_NAME'] . $BCVotingNode->getSiegelUrl() . '" />' . "\n";
		}
		return $tags;
	}
	/* /NVEDIT */

	public static function showRatingCommentRow($node, $links, $fields, $bcVotings) {
		$productName = strlen(BCTemplate::getProductNameByNode($node)) > 0 ? BCTemplate::getProductNameByNode($node) : "bank";
		echo '<div class="greyGradientInvert commentbar1">
				<div class="segment">Auf BankingCheck.de:</div>
				<div class="segment segmentsep">
					<span class="commentLink commentLink2">';
						if (user_access('post comments')) {
							$commentLink = '<ul class="links inline"><li class="comment_add first last"><a href="/comment/reply/'.$node->nid.'#comment-form" title="Teilen Sie Ihre Gedanken und Meinungen zu diesem Beitrag mit.">Kommentieren</a></li></ul>';
						} else {
							$commentLink = '<ul class="links inline"><li class="comment_add first last"><a href="javascript:showLogin();" title="Teilen Sie Ihre Gedanken und Meinungen zu diesem Beitrag mit.">Kommentieren</a></li></ul>';
						}
						echo $commentLink.'
					</span>
					<span class="commentCountBubble" style="padding-top: 1px;">
						'.$node->comment_count.'
					</span>
				</div>

				<div class="segment segmentsep">
					<span class="rateLink">
						<a href="/node/add/bewertung-'.$productName.'?itemId='.$node->nid.'">Bewerten</a>
					</span>
					<span class="commentCountBubble" style="padding-top: 1px;">'.$bcVotings->calcVotingCount().'</span>
				</div>

				</div>

				<div class="greyGradientInvert" style="position: relative; margin-top: 5px;">
					<span class="socialshareprivacy ssp2" data-url="http://'.$_SERVER["HTTP_HOST"].'/'.$node->path.'" data-text="'.strip_tags($node->title).' auf BankingCheck.de"></span>
				</div>';
				#print_r($bcVotings->showVotingCount());
				#print_r($_SERVER["HTTP_HOST"]);
	}

	public static function getSingleLineRatingCommentRow($node, $url = null, $fields = null) {
		if(!isset($url)) {
			if (user_access('post comments')) {
				$commentLink = '<ul class="links inline"><li class="comment_add first last"><a href="/comment/reply/'.$node->nid.'#comment-form">Kommentieren</a></li></ul>';
			} else {
				$commentLink = '<ul class="links inline"><li class="comment_add first last"><a href="javascript:showLogin();">Kommentieren</a></li></ul>';
			}

			$commentCount = $node->comment_count;
			$url = 'http://'.$_SERVER["HTTP_HOST"].'/'.$node->path;
			$dataText = $node->title;
		} else {
			if (user_access('post comments')) {
				$commentLink = $fields["comments_link"]->content;
			} else {
				$commentLink = '<ul class="links inline"><li class="comment_add first last"><a href="javascript:showLogin()">Kommentieren</a></li></ul>';
			}
			$commentCount = $fields["comment_count"]->content;
			$dataText = strip_tags($fields["title"]->content);
		}

		// Comments on other page?
		if (preg_match("/^bewertung_/", $node->type) === 1) {
			$fullPath = "/".$node->path;
			if ($fullPath != $_SERVER['REQUEST_URI'] && $commentCount>0) {
				$commentCount = "<a href='/".$node->path."'>".$commentCount."</a>";
			}
		}

		return '<div class="greyGradientInvert" style="position: relative;">
						<span class="rightSeperated commentWrapper">
							<span class="commentLink">'.$commentLink.'</span>
							<span class="commentCountBubble">'.$commentCount.'</span>
						</span>
						<span class="socialshareprivacy" data-url="'.$url.'" data-text="'.rawurlencode($dataText).'"></span>
					</div>';
	}

	public static function showSingleLineRatingCommentRow($node, $url, $fields) {
		echo BCTemplate::getSingleLineRatingCommentRow($node, $url, $fields);
	}

	public static function getPager($entries, $stepWidth, $link) {
		if (isset($_GET["page"])) $currentPage = $_GET["page"];
		else                      $currentPage = 0;

		$totalPages = floor($entries/$stepWidth);


		$html = '<div class="item-list"><ul class="pager">';

		if ($currentPage != 0) {
			$html .= '<li class="pager-first first"><a href="'.$link.'" title="Zur ersten Seite" class="active">« erste Seite</a></li>
<li class="pager-previous"><a href="'.$link.'?page='.($currentPage-1).'" title="Zur vorherigen Seite" class="active">‹ vorherige Seite</a></li>
<li class="pager-ellipsis">…</li>';
		}
		for ($count = max(0, $currentPage-6); $pagesWritten < 10; $count++) {
			$classes = '';
			if ($currentPage == 0)        $classes .= ' first ';
			if ($count == $currentPage) $html .= '<li class="'.$classes.' pager-current">'.($count+1).'</li>';
			else                          $html .= '<li class="'.$classes.' pager-item"><a href="'.$link.'?page='.$count.'" title="Gehe zu Seite '.($count+1).'" class="active">'.($count+1).'</a></li>';
			if ($count == $totalPages) break;
			$pagesWritten++;
		}
		if ($currentPage < $totalPages) {
			$html .= '<li class="pager-ellipsis">…</li>';
			$html .= '<li class="pager-next"><a href="'.$link.'?page='.($currentPage+1).'" title="Zur nächsten Seite" class="active">nächste Seite ›</a></li>';
			$html .= '<li class="pager-last last"><a href="'.$link.'?page='.$totalPages.'" title="Zur letzten Seite" class="active">letzte Seite »</a></li>';
		}
		$html .= '</ul></div>';
		return $html;
	}
}
