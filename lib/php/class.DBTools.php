<?php
/**
 * Tools for handling with a MySQL-Database
 * @version: 1.1
 * @since: 9.9.2007
 * Changelog 1.1
 * 	- cut propritary Documentation
 * 	- added some JavaSoc
 * 	- major changes and bugfixes on XML-Creaton
 * 	- added methos createSelectSQL, selectXML
 */
abstract class DBToolsBase {
	var $host;
	var $user;
	var $pass;
	var $db;
	/**
	 * @var String
	 */
	var $curQuery;
	var $ident; 						//Private, Connection-ID
	/**
	 * @var string verbos
	 * 	0: keine Fehler keine Warnings,
	 * 	1: Ausgabe von Fehlern
	 * 	2: Ausgabe von Fehlern und Warnings
	 * 	3: Abbruch bei Fehlern
	 * 	4: echo alle Queries
	 */
	var $verbose = 0;
	function fastDB ($database="") {
		if($database!="") {
			$this->db = $database;
		}
		$this->connect();
	}
	function setUtf8() {
		$this->query("SET NAMES utf8");
	}
	function connect() {
		$this->ident = mysql_connect($this->host,$this->user,$this->pass) or die("unable to connect to database");
		if($this->db != "") mysql_select_db($this->db) or die("unable to select database");
	}
	/**
	 * @return int MySQL-Resource
	 */
	function query($query) {
		if(!$this->ident) $this->connect();
		$this->curQuery = $query;
		if($this->verbose == 4) echo $query."<hr>";
		  $res = @mysql_query($query, $this->ident);
			if (!$res) throw new Exception("query failed: <strong>".mysql_error()."</strong><br /><pre>".htmlspecialchars($query)."</pre>");
		  return $res;
	}
	/**
	 * Gibt das erste Feld zur�ck
	 * @return string
	 */
	function simpleQuery($query) {
		$res = "";
		$res = $this->query($query);
		if (!$res) return false;
		$row = mysql_fetch_array($res);
		return $row[0];
	}
	function hashTable($query) {
		foreach($this->fullTableArrayNum($query) as $res) {
			$retr[$res[0]] = $res[1];
		}
		return $retr;
	}
	/**
	 * Gibt das erste Feld zurück
	 * @return string
	 * @deprecated
	 */
	function  simple_query($query) {
		return $this->simpleQuery($query);
	}
	/**
	 *gibt eine zeile zurück
	 * @return array
	 */
	function single_line($query) {
		$res = $this->query($query);
	  if (!$res) return false;
	  return mysql_fetch_array($res);
	}
	/**
	 *gibt eine zeile zurück
	 * @return array
	 */
	function single_line_assoc($query) {
		$res = array();
		$res = $this->query($query);
		if (!$res) return false;
		$res = mysql_fetch_assoc($res);
		if(is_array($res)) return $res;
		else return array();
	}
	function singleLineAssoc($query) {
		return $this->single_line_assoc($query);
	}
	function coloumArray($query) { //Gibt die erste Spalte eines Datensatzes als Array zurück
		$rtr = array();
		$res = $this->query($query);
	  	if (!$res) return array();
	  	$rtr = array();
		while ($row = mysql_fetch_array($res)) {
		  	$rtr[] = $row[0];
		}
		return $rtr;
	}
	function fullTableArray($query) { //Gibte eine Tabelle mit n Zeilen und Assoziativen Spalten als Array aus
		$rtr = array();
		$res = $this->query($query);
		if (!$res) return false;
		while ($row = mysql_fetch_assoc($res)) {
			$rtr[] = $row;
		}
		return $rtr;
	}
	function fullTableArrayNum($query) { //Gibt eine zweispalige Tabelle als assoziativer Array zurück
		$res = $this->query($query);
		$rtr = array();
		if (!$res) return false;
		while ($row = mysql_fetch_row($res)) {
			$rtr[$row[0]] = $row[1];
		}
		return $rtr;
	}
	function fullTableArrayAssoc($query) { //Gibt eine zweispalige Tabelle als assoziativer Array zurück
		$res = $this->query($query);
		if (!$res) return false;
		while ($row = mysql_fetch_row($res)) {
			$rtr[$row[0]] = $row[1];
		}
		return $rtr;
	}
	function hashTableArrayAssoc($query) { //Gibt eine zweispalige Tabelle als assoziativer Array zurück
		$res = $this->query($query);
		if (!$res) return false;
		while ($row = mysql_fetch_row($res)) {
			$rtr[$row[0]] = $row[1];
		}
		return $rtr;
	}
	function hashTableArrayNum($query) { //Gibt eine zweispalige Tabelle als numerisch Array zurück
		$res = $this->query($query);
		if (!$res) return false;
		while ($row = mysql_fetch_row($res)) {
			$rtr[$row[0]] = $row[1];
		}
		return $rtr;
	}
	function fetch_row($res) { // gibt erste zeile einer MySQL-Ressource zurück @returns: Array oder False
	  return @mysql_fetch_array($res);
	}
	function get_insert_id() { // Primary-Key des zuletzt betroffenen Records der Query @returns: Mixed
	  return @mysql_insert_id();
	}

