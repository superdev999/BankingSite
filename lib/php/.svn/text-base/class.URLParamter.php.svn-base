<?php
class URLParamter {
	public $name;
	public $value;
	public function URLParamter($name, $value="") {
		$this->name = $name;
		$this->value = $value;
	}
	public function render($urlencode=true) {
		if($urlencode) {
			$r = urlencode($this->name);
			if($this->value!="") $r .= "=".urlencode($this->value);
		} else {
			$r = $this->name;
			if($this->value != "") $r .= "=".$this->value;
		}
		return $r;
	}
}

?>