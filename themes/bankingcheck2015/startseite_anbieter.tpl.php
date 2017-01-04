<?php
	// reuse $bankenArray from startseite_marquee.tpl.php
	// select 24 random banken
	$randomSelectedKeys = array_rand($bankenArray, 24);
?>
<article class="module-b wide">
	<h2 class="header-a scheme-e">Anbieter</h2>
	<ul class="gallery-c">
		<?php
			foreach ($randomSelectedKeys as $nid => $value) {
				$file = $bankenArray[$value];
				$link = "/node/".$value;
				?>
				<li><a href="<?=$link; ?>"><img src="/<?=$file["filepath"]; ?>" alt="Logo" width="<?=$file["width"]; ?>" height="<?=$file["height"]; ?>"></a></li>
				<?php
			}
		?>
	</ul>
</article>
<p class="link-b a scheme-e"><a href="./">Alle Anbieter</a></p>