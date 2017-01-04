<?php
class DOMBuilder extends DOMDocument {
	/**
	 * @var array
	 */
	protected $nodeStack = array();
	/**
	 * @var bool
	 */
	protected $nextNodeWithin;
	/**
	 * @var DOMNode
	 */
	public $currentNode;
	/**
	 * @return DOMBuilder
	 */
	public function DOMBuilder() {
		parent::__construct();
	}
	public function innerXML(DOMNode $contextNode=NULL) {
		if(is_null($contextNode)) $contextNode = $this->documentElement;
		return $this->saveXML($contextNode);
	}
	/**
	 * @return DOMBuilder
	 */
	public function within() {
		$this->nextNodeWithin = true;
		return $this;
	}
	/**
	 * @return DOMBuilder
	 */
	public function endWithin() {
		if($this->nextNodeWithin) $this->nextNodeWithin = false;
		else array_pop($this->nodeStack);
		return $this;
	}
	/**
	 * @param string $version
	 * @param string $encoding
	 * @param string $standalone
	 * @return DOMBuilder
	 */
	public function xmlDeclaration($version="1.0", $encoding="utf-8", $standalone=true) {
		$this->version = $version;
		$this->encoding = $encoding;
		$this->standalone = $standalone;
	}
	/**
	 * @param string $name
	 * @return DOMBuilder
	 */
	public function node($name) {
		try {
			$node = $this->createElement($name);
		} catch(Exception $e) {
			$node = $this->createElement("element");
			$node->setAttribute("name", $name);
		}
		if(count($this->nodeStack)==0) {
			$this->appendChild($node);
			array_push($this->nodeStack, $node);
		} else {
			if($this->nextNodeWithin) {
				array_push($this->nodeStack, $this->currentNode);
				$this->nextNodeWithin = false;
			}
			if((count($this->nodeStack)-1)==0) array_push($this->nodeStack, $this->documentElement);
			$this->nodeStack[count($this->nodeStack)-1]->appendChild($node);
		}
		$this->currentNode = $node;
		return $this;
	}
	/**
	 * @param string $data
	 * @return DOMBuilder
	 */
	public function innerText($data, $useCData=false) {
		if($useCData) {
			$this->currentNode->appendChild($this->createCDATASection($data));
		} else {
			$this->currentNode->appendChild($this->createTextNode($data));
		}
		return $this;
	}
	/**
	 * @param string $name
	 * @param string $value
	 * @return DOMBuilder
	 */
	public function attribute($name, $value) {
		$this->currentNode->setAttribute($name, $value);
		return $this;
	}
	/**
	 * @param string $data
	 * @return DOMBuilder
	 */
	public function comment($data) {
		$this->currentNode->appendChild($this->createComment($data));
		return $this;
	}
	/**
	 * @param string $target
	 * @param string $data
	 * @return DOMBuilder
	 */
	public function processingInstruction($target, $data="") {
		$this->currentNode->appendChild($this->createProcessingInstruction($target, $data));
		return $this;
	}
	/**
	 * @param string $data
	 * @return DOMBuilder
	 */
	public function cdata($data="") {
		$this->currentNode->appendChild($this->createCDATASection($data));
		return $this;
	}
	/**
	 * @param string $xml
	 * @return DOMBuilder
	 */
	public function xml($xml) {
		$this->currentNode->appendChild($this->getDocumentFragment($xml));
		return $this;
	}
	/**
	 * removes current node
	 *
	 * @return DOMNode
	 */
	public function remove() {
		$toRemove = $this->currentNode;
		switch($toRemove->nodeType) {
			case 1:
				$this->currentNode = $this->currentNode->parentNode;
				$this->currentNode->removeChild($toRemove);
				break;
			case 2:
				$p = $this->currentNode->parentNode;
				$p->removeAttribute($this->currentNode->name);
				break;
			default:
				throw new Exception("not implemented node-type ".$toRemove->nodeType);
				break;
		}
		return $this;
	}
	protected function getDocumentFragment($xml) {
		if(strlen(trim($xml))==0) return;
		$frag = $this->createDocumentFragment();
		ob_start();
		$frag->appendXML(preg_replace("/<\?xml .*?\?>/is","", $xml));
		$res = ob_get_clean();
		if($res != "") {
			$frag->appendChild($this->createCDATASection($xml));
		}
		return $frag;
	}
}
?>
