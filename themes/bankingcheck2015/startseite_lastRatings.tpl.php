<article class="module-b quote-a">
	<h2>Zuletzt <i class="icon-quote-star"></i> bewertet</h2>
	<div>
		<?php
			foreach(BCVotings::getLastCommentedRatings(10, 30) as $novaBCVoting) {
				$comment = $novaBCVoting->getComment();
				$name = $novaBCVoting->getUsername();
				$date = "";
				?>
				<div>
					<p><q><?php echo $comment; ?></q> <span>Von <span><?php echo $name; ?></span></span></p>
				</div>
				<?php
			}
		?>
	</div>
</article>