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
$classes = explode("|", "CSV|DOMBuilder|DOMSelection|DOMBuilderTraverse|DOMFI|DBTools|DBToolsDrupal|DebugUtilities|BCDBRelated|BCImport|BCVotings|BCTemplate|BCProductdetailsOrder|BCAlertSender");
foreach($classes as $class) {
	require_once($_SERVER['DOCUMENT_ROOT']."/lib/php/class.$class.php");
}

$productName = BCTemplate::getProductNameByNode($node);
$bank = node_load(array("nid"=>$node->field_proditemmybank[0]["nid"]));
$bcVotings = new BCVotings($node->nid, $productName);
BCTemplate::showProductitemTabs($node);

?>
<div id="node-<?php print $node->nid; ?>" class="node <?php print $node_classes; ?> greyGradient">
	<div class="node-inner-0">
		<div class="node-inner-1">
			<div class="node-inner-2">
				<div class="node-inner-3">
					<div class="blueGradientInvert">
						<?php
						if(!ereg("kommentare",  $_SERVER['REQUEST_URI'])) {
						?>
						<div class="cols2_1">
							<div class="col1">
								<h2 class="title"><a href="<?php print $node_url; ?>"><?php print $title; ?></a></h2>
								<div class="content clearfix">
									<div class="banklogo">
										<a href="<?=url("node/".$bank->nid) ?>" title="<?=$bank->title?>">
											<img src="/<?=$bank->field_banklogo[0]["filepath"]?>" alt="<?=$bank->title?>" />
										</a>
									</div>
									<div class="productitem-body">
										<?php print $node->body; ?>
									</div>
									<div class="prodDataTable">
										<?php
										$bi = BCImport::showProdDataTable($node->nid);
										BCAlertSender::runInProductItem($node->nid);
										?>
									</div>
									<?php	BCTemplate::showRatingCommentRow($node, $links, $fields, $bcVotings);	?>
								</div>
							</div>
							<div class="col2">
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
									$bBank = false;
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
									<a href="/node/add/bewertung-<?=$productName."?itemId=".$node->nid?>">Jetzt bewerten</a>
								</div>
								<?php
								} else {
								?>
								<div class="rateNow">
									<a href="/node/add/bewertung-<?=$productName."?itemId=".$node->nid?>">Als Erster bewerten</a>
								</div>
								<?php
								}
								?>
								<div class="alertNewsletter">
									<?php BCAlertSender::NewsletterButtonText($node->nid); ?>
								</div>
								<?php
								
								$bankNode = new NovaBCProductitem($node);
								$bankUrl = $bankNode->getBankUrl();

								if(!is_null($bankUrl)) {
									?>
									<div class="banklink">
										<a href="<?php echo $bankUrl; ?>" rel="nofollow" title="Direkt zum Anbieter" target="_blank">Direkt zum Anbieter</a>
									</div>
									<?php
								}
								?>
								<div class="testSiegel">
								<?php
								foreach($node->field_testsigns as $sign) {
									if(is_file($_SERVER['DOCUMENT_ROOT']."/".$sign["filepath"])) {

								?>
								<div class="newImgContainer">
									<img src="/<?=$sign["filepath"]?>" alt="<?=$sign["filename"]?>" />
								</div>
							<?php
								}
							}
							?>
							</div>
						</div>
							<br class="clear" />
						</div>
									<?php
									} else {
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
									}
								?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
