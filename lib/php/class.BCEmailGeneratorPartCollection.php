<?php
class BCEmailGeneratorPartCollection extends Collection {
	/**
	 * @return BCEmailGeneratorPart
	 */
	public function current() {
		return parent::current();
	}
	/**
	 * @param int $index
	 * @return BCEmailGeneratorPart
	 */
	public function item($index) {
		return parent::item($index);
	}
	/**
	 * @param BCEmailGeneratorPart $item
	 */
	public function addItem(BCEmailGeneratorPart $item) {
		parent::addItem($item);
	}

}
?>