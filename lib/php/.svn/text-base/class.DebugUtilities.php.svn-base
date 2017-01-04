<?php
class DebugUtilities {
	private $durationStart;
	private $durationEnd;
	public static function startOutputBuffer() {
		ob_start();
	}
	public static function stopOutputBuffer($height="300px", $width="100%", $id="") {
		self::showInTextarea(ob_get_clean(), $height, $width, $id);
	}
	public static function showPropsInTextArea($input, $height="300px", $width="100%", $id="") {
		$toShow = array();
		foreach(get_object_vars($input) as $name=>$val) {
			$toShow[$name] = strval($val);
		}
		self::print_rInTextarea($toShow, $height, $width, $id, false);
		self::showCallingLine();
	}
	public static function print_rInTextarea($input, $height="300px", $width="100%", $id="", $showCallingLine=true) {
		$idXHTML = "";
		if($id != "") $idXHTML = ' id="'.$id.'" ';
		echo "<textarea $idXHTML style=\"width:$width;height:$height\">";
		if(ob_start()) {
			print_r($input);
			$res = ob_get_clean();
			echo htmlentities($res);
		}
		echo "</textarea>";
		if($showCallingLine) self::showCallingLine();
	}
	public static function print_rInTextfile($input, $filename, $showCallingLine=true) {
		if(ob_start()) {
			print_r($input);
			if($showCallingLine) self::showCallingLine();
			$res = ob_get_clean();
//			$f = fopen($filename, "w");
//			fputs($f, $res);
//			fclose($f);
		}
	}
	public static function showInTextarea($input, $height="300px", $width="100%", $id="", $name="") {
		if($id != "") $idXHTML = ' id="'.$id.'" ';
		if($name != "") $idXHTML = ' name="'.$name.'" ';
		echo "<textarea $idXHTML style=\"width:$width;height:$height\">".htmlentities($input)."</textarea>";
	}
	public static function showIframe($url, $height="300px", $width="100%", $id="") {
		if($id != "") $idXHTML = ' id="'.$id.'" ';
		echo '<iframe src="'.$url.'" height="'.$height.'" width="'.$width.'" '.$idXHTML.' ></iframe>';
	}
	public static function print_rInPreTags($input) {
		echo "<pre>";
		print_r($input);
		echo "</pre>";
	}
	public static function showInPre($input) {
		echo "<pre>";
		echo $input;
		echo "</pre>";
	}
	public static function showSession() {
		self::print_rInPreTags($_SESSION);
	}
	public static function showPost() {
		self::printInH1("\$_POST");
		self::print_rInPreTags($_POST);
		self::showCallingLine();
	}
	public static function showGet() {
		self::print_rInPreTags($_GET);
	}
	public static function showCookies() {
		self::print_rInPreTags($_COOKIE);
	}
	public static function showRequest() {
		self::print_rInPreTags($_REQUEST);
	}
	public static function showServer() {
		self::print_rInPreTags($_SERVER);
	}
	public static function showTextFile($filename, $height="300px", $width="100%", $id="") {
		$input = file_get_contents($filename);
		self::showInTextarea($input, $height, $width, $id);
	}
	public static function showEnvironment() {
		foreach(array("Session","Post","Get","Cookies","Server") as $part) {
			echo "<h1>$part</h1>";
			eval ("self::show$part();");
		}
	}
	/**
	 * Show relevant configuring
	 *
	 * @see http://de2.php.net/manual/de/ref.errorfunc.php
	 *
	 */
	public static function showErrorReportingConfig() {
		echo ini_get("display_errors")."<hr/>";
		echo error_reporting();
	}
	/**
	 * Show relevant configuring
	 *
	 * @see http://de2.php.net/manual/de/ref.errorfunc.php
	 *
	 */
	public static function setErrorReportingOnAnyway($strict=true) {
		ini_set("display_errors","On");
		if($strict) error_reporting(E_STRICT);
		else error_reporting(E_ALL);
	}
	/**
	 * Checks HTTP-Request if there is a paramter called $key. If it reads
	 * a "true" or "1" it's interpreded as there should be a debug mode.
	 *
	 * This is registered as Session-Configuration.
	 *
	 * If the parameter reads a "false" or a "1" it's interpreded, that there should
	 * be no session mode and that's registered in Session-Variables too.
	 *
	 * @param string $key
	 * @return boolean isInDebugMode
	 */
	public static function isDebugMode($key="DEBUG") {
		if(!is_array($_SESSION)) @session_start();
		if(is_array($_SESSION) && array_key_exists($key, $_REQUEST)) {
			if(array_key_exists($key, $_REQUEST)) {
				$_SESSION[$key] = self::requestParamMeansTrue($key);
			}
			return $_SESSION[$key];
		} else {
			return self::requestParamMeansTrue($key);
		}
	}
	private static function requestParamMeansTrue($key) {
		if (array_key_exists($key, $_REQUEST)) {
			return eregi("TRUE|1|on", $_REQUEST[$key]);
		}
	}
	public static function arrayToTable(array $input) {
		$csv = new CSV();
		$csv->setData($input);
	}
	public static function printArrayAsList(array $array, $numbered = true) {
		if($numbered) $tag = "ol";
		else $tag = "ul";
		echo "<$tag><li>".implode("</li><li>", $array)."</li></$tag>";
	}
	public static function showFileInTextarea($filename, $height="300px", $width="100%") {
		self::showInTextarea(file_get_contents($filename), $height, $width);
	}
	public function startDuration() {
		$this->durationStart = microtime(true);
	}
	public function printCurrentDurationsStatistics($message="") {
		echo "<hr>";
		if($message != "") echo $message."<br/>";
		echo "Start on: ".$this->getMicrotimeLine($this->durationStart)."<br>";
		echo "End on: ".$this->getMicrotimeLine(microtime(true))."<br>";
		echo "Length: ".(microtime(true) - $this->durationStart)."<br>";
		echo "Mem-Usage: ".(memory_get_usage(true));
	}
	public function endDuration() {
		$this->durationEnd = microtime(true);
	}
	public function endDurationAndPrint() {
		$this->endDuration();
		$this->showDurationStatistics();
	}
	public function showDurationStatistics() {
		echo "<hr>";
		echo "Start on: ".$this->getMicrotimeLine($this->durationStart)."<br>";
		echo "End on: ".$this->getMicrotimeLine($this->durationEnd)."<br>";
		echo "Length: ".($this->durationEnd - $this->durationStart)."<br>";
		echo "Mem-Usage: ".(memory_get_usage(true));
	}
	public static function backtraceInPreTags() {
		if(ob_start()) {
			debug_print_backtrace();
			self::print_rInPreTags(ob_get_clean());
		}
	}
	public static function backtraceShortInPreTags() {
		foreach(debug_backtrace() as $data) {
			$c .= $data["file"].":".$data["line"]."\t".$data["object"].":".$data["class"]."::".$data["function"]."(".implode(",", $data["args"]).")\n";
		}
		self::print_rInPreTags($c);
	}
	private function getMicrotimeLine($microtime) {
		return date("H:i:s", intval($microtime)).", ".substr($microtime, 10);
	}
	public static function showMatrixAsTable($matrix, $addStandardStylesheet=true, $inBox = true, $height="300px", $width="100%") {
		$t = new CSV();
		$t->setDataMaxHeader($matrix);
		if($inBox) {
			echo "<div style=\"width:$width;height:$height;scroll:auto;overflow:scroll;\">";
		echo $t->show($addStandardStylesheet);
			self::showCallingLine();
			echo "</div>";
	}
	}

