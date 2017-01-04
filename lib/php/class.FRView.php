<?php
class FRView {
	public static function partnerCenter($node) {
		if($node->type == "partnercenter_subpage") {
			$content = $node->body;
			$node = node_load($node->field_mypartnercenter[0]['nid']);
		}
		echo views_embed_view(
			"PartnerCenterNews",
			"page_1",
			11
		);
		echo views_embed_view(
			"PartnerCenterNews",
			"page_2",
			11
		);

		DebugUtilities::print_rInTextarea(node_load(array(
			"type "=>"partnercenter_news",
			"field_mypartnercenter_for_news"=>11
		)));
		DebugUtilities::print_rInTextarea($node->field_logo);
		echo "<img src='/".$node->field_logo_big[0]["filepath"]."' />";
		echo $node->field_logo->view;
		print theme('links', menu_navigation_links('menu-axa-investment-managers'));
	}
}
?>