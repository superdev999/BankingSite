<?php
class DOMDocumentCreator {
	/**
	 * @param mixed exp
	 * @return DOMDocument
	 */
	static public function create($exp) {
		if($exp=="") return new DOMDocument();
		if(get_class($exp)=="DOMDocument") return $exp;
		elseif(is_file($exp)) return DOMDocument::load($exp);
		else {
			$res = @DOMDocument::loadXML($exp);
			if(!$res) {
				$res = @DOMDocument::loadXML("<".$exp."/>");
				if(!$res) throw new Exception ("Failed creating XML $exp");
				else return $res;
			} else return $res;
		}
	}
}
?>
