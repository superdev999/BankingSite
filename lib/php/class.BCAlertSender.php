<?php
class BCAlertSender extends BCImport {
	/**
	 * Table to store subscritons
	 *
	 * @var string
	 */
	private static $subscriptionTable = "BCAlertSenderAbos";
	/**
	 * Table to store send messages
	 *
	 * @var string
	 */
	private static $sendTable = "BCAlertMails";
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
	 * sends out ALER-Mails in recentChanges
	 *
	 */
	public function cron() {
		#echo "Alert cron called.<br>";
		$bcProducts = array_keys($this->getProductItemIndex());
		#echo "SELECT fid FROM ".BCImport::$indexRecentChanges." WHERE fid IN (".implode(",", $bcProducts).") GROUP BY fid<br>";
		$res = $this->getDB()->query("SELECT fid FROM ".BCImport::$indexRecentChanges." WHERE fid IN (".implode(",", $bcProducts).") GROUP BY fid ");
		if(defined ('NOVA_CRON_DEBUG') && NOVA_CRON_DEBUG ) {
			echo "Num of results: ". mysql_num_rows($res)."<br>\n";
		}
		while ($fid = mysql_fetch_object($res)) {
			#echo "Fid: ".$fid->fid."<br>\n";
			try{
				$this->cronPerProduct($fid->fid);
			} catch (Exception $e) {
	    			echo 'Exception abgefangen: ',  $e->getMessage(), "\n";
	    			echo $e->getTraceAsString();
	    			echo "continue...\n";
			}
		}

		$this->getDB()->query("INSERT INTO  ".BCImport::$indexRecentChanges_Done." (
`fid` ,
`name` ,
`oldValue` ,
`value`
)
SELECT fid, name, oldValue, value FROM ".BCImport::$indexRecentChanges);
		$this->getDB()->query("TRUNCATE TABLE ".BCImport::$indexRecentChanges);
	}


	/**
	 * Shows message if user has subscribed to alert
	 * on this product, listens to action via GET[unsubscribe]
	 * or GET[subscribe]
	 *
	 * @param int $nid
	 */
	public static function runInProductItem($nid) {
		global $user;
				$affe = new BCAlertSender();

		if($user->uid == 0)  {
			echo "Um sich für einen ALERT-Newsletter anzumelden, müssen Sie sich registrieren <a href='/user/register'>Jetzt registrieren</a>";
			return;
		}
		$instance = new BCAlertSender();
		$data = array("nid"=>$nid, "uid"=>$user->uid);
		if(array_key_exists("unsubscribe", $_GET)) {
			$instance->getDB()->delete(self::$subscriptionTable, $data);
			drupal_set_message("Sie haben sich erfolgreich für den ALERT-Newsletter für dieses Produkt abgemeldet.");

		} if(array_key_exists("subscribe", $_GET)) {
			$instance->getDB()->insert(self::$subscriptionTable, $data);
			drupal_set_message("Sie haben sich erfolgreich für den ALERT-Newsletter für dieses Produkt angemeldet.");
		}
		if(count($instance->getDB()->select(self::$subscriptionTable, "*", $data))>0) {
			echo "Sie erhalten einen ALERT-Newsletter, sobald sich die Daten ändern <a href='".$affe->remove_get($_SERVER["REQUEST_URI"])."?unsubscribe'>abmelden</a>";
		} else {
			echo "Möchten Sie einen ALERT-Newsletter erhalten, sobald sich die Daten ändern? <a href='".$affe->remove_get($_SERVER["REQUEST_URI"])."?subscribe'>Hier anmelden</a>";
		}
	}

	/*Nova Edit Remove Getparameter 2012-06-12 */

	public static function remove_get($inhalt){
	
	$inhalt = explode('?', $inhalt);
	return $inhalt[0];
	
	}



/*Nova Ende Ende */


	/* Nova Edit Start 2011-11-25 */
	public static function NewsletterButtonText($nid){
		$affe = new BCAlertSender();
		global $user;
		if($user->uid == 0)  {
			echo "<a href='/user/register'>ALERT-Newsletter abonnieren</a>";
			return;
		}
		$instance = new BCAlertSender();
		$data = array("nid"=>$nid, "uid"=>$user->uid);

		if(count($instance->getDB()->select(self::$subscriptionTable, "*", $data))>0) {
			echo "<a href='".$affe->remove_get($_SERVER["REQUEST_URI"])."?unsubscribe'>ALERT-Newsletter kündigen</a>";
		} else {
			echo "<a href='".$affe->remove_get($_SERVER["REQUEST_URI"])."?subscribe'>ALERT-Newsletter abonnieren</a>";
		}

	}
	/* Nove Ende Ende */