	public static function printInH1($input, $showCallingLine = true) {
		echo "<h1>$input</h1>";
		if($showCallingLine) self::showCallingLine();
	}
	public static function printInH2($input, $showCallingLine = true) {
		echo "<h2>$input</h2>";
		if($showCallingLine) self::showCallingLine();
	}
	public static function printInH3($input, $showCallingLine = true) {
		echo "<h3>$input</h3>";
		if($showCallingLine) self::showCallingLine();
	}
	public static function printInH4($input, $showCallingLine = true) {
		echo "<h4>$input</h4>";
		if($showCallingLine) self::showCallingLine();
	}
	public static function hashArrayAsLine(array $array) {
		$parts = array();
		foreach($array as $k=>$v) {
			$parts[] = "<strong>$k:</strong>$v";
		}
		echo implode(" | ",$parts)."<hr>";
		self::showCallingLine();
	}
	public static function showDOMDocInIframe($exp, $height="300px", $width="100%", $id="", $filename="") {
		if(!is_a($exp, "DOMDocument")) {
			if($filename=="") {
				$url =  self::getIncrementingFileName("temp$id.xml");
				$filename = basename($url);
			} else {
				$url = dirFunctions::cutDocRoot($filename);
			}
			$doc = DOMDocumentCreator::create($exp);
		} else {
			$filename = "temp.xml";
			$doc = $exp;
		}
		$doc->save($filename);
		self::showIframe($url."?tmp=".uniqid(time()),$height, $width, $id);
		self::showCallingLine();
	}
	private static function getIncrementingFileName($basename) {
		if(is_int($GLOBALS["DEBUGUTILITIES_$basename"])) {
			$GLOBALS["DEBUGUTILITIES_$basename"]++;
		} else {
			$GLOBALS["DEBUGUTILITIES_$basename"] = 0;
		}
		return dirFunctions::addBeforeExtension($basename, $GLOBALS["DEBUGUTILITIES_$basename"]);
	}
	public static function printCMD($cmd, $height="300px", $width="100%", $id="", $name="") {
		exec($cmd, $m);
		$height = $height.";background-color:Black;color:White;font-family:Courier";
		self::print_rInTextarea($cmd.chr(13).implode(chr(13), $m),$height,$width, $id);
	}
	public static function printBacktrace($inTextArea=false, $height="300px", $width="100%", $id="") {
		ob_start();
		debug_print_backtrace();
		$res = ob_get_clean();
		if($inTextArea) self::print_rInTextarea($res, $height, $width, $id);
		else self::print_rInPreTags($res);
	}
	public static function showCallingLine() {
		ob_start();
		debug_print_backtrace();
		$res = ob_get_clean();
		echo "<pre>".array_pop(array_slice(explode("\n", $res),1,1))."</pre>";
	}
	public static function die2($messages) {
		ob_start();
		debug_print_backtrace();
		$res = ob_get_clean();
		echo "<h1>".$messages."</h1>";
		echo "<pre>".$res."</pre>";
		die();
	}
	public static function debugXSLT(DOMDocument $xml, DOMDocument $xsl, array $params=array(), $outPutIsXSL=false) {
//		self::printInH1("XML &amp; XSL");
		self::showDOMDocInIframe($xml, "300px","48%","xml");
		self::showDOMDocInIframe($xsl, "300px","48%","xsl");
		self::print_rInTextarea($params);
		$xsl = new xslt($xml, $xsl, $params, false);
		if($outPutIsXSL) {
			$xml = $xsl->process();
			self::showDOMDocInIframe($xml, "300px");
			self::printInH1("Result as HTML");
			$xsl->result->saveHTMLFile("temp.html");
		} else {
			$out = $xsl->transform();
			$fh = fopen("temp.html", "w");
			fputs($fh, $out);
			fclose($fh);
		}
		self::showIframe("temp.html", "300px");
	}
	public static function isIp($ip) {
		return $_SERVER["REMOTE_ADDR"] == $ip;
	}
	public static function printRequestIntoTextFile($filename="request.txt", $mod = FILE_APPEND) {
		ob_start();
		print_r($_REQUEST);
		file_put_contents($filename, ob_get_clean(), FILE_APPEND);
	}
	public static function printException(Exception $e) {
			$html = "<html><body><h1 style='color:red;'>Server Error</h1><hr/><i style='color:brown;font-size:14px;'>".$e->getMessage()."</i><br/>";
			$html .= "<h3>Exception: </h3>".get_class($e);
			$html .= "<h3>Location</h3>";
			$html .= "Thrown at <strong>".$e->getFile()."</strong> on line <strong>".$e->getLine()."</strong>";
			$from = max(array(($e->getLine()-10), 1));
			$to = $e->getLine()+10;
			exec("sed -n '{$from},{$to}p' '".$e->getFile()."' ", $m);
			$html .= "<pre>";
			foreach($m as $l) {
				$part = $from."\t".$l."\n";
				if($from == $e->getLine()) {
					$html .= "<strong>$part</strong>";
				} else {
					$html .= $part;
				}
				$from++;
			}
			$html .= "</pre>";
//			$html .= highlight_string(implode("\n", $m), true);
			$html .= "<h3>URL</h3>";
			$html .= $_SERVER['REQUEST_URI'];
			$html .= "<h3>Stack-Trace</h3><div style='background-color:#ffff99;font-family:monospace;padding:20px;'>".implode("<br/>", explode("\n", $e->getTraceAsString()))."</div></body></html>";
			echo $html;
	}
}
?>