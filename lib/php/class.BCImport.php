<?php
require_once($_SERVER['DOCUMENT_ROOT']."/lib/php/class.BCDBRelated.php");
class BCImport extends BCDBRelated {
	public static $indexTable = "BCImportNodeIndex";
//	public static $indexValueTable = "BCImportValueIndex";
	public static $indexValueTable = "BCImportXML";
	public static $indexRecentChanges = "BCImportXMLRecentChanges";
	public static $indexRecentChanges_Done = "BCImportXMLRecentChanges_Done";
	/**
	 * array of [FID] = nodeId
	 *
	 * @var array
	 */
	private $bankIndex = null;
	/**
	 * array of [FID] = nodeId
	 *
	 * @var array
	 */
	private $productItemIndex = null;
	/**
	 * all XML-Feeds
	 *
	 * @var DOMFI
	 */
	private $doc;
	private static $srcFileName = "/sites/all/modules/bcnav/faCache.xml";
	private static $bankenFeed = "http://data.financeads.net/getxmldata.php?w=13289&key=d2dbb72cc84ff56682acc75ae50e3552&cat=allcompanies";
	private static $voidBankData = array("bankId","bankName","bankLogo");
	public static $voidProductImportData = array("bank","bankid","logo","url","productdetails");
	/**
	 * Cache for the node_load
	 *
	 * @var array
	 */
	private $nodeCache = array();
	/**
	 * Builds a matrix for admin-view
	 *
	 * @return array
	 */
	public function getImportsTable() {
		$data = array();
		foreach($this->getSrc()->getNodes("//product") as $product) {
			$line = array();
			$id = $this->getSrc()->value("@id", $product);
			$idImported = $this->isImported($id, "productitem");
			$attr = array(
				"name"=>"ids[]",
				"type"=>"checkbox",
				"value"=>$id
			);
			if($idImported) {
				$attr["checked"] = "checked";
				$attr["disabled"] = "true";
			}
			$line["Import"] = self::wrap("","input", $attr);
			$line["FA-Id"]  = $id;
			$line["Typ"] = t($product->parentNode->nodeName);
			$bankid = $this->getSrc()->value("bankid", $product);
			if($this->isImported($bankid)) {
				$line["Bank"] = self::wrap(
					$this->getSrc()->value("bank", $product),
					"a",
					array(
						"class"=>"bankNotExits",
						"href"=>url("node/".$this->bankIndex[$bankid]),
						"target"=>"blank"
					)
				);
			} else {
				$line["Bank"] = self::wrap($this->getSrc()->value("bank", $product), "span", array("class"=>"bankNotExits"));
			}
			$line["Produkt"] = '<a href="'.$this->getSrc()->value("url", $product).'" target="_blank">'.$this->getSrc()->value("productname", $product).'</a>';
			if($idImported) {
				$line["Produkt"] .= " / ".self::wrap("interner Link", "a", array('href'=>url("node/".$this->productItemIndex[$id])));
			}
			$data[] = $line;
		}
		return $data;
	}
	/**
	 * Get a List of banks to import
	 *
	 * @return string
	 */
	public function getBankImportList() {
		$lines = array();
		foreach($this->getSrc()->getNodes("/fall/bankenFeed/allcompanies/bank") as $bankNode) {
			if($this->isImported($bankNode->getAttribute("id"))) {
				$lines[] = self::wrap(
					$this->getSrc()->value("bankName", $bankNode),
					"a",
					array(
						"href"=>url("node/".$this->bankIndex[$bankNode->getAttribute("id")]),
						"target"=>"blank"
					)
				);
			} else {
				$lines[] = self::wrap(
					$this->getSrc()->value("bankName", $bankNode),
					"a",
					array(
						"class"=>"bankNotExits",
						"href"=>parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH)."?import=".$bankNode->getAttribute("id")
					)
				);
			}
		}
		return implode(" | ", $lines);
	}
	/**
	 * Shows a label/value table, returns object for
	 * further usage
	 *
	 * @todo large tables
	 * @param unknown_type $nid
	 * @return BCImport
	 */
	public static function showProdDataTable($nid) {
		$instance = new BCImport();
		$fid = $instance->getFidByNode($nid);
		$product = $instance->getSrc()->getNode("//product[@id='$fid']");
		$node = $instance->getNode($nid);
		$nodeMyProduct = $instance->getNode($node->field_prodmyproduct[0]["nid"]);
		$productType = $nodeMyProduct->title;
		if(is_null($product)) {
			try {
				$data = DOMFI::getInstance($instance->getLastXML($nid, $fid));
			} catch(Exception $e) {
				return;
			}
		} else {
			$data = DOMFI::getInstance($instance->getSrc()->saveXML($product));
		}
		BCTemplate::showProdDataTable($data, $productType);
		return $instance;
	}
	public function getBankUrl($nid) {
		$r = $this->getSrc()->value("/fall/feed/*/product[@id='".$nid."']/url");
		if(is_array($r)) {
			return $r[0];
		} else {
			return $r;
		}
	}

	public function getBusinessUrl($id) {
		$r = $this->getSrc()->value("/fall/bankenFeed/allcompanies/bank[@id='".$id."']/bankUrl");
		if(is_array($r)) {
			return $r[0];
		} else {
			return $r;
		}
	}
	public function doImport($ids) {
		foreach($ids as $id) {
			$this->doImportProductItem($id);
		}
	}
	public function doImportBankByFeed($id) {
		$this->innerImportBank(
			$id,
			$this->getSrc()->value("/fall/bankenFeed/allcompanies/bank[@id='$id']/bankName"),
			$this->getSrc()->value("/fall/bankenFeed/allcompanies/bank[@id='$id']/bankLogo")
		);
	}
	private function doImportProductItem($id) {
		global $user;
		$product = $this->getSrc()->getNode("//product[@id='$id']");
		$bankId = $this->getSrc()->value("bankid", $product);
		if(!$this->isImported($bankId, "bank")) {
			if($this->doImportBank($product) === false) return;
		}
		$node = new stdClass();
		$node->type = "productitem";
		$node->language = "de";
		$node->uid = $user->uid;
		$node->status = 0; //@author:roessler: Produkte sollen bei ersten Import nicht veröffentlicht sein
		$node->comment = 2;
		$node->title = $this->getSrc()->value("bank", $product)." ".$this->getSrc()->value("productname", $product);
		$myproduct = t($product->parentNode->nodeName);
		$node->path = "produkte/{$myproduct}/".strtolower(self::propperFileString($this->getSrc()->value("bank", $product)))."-".strtolower(self::propperFileString($this->getSrc()->value("productname", $product)));
		$node->field_prodfinanceaddsid = array(array("value"=>$id));
		$node->field_proditemmybank = array(array("nid"=>$this->bankIndex[$bankId]));
		$node->field_prodmyproduct = array(array("nid"=>$this->getDB()->simpleQuery("SELECT nid FROM  `node` WHERE  `type` LIKE  'product' AND  `title` LIKE  '%{$myproduct}%'")));
		$dNode = node_save($node);
		if($dNode !== false) {
			$this->getDB()->insert(self::$indexTable, array("nid"=>$node->nid, "fid"=>$id, "type"=>"productitem"));
			$this->bankIndex = $this->getAnIndex("bank");
			drupal_set_message("Produkt <i>".$this->getSrc()->value("productname", $product)."</i> erfolgreich importiert");
			return true;
		} else {
			drupal_set_message("Produkt <i>".$this->getSrc()->value("productname", $product)."</i> konnte nicht importiert werden","error");
			return false;
		}
	}
	private function doImportBank(DOMNode $product) {
		$this->innerImportBank(
			$this->getSrc()->value("bankid", $product),
			$this->getSrc()->value("bank", $product),
			$this->getSrc()->value("logo", $product)
		);
	}
	private function innerImportBank($bankId, $bankName, $logo) {
		global $user;
		$node = new stdClass();
		$node->type = "bank";
		$node->language = "de";
		$node->uid = $user->uid;
		$node->status = 0;
		$node->comment = 2;
		$node->title = $bankName;
		$node->path = "banken/".self::propperFileString($bankName);
		$node->field_financeaddsid = array(array("value"=>$bankId));

		$dst = $_SERVER['DOCUMENT_ROOT']."/sites/default/files/imports/".basename($logo);
		copy($logo, $dst);
		$data = array(
			"uid"=>$user->uid,
			"filename"=>basename($dst),
			"filepath"=>str_replace($_SERVER['DOCUMENT_ROOT']."/","", $dst),
			"filemime"=>"image/gif",
			"filesize"=>filesize($dst),
			"status"=>"1",
			"timestamp"=>time()
		);
		$fid = $this->getDB()->insert("files", $data);
		$data["fid"] = $fid;
		$node->field_banklogo = array($data);
		$dNode = node_save($node);
		if($dNode !== false) {
			$this->getDB()->insert(self::$indexTable, array("nid"=>$node->nid, "fid"=>$bankId, "type"=>"bank"));
			$this->bankIndex = $this->getAnIndex("bank");
			drupal_set_message("Bank <i>".$bankName."</i> erfolgreich importiert");
			return true;
		} else {
			drupal_set_message("Bank <i>".$bankName."</i> nicht erfolgreich importiert", "error");
			return false;
		}
	}
	/**
	 * Download all and pack together all XML-Feeds
	 *
	 * @return DOMFI
	 */
	public function getSrc($update=false) {
		if($update) {
			$this->doc = $this->updateSrc();
		}
		if(is_null($this->doc)) {
			$this->doc = DOMFI::getInstance($this->getSrcFilename());
		}
		return $this->doc;
	}
	/**
	 * Download all and pack together all XML-Feeds, search vor new values
	 *
	 * @return DOMFI
	 */
	public function updateSrc() {
//		copy($this->getSrcFilename(), dirname($this->getSrcFilename())."/cvs/".date("YmdHis", time()).".xml");
		$doc = DOMFI::getInstance("<fall/>");
		foreach($this->getDB()->coloumArray("SELECT nid FROM  `node` WHERE  `type` LIKE  'product'") as $nid) {
			$doc->select("/fall");
			$n = node_load(array("nid"=>$nid));
			$src = $n->field_xmllink[0]["value"];
			$cDoc = DOMFI::getInstance($src);
			$doc->node("feed")
				->attribute("src", $src)
				->attribute("name", $n->title)
				->within()
				->xml($cDoc->innerXML())
				->endWithin();
		}
		$doc->node("bankenFeed")->within()->xml(self::$bankenFeed);
		$doc->save($this->getSrcFilename());
		$this->saveProductValues();
		return $doc;
	}
	public function getBankData($bankId) {
		$r = array();
		foreach($this->getSrc()->getNodes("/fall/bankenFeed/allcompanies/bank[@id='$bankId']/*") as $node) {
			if(!in_array($node->nodeName, self::$voidBankData)) {
				$r[t($node->nodeName)] = $node->nodeValue;
			}
		}
		return $r;
	}
	public function getBankDataUnformatted($bankId) {
		$r = array();
		foreach($this->getSrc()->getNodes("/fall/bankenFeed/allcompanies/bank[@id='$bankId']/*") as $node) {
			if(!in_array($node->nodeName, self::$voidBankData)) {
				$r[$node->nodeName] = $node->nodeValue;
			}
		}
		return $r;
	}
	
	public function getBankProductsData($bankId) {
		$bankIndex = $this->getBankIndex();
		$sql = 'SELECT
		content_type_productitem.nid AS nidProductItem,
		node.title AS title,
		node_product.title AS typeProduct
			FROM  `content_type_productitem`
			LEFT JOIN node ON node.nid =  `content_type_productitem`.`nid`
			LEFT JOIN node AS node_product ON node_product.nid =  `field_prodmyproduct_nid`
			WHERE node.status = 1 AND `field_proditemmybank_nid` = \''.$bankIndex[$bankId].'\'
		';
		return $this->getDB()->fullTableArray($sql);
	}
	public static function getCacheFileStatsAndUpdateIfOlderThan($sec=86400) {
		$i = new BCImport();
		if((time() -filemtime($i->getSrcFilename())) > $sec) {
			$i->updateSrc();
		}
		return "Datum des letzen Downloads von FinanceAdd: ".date("d.m.Y H:i:s", filemtime($i->getSrcFilename()));
	}
	public function isImported($fid, $what="bank") {
		switch($what) {
			case "bank": return array_key_exists($fid, $this->getBankIndex()); break;
			case "productitem": return array_key_exists($fid, $this->getProductItemIndex()); break;
			default: {
				throw new Exception("No such index `$what`");
			}
		}
	}
	/**
	 * returns an array [fid] = nid
	 *
	 * @return array
	 */
	public function getBankIndex() {
		if(is_null($this->bankIndex)) {
			$this->bankIndex = $this->getAnIndex("bank");
		}
		return $this->bankIndex;
	}
	/**
	 * returns an array [fid] = nid
	 *
	 * @return array
	 */
	public function getProductItemIndex() {
		if(is_null($this->productItemIndex)) {
			$this->productItemIndex= $this->getAnIndex("productitem");
		}
		return $this->productItemIndex;
	}
	private function getAnIndex($what) {
		return $this->getDB()->fullTableArrayNum("SELECT fid, nid FROM ".self::$indexTable." WHERE type = '$what'");
	}
	public function getFARechnerArray($rechnerName) {
		$r = array();
		$produktType = str_replace("rechner", "", $rechnerName);
		$sql = "SELECT content_type_productitem.nid
FROM `content_type_productitem`
LEFT JOIN node ON node.nid = content_type_productitem.field_prodmyproduct_nid
WHERE title LIKE '$produktType'";
		$rechnersProductitems = $this->getDB()->coloumArray($sql);
//		DebugUtilities::print_rInTextarea($rechnersProductitems);
		foreach($this->getProductItemIndex() as $fid=>$nid) {
//			if(in_array($nid, $rechnersProductitems)) {
				$bewertung = new BCVotings($nid, $produktType);
				$r[$fid] = array(
					1=>url("node/$nid"),
					2=>number_format($bewertung->getAverage(), 1)
				);
//			}
		}
		return $r;
	}
	/**
	 * Filename of all to gether file
	 *
	 * @return string
	 */
	protected function getSrcFilename() {
		return $_SERVER['DOCUMENT_ROOT'].self::$srcFileName;
	}
	public static function wrap($content, $tagName, array $attributes = array()) {
		$r = "<".$tagName;
		foreach($attributes as $k=>$v) {
			$r .= " ".$k.'="'.$v.'"';
		}
		$r .= ">".$content."</".$tagName.">";
		return $r;
	}

	public static function propperFileString($input) {
		$trans = array(
			"/ +/"=>"-",
			"/\//"=>"-",
			"/\,/"=>"-",
			"/:/"=>"-",
			"/'/"=>"-",
			"/\"/"=>"-",
			"/Ä/"=>"Ae",
			"/Ö/"=>"Oe",
			"/Ü/"=>"Ue",
			"/ä/"=>"ae",
			"/ö/"=>"oe",
			"/ß/"=>"ss",
			"/ü/"=>"ue"
		);
		$input = preg_replace(array_keys($trans),$trans, $input);
		$input = preg_replace("/\W+/","-", $input);
		//Umwandlung in Kleinbuchstaben auf Kundenwunsch AH
		$input = strtolower($input);
		return $input;
	}
	public function getNodeIdByFid($fid) {
		$t = $this->getProductItemIndex();
		return $t[$fid];
	}
	public function getFidByNode($nid) {
		$r = $this->getProductItemIndex();
		$t = array_flip($r);
		return $t[$nid];
	}
	private function getHash(DOMFI $m) {
		$hash = array();
		foreach($m->getNodes("//*") as $node) {
			$path = $node->getNodePath();
			if(
				ereg("^/product$", $path)
				|| ereg("^/product/productdetails\[[0-9]+\]$", $path)
				|| ereg("^/product/productdetails$", $path)
			) {
				continue;
			} else {
				$hash[$path] = $node->nodeValue;
			}
		}
		return $hash;
	}
	public function saveProductValues() {
		$now = date("Y-m-d H:i:s");
		foreach($this->getProductItemIndex() as $fid=>$nid) {
			$node = $this->getNode($nid);
			$nodeMyProduct = $this->getNode($node->field_prodmyproduct[0]["nid"]);
			$productType = $nodeMyProduct->title;
			$xpath = "/fall/feed[@name='$productType']/*/product[@id='$fid']";
			$productNode = $this->getSrc()->getNode($xpath);
			$save = false;
			if(!is_null($productNode)) {
				$xml = DOMFI::getInstance($this->getSrc()->saveXML($productNode));
				$previousXMLCode = $this->getLastXML($nid, $fid);
				if(!is_null($previousXMLCode)) {
					$previousXML = DOMFI::getInstance($previousXMLCode);
					$hash = $this->getHash($xml);
					$hash2 = $this->getHash($previousXML);
					$diff = array_diff_assoc($hash, $hash2);
					if(count($diff)>0) {
						foreach($diff as $name=>$value) {
							$this->getDB()->insert(
								self::$indexRecentChanges,
								array(
									"fid"=>$fid,
									"name"=>$name,
									"oldValue"=>$previousXML->value($name),
									"value" => $value
								)
							);
						}
						$save = true;
					}
				} else {
					$save = true;
				}
				if($save) {
					$this->getDB()->insert(
						self::$indexValueTable,
						array(
							"timestamp"=>$now,
							"nid"=>$nid,
							"fid"=>$fid,
							"title"=>$node->title,
							"xml"=>$xml->saveXML()
						)
					);
				}
			}
		}
	}
	/**
	 * Enter description here...
	 *
	 * @param int $nid
	 * @param int $fid
	 * @return string
	 */
	private function getLastXML($nid, $fid) {
		return $this->getDB()->simpleQuery("SELECT xml FROM ".self::$indexValueTable." WHERE fid = $fid AND nid = $nid ORDER BY `timestamp` DESC LIMIT 1");
	}
	/**
	 * Get an node (cached)
	 *
	 * @param int $nid
	 * @return stdClass a Drupal-Node
	 */
	private function getNode($nid) {
		if(!array_key_exists($nid, $this->nodeCache)) {
			$this->nodeCache[$nid] = node_load(array("nid"=>$nid));
		}
		return $this->nodeCache[$nid];
	}
}
?>