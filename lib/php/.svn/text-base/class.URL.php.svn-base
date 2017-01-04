<?php
class URL  {
	/**
	 * @var string
	 */
	protected $url;
	/**
	 * @var string
	 */
	public $protocoll;
	/**
	 * @var string
	 */
	public $user;
	/**
	 * @var string
	 */
	public $password;
	/**
	 * @var string
	 */
	public $host;
	/**
	 * @var string
	 */
	public $port;
	/**
	 * @var string
	 */
	public $path;
	/**
	 * @var string
	 */
	public $query;
	/**
	 * @var string
	 */
	public $anchor;
	public $pointsOnDir;
	public $pointsOnFile;
	public $filename;
	/**
	 * A collection of the parameters, only instanciated on demand
	 *
	 * @var URLParamterCollection
	 */
	public $parameters = NULL;
	/**
	 * Splits up a URL like this:
	 *
	 * http://hans:geheim@www.example.org:80/demo/example.cgi?land=de&stadt=aa#abschnitt1
	 * |      |    |      subdomains[]|   |  |                |                |
	 * |      |    |      |   |domain |tld|  |                |                |
	 * |      |    |      |               |  |                |                |
	 * |      |    |      Host            |  Pfad             Query            Anker
	 * |      |    Passwort               Port
	 * |      Benutzer
	 * Protokoll
	 *
	 * @param string url
	 */
	public function URL($url) {
		$this->url = $url;
		if(ereg("^h(t|f)tps?://", $url)) {
//			$r1 = "#^((ht|f)tp(s?))://((.*):(.*)@)?([-\w\.]+)+(:(\d+))?(/([-\w/_\.]*(\?\S+)?)?)?#";
			$r1 = "#^((ht|f)tp(s?))://((.*):(.*)@)?([-\w\.]+)+(:(\d+))?(.*)?#";
			preg_match($r1, $url, $m);
			$this->protocoll = $m[1];
			$this->user = $m[5];
			$this->password = $m[6];
			$this->host = $m[7];
			$this->port = $m[9];
			$pathPart = $m[10];
		} elseif(ereg("^(wsdl)://(.*)/(.*)", $url, $m)) {
			$this->protocoll = $m[1];
			$this->host = $m[2];
			$pathPart = $m[3];
		} else {
			$pathPart = $url;
		}
		if(ereg("\?",$pathPart)) {
			if(ereg("\#",$pathPart)) {
				$r2 = "/(.*)\?(.*)#(.*)/";
			} else {
				$r2 = "/(.*)\?(.*)/";
			}
			preg_match($r2, $pathPart, $m);
			if(count($m)>1) $this->path = $m[1];
			if(count($m)>2) $this->query = $m[2];
			if(count($m)>3) $this->anchor = $m[3];
		} else $this->path = $pathPart;
		if(ereg("\.[A-z]+$", $this->path)) {
			$this->pointsOnFile = true;
			$this->filename = basename($this->path);
		} else $this->pointsOnFile = false;
		$this->pointsOnDir = !$this->pointsOnFile;
		$this->parameters = new URLParamterCollection();
		if($this->query!="") $this->parameters->insertItemsByQueryString($this->query);
	}
	public function getDirs() {
		$r = array();
		foreach(explode("/", $this->path) as $k) {
			if(trim($k)!="") $r[] = urldecode($k);
		}
		return $r;
	}
	public function replaceDir($index, $dirname) {
		$dirs = $this->getDirs();
		$dirs[$index] = $dirname;
		$this->path = "/".implode("/", $dirs);
		if($this->pointsOnDir) $this->path .= "/";
		elseif($this->filename != "") $this->path .= "/".$this->filename;
	}
	public function matchTo($hostname, $dirname, $filename) {
		if($this->host == "") return false;
		else {
			if($this->pointsOnDir) return false;
			else return dirname($this->path) == $dirname && $this->filename == $filename;
		}
	}
	public function matchToHostAndPath($hostname, $path) {
		if($this->host == "") return false;
		else {
			if($this->path == "") return $this->host == $hostname;
			else return $this->host == $hostname && ereg($this->path, $path);
		}
	}
	public function matchExactlyToPath($path) {
		return $this->path == $path;
	}
	public function isInPath($path) {
		return ereg("^$path", $this->path);
	}
	public function getUrl($urlencode=true, $entityAmp=true) {
		return $this->getServerUrl().$this->getLocalUrl($urlencode, $entityAmp);
	}
	public function getServerUrl() {
		if($this->protocoll!="") $r .= $this->protocoll."://";
		if($this->user!="") {
			$r .= $this->user;
			if($this->password!="") $r .= ":".$this->password;
			$r .= "@";
		}
		if($this->host!="") $r.= $this->host;
		if($this->port!="") $r.=":".$this->port;
		return $r;
	}
	public function getLocalUrl($urlencode=true, $entityAmp=true) {
		$r = "";
		if($this->path!="") $r.=$this->path;
		if($this->parameters->length()>0) {
			$r .= "?".$this->parameters->getQueryString($urlencode, $entityAmp);
		} elseif($this->query!="") $r.="?".$this->query;
		if($this->anchor!="") $r.="#".$this->anchor;
		return $r;
	}
	/**
	 * Returns files assuming they are on the current filesystem
	 *
	 * @param string $indexFiles
	 * @return string
	 */
	public function getFilePath($indexFiles = "index.php") {
		$r = $_SERVER['DOCUMENT_ROOT'].$this->path;
		if($this->pointsOnDir) $r .= $indexFiles;
		return $r;
	}
	/**
	 * Enter description here...
	 *
	 * @return URL
	 */
	public static function getFromHttpReferer() {
		return new URL($_SERVER["HTTP_REFERER"]);
	}
	/**
	 * creates a instance of the current request
	 *
	 * @return URL
	 */
	public static function currentURL($addParamOrList="",$addParamValue="") {
		$prot = "http";
		if(array_key_exists("HTTPS", $_SERVER)) {
			if($_SERVER["HTTPS"]=="on") $prot = "https";
		}
		$r = new URL("$prot://".$_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"]);
		if($addParamOrList!="") {
			if(is_array($addParamOrList)) {
				$r->getParameters()->addItemsByArray($addParamOrList);
			} else {
				$r->getParameters()->setParameter(new URLParamter($addParamOrList,$addParamValue));
			}
		}
		return $r;
	}
	/**
	 * creates a urlstring of current url with additional parameter
	 *
	 * @return string
	 */
	public static function addParamToLocal($addParam="",$addParamValue="", $urlencode=true, $entityAmp=true) {
		$r = new URL($_SERVER["REQUEST_URI"]);
		if($addParam!="") $r->getParameters()->setParameter(new URLParamter($addParam,$addParamValue));
		return $r->getLocalUrl($urlencode, $entityAmp);
	}
	/**
	 * @return URLParamterCollection
	 */
	public function getParameters() {
		return $this->parameters;
	}
}
?>