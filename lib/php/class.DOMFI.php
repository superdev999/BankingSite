<?php
class DOMFI extends DOMBuilderTraverse {
	/**
	 * @var string
	 */
	protected $filename = "";
	protected $xpath = null;
	/**
	 * @param mixed $input
	 * @return DOMFI
	 */
	public static function getInstance($input) {
		$r = new DOMFI();
		if(is_object($input)) {
			if(is_a($input, "DOMFI")) {
				return $input;
			} elseif(is_a($input, "DOMDocument")) {
				$r->loadXML($input->saveXML());
			} else {
				$r->loadXML(DOMMapper::toString($input));
			}
		} elseif(is_array($input)) {
			$r->loadXML(DOMMapper::toString($input));
		} elseif(is_file($input)) {
			$r->loadXML(file_get_contents($input));
			$r->filename = $input;
		} elseif(@is_dir(dirname($input))) {
			$r->loadXML("<root/>");
			$r->nodeStack[] = $r->documentElement;
			$r->currentNode = $r->documentElement;
			return $r->loadDir(dirname($input), basename($input));
		} elseif(is_array($input)) {
			if(is_dir(dirname($input[0]))) {
				$r->loadXML("<root/>");
				$r->nodeStack[] = $r->documentElement;
				$r->currentNode = $r->documentElement;
				foreach($input as $dir) {
					$r->node("dir")
						->within()
						->attribute("path", dirname($dir))
						->attribute("link", str_replace($_SERVER['DOCUMENT_ROOT'],"", dirname($dir)));
					$r->loadDir(dirname($dir), basename($dir))->endWithin();
				}
			} else $r->loadXML(DOMMapper::toString($input));
		} elseif(ereg("^http://", $input)) {
			if(!$r->loadXML(file_get_contents($input))) throw new Exception("Couldn't load XML-File $input");
		} elseif(ereg("^wsdl://", $input, $m)) {
			$url = new URL($input);
			$r->loadWSDL(
				"http://".$url->host,
				$url->path,
				$url->getParameters()->toArray()
			);
		} elseif(ereg("<.*>", $input, $m)) {
			if(!$r->loadXML($input)) throw new Exception("Couldn't load XML: ".htmlspecialchars($input));
		} else {
			throw new Exception("Can't use $input ");
		}
		array_push($r->nodeStack, $r->documentElement);
		$r->currentNode = $r->documentElement;
		return $r;
	}
	public function loadDir($dir, $pattern="*.*") {
		$oldDir = getcwd();
		if(!is_dir($dir)) throw new Exception("$dir is not a directory");
		chdir($dir);
		foreach(glob($pattern) as $filename) {
			$ff = $dir."/".$filename;
			$this->node("file")->within()
				->attribute("filename", $ff)
				->attribute("filemtime", filemtime($ff))
				->attribute("filemtimeHR", date("Y-m-d H:i:s", filemtime($ff)))
				->attribute("link", str_replace($_SERVER['DOCUMENT_ROOT'],"",$ff))
				->xml(file_get_contents($filename))->endWithin();
		}
		chdir($oldDir);
		return $this;
	}

	public function fileGetContentsUTF8($fn) {
		$content = file_get_contents($fn);
		return mb_convert_encoding($content, 'UTF-8',mb_detect_encoding($content, 'UTF-8, ISO-8859-1', true));
	}

