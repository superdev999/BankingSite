<?php
/**
 * Abstraction of array for serveral uses
 */
class Collection implements Iterator {
	/**
	 * Internal (numeric!) array of items any kind
	 *
	 * @var array
	 */
	protected $items = array();
	/**
	 * Internal pointer
	 *
	 * @var int
	 */
	protected $index = 0;
	/**
	 * @return array
	 */
	public function getItems() {
		return $this->items;
	}
	/**
	 * @param array $items
	 */
	public function Collection(array $items=array()) {
		if($items != "") $this->items = $items;
	}
	/**
	 * add item at the end of the queue
	 *
	 * @param mixed $item
	 * @return void
	 */
	public function addItem($item) {
		$this->items[] = $item;
		return $item;
	}
	/**
	 * goes through the items and removes the given one
	 *
	 * @param mixed $item
	 * @return void
	 */
	public function removeItemByItem($item) {
		$newItems = array();
		$this->rewind();
		while($this->valid()) {
			if($this->current() !== $item) $newItems[] = $this->current();
			$this->next();
		}
		$this->items = $newItems;
	}
	/**
	 * removes item by his index
	 *
	 * @param mixed $index
	 * @return void
	 */
	public function removeItem($index) {
		$this->removeItemByItem($this->item($index));
	}
	/**
	 * @param int $id
	 * @return void
	 */
	public function getItemById($id) {
		return $this->items[$id];
	}
	/**
	 * @return mixed
	 */
	public function current() {
		return $this->items[$this->index];
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
		return $this->index<count($this->items);
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
		return count($this->items);
	}
	/**
	 * gets item by index
	 *
	 * @param int $index
	 * @return mixed
	 */
	public function item($index) {
		return $this->items[$index];
	}
	/**
	 * gets a random item
	 *
	 * @return mixed
	 */
	public function random() {
		$rand = rand(0, $this->length()-1);
		return $this->item($rand);
	}
	public function reverse() {
		$this->items = array_reverse($this->items);
	}
	/**
	 * returns items as array
	 *
	 * @return array
	 */
	public function toArray() {
		return $this->items;
	}
	public function getPosition($item) {
		for($i=0;$i<$this->length();$i++) {
			if($item == $this->item($i)) return $i;
		}
		return -1;
	}
	public function hastNextSibling($item) {
		return $this->getPosition($item) < ($this->length()-1);
	}
	/**
	 * Returns get_object_vars() of items as 2 dim-array
	 *
	 * @return array
	 */
	public function toMatrix() {
		$r = array();
		$this->rewind();
		while($this->valid()) {
			$c = $this->current();
			$line = array();
			foreach(get_object_vars($c) as $k=>$v) {
				if(is_object($v)) {
					if(method_exists($v, "toString")) {
						$v = $v->toString();
					} else {
						$v = serialize($v);
					}
				} elseif(is_array($v)) {
					$v = implode(",", $v);
				}
				$line[$k] = $v;
			}
			$r[] = $line;
			$this->next();
		}
		return $r;
	}
	/**
	 * shuffles items
	 *
	 * @return void
	 */
	public function shuffle() {
		$copyOfKeys = array_keys($this->items);
		shuffle($copyOfKeys);
		$shuffledItems = array();
		$this->rewind();
		$i=0;
		while($this->valid()) {
			$shuffledItems[] = $this->item($copyOfKeys[$i]);
			$this->next();
			$i++;
		}
		$this->items = $shuffledItems;
	}
	/**
	 * @param string $propertyName
	 * @param string $regEx
	 * @param boolean $v
	 * @return Collection
	 */
	public function grepItemProperty($propertyName, $regEx, $v=false) {
		$r = new Collection();
		$this->rewind();
		while($this->valid()) {
			$res = ereg($regEx, $this->current()->$propertyName);
			if($v) $res = !$res;
			if($res) {
				$r->addItem($this->current());
			}
			$this->next();
		}
		return $r;
	}
	/**
	 * Finds first item matches to Regex
	 *
	 * @param string $propertyName
	 * @param string $regEx
	 * @param boolean $v
	 * @return mixed
	 */
	public function getItemByProperty($propertyName, $regEx, $v=false) {
		$this->rewind();
		while($this->valid()) {
			$res = ereg($regEx, $this->current()->$propertyName);
			if($v) $res = !$res;
			if($res) {
				return $this->current();
			}
			$this->next();
		}
		throw new Exception("There is no item with $propertyName match to $regEx");
	}
	/**
	 * finds all Items matching to regex
	 *
	 * @param string $propertyName
	 * @param string $regEx
	 * @param boolean $v
	 * @return Collection
	 */
	public function getItemsByProperty($propertyName, $regEx, $v=false) {
		$r = new Collection();
		$this->rewind();
		while($this->valid()) {
			$res = ereg($regEx, $this->current()->$propertyName);
			if($v) $res = !$res;
			if($res) {
				$r->addItem($this->current());
			}
			$this->next();
		}
		return $r;
	}
	/**
	 * finds first item where the properties matches to a set of regexes
	 *
	 * @param array $propertyNameRegex
	 * @param boolean $v
	 * @param string $concatOperator
	 * @return mixed
	 */
	public function getItemByProperties(array $propertyNameRegex, $v=false, $concatOperator="and") {
		$res = $this->getItemsByProperties($propertyNameRegex, $v, $concatOperator);
		if($res->length()==0) throw new Exception("Nothing matched to that");
		else return $res->item(0);
	}
	/**
	 * finds all items where the properties matches to a set of regexes
	 *
	 * @param array $propertyNameRegex
	 * @param boolean $v
	 * @param string $concatOperator
	 * @return Collection
	 */
	public function getItemsByProperties(array $propertyNameRegex, $v=false, $concatOperator="and") {
		$r = new Collection();
		$this->rewind();
		while($this->valid()) {
			$res = true;
			foreach($propertyNameRegex as $propertyName=>$regex) {
				switch($concatOperator) {
					case "and":
						$res = $res && ereg($regex, $this->current()->$propertyName);
						break;
					case "or":
						$res = $res && ereg($regex, $this->current()->$propertyName);
						break;
					default:
						throw new Exception("concatOperator $concatOperator isn't implemented yet");
				}
			}
			if($v) $res = !$res;
			if($res) {
				$r->addItem($this->current());
			}
			$this->next();
		}
		return $r;
	}
	/**
	 * @param string $value
	 * @param string $regEx
	 * @param boolean $v
	 * @return Collection
	 */
	public function grepItemValue($value, $regEx, $v=false) {
		$r = new Collection();
		$this->rewind();
		while($this->valid()) {
			$res = ereg($regEx, $this->current());
			if($v) $res = !$res;
			if($res) {
				$r->addItem($this->current());
			}
			$this->next();
		}
		return $r;
	}
	/**
	 * @param string $property
	 * @return GroupedCollection
	 */
	public function groupItemsByProperty($property) {
		$r = new GroupedCollection(false, $property);
		$this->rewind();
		while($this->valid()) {
			$r->addItem($this->current());
			$this->next();
		}
		return $r;
	}
	/**
	 * @return GroupedCollection
	 */
	public function groupItemsByValue() {
		$r = new GroupedCollection(true);
		$this->rewind();
		while($this->valid()) {
			$r->addItem($this->current());
			$this->next();
		}
		return $r;
	}
	/**
	 * gets the last item
	 *
	 * @return mixed
	 */
	public function last() {
		return $this->item($this->length()-1);
	}
	/**
	 * gets the index
	 *
	 * @param mixed $item
	 * @return int
	 */
	public function getIndex($item) {
		$this->rewind();
		while($this->valid()) {
			if($this->current() === $item) return $this->key();
			$this->next();
		}
		throw new Exception("Can't find $item");
	}
	/**
	 * Replces item by item
	 *
	 * @param mixed $oldItem
	 * @param mixed $newItem
	 * @return void
	 */
	public function replaceItem($oldItem, $newItem) {
		$this->rewind();
		while($this->valid()) {
			if($this->current() === $oldItem) {
				$this->items[$this->key()] = $newItem;
				return;
			}
			$this->next();
		}
		throw new Exception("Can't find $item");
	}
	/**
	 * inserts item at $index-position
	 *
	 * @param int $index
	 * @param mixed $item
	 * @return void
	 */
	public function insertAt($index, $item) {
		$newItems = array();
		if($index >= $this->length()) throw new Exception("Out of Range $index");
		else {
			$this->rewind();
			while($this->valid()) {
				if($this->key() == $index) {
					$newItems[] = $item;
				}
				$newItems[] = $this->current();
				$this->next();
			}
		}
		$this->items = $newItems;
	}
	/**
	 * Adds item if is not part of the Collection
	 *
	 * @param mixed $item
	 * @return void
	 */
	public function addItemIfNotExits($item) {
		if(!$this->itemExits($item)) $this->addItem($item);
	}
	/**
	 * Adds item if is not part of the Collection
	 *
	 * @param string $propertyName
	 * @param string $regEx
	 * @param boolean $v
	 * @return void
	 */
	public function addItemIfNotExitsByProperty($item, $propertyName, $regEx, $v=false) {
		try {
			$r = $this->getItemByProperty($propertyName, $regEx, $v);
		} catch(Exception $e) {
			$this->addItem($item);
		}
	}
	/**
	 * Checks if item exits
	 *
	 * @param string $propertyName
	 * @param string $regEx
	 * @param boolean $v
	 * @return boolean
	 */
	public function itemExitsByProperty($propertyName, $regEx, $v=false) {
		try {
			$this->getItemByProperty($propertyName, $regEx);
			return true;
		} catch(Exception $e) {
			return false;
		}
	}
	/**
	 * Checks if item exits
	 *
	 * @param mixed $item
	 * @return boolean
	 */
	public function itemExits($item) {
		$this->rewind();
		while($this->valid()) {
			if($this->current() === $item) return true;
			$this->next();
		}
		return false;
	}
	/**
	 * @param mixed $propterty
	 * @return array
	 */
	public function getPropertyValues($propterty) {
		$r = array();
		$this->rewind();
		while($this->valid()) {
			$r[] = $this->current()->$propterty;
			$this->next();
		}
		return $r;
	}
	/**
	 * @param string $itemTag
	 * @param string $rootTag
	 * @param string $type
	 * @param string $lineTag
	 * @param boolean $returnAsDoc
	 * @return mixed
	 */
	public function toXML($itemTag="item", $rootTag="root", $type="attributes", $lineTag="line", $returnAsDoc=false) {
		//@todo use XMLBuilder, wicht exactly do the same thing, as shown in SSearchHitCollection
		$doc = DOMDocument::loadXML("<$rootTag/>");
		$docEl = $doc->documentElement;
		switch($type) {
			case "attributes":
				$this->rewind();
				while($this->valid()) {
					$item = $doc->createElement($itemTag);
					$this->valueToXML($item, $this->current());
					$docEl->appendChild($item);
					$this->next();
				}
				break;
			case "tags":
				$this->rewind();
				while($this->valid()) {
					$item = $doc->createElement($itemTag);
					$docEl->appendChild($item);
					$this->valueToXML($item, $this->current(),"","tags");
					$this->next();
				}
				break;
			default:
				die("Not implemented Type $type");
				break;
		}
		if($returnAsDoc) return $doc;
		else return $doc->saveXML();
	}

