<?php
/**
 * @version 1.41
 * @since 13.10.2007
 * Changelog 1.41 - 13.10.2007:
 * 	- added function setData
 *  - added parameter $addStandardStylesheet to show()
 *  - some JavaDoc, some modifiers
 * Changelog 1.4 - 28.09.2007:
 *  - added funtion selectMatchInRow
 *  - Bugfix in import-function: line-no was wrong if zeilenUeberspringen was set
 * Changelog 09.09.2007:
 *  - added function update() from Hauraton
 * Changelog 27.08.2007:
 *  - added function show() from ealiear versions
 *  - added parameter $ignoreCase in array_multisortvert from ealiear versions
 */
class CSV {
	public $file;
	public $felderTrennzeichen;
	/**
	 * Zeichenfolge mit denen Felder eingschlossen sind
	 * @var string
	 */
	public $felderEingeschlossen;
	/**
	 * Sagt, ob Felder (evtl.) eingschlossen sind
	 *
	 * @param boolean
	 */
	public $felderEingschlossenOptional;
	public $feldInhaltEscaped;
	public $zeilentrennung;
	public $zeilenUeberspringen;
	public $hasHeaders;
	public $asscocCols;
	public $spalten;
	/**
	 * Data - Array
	 *
	 * @var array
	 */
	public $data;
	public $maxImport;
	public $code;
	public function CSV($file="", $felderTrennzeichen=";", $felderEingeschlossen='"', $felderEingschlossenOptional=true, $feldInhaltEscaped="\\", $zeilentrennung="\n", $zeilenUeberspringen=0, $hasHeaders=true, $maxImport="", $asscocCols=true) {
		$this->file= $file;
		$this->felderTrennzeichen= $felderTrennzeichen;
		$this->felderEingeschlossen= $felderEingeschlossen;
		$this->felderEingschlossenOptional= $felderEingschlossenOptional;
		$this->feldInhaltEscaped= $feldInhaltEscaped;
		$this->zeilentrennung= $zeilentrennung;
		$this->zeilenUeberspringen= $zeilenUeberspringen;
		$this->hasHeaders = $hasHeaders;
		$this->maxImport = $maxImport;
		$this->asscocCols = $asscocCols;
		if($this->file != "") $this->import();
	}
	public function import() {
		unset($this->data);
		if($this->zeilentrennung == "*") {
			$zeilen = file($this->file);
		} else {
			$this->code = implode("", file($this->file));
			$zeilen = explode($this->zeilentrennung, $this->code);
		}
		if($this->maxImport == "") $this->maxImport = count($zeilen);
		if($this->hasHeaders) {
			$this->spalten = $this->getZeile(array_shift($zeilen));
		}
		$this->importRows($zeilen);
	}
	public function importCode($code, $max="") {
		$this->code = $code;
		$zeilen = explode($this->zeilentrennung, $this->code);

		if($max == "") $this->maxImport = count($zeilen);
		else $this->maxImport = $max;
		if($this->hasHeaders) {
			$this->spalten = $this->getZeile(array_shift($zeilen));
		}
		$this->importRows($zeilen);
	}
	public function importRows(array $zeilen) {
		unset($this->data);
		$i=0;
		while(count($zeilen) > 0 && $i<$this->maxImport) {
			if($i >= $this->zeilenUeberspringen) {
				$zData = $this->getZeile(array_shift($zeilen));
				$j=0;
				if(trim(implode($zData))!="") {
				foreach($zData as $field) {
					if($this->asscocCols) {
						$key = $this->spalten[$j];
						if($key == "") {
							$key = "Col$j";
						}
					} else {
						$key = $j;
					}

					$this->data[$i][$key] = $zData[$j];
					$j++;
				}
			} else {
				}
			} else {
				array_shift($zeilen);
			}
			$i++;
		} if($i==0) throw new Exception("Nothing to import on ".count($zeilen)." Rows.");
		}
	public function getZeile($arr) {
		unset($neueFelder);
		$felder = explode($this->felderTrennzeichen, $arr);
		foreach($felder as $feld) {
			if($this->felderEingschlossenOptional) {
				if($this->felderEingeschlossen != "") {
					if(str_replace($this->felderEingeschlossen, "", $feld) == $feld) $neueFelder[] = trim($feld);
					else $neueFelder[] = trim(str_replace($this->felderEingeschlossen, "", $feld));
				} else $neueFelder[] = trim($feld);
			} else {
				$neueFelder[] = trim(str_replace($this->felderEingeschlossen, "", $feld));
			}
		}
		return $neueFelder;
	}
	/**
	 * Extrahiert eine Spalte aus den Daten
	 *
	 * @author Dominik Fettel
	 * @param  string
	 * @return array
	 */
	public function getColumn($colName, $asList=false) {
		$singleCol = array();
		for($i=0;$i<count($this->data);$i++) {
			$singleCol[$colName][] = $this->data[$i][$colName];
		}
		if($asList) return $singleCol[$colName];
		else return $singleCol;
	}
	/**
	 * Creates a HTML-Table
	 * @param boolean adds lines an paddings to the HTML-Table
	 * @return string
	 */
	public function toString($addStandardStylesheet=false) {
		/**
		print "<pre>";
		print_r($this->spalten);
		print "<pre>";
		die();
		**/
		$retr = '<table width="100%" style="width:100%!important;">';

		if($this->hasHeaders) { 
			$retr .= "<thead><tr><th>";
			$ix = 0;
			foreach($this->spalten as $key=>$spalte) {
				if ($spalte !== 'order') {
					if ($ix < count($this->spalten)) {
						$closeopen = "</th><th>";
					} else {
						$closeopen = "";
					}
					$retr .= $spalte.$closeopen;
				} else { 
					$orderkey = $key;
				}
				$ix++;
			}
			$retr .= "</th><tr></thead>";
			$retr .= "<tbody>";
		}
		foreach($this->data as $row) {
			$retr .= "<tr><td>";
			$iy = 0;
			foreach ($row as $key => $field) {
				if($iy !== $orderkey) {
					if ($iy < count($row)) {
						$closeopen = "</td><td>";
					} else {
						$closeopen = "";
					}
					$retr .= $field.$closeopen;
				}
				$iy++;
			}
			// implode("</td><td>", $row);
			$retr .= "</td><tr>";
		}
		$retr .= "</tbody>";
		$retr .= "</table>";
		if($addStandardStylesheet) {
			$retr .= '<style>
<!--
table {
border-collapse:collapse;
}
td, th {
border: 1px solid #989898;
padding: 2px;
}
th {
background-color: #3366CC;
color:White;
}
tr:hover {
background-color: #FFFFCC;
}
td:hover {
background-color: #FFCC66;
}
-->
</style>';
		}
		return $retr;
	}
	/**
	 * Creates a XML-String from data
	 * @return string
	 * @param string
	 * @param string $type could be attributes, tags
	 * @param stirng $lineTag only neccessary if $type = tags
	 */
	public function toXML($itemTag="item", $rootTag="root", $type="attributes", $lineTag="line") {
		$this->xml = "<$rootTag>";
		switch($type) {
			case "attributes":
				for($i=0;$i<count($this->data);$i++) {
					$this->xml .= "<$itemTag";
					if(is_array($this->data[$i])) {
						foreach($this->data[$i] as $k=>$v) {
							$this->xml .= " $k=\"$v\"";
						}
					}
					$this->xml .= " />";
				}
				break;
			case "tags":
				for($i=0;$i<count($this->data);$i++) {
					$this->xml .= "<$itemTag>";
					if(is_array($this->data[$i])) {
						foreach($this->data[$i] as $k=>$v) {
							$this->xml .= "<item name=\"".htmlspecialchars($k)."\">".htmlspecialchars($v)."</item>";
						}
					}
					$this->xml .= "</$itemTag>";
				}
				break;
			default:
				die("Not implemented Type $type");
				break;
		}
		$this->xml .= "</$rootTag>";
		return $this->xml;
	}
	/**
	 * Creates a XML-DOMDocument from data
	 * @param string
	 * @param string
	 * @param string $type could be attributes, tags
	 * @param stirng $lineTag only neccessary if $type = tags
	 * @return DOMDocument
	 * @todo not implemented Yet
	 */
	public function toXMLDoc($itemTag="item", $rootTag="root", $type="attributes", $lineTag="line") {
		$returnValue = DOMDocument::load("<$rootTag/>");
		trigger_error("Function not impletmentd");
		return $returnValue;
	}
	/**
	 * USAGE:
	 * $this->select("elephants","land='africa'")
	 * @return array
	 */
	public function select($fields="*", $where="1", $asList=false) {
		$retr = array();
		if($fields=="*") {
			$selectFields = $this->spalten;
		} else {
			$selectFields = explode(",",$fields);
		}
		for ($i=0;$i<count($this->data);$i++) {
			$line = $this->data[$i];
			$select = false;
			if($where == "1") {
				$select = true;
			} else {
				preg_match("/(.*?)='(.*?)'/",$where, $m);
				if(count($m)==3) {
	 				$select = $line[$m[1]] == $m[2];
				} else {
					$select = eregi($where, implode($line));
				}
			}
			if($select) {
				for ($j=0;$j<count($selectFields);$j++) {
					$retrLine[$selectFields[$j]] = $line[$selectFields[$j]];
				}
				$retr[] = $retrLine;
			}
		}
		if($asList) {
			$newRetr = array();
			foreach($retr as $line) {
				$newRetr[] = $line[$selectFields[0]];
			}
			$retr = $newRetr;
		}
		return $retr;
	}
	public function selectMatchInRow($eregi) {
		return $this->select("*",$eregi);
	}
	public function selectScalar($field, $condition, $row=0) {
		$res = $this->select($field, $condition);
		return $res[$row][$field];
	}
	function selectLowerDate($feldname,$timelimit="", $count="3") {
		$j=1;
		if($timelimit=="") $timelimit=time();
		for($i=0;$i<count($this->data);$i++) {
			if(ereg("([0-9]{2}).([0-9]{2}).([0-9]{4})",$this->data[$i][$feldname],$date)) {
				$dateUnix = mktime(0, 0, 0, $date[2], $date[1], $date[3]);
				if($timelimit <= $dateUnix) {
					$retr[] = $this->data[$i];
					if($j++==$count) return $retr;
				}
			}
		}
		return $retr;
	}
	public function setData($array, $hasHeaders=true) {
		$this->hasHeaders = $hasHeaders;
		$this->spalten = array_keys($array[0]);
		$this->data = $array;
	}
	/**
	 * Creates a new One-Column-Table by 1-dim array, i.e. array("A","B","C") second argument is the name of the header
	 *
	 * @param array $array
	 * @param string$header
	 */
	public function setDataByList(array $array, $header) {
		$lines = array();
		foreach($array as $cell) {
			$lines[] = array($header=>$cell);
		}
		$this->setData($lines);
	}
	function toUTF8() {
		$newArray = array();
		foreach ($this->data as $k=>$value) {
			foreach ($value as $k2=>$value2) {
				$newArray[$k][utf8_encode($k2)] = utf8_encode($value2);
			}
		}
		$this->setData($newArray);
	}
	function fromUTF8() {
		$newArray = array();
		foreach ($this->data as $k=>$value) {
			foreach ($value as $k2=>$value2) {
				$newArray[$k][$k2] = utf8_decode($value2);
			}
		}
		$this->data = $newArray;
	}
	/**
	 * Filters the data in cols by Regular expression
	 * @param string
	 * @param string
	 * @param boolean if all not matching values should apply
	 * @return array
	 */
	public function filterData($col="*", $expr, $v=false, $ignoreCase = false) {
		if(!$ignoreCase) $func = "ereg";
		else $func = "eregi";
		$returnValue = array();
		for ($i=0;$i<count($this->data);$i++) {
			if($col=="*") $testOn = implode($this->data[$i]);
			else $testOn = $this->data[$i][$col];
			$add = $func($expr, $testOn);
			if($v) $add = !$add;
			if($add) $returnValue[] = $this->data[$i];
		}
		$this->setData($returnValue);
		return $returnValue;
	}
	/**
	 * Create CSV-Data
	 * @return string
	 */
	/**
	 * produces a 3dim Array with uniq values as key as first dimension an 1..* lines as list
	 *
	 * @param string $column Name of Column
	 * @return array
	 */
	function groupBy($column) {
		for($i=0;$i<count($this->data);$i++) {
			$group[$this->data[$i]["$column"]][] = $this->data[$i];
		}
		return $group;
	}
	/**
	 * Creates CSV-Text
	 * @return string
	 */
	function toCSV() {
		$ret = "";
		if($this->felderEingschlossenOptional) {
			$cn = $this->felderTrennzeichen;
		} else {
			$cn = $this->felderEingeschlossen.$this->felderTrennzeichen.$this->felderEingeschlossen;
		}
		if($this->hasHeaders) {
			$tmp .= implode($cn, $this->spalten).$this->zeilentrennung;
			$ret .= str_replace(';order','',$tmp);
		}
		for($i=0;$i<count($this->data);$i++) {
			$iz = 0;
			foreach ($this->data[$i] as $key => $val) {
				if ($key !== 'order') {
					if ($iz < count($this->data[$i])-1) {
						$ret .= $val.$cn;
					} else {
						$ret .= $val;
					}
				}
				$iz++;
			}
			$ret .= $this->zeilentrennung;
		}
		return utf8_decode($ret);
	}
	/**
	 * Save data as
	 *
	 * @param string $filename
	 * @param string $type [_CSV_|XML|HTML]
	 * @return void
	 */
	function saveAs($filename, $type="CSV") {
		$text = "";
		switch($type) {
			case "CSV":
				$text = $this->toCSV();
				break;
			case "XML":
				$text = $this->toXML();
				break;
			case "HTML":
				$text = $this->toString(true);
				break;
			case "RawCode":
				$text = $this->code;
				break;
			default:
				trigger_error("$type is not supoorted Yet");
				break;
		}
		$fh = fopen($filename, "w");
		fputs($fh, $text);
		fclose($fh);
	}
	/**
	 * Save Document
	 * @param string $type [_CSV_|XML|HTML]
	 * @return void
	 */
	function save($type="CSV") {
		$this->saveAs($this->file, $type);
	}
	function array_multisortvert($arr,$orderCol,$orderDir="ASC", $ignoreCase=false) {
		if(is_int($arr[0][$orderCol])||intval($arr[0][$orderCol])!=0) $orderMode = "int";
		for($i=0;$i<count($arr)-1;$i++) {
			$ind = $i;
			for($j=$i;$j<count($arr);$j++) {
				if($orderDir=="ASC") {
					if($orderMode == "int") {
						if(intval($arr[$ind][$orderCol]) > intval($arr[$j][$orderCol])) $ind = $j;
					} else {
						if($ignoreCase) {
							if(strcmp(strtoupper(substr($arr[$ind][$orderCol],0)),strtoupper(substr($arr[$j][$orderCol],0)))>0) $ind = $j;
						}
						else  if(strcmp($arr[$ind][$orderCol],$arr[$j][$orderCol])>0) $ind = $j;
					}
				} else {
					if($orderMode == "int") {
						if(intval($arr[$ind][$orderCol]) < intval($arr[$j][$orderCol])) $ind = $j;
					} else {
						if($ignoreCase) if(strcmp(strtoupper(substr($arr[$ind][$orderCol],0)),strtoupper(substr($arr[$j][$orderCol],0)))>0) $ind = $j;
						else if(strcmp($arr[$ind][$orderCol],$arr[$j][$orderCol])<0) $ind = $j;
					}
				}
			}
			$clip = $arr[$i];
			$arr[$i] = $arr[$ind];
			$arr[$ind] = $clip;
		}
		return $arr;
	}
	public function insert($data) {
		foreach($this->spalten as $spalte) {
			$row[$spalte] = $data[$spalte];
		}
		$this->data[] = $row;
		$this->save();
	}
	public function insertAt(array $data, $index) {
		$newData = array();
		for($i=0;$i<count($this->data);$i++)  {
			if($i==$index) $newData[] = $data;
			$newData[] = $this->data[$i];
		}
		$this->setData($newData);
	}
	/**
	 * Prints a table to stdout
	 *
	 * @param boolean $addStandardStylesheet
	 */
	public function show($addStandardStylesheet=false) {
		echo $this->toString($addStandardStylesheet);
	}
	public function update($line, $data, $save=true) {
		$this->data[$line] = $this->formatArray($data);
		if($save) $this->save();
	}
	public function addRow(array $data=array(), $save=true) {
		$this->update(count($this->data),$data, $save);
}
	protected function formatArray(array $data=array(), $glueArrays=false, $glue="|") {
		if($this->asscocCols) {
			$newLine = array();
			foreach($this->spalten as $col) {
				if($glueArrays) {
					if(is_array($data[$col])) {
						$val = implode($glue, $data[$col]);
					} else $val = $data[$col];
					$newLine[$col] = $val;
				} else {
				$newLine[$col] = $data[$col];
			}
		}
		}
		return $newLine;
	}
	/**
	 * removes a row by it's index (starting from 0)
	 *
	 * @param int $index
	 * @param boolean $save
	 * @return array data
	 */
	public function removeRow($index, $save=true) {
		$returnValue = array();
		for ($i=0;$i<count($this->data);$i++) {
			$add = $i!=$index;
			if($add) $returnValue[] = $this->data[$i];
		}
		$this->data = $returnValue;
		if($save) $this->save();
		return $returnValue;
	}
	/**
	 * Adds a column and fill with data (numerical array), if data is given
	 *
	 * @param string $name Name of the column
	 * @param array $data
	 * @param boolean $save
	 */
	public function addColumn($name, array $data=array(), $save=true) {
		$newData = array();
		$i=0;
		foreach($this->data as $line) {
			$line[$name] = $data[$i];
			$newData[] = $line;
			$i++;
		}
		$this->setData($newData, $this->hasHeaders);
		if($save) $this->save();
	}
	public function addColumnConst($name, $value, $save=true) {
		$arr = array_pad(array(),count($this->data),$value);
		$this->addColumn($name, $arr, $save);
	}
	/**
	 * @param string $name
	 */
	public function removeCol($name) {
		$newData = array();
		$i=0;
		foreach($this->data as $line) {
			foreach($line as $k=>$v) {
				if($k!=$name) $newData[$i][$k] = $v;
			}
			$i++;
		}
		$this->setData($newData);
	}
	public function renameCol($oldname, $newname) {
		$newData = array();
		$i=0;
		foreach($this->data as $line) {
			foreach($line as $k=>$v) {
				if($k!=$oldname) $newData[$i][$k] = $v;
				else $newData[$i][$newname] = $v;
			}
			$i++;
		}
		$this->setData($newData);
	}
	/**
	 * @param array $byColNames
	 * @param string $glue
	 * @return void
	 */
	public function consolidate(array $byColNames, $glue=",", array $colsToGlue) {
		$index = array();
		$newData = array();
		$i=0;
		$j=0;
		foreach($this->data as $line) {
			$key = $this->createRowKey($j, $byColNames);
			if(array_key_exists($key, $index)) {
				$oldRecord = $index[$key];
				foreach($this->spalten as $col) {
					if(in_array($col, $colsToGlue)) {
						$oldData = explode($glue, $newData[$oldRecord][$col]);
						$oldData[] = $line[$col];
						$newData[$oldRecord][$col] = implode($glue, $oldData);
					} else {
						if($line[$col] != "") $newData[$oldRecord][$col] = $line[$col];
					}
				}
			} else {
				$newData[] = $line;
				$index[$key] = $i;
				$i++;
			}
			$j++;
		}
		$this->setData($newData);
	}
	/**
	 * Creates a concatenating of a row as key
	 *
	 * @param int $rowIndex
	 * @param array $byColNames
	 * @return string
	 */
	protected function createRowKey($rowIndex, array $byColNames) {
		foreach($byColNames as $k) {
			$r .= $this->data[$rowIndex][$k];
		}
		return $r;
	}
	/**
	 * gives the count of datarows
	 *
	 * @return int
	 */
	public function length() {
		return count($this->data);
	}
	/**
	 * Removes doublettes.
	 *
	 * Recognized by the value of keyCols:
	 *  '*' means completly
	 *  'email' means a col email
	 *  'email,name' (no whitespaces!) means a key of email concated with name
	 *
	 * I.e. $keyCols='email' means that all rows with the same value in
	 * field email after it's first occurance will be deleted.
	 *
	 * @param string $keyCols
	 * @return void
	 */
	public function removeDoublettes($keyCols="*") {
		$kCols = array();
		if($keyCols=="*") $kCols = $this->spalten;
		else $kCols = explode(",", $keyCols);
		$used = array();
		$newData = array();
		$i=0;
		foreach($this->data as $line) {
			$key = $this->createRowKey($i, $kCols);
			if(!in_array($key, $used)) {
				$newData[] = $line;
				$used[] = $key;
			}
			$i++;
		}
		$this->setData($newData);
	}
	public function setDataMaxHeader($array, $glueArrays=false, $glue="|") {
		if (is_array($array)) {
			$maxHeader = array();
			foreach($array as $line) {
				foreach(array_keys($line) as $k) {
					if(!in_array($k, $maxHeader)) $maxHeader[] = $k; //  $ke$maxHeader = array_merge(array_keys($line), $maxHeader);
				}
			}
			$this->hasHeaders = true;
			$this->spalten = $maxHeader;
			$this->data = array();
			foreach($array as $line) $this->data[] = $this->formatArray($line, $glueArrays, $glue);
		} else {
			throw new Exception("CSV empty, array expected as param 1 in setDataMaxHeader()");
		}
	}
	public function sendToDownload($filename="") {
		if($filename == "") $filename = $this->file;
		if($filename == "") $filename = "Csv.".time().".csv";
		header("Content-Type:text/csv");
		header('Content-Disposition: attachment; filename="'.$filename.'"');
		echo $this->toCSV();
		die();
	}
}
?>
