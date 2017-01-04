<?php
// $Id: views-view-unformatted.tpl.php,v 1.6 2008/10/01 20:52:11 merlinofchaos Exp $
/**
 * @file views-view-unformatted.tpl.php
 * Default simple view template to display a list of rows.
 *
 * @ingroup views_templates
 */
if(count($rows) == 0) {
	$verb = "sind";
	$anzahl = "noch keine";
	$name = "News";
} elseif(count($rows) == 1) {
	$verb = "ist";
	$anzahl = "eine";
	$name = "News";
} else {
	$verb = "sind";
	$anzahl = count($rows);
	$name = "News";
}
echo '<div class="node node-type-productitem greyGradient">
	<div class="node-inner-0">
		<div class="node-inner-1">
			<div class="node-inner-2">
				<div class="node-inner-3">
					<div class="blueGradientInvert">
						<p>Es '.$verb.' zu diesem Produkt '.$anzahl.' '.$name.' vorhanden</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
';
?>
<?php foreach ($rows as $id => $row): ?>
  <div class="<?php print $classes[$id]; ?>">
    <?php print $row; ?>
  </div>
<?php endforeach; ?>
