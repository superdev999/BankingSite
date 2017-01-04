<?php
class URLDirStyleParam extends URL {
	/**
	 * 'directories'
	 *
	 * @var unknown_type
	 */
	protected $args = array();
	/**
	 * A key that is used, if the URL alreday has parameters
	 *
	 * @var unknown_type
	 */
	public $key = "d";
	/**
	 * True, if args where found in URL
	 *
	 * @var unknown_type
	 */
	protected $isInUrl = false;
	/**
	 * Constructor
	 *
	 * @param string $url
	 * @return URLDirStyleParam
	 */
	public function URLDirStyleParam($url) {
		parent::URL($url);
		if(ereg("^/(.*)/$", $this->query, $m)) {
			$this->args = explode("/", $m[1]);
			$this->isInUrl = true;
		} elseif($this->query != "") {
			$res = $this->getParameters()->getParameterValueByName($this->key);
			if(ereg("^/(.*)/$", $res, $m)) {
				$this->args = explode("/", $m[1]);
			}
		}
	}
	/**
	 * creates a instance of the current request
	 *
	 * @return URLDirStyleParam
	 */
	public static function currentURL() {
		if($_SERVER["HTTPS"]=="on") $prot = "https";
		else $prot = "http";
		$r = new URLDirStyleParam("$prot://".$_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"]);
		return $r;
	}
	/**
	 * @param array $args
	 */
	public function setArgs(array $args) {
		$this->args = $args;
	}
	/**
	 * @param int $index
	 * @param string $value
	 */
	public function setArg($index, $value) {
		$this->args[$index] = $value;
	}
	/**
	 * @return array
	 */
	public function getArgs() {
		return $this->args;
	}
	/**
	 * @param int $index
	 * @return string
	 */
	public function getArg($index) {
		return $this->args[$index];
	}
	/**
	 * Returns an associative array with prefixNUM as keys
	 *
	 * @param string $prefix
	 * @return array
	 */
	public function getArgsAssoc($prefix="URLDirStyleParamArg") {
		$r = array();
		foreach($this->args as $k=>$v) {
			$r[$prefix.$k] = $v;
		}
		return $r;
	}
	/**
	 * @return string
	 */
	public function getUrl() {
		if(count($this->args)==0) return parent::getUrl();
		else {
			if($this->isInUrl) {
				$this->query = $this->parseArgs();
				return parent::getUrl();
			} elseif($this->query == "") {
				return parent::getUrl()."?".$this->parseArgs();
			} else {
				$this->getParameters()->setParameter(new URLParamter($this->key,$this->parseArgs()));
				return parent::getUrl(false);
			}
		}
	}
	/**
	 * Returns a prepared URL-string to add an directory-expression on it
	 *
	 * @return string
	 */
	public function getUrlToSet() {
		if($this->isInUrl || $this->query == "") {
			$this->query = "";
			return parent::getUrl()."?";
		} else {
			$this->getParameters()->setParameter(new URLParamter($this->key,""));
			return parent::getUrl();
		}
	}
	protected function parseArgs() {
		return "/".implode("/",$this->args)."/";
	}
}
?>