	public function getXPath() {
		if(is_null($this->xpath)) $this->xpath = new DOMXPath($this);
		return $this->xpath;
	}
	public function setXPath() {
		throw new Exception("Doesn't make sense");
	}
	public function getFilename() {
		return $this->filename;
	}
	public function insert($input) {
		$this->xml(DOMFI::getInstance($input)->innerXML());
		return $this;
	}
	public function replace($xpath, $xml, DOMNode $context=null) {
		$frag = $this->createDocumentFragment();
		$frag->appendXML(DOMFI::getInstance($xml)->innerXML());
		foreach($this->getNodes($xpath) as $to) {
			if(is_a($to, "DOMAttr")) {
				preg_match('/="(.*)"/s', $xml, $val);
				if(count($val)>0) $to->parentNode->setAttribute($to->name, $val[1]);
				else $to->parentNode->setAttribute($to->name, $xml);
			} else $to->parentNode->replaceChild($frag, $to);
		}
		return $this;
	}
	public function loadWSDL($url, $service, array $params, $into="") {
		if($into == "") {
			$s = new SoapClient($url, array("trace"=>true));
			$res = $s->$service($params);
			$this->loadXML($s->__getLastResponse());
		} else throw new Exception("Not implemented yet");
	}
	/**
	 * @param  $input
	 * @param array $params
	 * @return string
	 */
	public function xslRaw($input, array $params=array()) {
		$f = DOMFI::getInstance($input);
		$p = new XSLTProcessor();
		$p->importStylesheet($f);
		foreach($params as $k=>$v) $p->setParameter("",$k, $v);
		return $p->transformToXml($this);
	}
	/**
	 * @param  $input
	 * @param array $params
	 * @return DOMFI
	 */
	public function xsl($input, array $params=array()) {
		$f = DOMFI::getInstance($input);
		$p = new XSLTProcessor();
		$p->importStylesheet($f);
		foreach($params as $k=>$v) $p->setParameter("",$k, $v);
		$r = DOMFI::getInstance($p->transformToXml($this));
		return $r;
	}
	public function shortXsl($xsltemplates, array $params = array()) {
		return $this->xsl('<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" >'.$xsltemplates.'</xsl:stylesheet>', $params);
	}
	/**
	 * @param mixed $input
	 * @param string $into
	 * @return DOMFI
	 */
	public function wrap($input, $into) {
		$r = DOMFI::getInstance($input);
		$r->select($into)->xml($this->innerXML());
		return $r;
	}
	/**
	 * @param string $url
	 * @return DOMFI
	 */
	public function send($url, $method = "POST") {
		return $this;
	}
	/**
	 * @param string $fromXpath
	 * @param string $toXpath
	 * @return DOMFI
	 */
	public function move($fromXpath, $toXpath) {
		$src = $this->getNodes($fromXpath, $context);
		foreach($this->getNodes($toXpath) as $to) {
			$this->currentNode = $to;
			foreach($src as $from) {
				$to->insertBefore($from);
			}
		}
		return $this;
	}
	/**
	 * @param string $fromXpath
	 * @param string $toXpath
	 * @return DOMFI
	 */
	public function moveBefore($fromXpath, $toXpath, $context=null) {
		$src = $this->getNodes($fromXpath, $context);
		foreach($this->getNodes($toXpath, $context) as $to) {
			foreach($src as $from) {
				$to->parentNode->insertBefore($from, $to);
			}
			$this->currentNode = $to;
		}
		return $this;
	}
	/**
	 * @param string $fromXpath
	 * @param string $toXpath
	 * @return DOMFI
	 */
	public function moveAfter($fromXpath, $toXpath) {
		$src = $this->getNodes($fromXpath, $context);
		foreach($this->getNodes($toXpath) as $to) {
			foreach($src as $from) {
				$to->parentNode->insertBefore($from, $to);
				$to->parentNode->insertBefore($to, $from);
			}
			$this->currentNode = $to;
		}
		return $this;
	}
	/**
	 * @param string $fromXpath
	 * @param string $toXpath
	 * @param int $repeats
	 * @return DOMFI
	 */
	public function copyAfter($fromXpath, $toXpath, $repeats=1, $fromContext=null) {
		$src = $this->getNodes($fromXpath, $context);
		foreach($this->getNodes($toXpath) as $to) {
			$this->currentNode = $to;
			foreach($src as $from) {
				for($i=0;$i<$repeats;$i++) {
					$frag = $this->getDocumentFragment($this->saveXML($from));
					$new = $to->parentNode->insertBefore($frag, $to);
					$to->parentNode->insertBefore($to, $new);
				}
			}
		}
		$this->selection->clear();
		return $this;
	}
	/**
	 * @param string $fromXpath
	 * @param string $toXpath
	 * @param int $repeats
	 * @return DOMFI
	 */
	public function copy($fromXpath, $toXpath, $repeats=1, $fromContext=null) {
		$src = $this->getNodes($fromXpath, $context);
		foreach($this->getNodes($toXpath) as $to) {
			$this->currentNode = $to;
			foreach($src as $from) {
				for($i=0;$i<$repeats;$i++) parent::xml($this->saveXML($from));
			}
		}
		$this->selection->clear();
		return $this;
	}
	/**
	 * @param string $xpath
	 * @return DOMFI
	 */
	public function remove($xpath, $context=null) {
		$this->select($xpath, $context);
		if($this->selection->length()>0) parent::remove();
		return $this;
	}
	/**
	 * Prints XML
	 *
	 * @param string $from
	 * @return DOMFI
	 */
	public function out($decode=false) {
		if($decode) echo utf8_decode($this->saveXML());
		else echo $this->saveXML();
		return $this;
	}
	/**
	 *
	 * @param unknown_type $decode
	 * @return DOMFI
	 */
	public function html($decode=false) {
		if($decode) echo utf8_decode(htmlspecialchars($this->saveXML()));
		else echo htmlspecialchars($this->saveXML());
		return $this;
	}
	/**
	 *
	 * @param unknown_type $decode
	 * @return DOMFI
	 */
	public function innerHtml($decode=false) {
		if($decode) echo utf8_decode(htmlspecialchars($this->saveXML($this->documentElement)));
		else echo htmlspecialchars($this->saveXML($this->documentElement));
		return $this;
	}
	/**
	 * @param string $filename
	 * @param string $from
	 * @return DOMFI
	 */
	public function save($filename="", $fromXPath="", $context=null) {
		if($filename == "") $filename = $this->filename;
		else $this->filename = $filename;
		if($fromXPath!="") {
			$this->select($fromXPath, $context);
			DOMFI::getInstance($this->innerXML($this->currentNode))->save($filename);
		} else {
			if(!@parent::save($filename)) throw new Exception("Couldn't save document as filename '$filename' ");
		}
		return $this;
	}
	/**
	 * @param string $xpath
	 * @param string $context
	 * @return mixed
	 */
	public function value($xpath, $context=null) {
		return $this->select($xpath, $context)->selection->value();
	}
	/**
	 * @param string $xpath
	 * @param string $context
	 * @return mixed
	 */
	public function firstValue($xpath, $context=null) {
		return $this->select($xpath, $context)->selection->firstValue();
	}
	/**
	 * @param string $xpath
	 * @param string $context
	 * @return mixed
	 */
	public function copyOf($xpath, $context=null) {
		$r = "";
		foreach($this->select($xpath, $context)->selection as $node) {
			$r .= $this->saveXML($node);
		}
		return $r;
	}
	/**
	 * @param string $xpath
	 * @param string $context
	 * @return mixed
	 */
	public function copyOfToFile($filename, $xpath, $context=null) {
		$r = "";
		foreach($this->select($xpath, $context)->selection as $node) {
			$r .= $this->saveXML($node);
		}
		file_put_contents($filename, $r);
	}
	/**
	 *
	 * @param string $xpath
	 * @param DOMNode $context
	 * @param string $value
	 * @return DOMFI
	 */
	public function setValue($xpath, $context=null, $value) {
		$this->select($xpath, $context)->selection->setValue($value);
		return $this;
	}
	public function object($node=null) {
		if(is_null($node)) $node = $this->documentElement;
		$className = $node->nodeName;
		$r = new $className();
		$sel = clone($this->select("*", $node)->getSelection());
		foreach($sel as $propObject) {
			$propName = $propObject->nodeName;
			$isel = clone($this->select("*", $propObject)->getSelection());
			$isel2 = clone($this->select("item/*", $propObject)->getSelection());
			$setterName = "set".strtoupper(substr($propName,0,1)).substr($propName,1);
			if(method_exists($r, $setterName)) $r->$setterName($this->saveXML($propObject));
			elseif($isel->length()==0) {
				$r->$propName = $propObject->nodeValue;
			} elseif($isel2->length()!=0) {
				//Vereinbarung!!!
				if(method_exists($r, "addItem")) {
					foreach($isel2 as $sub) {
						$r->addItem($this->object($sub));
					}
				} else {
					$items = array();
					foreach($isel2 as $sub) $items[] = $this->object($sub);
					$r->$propName = $items;
				}
			} else {
				foreach($isel as $sub) $r->$propName = $this->object($sub);
			}
		}
		return $r;
	}
	/**
	 * @param string $xpath
	 * @param DOMNode $context
	 * @return DOMNode
	 */
	public function getNode($xpath, $context=null) {
		$r = clone($this->select($xpath, $context)->getSelection());
		$this->selection->clear();
		return $r->item(0);
	}
	/**
	 * @param string $xpath
	 * @param DOMNode $context
	 * @return DOMSelection
	 */
	public function getNodes($xpath, $context=null) {
		$r = clone($this->select($xpath, $context)->getSelection());
		$this->selection->clear();
		return $r;
	}
	/**
	 * Output as Matrix
	 *
	 * @param array $options
	 * @return DOMFI
	 */
	public function matrix(array $options = array()) {
		$defaults = array(
			"output"=>"stdout",
			"cols"  => array(".")
		);
		$options = array_merge($defaults, $options);
		$data = array();
		$sel = clone($this->selection);
		foreach($sel as $n) {
			$line = array();
			if($options["cols"] == "*") {
				foreach($this->getNodes("*", $n) as $i) {
					$line[$i->nodeName] = $i->nodeValue;
				}
			} else {
				foreach($options["cols"] as $title=>$xpath) {
					$line[$title] = $this->value($xpath, $n);
				}
			}
			$data[] = $line;
		}
		$output = new CSV();
		$output->setDataMaxHeader($data);
		if($options["output"]=="stdout") echo $output->toString();
		elseif(ereg("csv$", $options["output"])) $output->saveAs($options["output"]);
		elseif(ereg("array", $options["output"])) return $output->data;
		else return $this;
	}
	/**
	 * @return DOMFI
	 */
	public function iframe($height="300px",$width="100%",$fn="temp.xml") {
		$this->save($fn);
		echo '<iframe src="'.$fn.'?t='.time().'" height="'.$height.'" width="'.$width.'" '.$idXHTML.' ></iframe>';
		return $this;
	}
	/**
	 * @return DOMFI
	 */
	public function saveAndArchive($filename="") {
		if($filename == "") $filename = $this->filename;
		$copy = $filename.".".date("YmdHis").".xml";
		copy($filename, $copy);
		$cmd = "zip -m ".preg_replace("/\.xml$/", ".zip", $filename)." ".$copy;
		exec($cmd);
		$this->save($filename);
		return $this;
	}
	/**
	 * @return DOMFI
	 */
	public function scanDir($dir, $repEx = "/.*/", $readContents = true, array $fileAttributes=array()) {
		$dir_iterator = new RecursiveDirectoryIterator("$dir");
		$iterator = new RecursiveIteratorIterator($dir_iterator, RecursiveIteratorIterator::SELF_FIRST);
		$startingNode = $this->currentNode;
		foreach ($iterator as $file) {
			if(is_file($file) && basename($file) != "." && basename($file) != "..") {
				if(preg_match($repEx, $file)>0) {
					$parts = explode(DIRECTORY_SEPARATOR, $file);
					for($i=0;$i<count($parts)+1;$i++) {
						$c = implode(DIRECTORY_SEPARATOR, array_slice($parts, 0, $i));
						if(is_dir($c)) {
							if($this->getNodes("//folder[@path='$c']", $this->currentNode)->length() == 0) {
								if(dirname($c) == "") {
									$this->currentNode = $startingNode;
								} else {
									$this->select("//folder[@path='".dirname($c)."']", $startingNode);
								}
								$this->node("folder")->within()
									->attribute("basename", basename($c))
									->attribute("path", $c)
									->endWithin();
							}
						} elseif(is_file($c)) {
							$this->select("//folder[@path='".dirname($c)."']", $startingNode);
							$this->node("file")->within()
								->attribute("basename", basename($c))
								->attribute("path", $c);
							foreach($fileAttributes as $attr) {
								switch($attr) {
									case "filemtime":
										$fmt = filemtime($c);
										$this->attribute("filemtime", $fmt)
											 ->attribute("filemtimef", date("Y-m-d H:i:s", $fmt));
										break;
								}
							}
							if($readContents) {
								$this->xml(file_get_contents($c));
							}
							$this->endWithin();
						}
					}
				}
			}
		}
		$this->currentNode = $startingNode;
		return $this;
	}
}
?>