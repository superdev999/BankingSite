<?php
class VotingReply {
	private $_node;
	public function __construct($nid) {
		if (is_integer($nid) || is_string($nid)) $this->_node = node_load(array("nid"=>$nid));	
		else if (is_object($nid)) {
			#var_dump($nid);
			if ("reply" == $nid->type) $this->_node = $nid;
			else {
				echo $this->_node->nid." is not a reply: ".$this->_node->type;
				return false;
			}
		} else throw new Exception($nid." is not a valid type.");
	}

	public function getReplyText() {
		return $this->_node->body;
	}

}
?>