	/**
	 * Unsubscribe users by md5($_GET["id"]) = ID of BCAlertMails
	 * and md5($_GET["r]) == MD5-Hash of uid
	 */
	public static function runInUnsubscribe() {
		$instance = new BCAlertSender();
		$md5id = $_GET["id"];
		$md5uid = $_GET["r"];
		if(ereg("^[0-9,a-z]{32}$", $md5id) && ereg("^[0-9,a-z]{32}$", $md5uid)) {
			$sql = "SELECT ".self::$subscriptionTable.".id FROM ".self::$sendTable."
			LEFT JOIN ".BCImport::$indexTable." ON ".self::$sendTable.".fid = ".BCImport::$indexTable.".fid
			LEFT JOIN ".self::$subscriptionTable." ON ".self::$subscriptionTable.".nid = ".BCImport::$indexTable.".nid
			WHERE md5(".self::$sendTable.".id) = '$md5id' AND type = 'productitem' AND md5(".self::$subscriptionTable.".uid) = '$md5uid'
			";
			$todel = $instance->getDB()->simpleQuery($sql);

			if(is_numeric($todel)) {
				$sql = "DELETE FROM ".self::$subscriptionTable." WHERE id = $todel";
				$instance->getDB()->query($sql);
				echo "Sie haben sich erfolgreich von unserem Dienst abgemeldet. <a href='/'>Zur Homepage</a>";
			} else die();
		} else die();
	}
	/**
	 * Build an send the alert-mails for a fid
	 *
	 * @param int $fid
	 */
	public function cronPerProduct($fid) {
		$productNid = $this->getNodeIdByFid($fid);
		$recipients = $this->getDB()->coloumArray("SELECT uid FROM ".self::$subscriptionTable." WHERE nid = ".$productNid);
		// Divide into full recipients and customer
		// full recipients
		$fullRecipientsMails = array("@bankingcheck.de", "bc-alert@boedger.de", "konditionen@boedger.de", "daniel@boedger.de", "melanie@boedger.de", "gerry@boedger.de", "anika@boedger.de");
		#echo "All recipients: ".print_r($recipients, true)."<br>";

		if(defined ('NOVA_CRON_DEBUG_TEST') && NOVA_CRON_DEBUG_TEST ) {
			$recipients = array('2259');
		}

		if(count($recipients) > 0) {
		  $fullRecipients = array();
		  $customerRecipients = array();
		  foreach ($recipients as $uid)
		  {
		    $found = false;
        	foreach ($fullRecipientsMails as $fullRecipientsMail)
		    {
		      if (strpos($fullRecipientsMail, $this->getUser($uid)->mail) !== false)
          		{
            		#echo $this->getUser($uid)->mail." (".$uid.") receives full content.<br>"; 
            		$found = true;
            		break;                          
          		}           
        	}
        	if ($found) $fullRecipients[] = $uid;
        	else {
          		#echo $this->getUser($uid)->mail." (".$uid.") receives customer content.<br>"; 
          	$customerRecipients[] = $uid;
       	 	}           
      	}
      
	      if(defined ('NOVA_CRON_DEBUG') && NOVA_CRON_DEBUG ) {
		      echo "<br><br>";
		      echo "Full receivers: ".print_r($fullRecipients, true)."<br>";
		      echo "Customer receivers: ".print_r($customerRecipients, true)."<br>";
		  }
      
      
	      {// Full data  
				$changes = array();
				$sql = "SELECT * FROM ".BCImport::$indexRecentChanges." WHERE fid = $fid";
				$changes = $this->getDB()->fullTableArray($sql);
				$text = "<ul>
	";
				//echo "Changes: ".print_r($changes, true)."<br>";			
				foreach($changes as $change) {
					//echo "Change: ".print_r($change, true)."<br>";			
					$key = basename($change["name"]);
					#echo "Key: ".$key."<br>";
					$text .= "<li>
	".t($key)." von
	<strike>".BCImportFormats::formatValue($key, $change["oldValue"])."</strike>
	auf
	<strong>".BCImportFormats::formatValue($key, $change["value"])."</strong>
	</li>
	";
				}
				$text .= "
	</ul>";
				$prod = $this->getNode($productNid);
				$myBank = $this->getNode($prod->field_proditemmybank[0]["nid"]);
				$subject = "ALERT-Newsletter: ".$prod->title." v. ".date("d.m.Y");
				$data = array(
					"fid"=>$fid,
					"subject"=>$subject,
					"filename"=>$_SERVER['DOCUMENT_ROOT']."/emails/alert/".date("Y")."/".date("m")."/".date("d")."/".BCImport::propperFileString($subject).".html",
					"recipients"=>count($recipients)
				);
				$id = $this->getDB()->insert(self::$sendTable, $data);
				$gen = new BCEmailGenerator($id, $data["filename"], $subject);
				$gen->addContent(
					new BCEmailGeneratorPartSection(
						array(
							"/".$myBank->path=>$myBank->title,
							"/".$prod->path=>$prod->title
						),
						"Folgende Daten bei dem von Ihnen ausgewählten Produkt haben sich geändert",
						$this->getDB()->simpleQuery("SELECT CONCAT('/', filepath) FROM files WHERE fid = ".$myBank->field_banklogo[0]["fid"]),
						"/".$prod->path,
						$text,
						"Zum Produkt",
						"/".$prod->path
					)
				);
				#echo "Send full data to ".print_r($fullRecipients, true)."<br><br><hr>";
				$gen->saveStatic();		
				foreach($fullRecipients as $uid) {
					$mail_user = $this->getUser($uid);
					$email = new BCEmail($mail_user , $gen);

					try{
						if(!$email->send()) {
							$mail_address = $mail_user->mail;
							throw new Exception("Can't send to user with mail=\"$mail_address\" and uid=\"$uid\"");
						}
					} catch (Exception $e) {
		    			echo 'Exception abgefangen: ',  $e->getMessage(), "\n";
		    			echo $e->getTraceAsString();
		    			echo "continue...\n";
					}

				}
			}// EndFull data  

			{// Part data
				
			  $changes = array();
				$sql = "SELECT * FROM ".BCImport::$indexRecentChanges." WHERE fid = $fid";
				$changes = $this->getDB()->fullTableArray($sql);
				$text = "<ul>
	";
				//echo "Changes: ".print_r($changes, true)."<br>";
	      $sensitiveData = array ("product id", "bankid", "logo", "url");
	      $sentDataKeys = array();			
				foreach($changes as $change) {
					#echo "Change: ".print_r($change, true)."<br>";			
					$key = basename($change["name"]);
					#echo "Key: ".$key."<br>";
					if (in_array($key, $sensitiveData)) 
	        {
	          #echo "Is sensitive data.<br>";
	          continue;
	        }
	        $sentDataKeys[] = $key;
					$text .= "<li>
".t($key)." von
<strike>".BCImportFormats::formatValue($key, $change["oldValue"])."</strike>
auf
<strong>".BCImportFormats::formatValue($key, $change["value"])."</strong>
</li>
";
				}
				$text .= "</ul>";
				if (count($sentDataKeys) == 0) {
					#echo "All data sensitive. Not sent.<br><hr>";
					return;
				}
				$prod = $this->getNode($productNid);
				$myBank = $this->getNode($prod->field_proditemmybank[0]["nid"]);
				$subject = "ALERT-Newsletter: ".$prod->title." v. ".date("d.m.Y");
				$data = array(
					"fid"=>$fid,
					"subject"=>$subject,
					"filename"=>$_SERVER['DOCUMENT_ROOT']."/emails/alert/".date("Y")."/".date("m")."/".date("d")."/".BCImport::propperFileString($subject).".html",
					"recipients"=>count($recipients)
				);
				$id = $this->getDB()->insert(self::$sendTable, $data);
				$gen = new BCEmailGenerator($id, $data["filename"], $subject);
				$gen->addContent(
					new BCEmailGeneratorPartSection(
						array(
							"/".$myBank->path=>$myBank->title,
							"/".$prod->path=>$prod->title
						),
						"Folgende Daten bei dem von Ihnen ausgewählten Produkt haben sich geändert",
						$this->getDB()->simpleQuery("SELECT CONCAT('/', filepath) FROM files WHERE fid = ".$myBank->field_banklogo[0]["fid"]),
						"/".$prod->path,
						$text,
						"Zum Produkt",
						"/".$prod->path
					)
				);
				#echo "Send partly data to ".print_r($customerRecipients, true)."<br><hr>";
	      		$gen->saveStatic();		
				foreach($customerRecipients as $uid) {
					try{
						$email = new BCEmail($this->getUser($uid), $gen);
						if(!$email->send()) {
							$mail_address = $mail_user->mail;
							throw new Exception("Can't send to user with mail=\"$mail_address\" and uid=\"$uid\"");
						}
					} catch (Exception $e) {
		    			echo 'Exception abgefangen: ',  $e->getMessage(), "\n";
		    			echo $e->getTraceAsString();
		    			echo "continue...\n";
					}
				}	
			}//End Part data
		}
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
