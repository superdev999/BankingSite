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
$classes = explode("|", "CSV|DOMBuilder|DOMSelection|DOMBuilderTraverse|DOMFI|DBTools|DBToolsDrupal|DebugUtilities|BCDBRelated|BCImport|BCVotings|BCTemplate");
foreach($classes as $class) {
	require_once($_SERVER['DOCUMENT_ROOT']."/lib/php/class.$class.php");
}
?>
<h3>node-bank.tpl.php</h3>
<div class="tabs">
	<?php
	BCTemplate::showBankTabs($node);
	$import = new BCImport();
	$productName = "bank";
	$bank = node_load(array("nid"=>$node->field_bank_bankitem[0]["nid"]));
	$bcVotings = new BCVotings($node->nid, $productName);
	?>
	<div>
		<div>
			<div class="cols2_1">
				<div class="col1" style="width:70%;float:left;">
					
					<h2 class="title"><a href="<?php print $node_url; ?>"><?php print $title; ?></a></h2>
					<div class="bcCustomer"></div>
					<div class="banklogo"><img src="/<?=$node->field_banklogo[0]["filepath"]?>" alt="<?=$title?>" /></div>
					<div class="bank-body">
						<?php print $node->body; ?>
					</div>
					<br class="clear" />
					<div class="bankDataTable">
						<table>
							<thead><th colspan="2">Bankendetails</th></thead>
							<tbody>
								<?php
								$i=0;
								foreach($import->getBankData($node->field_financeaddsid[0]["value"]) as $k=>$v) {
									if($k != "Webseite"){
										?>
										<tr class="<?=BCTemplate::getNextRowClass($i++)?>">
											<th width="200"><?=t($k)?></th>
											<td><?=BCImportFormats::formatValue($k, $v, $node->nid) ?></td>
										</tr>
										<?php
									}
								}
								?>
							</tbody>
						</table>
					</div>
					<?php
						$prods = $import->getBankProductsData($node->field_financeaddsid[0]["value"]);
						if(count($prods)>0) {
						?>
							<h3>Verfügbare Produkte und deren Bewertung</h3>
							<div class="bankProdTable">
								<table>
									<thead>
										<tr>
											<th width="200">Produkt</th>
											<th>Bewertung</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$i = 0;
										foreach($prods as $line) {
											?>
											<tr class="<?=BCTemplate::getNextRowClass($i++)?>">
												<td><a href="<?=url("node/".$line["nidProductItem"]) ?>" title="<?=$title." - ".$line["title"]?>"><?=$line["title"]?></a></td>
												<td>
													<?php
													$votings = new BCVotings($line["nidProductItem"], $line["typeProduct"]);
													$avg1 = $votings->getAverage();
													if($avg1 != "0"){
														echo $avg1;
													}
													?>
												</td>
											</tr>
											<?php
										}
										?>
									</tbody>
								</table>
							</div>
							<?php
						}
						BCTemplate::showRatingCommentRow($node, $links, $fields, $bcVotings);
						?>
					</div>
					<div class="col2" style="width:30%;float:left;">
					<?php
						if($bcVotings->getAverage()>0) {
						?>
						<div id="activeContent">
							<div class="siegel">
								<?php
								$bcVotings->showSiegel();
								?>
							</div>
							<?php
							$bBank = true;
							$bcVotings->showVotings($bBank);
							?>
						</div>
						<span>Zeitraum: </span>
						<select name="<?=$node->nid?>" rel="<?=$productName?>" id="showVotingsForTimespan">
							<option value="0">gesamter Zeitraum</option>
							<option value="1">letzte 30 Tage</option>
							<option value="3">letzte 3 Monate</option>
							<option value="6">letzte 6 Monate</option>
							<option value="12">letzte 12 Monate</option>
							<option value="15">Award 2015 (Jan-Apr 2015)</option>
							<option value="14">Award 2014 (Jan-Mai 2014)</option>
							<option value="13">Award 2013 (Jan-Sep 2013)</option>
						</select>
						<?php
						$bcVotings->showPromotingRate();
						?>

						<div class="rateNow">
							<a href="/node/add/bewertung-bank?itemId=<?php echo $node->nid; ?>">Jetzt bewerten</a>
						</div>
						<?php
					} else {
						?>
						<div class="rateNow">
							<a href="/node/add/bewertung-bank?itemId=<?php echo $node->nid; ?>">Als Erster bewerten</a>
						</div>
						<?php
					}

					$bankNode = new NovaBCBank($node);
					$bankUrl = $bankNode->getBankUrl();

					if(!is_null($bankUrl)) {
						?>
						<div class="banklink">
							<a href="<?php echo $bankUrl; ?>" rel="nofollow" title="Direkt zum Anbieter" target="_blank">Direkt zum Anbieter</a>
						</div>
						<?php
					}
					?>
					<div class="region-subleft">
						<?php
							print $subleft;
						?>
					</div>
				</div>
				<br class="clear">
			</div>
		</div>
		<div>Bewertungen</div>
		<div >
			<?php
				if($node->comment_count == 0) {
					$verb = "sind";
					$anzahl = "keine";
					$name = "Kommentare";
				} elseif($node->comment_count == 1) {
					$verb = "ist";
					$anzahl = "1";
					$name = "Kommentar";
				} else {
					$verb = "sind";
					$anzahl = $node->comment_count;
					$name = "Kommentare";
				}
				echo "<h2>".$node->title."</h2><p>Es $verb zu diesem Produkt $anzahl $name vorhanden.</p>";

			?>
		</div>
	</div>
</div>
<script>
$(document).ready(function() {
	$('.field-field-bccustomer')
		.appendTo('.bcCustomer')
			.css('float', 'right')
				.css('margin-right', '25px')
					.css('font-size', '12px');
});
</script>