	/**
	 * @param mixed $item
	 * @param mixed $object
	 * @param string $key
	 * @param string $type
	 * @return void
	 */
	protected function valueToXML($item, $object, $key = "", $type="attributes") {
		switch($type) {
			case "attributes":
					if(is_object($object)) {
						foreach(get_object_vars($object) as $k=>$v) {
							$this->valueToXML($item, $v, $k, "attributes");
						}
					} elseif(is_array($object)) {
						$item->setAttribute($key, implode(" ", $object));
					} else {
						$item->setAttribute($key, $object);
					}
				break;
			case "tags":
					if(is_object($object)) {
						foreach(get_object_vars($object) as $k=>$v) {
							$this->valueToXML($item, $v, $k, "tags");
						}
					} elseif(is_array($object)) {
						/* @todo this will not work if array values are objects. */
						$entry = $item->ownerDocument->createElement($key, implode(" ", $object));
						$item->appendChild($entry);
					} else {
						$entry = $item->ownerDocument->createElement($key, $object);
						$item->appendChild($entry);
					}
				break;
			default:
				die("Not implemented Type $type");
		}
	}
	/**
	 * @return void
	 */
	public function clear() {
		$this->items = array();
	}
	/**
	 * Moves an item after another one
	 *
	 * @param int $index
	 * @param int $afterIndex
	 * @return void
	 */
	public function moveItem($index, $afterIndex) {
		$newItems = array();
		$this->rewind();
		while($this->valid()) {
			if($this->key() != $index) $newItems[] = $this->current();
			if($this->key() == $afterIndex) $newItems[] = $this->item($index);
			$this->next();
		}
		$this->items = $newItems;
	}
	public function reArrange(array $indexes) {
		$newOrder = array();
		$i=0;
		foreach($indexes as $ind) {
			$newOrder[$i] = $this->item($ind);
			$i++;
		}
		for($j=$i;$j<$this->length();$j++) {
			$newOrder[$j] = $this->item($j);
		}
		$this->items = $newOrder;
	}
	/**
	 * Sorts the collection
	 *
	 * @param input $arr
	 * @param string $orderCol
	 * @param string $orderDir ASC|DESC
	 * @param boolean $ignoreCase
	 * @param string $orderMode int|*
	 * @return void
	 */
	public function sort($propertyName, $orderDir="ASC", $ignoreCase=false, $flags = SORT_REGULAR) {
		$sortArray = array();
		$this->rewind();
		while($this->valid()) {
			if($ignoreCase) {
				$sortArray[] = strtolower($this->current()->$propertyName);
			} else $sortArray[] = $this->current()->$propertyName;
			$this->next();
		}

		if($orderDir == "ASC") asort($sortArray, $flags);
		else arsort($sortArray, $flags);
		$this->reArrange(array_keys($sortArray));
	}
	/**
	 *
	 * @param int $from
	 * @param int $to
	 * @return Collection
	 */
	public function cut($from, $to=NULL) {
		$r = new Collection();
		$newItems = array();
		if(is_null($to)) $to = $this->length();
		$i=0;
		$this->rewind();
		while($this->valid()) {
			if($i>=$from && $i<$to) $r->addItem($this->current());
			else $newItems[] = $this->current();
			$this->next();
			$i++;
		}
		$this->items = $newItems;
		return $r;
	}
	public function createHash($indexProperty, $valueProptery) {
		$r = array();
		$this->rewind();
		while($this->valid()) {
			$r[$this->current()->$indexProperty] = $this->current()->$valueProptery;
			$this->next();
		}
		return $r;
	}
	public function removeItemsByProperty(Collection $r, $propertyName, $regEx, $v=false) {
		$this->rewind();
		while($this->valid()) {
			$res = ereg($regEx, $this->current()->$propertyName);
			if($v) $res = !$res;
			if(!$res) {
				$r->addItem($this->current());
			}
			$this->next();
		}
		return $r;
	}
}
?>