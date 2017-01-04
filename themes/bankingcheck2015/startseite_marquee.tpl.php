<ul class="gallery-b">
	<?php
	# load all productitems with their ratings
	$votingsIndex = BCVotings::getIndex();
	#dpm($votingsIndex);
	# load all productitems with their banken
	$query = db_query("SELECT * FROM `node` JOIN content_type_productitem USING(nid) WHERE type='productitem'");
	$productItemArray = array();
	while ($result = db_fetch_array($query)) {
		$productItemArray[] = $result;
	}
	#dpm($productItemArray);
	# load all banken with their logos
	$query = db_query("SELECT nid, filepath, field_banklogo_data FROM `content_type_bank` JOIN `files` ON `content_type_bank`.`field_banklogo_fid` = `files`.`fid`");
	$bankenArray = array();
	while ($result = db_fetch_array($query)) {
		$data = unserialize($result["field_banklogo_data"]);
		$bankenArray[$result["nid"]] = array("filepath" => $result["filepath"], "width" => $data["width"], "height" => $data["height"]);
	}
	#dpm($bankenArray);

	foreach ($productItemArray as $key => $productItem) {
		$file = $bankenArray[$productItem["field_proditemmybank_nid"]];
		$nodepath = "/node/".$productItem["nid"];
		$title = $productItem["title"];
		$rating = number_format($votingsIndex[$productItem["nid"]]["average"], 1);
		echo '<li><img src="'.$file["filepath"].'" alt="" width="'.$file["width"].'" height="'.$file["height"].'"> <a href="'.$nodepath.'">'.$title.'<span class="rating-a" data-max="5">'.$rating.'</span></a></li>';
	}
	?>
</ul>