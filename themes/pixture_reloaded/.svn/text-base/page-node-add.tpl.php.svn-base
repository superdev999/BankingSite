<?php
include "page.tpl.php";
if(ereg("^bewertung-", arg(2))) {
	if(ereg("^bewertung-bank", arg(2))) {
		$ratedNode = node_load(array("nid" => $_GET["itemId"]));
		echo "<script>\$jq(document).ready(function(){\$jq('form#node-form>div>div>div>fieldset:first>legend').html(\$jq('form#node-form>div>div>div>fieldset:first>legend').html().substring(0, \$jq('form#node-form>div>div>div>fieldset:first>legend').html().length-1) + \": <a href='/".$ratedNode->path."'>".$ratedNode->title."</a>\");\$jq('#edit-field-bank-bankitem-0-nid-nid-wrapper').hide();});</script>";
	} else {
		$ratedNode = node_load(array("nid" => $_GET["itemId"]));
		$bankNode = node_load(array("nid" => $ratedNode->field_proditemmybank[0]["nid"]));
		echo "<script>\$jq(document).ready(function(){\$jq('form#node-form>div>div>div>fieldset:first>legend').html(\$jq('form#node-form>div>div>div>fieldset:first>legend').html().substring(0, \$jq('form#node-form>div>div>div>fieldset:first>legend').html().length-1) + \": <a href='/".$bankNode->path."'>".$bankNode->title."</a> - <a href='/".$ratedNode->path."'>".$ratedNode->title."</a>\")});</script>";
	}
}
?>