<?php
ob_start();
require_once $_SERVER['DOCUMENT_ROOT'].'/sites/all/libraries/swift/lib/swift_required.php';
$debugFile = $_SERVER['DOCUMENT_ROOT']."/sites/all/libraries/quickrating/debug_verifyMail.txt";
$dataFileNotPublished = $_SERVER['DOCUMENT_ROOT']."/sites/all/libraries/quickrating/notifiedNotPublished.txt";
$dataFileJustPublished = $_SERVER['DOCUMENT_ROOT']."/sites/all/libraries/quickrating/notifiedJustPublished.txt";
$node = $context["node"];
$verifyScriptPath = "/verifyMail/";
print_r($node);
if ($node->uid == 0 && ereg("^bewertung_", $node->type)) {
	$nid = $node->nid;	
	switch ($node->type) {
		case "bewertung_autokredit":
			$token = md5($node->field_carloans_token[0][value]."80uTKSNI6mT68IJuaDgC");
			$mail = $node->field_carloans_mailadress[0][value];
			$productId = $node->field_carloans_myproductitem[0]["nid"];
			$mailApproved = $node->field_carloans_mailapproved[0]["value"];
			break;
		case "bewertung_baufinanzierung":
			$token = md5($node->field_mortgages_token[0][value]."80uTKSNI6mT68IJuaDgC");
			$mail = $node->field_mortgages_mailadress[0][value];
			$productId = $node->field_mortgages_myproductitem[0]["nid"];
			$mailApproved = $node->field_mortgages_mailapproved[0]["value"];
			break;
		case "bewertung_depot":
			$token = md5($node->field_brokerage_token[0][value]."80uTKSNI6mT68IJuaDgC");
			$mail = $node->field_brokerage_mailadress[0][value];
			$productId = $node->field_brokerage_myproductitem[0]["nid"];
			$mailApproved = $node->field_brokerage_mailapproved[0]["value"];
			break;
		case "bewertung_festgeld":
			$token = md5($node->field_fixeddeposits_token[0][value]."80uTKSNI6mT68IJuaDgC");
			$mail = $node->field_fixeddeposits_mailadress[0][value];
			$productId = $node->field_myproductitem[0]["nid"];
			$mailApproved = $node->field_fixeddeposits_mailapproved[0]["value"];
			break;
		case "bewertung_girokonto":
			$token = md5($node->field_currentaccount_token[0][value]."80uTKSNI6mT68IJuaDgC");
			$mail = $node->field_currentaccount_mailadress[0][value];
			/* NVEDIT OS 18.12.2012 Der Schreibfehler "myproductim" anstelle von "myproductitem" stimmt so... /NVEDIT */
			$productId = $node->field_currentaccount_myproductim[0]["nid"];
			$mailApproved = $node->field_currentaccount_mailapprove[0]["value"];
			break;
		case "bewertung_kreditkarte":
			$token = md5($node->field_creditcard_token[0][value]."80uTKSNI6mT68IJuaDgC");
			$mail = $node->field_creditcard_mailadress[0][value];
			$productId = $node->field_creditcard_myproductitem[0]["nid"];
			$mailApproved = $node->field_creditcard_mailapproved[0]["value"];
			break;
		case "bewertung_mietkaution":
			$token = md5($node->field_rentalbonds_token[0][value]."80uTKSNI6mT68IJuaDgC");
			$mail = $node->field_rentalbonds_mailadress[0][value];
			$productId = $node->field_rentalbonds_myproductitem[0]["nid"];
			$mailApproved = $node->field_rentalbonds_mailapproved[0]["value"];
			break;
		case "bewertung_ratenkredit":
			$token = md5($node->field_loans_token[0][value]."80uTKSNI6mT68IJuaDgC");
			$mail = $node->field_loans_mailadress[0][value];
			$productId = $node->field_loans_myproductitem[0]["nid"];
			$mailApproved = $node->field_loans_mailapproved[0]["value"];
			break;
		case "bewertung_tagesgeld":
			$token = md5($node->field_dailyallowances_token[0][value]."80uTKSNI6mT68IJuaDgC");
			$mail = $node->field_dailyallowances_mailadress[0][value];
			$productId = $node->field_call_myproductitem[0]["nid"];
			$mailApproved = $node->field_dailyallowances_mailapprov[0]["value"];
			break;
		case "bewertung_bank":
			$token = md5($node->field_bank_token[0][value]."80uTKSNI6mT68IJuaDgC");
			$mail = $node->field_bank_mailadress[0][value];
			$productId = $node->field_bank_bankitem[0]["nid"];
			$mailApproved = $node->field_bank_mailapproved[0]["value"];
			break;
		default:
			echo "ERROR: Undefined Node type ".$node->type;
			$token = $node->type;
			$mail = "godard@nova-web.de";
	}
	if ($node->status == 0 && $mailApproved == 0) {
		// Neu erzeugt und noch nicht bestätigt
		// Deserialisieren
		if (is_file($dataFileNotPublished)) $serialized = file_get_contents($dataFileNotPublished);
		$trailer = "";
		$deserialized = unserialize($serialized);
		if ($deserialized !== false) {
			#echo "Unserializing ".$serialized;
			$trailer .= print_r($deserialized, true);
			$alreadyNotifiedFor = $deserialized;
			#echo "\nUnserialized: ".print_r($alreadyNotifiedFor, true);
		} else {
			echo "New array created.\n";
			$alreadyNotifiedFor = array();
		}
		// Schauen, ob bereits benachrichtigt
		if (!in_array($node->nid, $alreadyNotifiedFor)) {
			// Mail senden
			if (strlen($mail) < 2) $mail = "godard@nova-web.de";
			$url = BCEmailGenerator::fl($verifyScriptPath.$nid."/".$token);
			$filename = $_SERVER['DOCUMENT_ROOT']."/emails/verifyRatings/".$token.".html";
			$gen = new BCEmailGenerator($id, $filename, "&nbsp;");
			$headline = "Bitte best&auml;tigen Sie Ihre Bewertung auf BankingCheck, damit ihre Bewertung ver&ouml;ffentlicht werden kann:";
			$headerBar = '<table><tr><td><img src="'.BCEmailGenerator::fl('/themes/pixture_reloaded/images/people.png').'"></td><td style="width: 435px; height: 19px;font-size: 15px;vertical-align: middle;padding-top: 7px;padding-bottom: 5px;padding-left: 9px;font-weight: bold;">Hallo '.$mail.'</td><td style="height: 32px; width: 79px;"><a href="'.BCEmailGenerator::fl('/user/register').'" style="color: white; text-decoration: none;"><img src="'.BCEmailGenerator::fl('/themes/pixture_reloaded/images/modal_login_new.png').'"></a></td></tr></table><hr>';
			
			
			$body = "<img src='".BCEmailGenerator::fl("/sites/default/files/bcgrafiken/readMore.png")."'><a href='".$url."'>Hier Bewertung freigeben</a><br><br>Wenn Sie den Link nicht anklicken können, kopieren Sie bitte folgende URL in ihren Browser: ".$url;			
			$noHtmlBody = "Wenn diese Nachricht in Ihrem E-Mail-Programm nicht richtig angezeigt wird, klicken Sie bitte auf den folgenden Link oder kopieren ihn in die Adresszeile Ihres Browsers: ".$gen->getStaticFileUrl()."\n\n\nBitte bestaetigen Sie Ihre Bewertung auf BankingCheck, damit ihre Bewertung gespeichert werden kann:\n\nHier Bewertung freigeben: ".$url."\n\n\n\nCopyright © ".date("Y")." BankingCheck.de\nKontakt: ".BCEmailGenerator::fl("/kontakt")."\nImpressum: ".BCEmailGenerator::fl("/Impressum");
			$design = new BCEmailGeneratorPartSection(null, $headline, null, null, $body, null, null, $headerBar);
			$gen->addContent($design);
			$gen->saveStatic();
			$email = new Email($mail, BCEmail::$fromAddress, "Bitte bestaetigen Sie ihre Bewertung auf Bankingcheck.de", $noHtmlBody);
			$email->addHTML($gen->personalize($user), true);
			if(!$email->send())	throw new Exception("Can't send to ".$mail);
			$alreadyNotifiedFor[] = $node->nid;
		} else echo "Already notified";
		$serialized = serialize($alreadyNotifiedFor);
		echo "Serialized: "+$serialized;
		file_put_contents($dataFileNotPublished, $serialized);
	} elseif ($node->status != 0) {
		// Gerade freigeschaltet
		if ($node->field_notify_publish[0]["value"] == "1") {
			echo "Benutzer wird benachrichtigt.";
			
			// Deserializing
			if (is_file($dataFileJustPublished)) $serialized = file_get_contents($dataFileJustPublished);
			$trailer = "";
			$deserialized = unserialize($serialized);
			if ($deserialized !== false) {
				#echo "Unserializing ".$serialized;
				$trailer .= print_r($deserialized, true);
				$alreadyNotifiedFor = $deserialized;
				echo "\nUnserialized: ";
				print_r($alreadyNotifiedFor);
			} else {
				echo "New array created.\n";
				$alreadyNotifiedFor = array();
			}
			
			
			/* NVEDIT OS 20.12.2012 Das switch oben scheint manchmal keine Produktid zu liefern. Das hier scheint zu klappen:  */	
			/* NVEDIT FG 26.12.2012 Bei Tagesgeld war der Feldname falsch. Gefixt. */			
			if(is_null($productId)){			
				watchdog('verifyMail', "No product id for node ".$node->nid." found.", NULL, WATCHDOG_CRITICAL);				
			}
			/* /NVEDIT */
			
			// Schauen, ob bereits benachrichtigt
			if (!in_array($node->nid, $alreadyNotifiedFor)) {
				// Mail senden
				if (strlen($mail) < 2) $mail = "godard@nova-web.de";
				$filename = $_SERVER['DOCUMENT_ROOT']."/emails/publishedRatings/".$token.".html";
				$gen = new BCEmailGenerator($id, $filename, "&nbsp;");
				$headline = "Ihre Bewertung wurde gerade freigeschaltet.<br>Vielen Dank für Ihren Beitrag.";
				$ratedProduct = node_load(array("nid"=>$productId));
				$nodeMyBank = node_load(array("nid"=>($ratedProduct->field_proditemmybank[0]["nid"])));
				$bankName = $nodeMyBank->title;
				
				
				/* NVEDIT OS 22.12.2012 Diese Bewertung muss auch schon beachtet werden daher reindex!  */
				$BCVotingNode = BCVotings::byRatingNode($node);
				$BCVotingNode->reindex();
				/* /NVEDIT */
				
				$link = '/node/'.$node->nid;
				echo "Original link ".$link."\n";
				if (strlen($node->path)>0) {
					$link = "/".$node->path;
					echo "new link ".$link."\n";
				}
				/* NVEDIT OS 19.12.2012 updated share links:  */
				//old: https://www.facebook.com/sharer/sharer.php?u=".BCEmailGenerator::fl($link)."&t=Ich habe gerade das ". $ratedProduct->title." der " . $bankName . " auf BankingCheck bewertet: ".BCEmailGenerator::fl($link)."
				
				$fbAgs = array('app_id=394856827230388',
								'link=' . BCEmailGenerator::fl($link),
								'redirect_uri=' . urlencode("http://www.facebook.com/"),
								'name=' . urlencode("Bewertung von " . $ratedProduct->title . " der " .  $bankName . " auf BankingCheck.de" ),
								'description=' . urlencode('Ich habe gerade ' . $ratedProduct->title  .' der ' . $bankName . ' auf BankingCheck bewertet. Hier könnt Ihr meine Bewertung nachlesen!'),
								'picture=' . BCEmailGenerator::fl($BCVotingNode->getSiegelUrl())
								);
				
				
				$body = "
				<a href='https://www.facebook.com/dialog/feed?" . implode('&', $fbAgs) . "'><img src='".BCEmailGenerator::fl('/themes/pixture_reloaded/images/facebook_recommend.png')."' alt='Bei Facebook empfehlen' /></a>
				<a href='https://twitter.com/intent/tweet?source=webclient&text=". urlencode("Ich habe gerade ".$ratedProduct->title. " der " . $bankName . " bei @BankingCheck bewertet: ") . BCEmailGenerator::fl($link)."' title='Twittern'><img src='".BCEmailGenerator::fl('/themes/pixture_reloaded/images/tweet.png')."' alt='Twitter' /></a>
				<a href='https://plus.google.com/share?hl=de&url=".BCEmailGenerator::fl($link)."&title=" .urlencode("Ich habe gerade ".$ratedProduct->title. " der " . $bankName . " bei +BankingCheck bewertet") ."' title='Bei Google+ empfehlen'><img src='".BCEmailGenerator::fl('/themes/pixture_reloaded/images/plus1.png')."' alt='Google +1' /></a>
				";
				/* /NVEDIT */
				/*$headerBar = '<img src="'.BCEmailGenerator::fl('/themes/pixture_reloaded/images/people.png').'" style="float: left;"><div style="height: 19px;font-size: 15px;vertical-align: middle;padding-top: 7px;padding-bottom: 5px;padding-left: 9px;float: left;font-weight: bold;">Hallo '.$mail.'</div><div style="float: right;height: 23px;padding-top: 7px;font-size: 13px;background-image: url('.BCEmailGenerator::fl('/themes/pixture_reloaded/images/modal_login.png').');color: white;background-repeat: no-repeat; width: 77px; text-align: center;"><a href="'.BCEmailGenerator::fl('/user/register').'" style="color: white; text-decoration: none;">Mein Profil</a></div><div style="clear:both"></div><hr>';*/
				
				
				$headerBar = '<table><tr><td><img src="'.BCEmailGenerator::fl('/themes/pixture_reloaded/images/people.png').'"></td><td style="width: 435px; height: 19px;font-size: 15px;vertical-align: middle;padding-top: 7px;padding-bottom: 5px;padding-left: 9px;font-weight: bold;">Hallo '.$mail.'</td><td style="height: 32px; width: 79px;"><a href="'.BCEmailGenerator::fl('/user/register').'" style="color: white; text-decoration: none;"><img src="'.BCEmailGenerator::fl('/themes/pixture_reloaded/images/modal_login_new.png').'"></a></td></tr></table><hr>';
				
				
				
				$noHtmlBody = "Wenn diese Nachricht in Ihrem E-Mail-Programm nicht richtig angezeigt wird, klicken Sie bitte auf den folgenden Link oder kopieren ihn in die Adresszeile Ihres Browsers: ".$gen->getStaticFileUrl()."\n\n\nIhre Bewertung wurde gerade freigeschaltet.\nVielen Dank für Ihren Beitrag.\nZu Ihrem Beitrag: ".BCEmailGenerator::fl($link)."\n\n\n\nCopyright © ".date("Y")." BankingCheck.de\nKontakt: ".BCEmailGenerator::fl("/kontakt")."\nImpressum: ".BCEmailGenerator::fl("/Impressum");			
        		$design = new BCEmailGeneratorPartSection(null, $headline, null, null, $body, "zur Bewertung", $link, $headerBar);
				$gen->addContent($design);
				$gen->saveStatic();
				$email = new Email($mail, BCEmail::$fromAddress, "Ihre Bewertung wurde gerade freigeschaltet.", $noHtmlBody);
				$email->addHTML($gen->personalize($user), true);
				if(!$email->send())	throw new Exception("Can't send to ".$mail);
				$alreadyNotifiedFor[] = $node->nid;
			} else echo "Already notified\n";
			$serialized = serialize($alreadyNotifiedFor);
			echo $serialized;
			file_put_contents($dataFileJustPublished, $serialized);
		} else echo "Benutzer wird nicht benachrichtigt.\n";
	}
}
elseif ($node->uid != 0) echo "Not an anonymous rating.";
elseif (!ereg("^bewertung_", $node->type)) echo "Not a rating";
else echo "Just called.";
$output = ob_get_clean();
file_put_contents($debugFile, $output);
?>