	function get_num_rows($res) { //Anzahl der betroffenen Records der Query @returns: Int
	  return @mysql_num_rows($res);
	}
	public function getNumRowsByQuery($query) {
		$this->query($query);
		return mysql_affected_rows($this->ident);
	}
	function close () { // schließt verbindung
		try {
		mysql_close($this->ident);
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}
	function select_db($newDB) {
		$this->db = $newDB;
		mysql_select_db($this->db);
	}
	function importFile($file, $tabelle, $felderTrennzeichen=";", $felderEingeschlossen="", $felderEingschlossenOptional=true, $feldInhaltEscaped="\\", $zeilentrennung="\n", $zeilenUeberspringen=0, $spalten="", $ueberschreiben=true) {
		if(!is_file($file)) die("$file ist kein File");
		$sql = "LOAD DATA INFILE '".realpath($file)."' REPLACE  INTO TABLE $tabelle FIELDS TERMINATED BY '$felderTrennzeichen' ";
		if($felderEingschlossenOptional) $sql .= "OPTIONALLY";
		$sql .= " ENCLOSED BY '".addslashes($feldInhaltEscaped)."' ESCAPED BY '".addslashes($feldInhaltEscaped)."' LINES TERMINATED BY '$zeilentrennung'";
		if($zeilenUeberspringen > 0) $sql .= " IGNORE $zeilenUeberspringen LINES ";
		//if(is_array($spalten)) $sql .= implode("," $spalten);
		echo $sql;
	}
	function getColNames($table) {
		$data = $this->fullTableArray("SHOW COLUMNS FROM $table");
		foreach($data as $line) {
			$retr[] = $line[Field];
		}
		return $retr;
	}
	/* Funktion um eine Tabelle mit einem Array $data zu überschreiben
	$table: Tabelle
	$data[][]: daten
	$col: Array: wenn spezielle Spalten angegeben werden
	*/
	function insertIgnoreArray($table, $data, $col="", $append=false) {
		if(!$append) $this->query("truncate $table");
		if(is_array($col)) {
			if(is_string($col[0])) { //Assoziative Spaltenname
				foreach($data as $line) {
					$sql = "insert into $table set ";
					unset($field);
					$i = 0;
					$line_keys = array_keys($line);
					foreach($col as $key) {
						$keys = array_keys($line);
						if(!is_string($keys[$i])) $val = $line[$i];
						else $val = $line[$key];
						$field[] = "$key = '$val'";
						$i++;
					}
					$sql .= implode(", ", $field);
					$this->query($sql);
				}
			} else {
				$colName = $this->getColNames($table);
				foreach($data as $line) {
					//$line muss auf alle Fälle numerisch sein:
					unset($value);
					foreach($line as $field) $value[] = $field;
					$sql = "insert into $table set ";
					unset($field);
					for($i=0; $i<count($col); $i++) $field[] = $colName[$col[$i]]." = '".$value[$i]."'";
					$sql .= implode(", ", $field);
					$this->query($sql);
					echo $sql;
				}
			}
		} else {
			if(is_array($data)) {
				$colName = $this->getColNames($table);
				foreach($data as $line) {
					$sql = "insert into $table set ";
					$keys = array_keys($line);
					unset($field);
					if(is_string($keys[0])) { //Assoziative Daten
						foreach(array_keys($line) as $key) $field[] = "$key = '".$line[$key]."'";
					} else {
						for($i=0; $i<count($colName); $i++) $field[] = $colName[$i]." = '".$line[$i]."'";
					}
					$sql .= implode(", ", $field);
					$this->query($sql);
				}
			}
		}
	}
	/* Funktion um eine Tabelle mit einem Array $data zu upzudaten
	$table: Tabelle
	$data[][]: daten
	$key: Schlüsselspalte anhand derer upgedatet wird, kann numerisch oder der Spaltenname sein,
	$col: Array: wenn spezielle Spalten angegeben werden
	*/
	function recordExists($table, $col, $value) {
		if(!is_string($col)) {
			$cols = $this->getColNames($table);
			$col = $cols[$col];
		}
		$c = $this->simple_query("SELECT `$col` FROM  `$table` WHERE `$col` = '$value'");
		return $c != "";
	}
	function insertUpdateArray($table, $data, $keyCol, $col="") {
		$tempTab1 = $table."_1_".date("Ymdhis");
		$this->copyTable($table, $tempTab1, $noData=true);
		$this->insertIgnoreArray($tempTab1, $data);
		if(!is_string($keyCol)) {
			$cols = $this->getColNames($table);
			$keyCol = $cols[$keyCol];
		}
		$MengeA = $this->coloumArray("select $keyCol from $table");
		$MengeB = $this->coloumArray("select $keyCol from $tempTab1");
		$MengeAGeschnittenB = array_intersect($MengeA, $MengeB);
		foreach($MengeAGeschnittenB as $del) $this->query("delete from $table where $keyCol = '$del'");
		$data = $this->fullTableArray("select * from $tempTab1");
		$this->insertIgnoreArray($table, $data, $col, $append=true);
		$this->query("drop table $tempTab1");
		return array(count($MengeA), count($MengeB), count($MengeAGeschnittenB));
	}
	function getCreateTableSQL($table, $fields) {
		foreach($fields as $col) {
			$sql = "`".$col[Field]."` ".$col[Type];
			if($col[Null] != "YES") $sql .= " NOT NULL";
			if($col["Default"] != "")$sql .= " default '".$col["Default"]."' ";
			$sql .= " ".$col[Extra]." ";
			switch($col[Key]) {
				case "PRI": $addRows[] = "PRIMARY KEY  (`".$col[Field]."`)";
				break;
				case "UNI": $addRows[] = "UNIQUE KEY `".$col[Field]."` (`".$col[Field]."`)";
				break;
				case "MUL": $addRows[] = "FULLTEXT KEY `".$col[Field]."` (`".$col[Field]."`)";
				break;
			}
			if($col[Extra] == "auto_increment") $aI = true;
			$rows[] = $sql;
		}
		$sql = 'CREATE TABLE `'.$table.'` (
		';
		$sql .= implode(",\n", $rows);
		if(count($addRows)>0) $sql .= ",\n".implode(",\n", $addRows);
		$sql .= ") TYPE=MyISAM";
		if($aI) $sql .= " AUTO_INCREMENT=1";
		$sql .= ";";
		return $sql;

	}
	function getTableCreateCode($srcTable) {
		// Tabellenstruktur kopieren
		$desc = $this->fullTableArray("describe ".$srcTable);
		$aI = false;
		foreach($desc as $col) {
			$sql = "`".$col[Field]."` ".$col[Type];
			if($col[Null] != "YES") $sql .= " NOT NULL";
			if($col["Default"] != "")$sql .= " default '".$col["Default"]."' ";
			$sql .= " ".$col[Extra]." ";
			switch($col[Key]) {
				case "PRI": $addRows[] = "PRIMARY KEY  (`".$col[Field]."`)";
				break;
				case "UNI": $addRows[] = "UNIQUE KEY `".$col[Field]."` (`".$col[Field]."`)";
				break;
				case "MUL": $addRows[] = "FULLTEXT KEY `".$col[Field]."` (`".$col[Field]."`)";
				break;
			}
			if($col[Extra] == "auto_increment") $aI = true;
			$rows[] = $sql;
		}
		$sql = 'CREATE TABLE `'.$srcTable.'` (
		';
		$sql .= implode(",\n", $rows);
		if(count($addRows)>0) $sql .= ",\n".implode(",\n", $addRows);
		$sql .= ") TYPE=MyISAM";
		if($aI) $sql .= " AUTO_INCREMENT=1";
		$sql .= ";";
		return $sql;
	}
	function copyTable($srcTable, $dstTable, $noData=false) {
		// Tabellenstruktur kopieren
		$sql = $this->getTableCreateCode($srcTable);
		$sql = str_replace("CREATE TABLE `$srcTable` (", "CREATE TABLE `$dstTable` (", $sql);
		$this->query($sql);
		$data = $this->fullTableArray("select * from ".$srcTable);
		if(is_array($data)&&!$noData) $this->insertIgnoreArray($dstTable, $data);
	}
	function toCsv($query, $felderTrennzeichen=";", $felderEingeschlossen="", $feldInhaltEscaped="\\", $zeilentrennung="\n", $zeilenUeberspringen=0, $spalten="", $ueberschreiben=true, $hasHeaders=true) {
		$data = $this->fullTableArray($query);
		if($hasHeaders) {
			if(is_array($spalten)) $lines[] = $felderEingeschlossen.implode($felderEingeschlossen.$felderTrennzeichen.$felderEingeschlossen, $spalten).$felderEingeschlossen;
			else $lines[] = $felderEingeschlossen.implode($felderEingeschlossen.$felderTrennzeichen.$felderEingeschlossen, array_keys($data[0])).$felderEingeschlossen;
		}
		if(is_array($spalten)) {
		} else {
			foreach($data as $line) $lines[] = $felderEingeschlossen.implode($felderEingeschlossen.$felderTrennzeichen.$felderEingeschlossen, $line).$felderEingeschlossen;
		}
		return implode($zeilentrennung, $lines);
	}
	function renameTable($src, $dst) {
		$this->query("ALTER TABLE `$src` RENAME `$dst`");
	}
	function countRows($query) {
		$res = $this->query($query);
		if($res) return mysql_num_rows($res);
	}
	function getTableRows($table) {
		return $this->simpleQuery("SELECT COUNT(*) FROM $table");
	}
	function getProto() {
		//exec("whoami", $m);
		exec("/usr/bin/mysqlbinlog /var/lib/mysql/cvs-bin.023", $m);
  		return $m;
	}
	function fieldExists($fieldName, $table) {
		$res = $this->query("show COLUMNS from $table LIKE '$fieldName'");
		return mysql_num_rows($res) > 0;
	}
	function fromXML($XMLFile, $XPathQuery, $table, $append="false") {
		$XPath = new XPath($XMLFile);
		if(!$append) $this->query("TRUNCATE TABLE $table");
		foreach($XPath->match($XPathQuery) as $res) {
			$this->insert($table, $XPath->getAttributes($res));
		}
	}
	function splitMassQuery(&$ret, $sql, $release)
	{
	    $sql          = trim($sql);
	    $sql_len      = strlen($sql);
	    $char         = '';
	    $string_start = '';
	    $in_string    = FALSE;
	    $time0        = time();

	    for ($i = 0; $i < $sql_len; ++$i) {
		$char = $sql[$i];

		// We are in a string, check for not escaped end of strings except for
		// backquotes that can't be escaped
		if ($in_string) {
		    for (;;) {
			$i         = strpos($sql, $string_start, $i);
			// No end of string found -> add the current substring to the
			// returned array
			if (!$i) {
			    $ret[] = $sql;
			    return TRUE;
			}
			// Backquotes or no backslashes before quotes: it's indeed the
			// end of the string -> exit the loop
			else if ($string_start == '`' || $sql[$i-1] != '\\') {
			    $string_start      = '';
			    $in_string         = FALSE;
			    break;
			}
			// one or more Backslashes before the presumed end of string...
			else {
			    // ... first checks for escaped backslashes
			    $j                     = 2;
			    $escaped_backslash     = FALSE;
			    while ($i-$j > 0 && $sql[$i-$j] == '\\') {
				$escaped_backslash = !$escaped_backslash;
				$j++;
			    }
			    // ... if escaped backslashes: it's really the end of the
			    // string -> exit the loop
			    if ($escaped_backslash) {
				$string_start  = '';
				$in_string     = FALSE;
				break;
			    }
			    // ... else loop
			    else {
				$i++;
			    }
			} // end if...elseif...else
		    } // end for
		} // end if (in string)

		// We are not in a string, first check for delimiter...
		else if ($char == ';') {
		    // if delimiter found, add the parsed part to the returned array
		    $ret[]      = substr($sql, 0, $i);
		    $sql        = ltrim(substr($sql, min($i + 1, $sql_len)));
		    $sql_len    = strlen($sql);
		    if ($sql_len) {
			$i      = -1;
		    } else {
			// The submited statement(s) end(s) here
			return TRUE;
		    }
		} // end else if (is delimiter)

		// ... then check for start of a string,...
		else if (($char == '"') || ($char == '\'') || ($char == '`')) {
		    $in_string    = TRUE;
		    $string_start = $char;
		} // end else if (is start of string)

		// ... for start of a comment (and remove this comment if found)...
		else if ($char == '#'
			 || ($char == ' ' && $i > 1 && $sql[$i-2] . $sql[$i-1] == '--')) {
		    // starting position of the comment depends on the comment type
		    $start_of_comment = (($sql[$i] == '#') ? $i : $i-2);
		    // if no "\n" exits in the remaining string, checks for "\r"
		    // (Mac eol style)
		    $end_of_comment   = (strpos(' ' . $sql, "\012", $i+2))
				      ? strpos(' ' . $sql, "\012", $i+2)
				      : strpos(' ' . $sql, "\015", $i+2);
		    if (!$end_of_comment) {
			// no eol found after '#', add the parsed part to the returned
			// array if required and exit
			if ($start_of_comment > 0) {
			    $ret[]    = trim(substr($sql, 0, $start_of_comment));
			}
			return TRUE;
		    } else {
			$sql          = substr($sql, 0, $start_of_comment)
				      . ltrim(substr($sql, $end_of_comment));
			$sql_len      = strlen($sql);
			$i--;
		    } // end if...else
		} // end else if (is comment)

		// ... and finally disactivate the "/*!...*/" syntax if MySQL < 3.22.07
		else if ($release < 32270
			 && ($char == '!' && $i > 1  && $sql[$i-2] . $sql[$i-1] == '/*')) {
		    $sql[$i] = ' ';
		} // end else if

		// loic1: send a fake header each 30 sec. to bypass browser timeout
		$time1     = time();
		if ($time1 >= $time0 + 30) {
		    $time0 = $time1;
		    //header('X-pmaPing: Pong');
		} // end if
	    } // end for

	    // add any rest to the returned array
	    if (!empty($sql) && ereg('[^[:space:]]+', $sql)) {
		$ret[] = $sql;
	    }

	    return TRUE;
	} // end of the 'PMA_splitSqlFile()' function
	function massQuery($sql) {
		$ret = "";
		$this->splitMassQuery($ret, $sql, 40000);
		if(sizeof($ret)>0) {
			foreach($ret as $r)
				$result=$this->query($r);
			return $result;
		} else {
			return false;
		}
	}
	/**
	 * Creates XML From Query
	 *
	 * @param string $query
	 * @param string $itemTag
	 * @param string $rootTag
	 * @param string $type
	 * @param string $lineTag
	 * @param boolean $returnAsDoc
	 * @return string|DOMDocument
	 */
	public function queryToXML($query, $itemTag="item", $rootTag="root", $type="attributes", $lineTag="line", $returnAsDoc=false, $utf8Encode=false) {
		$res = $this->fullTableArray($query);
		if($utf8Encode) {
			$newRes = array();
			foreach($res as $line) {
				$newRow = array();
				foreach($line as $k=>$v) $newRow[$k] = utf8_encode($v);
				$newRes[] = $newRow;
			}
			$res = $newRes;
		}
		$gen = new XMLGenerator($rootTag);
		foreach($res as $key=>$line) {
			if($type == "attributes") $gen->objectToXMLInNewTag($itemTag, $line, NULL, "attributes", false, $itemTag);
			elseif($type == "tags") $gen->objectToXMLInNewTag($lineTag, $line, NULL, "tags", false, $itemTag);
			else throw new Exception("$type is not implemented");
		}
		if($returnAsDoc) return $gen->doc;
		else return $gen->doc->saveXML($gen->docEl);
	}
	/**
	 * Creates a XML-String from data
	 * @return string
	 * @param string
	 * @param string
	 * @param string $type could be attributes, tags
	 * @param stirng $lineTag only neccessary if $type = tags
	 *
	 */
	public function arrayToXML($data, $itemTag="item", $rootTag="root", $type="attributes", $lineTag="line", $returnAsDoc=false) {
		$doc = DOMDocument::loadXML("<$rootTag/>");
		$docEl = $doc->documentElement;
		switch($type) {
			case "attributes":
				for($i=0;$i<count($data);$i++) {
					$item = $doc->createElement($itemTag);
					if(is_array($data[$i])) {
						foreach($data[$i] as $k=>$v) {
							$item->setAttribute($k, $v);
						}
					}
					$docEl->appendChild($item);
				}
				break;
			case "tags":
				foreach($data as $arrEntry) {
					$item = $doc->createElement($itemTag);
					$docEl->appendChild($item);
					foreach($arrEntry as $key => $value) {
						$entry = $doc->createElement($itemTag, htmlspecialchars($value));
						$entry->setAttribute("name", $key);
						$item->appendChild($entry);
					}
				}
				break;
			case "namedtags":
				foreach($data as $arrEntry) {
					$item = $doc->createElement($lineTag);
					$docEl->appendChild($item);
					foreach($arrEntry as $key => $value) {
						$entry = $doc->createElement($key, htmlspecialchars($value));
						$item->appendChild($entry);
						}
					}
				break;
			default:
				die("Not implemented Type $type");
				break;
		}
		if($returnAsDoc) return $doc;
		else return $doc->saveXML($docEl);
	}
	/**
	 * Enter description here...
	 *
	 * @param string $line
	 * @param string $type
	 * @param string $lineTag
	 * @param string $itemTag
	 * @return string
	 */
	static function lineToXML($line, $type="attributes", $lineTag="line", $itemTag="item") {
		$doc = self::arrayToXML(array($line), $itemTag, "root", $type, $lineTag, true);
		return $doc->saveXML($doc->getElementsByTagName($lineTag)->item(0));
	}

	function selectNotEmpty($table, $fields, $cond="1", $condConnection="and") {
		$res = $this->select($table, $fields, $cond, $condConnection);
		return is_array($res);
	}
	/**
		table: 	SQL-Tabelle
		fields:	SQL-Felder
		cond:	Bedingung(en), kann Vektor oder Skalar sein
		condConnection:	(AND/OR) Verknüpfung der Bedingungen,  kann Vektor oder Skalar sein
		 	1. Fall cond ist Skalar: dann gilt für die Query: "where $cond" (condConnection ist bedeutungslos)
		 	2. Fall cond ist Vektor:
		 		2.a) condConnection ist Skalar:
		 			dann gilt für die Query: "where
		 				$cond[Key] = $cont[Value] $condConnection $cond[Key] = $cont[Value] ...
		 		2.b) condConnection ist Vektor:
		 			dann gilt für die Query: "where
		 				$cond[Key] = $cont[Value] $condConnection[0] $cond[Key] = $cont[Value] $condConnection[0] ...

		 	Der Where-String wird in $this->_getWhereExpression() ausbaldowert
	*/
	function select($table, $fields="*", $cond="1", $condConnection="and", $orderBy="") {
		$sql = $this->createSelectSQL($table, $fields, $cond, $condConnection, $orderBy);
		return $this->fullTableArray($sql);
	}
	public function createSelectSQL($table, $fields="*", $cond="1", $condConnection="and", $orderBy="") {
		$sql = "SELECT $fields FROM $table WHERE ".$this->_getWhereExpression($cond, $condConnection);
		if($orderBy != "") $sql .= " ORDER BY $orderBy";
		return $sql;
	}
	public function selectXML($table, $fields="*", $cond="1", $condConnection="and", $orderBy="", $itemTag="item", $rootTag="root", $type="attributes", $lineTag="line") {
		$sql = $this->createSelectSQL($table, $fields, $cond, $condConnection, $orderBy);
		return $this->queryToXML($sql, $itemTag, $rootTag, $type,$lineTag);
	}
	function insert($table, $data) {
		foreach($this->getColNames($table) as $field) {
			if($data[$field]!="") $insertData[$field] = $data[$field];
		}
		return $this->insertInner($table, $insertData);
	}
	function insertInner($table, $data) {
		$sql = "insert into $table set ".$this->_getSetExpression($data);
		$this->query($sql);
		return $this->get_insert_id();
	}
	function delete($table, $cond="1", $condConnection="and") {
		$sql = "delete from $table where ".$this->_getWhereExpression($cond, $condConnection);
		$this->query($sql);
		return $this->get_insert_id();
	}
	function update($table, $data, $cond="1", $condConnection="and") {
		$sql = "update $table set ".$this->_getSetExpression($data)." where ".$this->_getWhereExpression($cond, $condConnection);
		$this->query($sql);
		return $this->get_insert_id();
	}
	function _getWhereExpression($cond, $connection) {
		if(is_array($cond)) {
			foreach($cond as $k=>$v) $retr[] = "$k = '$v'";
			if(is_array($connection) && (count($cond) == count($connection)-1)) { //cond && connection Matrizen
				for($i=0;$i<count($cond);$i++) {
					$sql .= $retr[$i]." ".$connection[$i]." ";
				}
				return $sql." ".$connection[$i++];
			} else { //cond Matrix und connect Skalar:
				return implode(" $connection ", $retr);
			}
		} else { //cond ist skalar
			return $cond;
		}
	}
	function _getSetExpression($data) {
		if(is_array($data)) {
			foreach($data as $k=>$v) $retr[] = "`$k` = '".addslashes($v)."'";
			return implode(",", $retr);
		} return $data;
	}
	function _getAndExpression($data) {
		if(is_array($data)) {
			foreach($data as $k=>$v) $retr[] = "$k = '$v'";
			return implode(" AND ", $retr);
		} return $data;
	}
	function print_q($query, $printYet=true, $max=-1, $statistics=false, $standardStyleSheet=true) {
		if(!is_array($query)) $data = $this->fullTableArray($query);
		else $data=$query;
		if(sizeof($data)>0) {
			$html = "";
			if($standardStyleSheet) {
				$html = "<style>div.QueryVisualizer { background-color:#FFFFEE; } div.QueryVisualizer * { font-size:10px; font-family:Tahoma; } div.QueryVisualizer div.table { border:1px solid Black; height:300px; overflow:scroll; width:200px; float:left; } div.QueryVisualizer div.table td { border-bottom:1px dotted; } div.QueryVisualizer caption { font-size:11px; font-weight:bold; text-align:left; }
table.DBTool_print_q {
border-collapse:collapse;
font-size:11px;
font-family:Tahoma, sans-serif;
}
table.DBTool_print_q th {
background-color:#ddd;
}
table.DBTool_print_q th,
table.DBTool_print_q td {
border:1px solid #aaa;
vertical-align:top;
}
tr.alt {
background-color:#f0fef0;
}
tr:hover {
background-color:#ffffa0;
}
td:hover {
background-color:#FFCC99;
cursor:pointer;
}
table.DBTool_print_q textarea {
width:600px;
height:100px;
text-align:left;
border:1px solid black;
display:block;
}
</style>";
			}
			$html .= '<table class="DBTool_print_q">';
			if($statistics) {
				$html .= "<caption>Results: ".count($data)." <textarea>".$query."</textarea></caption>";
			}
			$html .= '<thead><tr><th>'.implode("</th><th>", array_keys($data[0]))."</th></tr></thead><tbody>";
			$i=0;
			foreach($data as $row) {
				unset($newRow);
				foreach($row as $col) $newRow[] = $col;
				$i%2==0?$rowClass=' class="alt" ':$rowClass="";
				$html .= '<tr'.$rowClass.'><td>'.implode("</td><td>", $newRow)."</td></tr>";
				if($max>-1) {
					if($i++>=($max-1)) break;
			}
			}
			$html .= '</tbody></table>';
		}
		if($printYet) echo $html;
		else return $html;
	}
	function tableExists($table) {
		return $this->simple_query("show tables like '$table'") == $table;
	}
    // {{{ function parseDSN($dsn)

    /**
     * Parse a data source name.
     *
     * Additional keys can be added by appending a URI query string to the
     * end of the DSN.
     *
     * The format of the supplied DSN is in its fullest form:
     * <code>
     *  phptype(dbsyntax)://username:password@protocol+hostspec/database?option=8&another=true
     * </code>
     *
     * Most variations are allowed:
     * <code>
     *  phptype://username:password@protocol+hostspec:110//usr/db_file.db?mode=0644
     *  phptype://username:password@hostspec/database_name
     *  phptype://username:password@hostspec
     *  phptype://username@hostspec
     *  phptype://hostspec/database
     *  phptype://hostspec
     *  phptype(dbsyntax)
     *  phptype
     * </code>
     *
     * @param   string  Data Source Name to be parsed
     *
     * @return  array   an associative array with the following keys:
     *  + phptype:  Database backend used in PHP (mysql, odbc etc.)
     *  + dbsyntax: Database used with regards to SQL syntax etc.
     *  + protocol: Communication protocol to use (tcp, unix etc.)
     *  + hostspec: Host specification (hostname[:port])
     *  + database: Database to use on the DBMS server
     *  + username: User name for login
     *  + password: Password for login
     *
     * @access  public
     * @author  Tomas V.V.Cox <cox@idecnet.com>
     */
    function parseDSN($dsn) {
//         $parsed = $GLOBALS['_MDB2_dsninfo_default'];

        if (is_array($dsn)) {
            $dsn = array_merge($parsed, $dsn);
            if (!$dsn['dbsyntax']) {
                $dsn['dbsyntax'] = $dsn['phptype'];
            }
            return $dsn;
        }

        // Find phptype and dbsyntax
        if (($pos = strpos($dsn, '://')) !== false) {
            $str = substr($dsn, 0, $pos);
            $dsn = substr($dsn, $pos + 3);
        } else {
            $str = $dsn;
            $dsn = null;
        }

        // Get phptype and dbsyntax
        // $str => phptype(dbsyntax)
        if (preg_match('|^(.+?)\((.*?)\)$|', $str, $arr)) {
            $parsed['phptype']  = $arr[1];
            $parsed['dbsyntax'] = !$arr[2] ? $arr[1] : $arr[2];
        } else {
            $parsed['phptype']  = $str;
            $parsed['dbsyntax'] = $str;
        }

        if (!count($dsn)) {
            return $parsed;
        }

        // Get (if found): username and password
        // $dsn => username:password@protocol+hostspec/database
        if (($at = strrpos($dsn,'@')) !== false) {
            $str = substr($dsn, 0, $at);
            $dsn = substr($dsn, $at + 1);
            if (($pos = strpos($str, ':')) !== false) {
                $parsed['username'] = rawurldecode(substr($str, 0, $pos));
                $parsed['password'] = rawurldecode(substr($str, $pos + 1));
            } else {
                $parsed['username'] = rawurldecode($str);
            }
        }

        // Find protocol and hostspec

        // $dsn => proto(proto_opts)/database
        if (preg_match('|^([^(]+)\((.*?)\)/?(.*?)$|', $dsn, $match)) {
            $proto       = $match[1];
            $proto_opts  = $match[2] ? $match[2] : false;
            $dsn         = $match[3];

        // $dsn => protocol+hostspec/database (old format)
        } else {
            if (strpos($dsn, '+') !== false) {
                list($proto, $dsn) = explode('+', $dsn, 2);
            }
            if (strpos($dsn, '/') !== false) {
                list($proto_opts, $dsn) = explode('/', $dsn, 2);
            } else {
                $proto_opts = $dsn;
                $dsn = null;
            }
        }

        // process the different protocol options
        $parsed['protocol'] = (!empty($proto)) ? $proto : 'tcp';
        $proto_opts = rawurldecode($proto_opts);
        if (strpos($proto_opts, ':') !== false) {
            list($proto_opts, $parsed['port']) = explode(':', $proto_opts);
        }
        if ($parsed['protocol'] == 'tcp') {
            $parsed['hostspec'] = $proto_opts;
        } elseif ($parsed['protocol'] == 'unix') {
            $parsed['socket'] = $proto_opts;
        }

        // Get dabase if any
        // $dsn => database
        if ($dsn) {
            // /database
            if (($pos = strpos($dsn, '?')) === false) {
                $parsed['database'] = $dsn;
            // /database?param1=value1&param2=value2
            } else {
                $parsed['database'] = substr($dsn, 0, $pos);
                $dsn = substr($dsn, $pos + 1);
                if (strpos($dsn, '&') !== false) {
                    $opts = explode('&', $dsn);
                } else { // database?param1=value1
                    $opts = array($dsn);
                }
                foreach ($opts as $opt) {
                    list($key, $value) = explode('=', $opt);
                    if (!isset($parsed[$key])) {
                        // don't allow params overwrite
                        $parsed[$key] = rawurldecode($value);
                    }
                }
            }
        }

        return $parsed;
    }
    public function getMaxColumnLength($table, $col) {
    	return $this->simpleQuery("SELECT max(length( `$col` )) FROM `$table` ");
    }
    public function __destruct() {
    	//$this->close();
    }
	public function getEnumValues($table, $field) {
		foreach($this->fullTableArray("DESC `$table`") as $line) {
			if($line["Field"]==$field) {
				if(ereg("enum\((.*)\)", $line["Type"], $m)) {
					$values = array();
					foreach(explode(",", $m[1]) as $value) {
						if(ereg("'(.*)'", $value, $m)) $values[] = $m[1];
						else $values[] = $value;
					}
				}
				return $values;
			}
		}
	}
}
class DBTools extends DBToolsBase {
	function DBTools ($dsn) {
		$this->dsn = $dsn;
		$this->parsedDSN = parent::parseDSN($this->dsn);
		$this->host = $this->parsedDSN["hostspec"];
		if(array_key_exists("port", $this->parsedDSN)) {
			if($this->parsedDSN["port"]!="") $this->host .= ":".$this->parsedDSN["port"];
		}
		$this->user = $this->parsedDSN["username"];
		$this->pass = $this->parsedDSN["password"];
		$this->db = $this->parsedDSN["database"]; //Default-Datenbank
		parent::fastDB();
	}
}
?>
