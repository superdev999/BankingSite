<?php

if (!isset($catName)) {
	dpm("Nicht gesetzter Kategoriename");
}
if (!isset($onlyAward)) {
	$onlyAward = 1;
}
// get Nid of Anbieterkategorie
// should be in $catName
$catNode = node_load(array('title' => $catName, 'type' => 'anbieterkategorie'));
?>
<div class="node-form">
<div class="standard" id="firstFormWrapper">
<form action="/" method="GET" id="ratingForm">
			<input type="hidden" id="bankval" name="bank" value=""> <input
				type="hidden" id="produktval" name="produkt" value=""> <input
				type="hidden" id="currentprodukt" name="currentprodukt" value=""> <input
				type="hidden" id="currentbank" name="currentbank" value=""> <input
				type="hidden" id="currentanbieterkategorien" name="currentanbieterkategorien" value=""> <input
				type="hidden" id="qForm" name="q" value="">
				<!-- Sollen nur Award2015-Teilnehmer angezeigt werden? -->
				<input type="hidden" id="qForm" name="award2015" value="<?php echo $onlyAward; ?>">

				<div id="bewertungsstrecke_kategorie" class="bewertungsstrecke">
				<h2 class="bewertungbox bewertungbox_extralarge">Award-Teilnehmer <?php echo $catNode->title; ?></h2>
				<div class="umrandung_blau">
					<div id="anbieterkategorien" class="anbieterkategorien anbieterkategorienaccordion">
						<?php
						$group = new stdClass();
						// Titel der Kategorie
						$group->title = $catNode->title;
						// Nid der Kategorie
						$group->nid = $catNode->nid;
						$groups = array($group);
						$novaBCAccordion = new NovaBCAccordion('anbieterkategorien', $groups);
						echo $novaBCAccordion->getHTML();
						$novaBCAccordion->setJavascript('[]', "/sites/all/libraries/quickrating/listItems-cats.php", "/sites/all/libraries/quickrating/getProductItems-cats.php");

						drupal_add_js('$(function() {$jq("div.anbieterkategorienaccordion h3").click().hide();});', 'inline');

						?>
						<p class="clear" style="margin-bottom: 0"></p>
					</div>

					</div>
				</div>
			</div>
</form>
</div>
</div>
