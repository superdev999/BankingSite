<?php
//zum Seitenstart step auf 1 setzen
$step = 1;
#print_r($_REQUEST);
#echo arg(0);
if ($_GET["action"] == "mailApproved") $step = 2;
elseif ($_GET["action"] == "bewertung-zeigen") $step = 3;
elseif (isset($_POST["nid"])) $step = 3;
if (!isset($step)) print_r($_REQUEST);
if ($step == 1) {
	?>
<!--<div id="bewertungsfinanzheader"></div>-->

<div class="node-form">
	<div class="" id="firstFormWrapper">
		<form action="/" method="GET" id="ratingForm">
			<input type="hidden" id="bankval" name="bank" value=""> <input
				type="hidden" id="produktval" name="produkt" value=""> <input
				type="hidden" id="currentP" name="currentP" value=""> <input
				type="hidden" id="currentB" name="currentB" value=""> <input
				type="hidden" id="qForm" name="q" value="">
			<div id="bewertunsstrrecke_l">
				<!--<h2 class="bewertungbox bewerbreit">Nach Bank auswählen</h2>-->
				<div class="umrandung_blau">
					<div id="bank" class="baccordion bankaccordion">

						<?php							
						$start=true;
						$last_alpha='';				
							
							
						echo '<div class="accordionItem">';
						echo '<h3 class="accordionTitle">#</h3>';
						echo '<div class="accordionBody" id="belement0"><div class="content"><img src="/themes/pixture_reloaded/images/ajax-loader.gif" /></div><div class="bewertung"></div></div></div>';
							
							

						$alphabet=range('A', 'Z');
						foreach($alphabet as $ap)
						{
							echo '<div class="accordionItem">';
							echo '<h3 class="accordionTitle">'.$ap.'</h3>';
							echo '<div class="accordionBody" id="belement'.$ap.'"><div class="content"><img src="/themes/pixture_reloaded/images/ajax-loader.gif" /></div><div class="bewertung"></div></div></div>';
						}
						?>

						<p class="clear" style="margin-bottom: 0"></p>
					</div>
				</div>
			</div>
<!--
			<div id="bewertunsstrrecke_r">
				<h2 class="bewertungbox">Nach Produkt auswählen</h2>
				<div class="umrandung_blau">
					<div id="produkt" class="paccordion">

						<?php 	

						
/**	
						$start=true;
						$last_alpha='';
						$products = db_query('SELECT * FROM {node} WHERE type="%s" ORDER BY title', "product");
							
						while ($productNode = db_fetch_object ($products)) {

							$alpha=$productNode->title;

							if($alpha!==$last_alpha){
								if(!$start) echo '</div></div>';
									
								$start=false;
									
								echo '<div class="accordionItem">';
								echo '<h3 class="accordionTitle">'.$productNode->title.'</h3>';
								echo '<div class="accordionBody" id="pelement'.$productNode->nid.'"><div class="content">';

									
							}

							echo "<img src='/themes/pixture_reloaded/images/ajax-loader.gif' /></div><div class='bewertung'></div>";

						}
						//letztes Element schließen
						echo '</div></div>'
							
						**/?>
					</div>-->
				</div>
			</div>

			<p class="clear"></p>
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
	<p class="blue">Wir würden uns sehr freuen, wenn Sie sich registrieren würden. So können Sie von vielen tollen Vorteilen profitieren.</p>
	<ul class="advList">
	<li>Personalisierter Newsletter nach Ihren Interessen</li>
	<li>Schneller Zugriff auf persönliche Einstellungen</li>
	<li>Weitere Kommentare und Bewertungen verfassen</li>
	</ul>
	</div>
	<div class="reg_button"><a href="user/register" title="Ich will mitmachen" class="buttonLike people">Ich will mitmachen!</a></div>';
	// TODO(fg): Balken mit Registrieren-Vorteilen einbauen
	// TODO(fg): Gewinnspiel ausblenden
	$node = node_load(array("nid"=>$_GET["nid"]));
	BCTemplate::showRatingLine($node);
} elseif ($step == 3) {	
	$node = node_load(array("nid"=>$_GET["bewertungsnid"]));
	BCTemplate::showRatingLine($node);
} else {
	throw new Exception("Invalid URL parameter");
}
?>