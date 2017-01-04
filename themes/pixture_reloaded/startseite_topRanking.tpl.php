<article class="tabs-c">
	<ul>
		<li><a href="./">Am besten bewertet* <i class="icon-star-empty"></i></a></li>
		<li><a href="./">Am häufigsten bewertet <i class="icon-glory-cup"></i></a></li>
		<li><a href="./">Neue Produkte <i class="icon-bank"></i></a></li>
	</ul>
	<div>
		<div>
			<ol class="list-g">
				<?php
				foreach(BCVotings::getBestRatedMin100(10) as $nid=>$average) {
					$node = node_load(array("nid"=>$nid));
					$logoUrl = $node->field_banklogo[0]["filepath"];
					$name = $node->title;
					$url = "/".$node->path;
					$bewertenUrl = "/node/add/bewertung-bank?itemId=".$node->nid;
					$rating = $average;
					?>
					<li>
						<a href="<?php echo $url; ?>">
							<span class="img">
								<img src="<?php echo $logoUrl; ?>" alt="<?php echo $name; ?>" width="167" height="53">
							</span> <?php echo $name; ?>
						</a>
						<ul class="list-b">
							<li><a href="<?php echo $bewertenUrl; ?>"><i class="icon-star"></i> <span class="tip left">Jetzt bewerten</span></a></li>
							<li><span class="graph-a vf" data-max="5"><?php echo $rating; ?></span></li>
						</ul>
					</li>
					<?php
				}
				?>
			</ol>
			<p class="scheme-g">* Kundenbewertungen (Durchschnitt aus mindestens 98 Bewertungen)</p>
		</div>
		<div>
			<ol class="list-g">
				<?php
				foreach(BCVotings::getMostRated(10) as $nid=>$count) {
					$node = node_load(array("nid"=>$nid));
					$name = $node->title;
					$url = "/".$node->path;
					$bewertenUrl = "";
					$logoUrl = "";
					if ($node->type == "bank") {
						$bewertenUrl = "/node/add/bewertung-bank?itemId=".$node->nid;
						$logoUrl = $node->field_banklogo[0]["filepath"];
					} elseif ($node->type == "productitem") {
						$novaBCProductitem = new NovaBCProductitem($node);
						$bank = $novaBCProductitem->getAnbieter();
						$product = $novaBCProductitem->getProduct();
						$bewertenUrl = "/node/add/bewertung-".strtolower($product->title)."?itemId=".$node->nid;
						$logoUrl = $bank->field_banklogo[0]["filepath"];
					}
					?>
					<li>
						<a href="<?php echo $url; ?>">
							<span class="img">
								<img src="<?php echo $logoUrl; ?>" alt="<?php echo $name; ?>" width="167" height="53">
							</span> <?php echo $name; ?>
						</a>
						<ul class="list-b">
							<li><a href="<?php echo $bewertenUrl; ?>"><i class="icon-star"></i> <span class="tip left">Jetzt bewerten</span></a></li>
							<li><?php echo $count; ?></li>
						</ul>
					</li>
					<?php
				}
				?>
			</ol>
		</div>
		<div>
			<?php
			  echo views_embed_view("new_products", "block");
			?>
		</div>
	</div>
</article>