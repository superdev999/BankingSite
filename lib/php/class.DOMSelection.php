<?php
class DOMSelection implements Iterator {
	/**
	 * @var DOMNodeList
	 */
	protected $items;
	/**
	 * @var int
	 */
	protected $index = 0;
	/**
	 * @var DOMDocument
	 */
	protected $doc;
	/**
	 * @var DOMXPath
	 */
	protected $xpath = NULL;
	/**
	 * @param DOMDocument $document
	 */
	public function DOMSelection(DOMDocument $document) {
		$this->doc = $document;
	}
	/**
	 * @return DOMNode
	 */
	public function current() {
		return $this->items->item($this->index);
	}
	/**
	 * @return boolean
	 */
	public function next() {
		$this->index++;
		return $this->valid();
	}
	/**
	 * Returns the current key
	 *
	 * @return int
	 */
	public function key() {
		return $this->index;
	}
	/**
	 * @return boolean
	 */
	public function valid() {
		return $this->index<$this->items->length;
	}
	/**
	 * @return void
	 */
	public function rewind() {
		$this->index = 0;
	}
	/**
	 * gets the count of items
	 *
	 * @return int
	 */
	public function length() {
		return $this->items->length;
	}
	/**
	 * gets item by index
	 *
	 * @param int $index
	 * @return DOMNode
	 */
	public function item($index) {
		return $this->items->item($index);
	}
	/**
	 * @return void
	 */
	public function clear() {
		$this->items = array();
	}
		/**
	 * @param string $selection
	 * @param DOMNode $context
	 * @return DOMSelection
	 */
	public function select($selection, DOMNode $context=NULL) {
		if($this->isXpath($selection)) $this->selectByXpath($selection, $context);
		else $this->selectByCSS($selection);
		return $this;
	}
	/**
	 * @param string $selection
	 * @return bool
	 */
	protected function isXpath($selection) {
		return true;
	}
	/**
	 * @param string $selection
	 * @return void
	 */
	protected function selectByCSS($selection) {
	}
	/**
	 * @param string $selection
	 * @param DOMNode $context
	 * @return void
	 */
	protected function selectByXpath($selection, DOMNode $context=NULL) {
		if(is_null($context)) $this->items = $this->getXPath()->query($selection);
		else $this->items = $this->getXPath()->query($selection, $context);
	}
	/**
	 * @return DOMXPath
	 */
	protected function getXPath() {
		if(is_null($this->xpath)) $this->xpath = new DOMXPath($this->doc);
		return $this->xpath;
	}
	/**
	 * @return DOMDocument
	 */
	public function getDoc() {
		return $this->doc;
	}
	/**
	 * @return mixed
	 */
	public function value() {
		if($this->items->length == 0) return null;
		elseif($this->items->length == 1) return $this->getValueFromItem($this->item(0));
		else {
			$r = array();
			$this->rewind();
			while($this->valid()) {
				$r[] = $this->getValueFromItem($this->current());
				$this->next();
			}
			return $r;
		}
	}
	/**
	 * @return mixed
	 */
	public function firstValue() {
		if($this->items->length == 0) return null;
		else return $this->getValueFromItem($this->item(0));
	}
	/**
	 * Returns value from Result of XPath-Query
	 * @param mixed node
	 * @return string
	 * @author Dominik Fettel <fettel@navigate.de>
	 */
	protected function getValueFromItem($node) {
		if(is_a($node, "DOMAttr")) return $node->value;
		elseif(is_a($node, "DOMText")) return $node->wholeText;
		elseif(is_a($node, "DOMNode")) {
			return $node->nodeValue;
		}	else {
			throw new Exception(get_class($node)." is not supported as result by now");
			return false;
		}
	}
	public function setValue($value) {
		if($this->items->length == 0) return;
		elseif($this->items->length == 1) return $this->setValueInItem($this->item(0), $value);
		else {
			$r = array();
			$this->rewind();
			while($this->valid()) {
				$r[] = $this->setValueInItem($this->current(), $value);
				$this->next();
			}
			return $r;
		}
	}
	/**
	 *
	 * @param int $from
	 * @param int $to
	 * @return DOMSelection
	 */
	public function slice($from, $to=NULL) {
		$r = new DOMSelection($this->doc);
		if(is_null($to)) $to = $this->length();
		$i=0;
		$this->rewind();
		while($this->valid()) {
			if($i>=$from && $i<$to) $r->addItem($this->current());
			$this->next();
			$i++;
		}
		return $r;
	}
	/**
	 * Sets value in node depending on it's type
	 *
	 * @param mixed node
	 * @return string
	 * @author Dominik Fettel <fettel@navigate.de>
	 */
	protected function setValueInItem($node, $value) {
		if(is_a($node, "DOMAttr")) $node->value = $value;
		elseif(is_a($node, "DOMText")) $node->data = $value;
		elseif(is_a($node, "DOMNode")) {
			if(ereg("<.*>", $value)) {
				$node->nodeValue = "";
				$frag = $this->getDoc()->createDocumentFragment();
				$frag->appendXML($value);
				$node->appendChild($frag);
			} else $node->nodeValue = $value;
		}	else {
			throw new Exception(get_class($node)." is not supported as result by now");
		}
	}
}
?>