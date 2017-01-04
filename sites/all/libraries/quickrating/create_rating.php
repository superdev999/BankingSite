<?php
#print_r($_REQUEST);
#echo arg(0);
if ($_GET["action"] == "mailApproved") $step = 2;
elseif ($_GET["action"] == "bewertung-zeigen") $step = 3;
elseif (isset($_POST["nid"])) $step = 3;
if (!isset($step)) print_r($_REQUEST);
if ($step == 1) {
	?>
<div id="bewertungsfinanzheader"></div>

<div class="node-form">
	<div class="standard" id="firstFormWrapper">
		<form action="/" method="GET" id="ratingForm">
			<input type="hidden" id="bankval" name="bank" value=""> <input
				type="hidden" id="produktval" name="produkt" value=""> <input
				type="hidden" id="currentprodukt" name="currentprodukt" value=""> <input
				type="hidden" id="currentbank" name="currentbank" value=""> <input
				type="hidden" id="currentanbieterkategorien" name="currentanbieterkategorien" value=""> <input
				type="hidden" id="qForm" name="q" value="">

			<div id="bewertunsstrrecke_l" class="bewertungsstrecke">
				<h2 class="bewertungbox bewerbreit">Nach Anbieter auswählen</h2>
				<div class="umrandung_blau">
					<div id="bank" class="bankaccordion">
						<?php
						$alphabet=range('A', 'Z');
						$groups = array();
						$other = new stdClass();
						$other->title = "#";
						$other->nid = 0;
						$groups[] = $other;
						foreach($alphabet as $letter)
						{
							$letterObject = new stdClass();
							$letterObject->title = $letter;
							$letterObject->nid = $letter;
							$groups[] = $letterObject;
						}

						$novaBCAccordion = new NovaBCAccordion('bank', $groups);
						echo $novaBCAccordion->getHTML();
						$novaBCAccordion->setJavascript('["anbieterkategorien", "produkt"]', "/sites/all/libraries/quickrating/listItems-2.php", "/sites/all/libraries/quickrating/getProductItems.php");
						?>
						<p class="clear" style="margin-bottom: 0"></p>
					</div>
				</div>
			</div>

			<div id="bewertunsstrrecke_r" class="bewertungsstrecke">
				<h2 class="bewertungbox">Nach Produkt auswählen</h2>
				<div class="umrandung_blau">
					<div id="produkt" class="produktaccordion">
						<?php
						$groups = NovaBCAccordion::getGroupsByQuery(db_query('SELECT * FROM {node} WHERE type="%s" ORDER BY title', "product"));
						$novaBCAccordion = new NovaBCAccordion('produkt', $groups);
						echo $novaBCAccordion->getHTML();
						$novaBCAccordion->setJavascript('["anbieterkategorien", "bank"]', "/sites/all/libraries/quickrating/listItems.php", "/sites/all/libraries/quickrating/getProductItems-2.php");
						?>
					</div>
				</div>
			</div>

			<p class="clear"></p>

			<div id="bewertungsstrecke_kategorie" class="bewertungsstrecke">
				<h2 class="bewertungbox bewertungbox_extralarge">Nach Kategorie auswählen</h2>
				<div class="umrandung_blau">
					<div id="anbieterkategorien" class="anbieterkategorien anbieterkategorienaccordion">
						<?php
						$groups = NovaBCAccordion::getGroupsByQuery(db_query('SELECT * FROM {node} WHERE type="%s" ORDER BY title', "anbieterkategorie"));
						$novaBCAccordion = new NovaBCAccordion('anbieterkategorien', $groups);
						echo $novaBCAccordion->getHTML();
						$novaBCAccordion->setJavascript('["produkt", "bank"]', "/sites/all/libraries/quickrating/listItems-cats.php", "/sites/all/libraries/quickrating/getProductItems-cats.php");
						?>
						<p class="clear" style="margin-bottom: 0"></p>
					</div>

					</div>
				</div>
			</div>
		</form>
	</div>
	<div id="backToFirstFormWrapper" style="display: none; float: left;">
		<button onclick="backToFirstForm()" class="form-submit"
			id="backToFirstForm">Zurück</button>
	</div>
</div>
<?php
} elseif ($step == 2) {
	echo '<div id="block-block-4" class="reg_teaser">
	<p class="blue" style="display: block;">Wir würden uns sehr freuen, wenn Sie sich registrieren würden. So können Sie von vielen tollen Vorteilen profitieren.</p>
	<ul class="advList">
	<li>Personalisierter Newsletter nach Interessen</li>
	<li>Schneller Zugriff auf persönliche Einstellungen</li>
	<li>Weitere Kommentare und Bewertungen verfassen</li>
	</ul>
	</div>
	<div class="reg_button"><a href="user/register" title="Ich will mitmachen" class="buttonLike people">Ich will mitmachen!</a></div>';
	$node = node_load(array("nid"=>$_GET["nid"]));
	BCTemplate::showRatingLine($node);
} elseif ($step == 3) {
	$node = node_load(array("nid"=>$_GET["bewertungsnid"]));
	BCTemplate::showRatingLine($node);
} else {
	throw new Exception("Invalid URL parameter");
}
?>
