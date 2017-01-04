<?php
class BCNodeSender extends BCDBRelated {
	/**
	 * Nodes to be send
	 *
	 * @var array
	 */
	private $nid = array();
	/**
	 * BCEmailGenerator instance for generating HTML
	 *
	 * @var BCEmailGenerator
	 */
	private $gen = null;
	/**
	 * Cache for the node_load
	 *
	 * @var array
	 */
	private $nodeCache = array();
	/**
	 * Cache for the user_load
	 *
	 * @var array
	 */
	private $userCache = array();
	/**
	 * Users who have checked profile_newsletter
	 *
	 * @var unknown_type
	 */
	private $subscribers;
	private static $subscribeProfileFid = 7;
	public static $id = "node_sender";
	public function BCNodeSender(array $nids=null) {
		if(!is_null($nids)) {
			$this->nids = $nids;
			$subject = "BankingCheck-Newsletter v. ".date("d.m.Y");
			$staticFile = $_SERVER['DOCUMENT_ROOT']."/emails/newsletter/".date("Y")."/".date("m")."/".date("d")."/".BCImport::propperFileString($subject).".html";
			$this->gen = new BCEmailGenerator(self::$id, $staticFile, $subject);
			$this->buildNewsletter();
			$this->subscribers = $this->getSubscribers();
		}
	}
	/**
	 * Creates a preview
	 *
	 * @return string
	 */
	public function preview() {
		$r = "";
		$r = "<fieldset><legend>Empfänger</legend><ol>";
		foreach($this->subscribers as $user) {
			$r .= "<li>".$user->mail." <a href='?sendOnlyTo={$user->uid}&{$this->buildNidRequest()}'>Einzeln versenden</a></li>";
		}
		$r .= "</ol>";
		$r .= "<a href='?send={$user->uid}&{$this->buildNidRequest()}'>Alle Versenden</a></fieldset>";
		$r .= "<fieldset><legend>Vorschau</legend>";
		$this->gen->saveStatic();
		$r .= '<iframe src="'.$this->gen->getStaticFileUrl().'?time='.time().'" style="width:100%;height:600px;"></iframe>';
		$r .= "</fieldset>";
		return $r;
	}
	/**
	 * Send out the mails
	 */
	public function send($uid=null) {
		if(is_null($uid)) {
			foreach($this->subscribers as $uid=>$user) {
				$this->innerSend($uid);
			}
		} elseif(array_key_exists($uid, $this->subscribers)) {
			$this->innerSend($uid);
		}
	}
	public static function runInUnsubscribe() {
		$instance = new BCNodeSender();
		$md5uid = $_GET["r"];
		if(ereg("^[0-9,a-z]{32}$", $md5uid)) {
			$sql = "UPDATE profile_values SET value = '0' WHERE fid = ".self::$subscribeProfileFid." AND MD5(uid) = '$md5uid'";
			$instance->getDB()->query($sql);
			echo "Sie haben sich erfolgreich von unserem Dienst abgemeldet. <a href='/'>Zur Homepage</a>";
		}
	}
	private function buildNidRequest() {
		$parts = array();
		for($i=0;$i<count($this->nids);$i++) {
			$parts[] = $i."=".$this->nids[$i];
		}
		return implode("&", $parts);
	}
	private function innerSend($uid) {
		$user = $this->getUser($uid);
		$mail = new BCEmail($user, $this->gen);
		if($mail->send()) {
			drupal_set_message("E-Mail an ".$user->mail." erfolgreich losgeschickt.");
		} else {
			drupal_set_message("E-Mail an ".$user->mail." nicht erfolgreich losgeschickt.","error");
		}
	}
	private function buildNewsletter() {
		foreach($this->nids as $nid) {
			$upperLineLinks = null;
			$node = $this->getNode($nid);
			if($node->type != "story") {
				drupal_set_message("Inhalt vom Typ `$node->type` werden ignoriert","error");
			} else {
				$link = "/".$node->path;
				$myBankId = $node->field_mybank[0]["nid"];
				if($myBankId != "") {
					$myBankNode = $this->getNode($myBankId);
					$upperLineLinks["/".$myBankNode->path] = $myBankNode->title;
				}
				$myProductId = $node->field_myproduct[0]["nid"];
				if($myProductId != "") {
					$myProductNode = $this->getNode($myProductId);
					$upperLineLinks["/".$myProductNode->path] = $myProductNode->title;
				}
				if(count($node->files)>0) {
					$file = array_pop($node->files);
					$imgUrl = "/".$file->filepath;
					$imgLink = $link;
				}
				$this->gen->addContent(new BCEmailGeneratorPartSection(
					$upperLineLinks,
					$node->title,
					$imgUrl,
					$imgLink,
					strip_tags($node->teaser,"<p><strong><span>"),
					"Den ganzen Beitrag lesen",
					$link
				));
			}
		}
	}
	/**
	 * Users who have checked profile_newsletter
	 *
	 */
	private function getSubscribers() {
		$sql = "SELECT uid FROM profile_values WHERE fid = ".self::$subscribeProfileFid." AND value = '1'";
		foreach($this->getDB()->coloumArray($sql) as $uid) {
			$r[$uid] = $this->getUser($uid);
		}
		return $r;
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
	/**
	 * Get an user (cached)
	 *
	 * @param int $uid
	 * @return stdClass a Drupal-User
	 */
	private function getUser($uid) {
		if(!array_key_exists($uid, $this->userCache)) {
			$this->userCache[$uid] = user_load($uid);
		}
		return $this->userCache[$uid];
	}
}
?>