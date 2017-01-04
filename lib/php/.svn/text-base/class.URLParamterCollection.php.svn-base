<?php
class URLParamterCollection extends Collection {
	/**
	 * @return URLParamter
	 */
	public function current() {
		return parent::current();
	}
	/**
	 * @param int $index
	 * @return URLParamter
	 */
	public function item($index) {
		return parent::item($index);
	}
	/**
	 * @param URLParamter $item
	 */
	public function addItem(URLParamter $item) {
		parent::addItem($item);
	}
	/**
	 * Sets a key/value pair
	 *
	 * @param string $name
	 * @param string $value
	 * @return URLParamterCollection
	 */
	public function setParameterByNameValue($name, $value="") {
		$this->setParameter(new URLParamter($name, $value));
		return $this;
	}
	public function setParameter(URLParamter $param) {
		try {
			$lookup = $this->getItemByProperty("name","^$param->name$");
			$lookup->value = $param->value;
		} catch(Exception $e) {
			$this->addItem($param);
		}
	}
	public function insertItemsByQueryString($queryString, $clear = true) {
		if($clear) $this->clear();
		foreach(preg_split("/&(amp;)?/", $queryString) as $param) {
			$parts = explode("=", $param);
			$this->addItem(new URLParamter($parts[0], urldecode($parts[1])));
		}
	}
	public function getQueryString($urlencode=true, $entityAmp=true) {
		$retPars = array();
		$this->rewind();
		while($this->valid()) {
			$retPars[] = $this->current()->render($urlencode);
			$this->next();
		}
		if($entityAmp) return implode("&amp;", $retPars);
		else return implode("&", $retPars);
	}
	public static function byQueryString($queryString) {
		$r = new URLParamterCollection();
		$r->insertItemsByQueryString($queryString);
		return $r;
	}
	public function removeParameterByName($name) {
		try {
			$lookup = $this->getItemByProperty("name","^$name$");
			$this->removeItemByItem($lookup);
		} catch(Exception $e) {
		}
	}
	public function getParameterValueByName($name) {
		try {
			$lookup = $this->getItemByProperty("name","^$name$");
			return $lookup->value;
		} catch(Exception $e) {
			return "";
		}
	}
	public function paramExists($name) {
		try {
			$this->getItemByProperty("name","^$name$");
			return true;
		} catch (Exception $e) {
			return false;
		}
	}
	public function addItemsByArray(array $a) {
		foreach($a as $name=>$value) {
			$this->setParameter(new URLParamter($name, $value));
		}
	}
	public function toArray() {
		$r = array();
		$this->rewind();
		while($this->valid()) {
			$r[$this->current()->name] = $this->current()->value;
			$this->next();
		}
		return $r;
	}
}

?>