<?php
class DOMBuilderTraverse extends DOMBuilder {
	/**
	 * @var DOMSelection
	 */
	protected $selection;
	public function DOMBuilderTraverse() {
		$this->selection = new DOMSelection($this);
	}
	/**
	 * @param string $selection
	 * @param DOMNode $context
	 * @return DOMFI
	 */
	public function select($selection, DOMNode $context=NULL) {
		$this->getSelection()->select($selection, $context);
		if($this->selection->length()>0) {
			$this->currentNode = $this->selection->item(0);
		}
		return $this;
	}
	/**
	 * @return DOMSelection
	 */
	public function getSelection() {
		if(is_null($this->selection)) $this->selection = new DOMSelection($this);
		return $this->selection;
	}
	/**
	 * @param string $name
	 * @return DOMFI
	 */
	public function remove() {
		if($this->selection->length()>0) {
			foreach($this->selection as $n) {
				$this->currentNode = $n;
				parent::remove();
			}
		} else parent::remove();
		return $this;
	}
	/**
	 * @param string $name
	 * @return DOMFI
	 */
	public function node($name) {
		if($this->selection->length()>0) {
			foreach($this->selection as $n) {
				$this->currentNode = $this->createElement($name);
				$n->appendChild($this->currentNode);
			}
			$this->selection->clear();
		} else parent::node($name);
		return $this;
	}
	/**
	 * @param string $data
	 * @return DOMFI
	 */
	public function innerText($data, $useCData=false) {
		if($this->selection->length()>0) {
			foreach($this->selection as $n) {
				$this->currentNode = $n;
				parent::innerText($data, $useCData=false);
			}
		} else parent::innerText($data, $useCData=false);
		return $this;
	}
	/**
	 * @param string $name
	 * @param string $value
	 * @return DOMFI
	 */
	public function attribute($name, $value) {
		if($this->selection->length()>0) {
			foreach($this->selection as $n) {
				$this->currentNode = $n;
				parent::attribute($name, $value);
			}
		} else parent::attribute($name, $value);
		return $this;
	}
	/**
	 * @param string $data
	 * @return DOMFI
	 */
	public function comment($data) {
		if($this->selection->length()>0) {
			foreach($this->selection as $n) {
				$this->currentNode = $n;
				parent::comment($name, $value);
			}
		} else parent::comment($name, $value);
		return $this;
	}
	/**
	 * @param string $target
	 * @param string $data
	 * @return DOMFI
	 */
	public function processingInstruction($target, $data="") {
		if($this->selection->length()>0) {
			foreach($this->selection as $n) {
				$this->currentNode = $n;
				parent::processingInstruction($target, $data="");
			}
		} else parent::processingInstruction($target, $data="");
		return $this;
	}
	public function innerXML($xpath="/*", $contextNode=null) {
		$this->select($xpath, $contextNode);
		foreach($this->selection as $n) {
			$r .= parent::innerXML($n);
		}
		return $r;
	}
	/**
	 * @param string $xml
	 * @return DOMFI
	 */
	public function xml($xml) {
		if($this->selection->length()>0) {
			foreach($this->selection as $n) {
				$this->currentNode = $n;
				parent::xml(DOMFI::getInstance($xml)->innerXML());
			}
		} else parent::xml(DOMFI::getInstance($xml)->innerXML());
		return $this;
	}
}