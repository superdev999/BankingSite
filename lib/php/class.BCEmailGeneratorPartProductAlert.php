<?php
class BCEmailGeneratorPartProductAlert extends BCEmailGeneratorPartSection {
	private $nid;
	private $productItemNode = null;
	private $bankNode = null;
	public function createHTML($user = null) {
		$bankLink = "BANK";
		$productLink = "PRODUCT";
		$r = BCEmailGenerator::render("p", date("d. F Y")." / ".$bankLink." / ".$productLink, null, "upperLine");
		return $r;
	}
}
?>