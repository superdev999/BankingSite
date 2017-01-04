<?php
$node = node_load(array("nid"=>$row->nid));

$novaBCVoting = new NovaBCVoting($row->nid);
$myProdukt = $novaBCVoting->getRatedProductItemNode();
?>
<div class="latestRated row">
	<div class="latestRatedMeta">
	<?php
	if ($node->name == "") $node->name = "Anonym";
	?>
		<a href="<?=url("node/".$node->nid)?>" title="Zur Bewertung von <?=$myProdukt->title?> von <?=$node->name?>">
			<strong>Produkt: </strong><?php echo $myProdukt->title; ?><br/>
			<strong>Bewertung: </strong><?php echo $novaBCVoting->getAverage(); ?><br/>
			<strong>Von: </strong><?php echo $node->name; ?><br/>
		</a>
	</div>
	<div class="latestRatedText">
		<p><?php echo $novaBCVoting->getComment(); ?></p>
	</div>
</div>
<?php

?>