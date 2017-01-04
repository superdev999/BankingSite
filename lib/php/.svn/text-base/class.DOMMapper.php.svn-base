<?php
class DOMMapper extends DOMBuilder {
	/**
	 * @var string
	 */
	protected $defaultItemName = "item";
	/**
	 * @param mixed $input
	 * @return DOMMapper
	 */
	public static function map($input) {
		$i = new DOMMapper();
		return $i->toXML($input);
	}
	/**
	 * @param mixed $input
	 * @return string
	 */
	public static function toString($input) {
		$i = new DOMMapper();
		return $i->toXML($input)->saveXML();
	}
	/**
	 * @param mixed $input
	 * @return DOMMapper
	 */
	public function toXML($input) {
		if($this->isObject($input)) {
			if(method_exists($input, "toXML") && false) {
				$this->xml($input->toXML());
			} else {
				$r = $this->node(get_class($input))->within();
				foreach(get_object_vars($input) as $name=>$value) {
					$this->node($name)->within()->toXML($value)->endWithin();
				}
				$this->endWithin();
			}
		} elseif($this->isTraversable($input)) {
			if(is_null($this->documentElement)) $this->node($this->defaultItemName)->within();
			foreach($input as $key=>$item) {
				if(ereg("^[a-z,A-Z]+", $key)) $this->node($key)->within()->toXML($item)->endWithin();
				else $this->node($this->defaultItemName)->within()->toXML($item)->endWithin();
			}
		} elseif(ereg("^<.*>$", $input)) {
			$this->xml($input);
		} else $this->innerText($input, $this->isCData($value));
		return $this;
	}
	/**
	 * @return DOMMapper
	 */
	public function node($tagname) {
		return parent::node($this->normalizeTagName($tagname));
	}
	/**
	 * @param  $input
	 * @return string
	 */
	protected function normalizeTagName($input) {
		return $input;
	}
	/**
	 * @param  $input
	 * @return bool
	 */
	protected function isObject($input) {
		return is_object($input);
	}
	/**
	 * @param  $input
	 * @return bool
	 */
	protected function isTraversable($input) {
		return is_array($input);
	}
	/**
	 * @param  $input
	 * @return bool
	 */
	protected function isCData($input) {
		return false;
	}

}
?>