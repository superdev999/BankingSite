<?php
class DOMShortText extends DOMFI {
	/**
	 * The Text-Source
	 *
	 * @var string
	 */
	public $text;
	public static $nodeChar = "+";
	/**
	 * Constructor
	 *
	 * @param string $text
	 * @return DOMShortText
	 */
	public function DOMShortText($text) {
		$this->selection = new DOMSelection($this);
		if(mb_ereg("^<.*>", $text)) {
			$this->loadXML($text);
			$this->text = $this->toText()->text;
		} else {
			$this->text = trim($text);
			$this->toXML();
		}
	}
	/**
	 * Creates an instance of DOMShortText
	 *
	 * @param string $text
	 * @return DOMShortText
	 */
	public static function getInstance($text) {
		$instance = new DOMShortText($text);
		return $instance;
	}
	/**
	 * converts $this->text intto DOM
	 *
	 * @return DOMShortText
	 *
	 */
	public function toXML() {
		$isRoot = true;
		$cLevel = 0;
		foreach(mb_split("\n", $this->text) as $line) {
			//A TAG
			if(mb_ereg("(\++)([a-zA-z,'_']{1}[a-zA-z,'_',0-9]*)", $line, $m)) {
				if($isRoot) {
					$this->loadXML("<".$m[2]."/>");
					$this->currentNode = $this->documentElement;
					$this->nodeStack = array($this->currentNode);
					$isRoot = false;
					$cLevel = 1;
				} else {
					$nextLevel = strlen($m[1]);
					if($nextLevel>$cLevel) {
						for($i=$cLevel;$i<$nextLevel;$i++) {
							$this->within();
						}
						$cLevel = $nextLevel;
					} elseif($nextLevel<$cLevel) {
						for($i=$cLevel;$i>$nextLevel;$i--) {
							$this->endWithin();
						}
						$cLevel = $nextLevel;
					}
					$this->node($m[2]);
				}
			} elseif(mb_ereg("([a-zA-z,'_']{1}[a-zA-z,'_',0-9]*):(.*)",$line, $m)) {
				$this->attribute($m[1], $m[2]);
			} else {
				$this->within()->innerText($line)->endWithin();
			}
		}
		return $this;
	}
	/**
	 * Converts DOM to $this->text
	 *
	 * @return DOMShortText
	 */
	public function toText() {
		$this->text = $this->toTextInner($this->documentElement, 1);
		return $this;
	}
	/**
	 * Helper-Method for converting a descent node into text
	 *
	 * @param DOMElement $node
	 * @return string
	 */
	private function toTextInner(DOMElement $node, $level) {
		$r = array();
		$r[] = str_pad("",$level, "+").$node->nodeName;
		foreach($node->attributes as $att) {
			$r[] = $att->name.":".$att->value;
		}
		foreach($node->childNodes as $child) {
			if(is_a($child, "DOMElement")) {
				$r[] = $this->toTextInner($child, $level+1);
			} elseif(is_a($child, "DOMText") && trim($child->nodeValue)!="") {
				$r[] = $child->nodeValue;
			}
		}
		return implode("\n", $r);
	}
	/**
	 * Save XML into $filename
	 *
	 * @param string $filename
	 * @return DOMShortText
	 */
	public function save($filename) {
		file_put_contents($filename, $this->saveXML());
		return $this;
	}
	/**
	 * Saves the text into $filename
	 *
	 * @param string $filename
	 * @return DOMShortText
	 */
	public function saveText($filename) {
		file_put_contents($filename, $this->saveXML());
		return $this;
	}
}

?>