<?php
abstract class BCEmailGeneratorPart {
	/**
	 * Subparts
	 *
	 * @var BCEmailGeneratorPartCollection
	 */
	public $parts = null;
	/**
	 * Enter description here...
	 *
	 * @return BCEmailGeneratorPart
	 */
	public function BCEmailGeneratorPart() {
		$this->parts = new BCEmailGeneratorPartCollection();
	}
	abstract public function createHTML($user = null);
	/**
	 * Add the part and returns itself (Floating Interfaces)
	 *
	 * @param BCEmailGeneratorPart $part
	 * @return BCEmailGeneratorPart
	 */
	public function addPart(BCEmailGeneratorPart $part) {
		if(is_null($this->parts)) {
			throw new Exception("Non objec");
		}
		$this->parts->addItem($part);
		return $this;
	}
	/**
	 * Add the part and returns the part (Floating Interfaces)
	 *
	 * @param BCEmailGeneratorPart $part
	 * @return BCEmailGeneratorPart
	 */
	public function addPartGoInto(BCEmailGeneratorPart $part) {
		$this->parts->addItem($part);
		return $part;
	}
	public function createSubpartsHTML($user = null) {
		$r = "";
		$this->parts->rewind();
		while($this->parts->valid()) {
			$r .= $this->parts->current()->createHTML($user);
			$this->parts->next();
		}
		return $r;
	}
}
?>