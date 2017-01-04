<?php

require 'PHPMailer/class.phpmailer.php';
/**
 * @version 0.1
 * @since 07.08.07
 */
interface MailCoder {
	/**
	 * Returns code belongs to header
	 * @return string code
	 */
	public function getHeader();
	/**
	 * Returns code belongs to Body (without boundary)
	 * @return string code
	 */
	public function getBody();
	/**
	 * Sets the code for several purposes
	 * @param string $code
	 * @return void
	 */
	public function setCode($code);
}
abstract class MailCoderBase {
	protected function mapCharset($input) {
		switch($input) {
			case "UTF-8":
			case "utf-8":
			case "utf8":
			case "UTF8":
				return "utf-8";
				break;
			case "iso-8859-1":
			case "ISO-8859-1":
				return "iso-8859-1";
				break;
			case "us-ascii":
				return "us-ascii";
				break;
			default:
				trigger_error("The charset $input isn't supported yet", E_USER_ERROR);
				break;
		}
	}
}
/**
 * represents an abstract class for generating E-Mail code-fragments for text
 * @version 0.1
 * @since 07.08.07
 *
 */
class MailTextCoder extends MailCoderBase implements MailCoder {
	public $transferEncoding;
	public $contentType = "text/plain";
	public $charset;
	protected $lineTermination = "\n";
	public $ingoingTextCharset;
	private $text;
	public $code;
	/**
	 * Constructor, sets text
	 */
	public function MailTextCoder($text, $ingoingTextCharset="utf-8", $targetCharset="utf-8") {
		$this->ingoingTextCharset = $this->mapCharset($ingoingTextCharset);
		$this->setCharset($targetCharset);
		$this->setCode($text);
	}
	public function setCode($text) {
		$this->text = $text;
	}
	/**
	 * registers charset to parse to
	 * @throws E_USER_ERROR
	 * @param string $charset
	 * @return void
	 */
	protected final function setCharset($charset) {
		switch($this->mapCharset($charset)) {
			case "utf-8":
				$this->charset = "utf-8";
				$this->transferEncoding = "8bit";
				break;
			case "iso-8859-1":
				$this->charset = "iso-8859-1";
				$this->transferEncoding = "8bit";
				break;
			case "us-ascii":
				$this->charset = "us-ascii";
				$this->transferEncoding = "7bit";
				break;
		}
	}
	/**
	 * Delivers headers Content-Type, charset, Content-Transfer-Encoding
	 * @return string
	 */
	public function getHeader() {
		$returnValues[] = "Content-Type: ".$this->contentType.";".'charset="'.$this->charset.'"';
		$returnValues[] = "Content-Transfer-Encoding: ".$this->transferEncoding;
		return implode($this->lineTermination, $returnValues);
	}
	/**
	 * Delivers text in encoding according to $this->charset
	 * @return string
	 */
	public function getBody() {
		if($this->ingoingTextCharset == $this->charset) return $this->text;
		else {
			if($this->charset == "utf-8") {
				return utf8_encode($this->text);
			} else {
				if($this->ingoingTextCharset == "utf-8") return utf8_decode($this->text);
			}
		}
	}
}
/**
 * @version 0.1
 * @since 07.08.07
 */
class TextMailTextCoder extends MailTextCoder {
	public function Mailtext($text, $ingoingTextCharset="utf-8") {
		parent::MailTextCoder($text, $ingoingTextCharset);
		$this->transferEncoding = "8bit";
	}
}

/**
 * @version 0.1
 * @since 07.08.07
 */
class BothUTF8TextCoder extends MailTextCoder {
	public function __construct($text) {
		parent::MailTextCoder($text,"utf-8");
		$this->setCharset("utf-8");
	}
}

/**
 * Default encoding for Email since version 1.0
 * Incomming UTF-8, outgoing UTF-8, means no conversion at all
 * @version 0.1
 * @since 07.08.07
 */
class HTMLMailTextCoder extends MailTextCoder {
	public function HTMLMailTextCoder($text, $ingoingTextCharset="utf-8") {
		parent::MailTextCoder($text, $ingoingTextCharset);
		$this->contentType = "text/html";
		$this->setCharset("utf-8");
	}
}

/**
 * Represents and sends E-Mails
 *
 * Changelog:
 * 	version 1.2
 * 		- encapsulates Mailtext and MailHTML as children of MailTextCode
 * 		- added setTextMailTextCoder, setHTMLMailTextCoder
 * 		- bug in generateMailCodeAndHeaders: Mailext was overwritten even if was set
 * 		- compatible with 1.1, 1.0
 *  version 1.1
 * 		- added documentation
 * 		- delteted deprected send()
 * 		- added validateEmailString()
 * 		- added setAdditionalParameter() instead of addAditional_parameters
 * 		- added modifiers, so the class is NOT MORE AVAILABLE ON PHP4
 * 		- compatible with 1.0
 * 		- send returns not automaticly TRUE, but the result of mail()
 *  version 1.0
 * 		- charset for HTML changed to utf-8
 * 		- not compatible to 0.9
 *
 * @author fettel <fettel@navigate.de>
 * @author
 * @since 7. 08. 2007
 * @version 1.2
 */
class Email {
	/**
	 * Recipient
	 *
	 * @var string To
	 */
	public $To = "";
	/**
	 * @var string
	 */
	public $From = "";
	/**
	 * @var string
	 */
	public $ReplyTo = "";
	/**
	 * Where errors should by send
	 *
	 * @var string
	 */
	public $ErrorsTo = "";

	/**
	 * @var string
	 */
	public $Subject = "";
	/**
	 * @var string
	 */
	public $Mailtext = "";

	/**
	 * @var TextMailTextCoder
	 */
	public $mailTextCoder = null;

	/**
	 * @var int
	 */
	public $Priority = 3;
	/**
	 * @var string
	 */
	public $HTMLtext = "";
	/**
	 * @var HTMLMailTextCoder
	 */
	public $HTMLTextCoder = null;

	/**
	 * Placeholder for filenames to attach
	 *
	 * @var array
	 */
	public $Files = array();
	/**
	 * List of extensions that indicates the accepted filetypes
	 *
	 * @var array
	 */
	public $acceptedFiletypes = array("txt", "rtf", "htm", "html", "php", "jpg", "jpeg", "gif", "bmp", "pdf", "doc", "xls", "ppt", "pps", "csv");
	/**
	 * Max allowd coutn of bytes
	 *
	 * @var string
	 */
	public $maxByteSize = "750000";
	/**
	 * Indicates, wheater the mail is mixed type or not
	 *
	 * @var boolean
	 */
	public $htmlAndText = false;
	/**
	 * Indicates, wheater a disposition-notification is required or not
	 *
	 * @var boolean
	 */
	public $dispositionNotification = false;
	/**
	 * Feeds the additional_parameters parameter of PHP-mail() if set
	 *
	 * @var boolean
	 */
	public $additional_parameters = '';
	/**
	 * A list of codes that are not allowed in headers
	 *
	 * @var array
	 */
	private $badCodes = array("bcc:","cc:");
	/**
	 * @var mixed htmlDecoder
	 */
	public $htmlDecoder = NULL;

	/**
	 * Constructor, just set To, From, Subject an Mailtext-Member
	 *
	 * @param string To
	 * @param string From
	 * @param string Subject
	 * @param string [Mailtext]
	 * @param boolean sendNow
	 */
	public function Email($To,$From,$Subject,$Mailtext="", $sendNow=false) {
		$this->To=$To;
		$this->From=$From;
		$this->Subject=$Subject;
		$this->Mailtext=$Mailtext;
		if($sendNow) return $this->send();
	}
	/**
	 * Alias for setAdditional_parameters
	 *
	 * @deprecated because not in name-schema
	 * @param string $additional_parameters
	 * @return void
	 */
	public function addAdditional_parameters($additional_parameters) {
		$this->setAddtionalParameters($additional_parameters);
	}
	/**
	 * @param string additional_parameters
	 */
	public function setAddtionalParameters($additional_parameters) {
		$this->additional_parameters = $additional_parameters;
	}
	/**
	 * Antwortadresse
	 *
	 * @param string ReplyTo
	 */
	public function setReplyTo($ReplyTo) {
		$this->ReplyTo=$ReplyTo;
	}
	/**
	 * ErrorsTo-Adresse
	 *
	 * @param string ErrorsTo
	 */
	public function setErrorsTo($ErrorsTo) {
		$this->ErrorsTo=$ErrorsTo;
	}
	/**
	 * @param string Priority
	 */
	public function setPriority($Priority) {
		$this->Priority=$Priority;
	}
	/**
	 * @param mixed size
	 */
	public function setMaxFileSize($size) {
			$this->maxByteSize=$size;
	}
	/**
	 * If $mailTextCode is null a TextMailTextCoder is instanciated
	 *
	 * @return void
	 * @param MailCoder mailTextCoder
	 */
	public function setMailtextCoder(MailCoder $mailTextCoder = null) {
		if($mailTextCoder === null) $this->mailTextCoder = new TextMailTextCoder($this->Mailtext);
		else $this->mailTextCoder = $mailTextCoder;
	}
	/**
	 * If $mailTextCode is null a HTMLMailTextCoder is instanciated
	 *
	 * @return void
	 * @param MailTextCoder mailTextCoder
	 */
	public function setHTMLtextCoder(MailCoder $mailTextCoder = null) {
		if($mailTextCoder === null) $this->HTMLTextCoder = new HTMLMailTextCoder($this->HTMLtext);
		else $this->HTMLTextCoder = $mailTextCoder;
	}
	/**
	 * Adds acceptedFiletypes by comma-seperated string to exiting ones
	 *
	 * @example setFileTypes("doc,xsl");
	 * @param string $types
	 */
	public function addFileTypes($types) {
		$types = strtolower($types);
		$parts = explode(',', $types);
		foreach($parts as $key => $value) {
			$this->acceptedFiletypes[] = $value;
		}
	}
	/**
	 * Sets acceptedFiletypes by comma-seperated String
	 *
	 * @example setFileTypes("doc,xsl");
	 * @param string $types
	 */
	public function setFileTypes($types) {
		$types = strtolower($types);
		$parts = explode(',', $types);
		$this->acceptedFiletypes = $parts;
	}
	/**
	 * Adds a file virtually, code is generated by this->send
	 *
	 * @return boolean
	 * @param string $filename
	 * @throws E_USER_ERROR
	 */
	public function addFile($filename) {
		$tempattach=explode(".",$filename);
		if(in_array($tempattach[count($tempattach)-1], $this->acceptedFiletypes)) {
			if(is_file($filename)) {
				if(filesize($filename)<=$this->maxByteSize) array_push($this->Files, $filename);
				else {
					trigger_error("Dateigröße von $filename: filesize($filename). Erlaubt: ".$this->maxByteSize, E_USER_ERROR);
				}
			} else {
				trigger_error("Datei $filename wurde nicht gefunden.", E_USER_ERROR);
			}
		} else  {
				trigger_error("Dateityp ".$tempattach[count($tempattach)-1]." ist nicht erlaubt.", E_USER_ERROR);
		}
		return true;
	}
	/**
	 * @param string htmltext
	 * @param boolean htmlAndText
	 */
	public function addHTML($htmltext, $htmlAndText=false) {
		$this->HTMLtext=$htmltext;
		$this->htmlAndText = $htmlAndText;
	}
	/**
	 * Validates E-Mail-Adress by using the string-validation-method an tries to put a empty email to reception-server
	 *
	 * @return array[0:boolean Succeess, 1:string message]
	 * @param string $Email
	 */
	public function validateAddress($Email)  {
		global $HTTP_HOST;
		$result = array();
		if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $Email)) {
			$result[0]=false;
			$result[1]="$Email is not properly formatted";
			return $result;
		}
		list ( $Username, $Domain ) = split ("@",$Email);
		if (getmxrr($Domain, $MXHost)) {
			$ConnectAddress = $MXHost[0];
		} else {
			$ConnectAddress = $Domain;
		}
		$Connect = fsockopen ( $ConnectAddress, 25 );
		if ($Connect) {
			if (ereg("^220", $Out = fgets($Connect, 1024))) {
				fputs ($Connect, "HELO $HTTP_HOST\r\n");
				$Out = fgets ( $Connect, 1024 );
				fputs ($Connect, "MAIL FROM: <{$Email}>\r\n");
				$From = fgets ( $Connect, 1024 );
				fputs ($Connect, "RCPT TO: <{$Email}>\r\n");
				$To = fgets ($Connect, 1024);
				fputs ($Connect, "QUIT\r\n");
				fclose($Connect);
				if (!ereg ("^250", $From) || !ereg ( "^250", $To )) {
					$result[0]=false;
					$result[1]="Server rejected address '$Email'";
					return $result;
				}
			} else {
				$result[0] = false;
				$result[1] = "Keine Antwort vom Server zurckerhalten!";
				return $result;
			}
		} else {
			$result[0]=false;
			$result[1]="Keine Verbindung zum E-Mail Server vorhanden.";
			return $result;
		}
		$result[0]=true;
		$result[1]="$Email appears to be valid.";
		return $result;
}
	/**
	 * Validates $email or eMail-part in ...<email AT tld.com>
	 * @return boolean
	 * @param string address
	 */
	public static function validateEmailString($address) {
		if(ereg( ".*<(.+)>", $address, $regs )) {
			$address = $regs[1];
		}
		if(ereg( "^[^@  ]+@([a-zA-Z0-9\-]+\.)+([a-zA-Z0-9\-]{2}|net|com|gov|mil|org|edu|info|name|int)\$",$address)) return true;
		else return false;
	}
	/**
	 * Alias for validateEmailString
	 *
	 * @deprecated because not in name-schema
	 * @param string address
	 */
	public function ValidEmailString($address)  {
		return $this->validateEmailString($address);
	}
	/**
	 * Builds Prio, From, Disposition-Notification, Reply-To, Sender, Errors-To, X-Mail, User_Agent
	 *
	 * @return string $stHead
	 */
	public function standardHeaders() {
		$clearText = array("Highest", "High", "Normal", "Low", "Lowest");
		$stHead = "\nX-Priority: ".$this->Priority;
		$stHead .= "\nX-MSMail-Priority: ".$clearText[$this->Priority-1];
		$stHead .= "\nImportance: ".$clearText[$this->Priority-1];
		$stHead .= "\nFrom: ".$this->From;
		$stHead .= "\nX-Envelope-From: ".$this->From;
		if($this->dispositionNotification) {
			$stHead .= "\nDisposition-Notification-To:".$this->From;
		}
		if($this->ReplyTo=="")  $this->ReplyTo=$this->From;
		$stHead .= "\nReply-To: ".$this->ReplyTo;
		$stHead .= "\nSender: ".$this->From;
		if($this->ErrorsTo=="")  $this->ErrorsTo=$this->From;
		$stHead .= "\nErrors-to: ".$this->ErrorsTo;
		$stHead .= "\nX-Mailer: Class Email (c) Navigate AG ".date("Y");
		$stHead .= "\nUser-Agent: Class Email (c) Navigate AG ".date("Y");
		return $stHead;
	}
	/**
	 * Builds code for attachments from this->Files
	 *
	 * @param string $boundary
	 * @return string $code
	 * @todo transform code-generation to MailCoder-children
	 */
	public function buildAttachments($boundary) {  //fgt der Mail ein Attachment hinzu
		$code = "";
		$sep= chr(13).chr(10);
		$ata = array();
		for($i=0; $i<count($this->Files); $i++) {
			$code .= "\n--$boundary";
			$prefileType = $tempattach=explode(".",$this->Files[$i]);
			$fileType = $prefileType[count($prefileType)-1];
			$code .= "\nContent-Type: application/$fileType;";
			$code .= "\n".' name="'.basename($this->Files[$i]).'";';
			$code .= "\n".'Content-Transfer-Encoding: base64';
			$code .= "\n".'Content-Disposition: attachment;';
			$code .= "\n".' filename="'.basename($this->Files[$i]).'"'."\n\n";
			$linesz= filesize($this->Files[$i])+1;
			$fp= fopen($this->Files[$i], 'r');
			$code .= chunk_split(base64_encode(fread($fp, $linesz)));
			fclose($fp);
		}
		return $code;
	}
	public function send() {
		$this->checkForBadCode($this->To);
		$this->checkForBadCode($this->From);
		$this->checkForBadCode($this->Subject);
		$codes = $this->generateMailCodeAndHeaders();
		$this->checkForBadCode($codes["mailCode"]);
		$this->checkForBadCode($codes["addHeaders"]);
		$tempTo=explode(";",$this->To);
		$retr = true;
		if ($_ENV["HTTP_HOST"] == "www.testsystem.de.bankingcheck.nova-web.de") {
	      $this->Subject = "[Testsystem] ".$this->Subject;
	      if ((strpos($this->To, "nova-web.de") === FALSE) && (strpos($this->To, "boedger.de") === FALSE) && (strpos($this->To, "asd@esscapa.de") === FALSE) && (strpos($this->To, "bankingcheck.de") === FALSE)) {
	        // Not a valid receiver
	        $codes["mailCode"] = "[Testsystem] Diese Mail sollte gesendet werden an: ".$this->To."<br>\n".$codes["mailCode"];
	        $tempTo = array("godard@nova-web.de");
	      }
	    }




		//var_dump($tempTo);die();
	    // if(defined ('NOVA_CRON_DEBUG') && NOVA_CRON_DEBUG ) {
	    // 	echo 'Would send to: ' . implode(',', $tempTo) ."\n";
	    // 	$tempTo = array("schneider@nova-web.de");
	    // 	return true;
	    // }


		$retrAll = true;
		//$mbox=imap_open($mailbox, $username, $password);
		for ($i=0; $i<count($tempTo); $i++) {
			$mail = new PHPMailer;
			$mail->isSMTP();                                      // Set mailer to use SMTP
			$mail->Host = 'mail.bankingcheck.de';  // Specify main and backup server
			$mail->SMTPAuth = true;                               // Enable SMTP authentication
			$mail->Username = 'noreplya';                            // SMTP username
			$mail->Password = 'swGmFtu!zz4ba';                           // SMTP password			

			//$mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted
			$mail->From = 'info@bankingcheck.de';
			$mail->FromName = 'BankingCheck.de';
			$mail->addReplyTo('info@bankingcheck.de');

			$mail->addAddress($tempTo[$i]);              // Name is optional

			$mail->isHTML(true);                                  // Set email format to HTML

			$mail->Subject = $this->Subject;
			// $mail->Body    = $codes["mailCode"]; 
			


			if (isset($this->mailTextCoder)) {
				$altbody = $this->mailTextCoder->getBody();

				if (!empty($altbody)) {
					$mail->AltBody = $altbody;
				}

			}

			if (isset($this->HTMLTextCoder)) {
				$body = $this->HTMLTextCoder->getBody();

				if (!empty($body)) {
					$mail->Body = $body;
				}

			}

		
			$mail->CharSet = $this->mailTextCoder->charset;
			$mail->Encoding = $this->mailTextCoder->transferEncoding;

			//var_dump($this->mailTextCoder);
			$retr = $mail->send();
			
			if(!$retr) {
				if(defined ('NOVA_CRON_DEBUG') && NOVA_CRON_DEBUG ) {
					echo 'Mailer Error: ' . $mail->ErrorInfo;
					var_dump($tempTo[$i]);
					var_dump($this->Subject);
					
					//var_dump($codes["addHeaders"]);
					//var_dump($this->additional_parameters);
					$retrAll = false;
				}
				watchdog('class.Email', 'Connection to SMTP server '.$mail->Host.' failed: '.print_r($mail->ErrorInfo, true).'.', array(), WATCHDOG_CRITICAL);
			}


		}
		//imap_close($mbox);
		// var_dump($retr);

		return $retrAll;
	}
	/**
	 * Checks given string for all expression in this->bad
	 *
	 * @throws E_USER_ERROR
	 * @param string $toCheck
	 * @return void
	 */
	public function checkForBadCode($toCheck) {
		foreach($this->badCodes as $badCode) {
			if(preg_match("/$badCode/is", $toCheck)) {
				trigger_error("Unerlaubtes Vorkommen von '$badCode'", E_USER_ERROR);
			}
		}
	}
	/**
	 * @return array return["mailCode","addHeaders"]
	 */
	public function generateMailCodeAndHeaders() {
		if($this->mailTextCoder === null) $this->setMailtextCoder();
		$this->mailTextCoder->setCode($this->Mailtext);

		if($this->HTMLTextCoder === null) $this->setHTMLtextCoder();
		$this->HTMLTextCoder->setCode($this->HTMLtext);

		$boundary= "_Multipart_Boundary_".md5(time());
		if($this->htmlAndText) {
			$addHeaders = "MIME-Version: 1.0";
			$addHeaders .= "\nContent-Type: multipart/alternative;\n boundary=\"$boundary\"";
			$addHeaders .= $this->standardHeaders();

			if($this->Mailtext == "") {
				$temp=$this->HTMLtext;
				$temp=preg_replace("<([^>]+)>", "", str_replace("<br>","\n",$temp));
				$this->mailTextCoder->setCode($temp);

			}
			$mailCode = "This is a multi-part message in MIME format\n";
			$mailCode .= "\n--$boundary\n";
			$mailCode .= $this->mailTextCoder->getHeader()."\n\n";
			$mailCode .= $this->mailTextCoder->getBody();
			$mailCode .= "\n--$boundary\n";

			$mailCode .= $this->HTMLTextCoder->getHeader()."\n\n";
			$mailCode .= $this->HTMLTextCoder->getBody();

			$mailCode .= "\n--$boundary--\n";
		} else {
			if(count($this->Files)>0) {
				$addHeaders = "MIME-Version: 1.0";
				$addHeaders .= "\nContent-Type: multipart/mixed;\n boundary=\"$boundary\"";
				$addHeaders .= $this->standardHeaders();
				$mailCode = "This is a multi-part message in MIME format\n";
				$mailCode .= "\n--$boundary\n";

				if($this->HTMLtext != "") {
					$mailCode .= $this->HTMLTextCoder->getHeader()."\n\n";
					$mailCode .= $this->HTMLTextCoder->getBody();
				} else {
					$mailCode .= $this->mailTextCoder->getHeader()."\n\n";;
					$mailCode .= $this->mailTextCoder->getBody();
				}
				$mailCode .= $this->buildAttachments($boundary);
				$mailCode .= "\n--$boundary--\n";

			} else {
				if ($this->HTMLtext != "") {
					$addHeaders = $this->HTMLTextCoder->getHeader();
					$addHeaders .= $this->standardHeaders();
					$mailCode = $this->HTMLTextCoder->getBody();
				} else {
					$addHeaders = $this->mailTextCoder->getHeader();
					$addHeaders .= $this->standardHeaders();
					$mailCode = $this->mailTextCoder->getBody();
				}
			}
		}
		return array("mailCode"=>$mailCode, "addHeaders"=>$addHeaders);
	}
	/**
	 * Generates eml-Formates code
	 *
	 * @return string
	 */
	public function generateEmlCode() {
		$codes = $this->generateMailCodeAndHeaders();
		$code = "To: ".$this->To."
Subject: ".$this->Subject."
".$codes["addHeaders"]."

".$codes["mailCode"];
		return $code;
	}
	/**
	 * Saves eMail as file in eml-format
	 *
	 * @param string $filename
	 */
	public function saveAsEml($filename) {
		$fp = fopen($filename, "w");
		fputs($fp, $this->generateEmlCode());
		fclose($fp);
	}
	/**
	 * Attaches eMail to given file in eml-format
	 *
	 * @param string $filename
	 */
	public function appendAsEmlCode($filename) {
		$fp = fopen($filename, "a");
		fputs($fp, $this->generateEmlCode());
		fclose($fp);
	}
	public function setHosteuropeMode() {
		$this->setAddtionalParameters("-f info@".$_SERVER['HTTP_HOST']);
	}
	public function decodeUtf8() {
		$this->Mailtext = utf8_decode($this->Mailtext);
		$this->HTMLtext = utf8_decode($this->HTMLtext);
	}
	/**
	 * Generates a Email-Instance from a .eml-File or a .eml-Code.
	 * If data is set, this function will replace the markups in the code
	 *
	 * @param string $stringOrFile
	 * @param array $data
	 * @param string $regExForPlaceholders
	 * @return Email
	 */
	public static function generateFromEml($stringOrFile, array $data=array(), $regExForPlaceholders="\[(.*?)\]") {
		if(is_file($stringOrFile)) $code = file_get_contents($stringOrFile);
		elseif(is_file($_SERVER['DOCUMENT_ROOT'].$stringOrFile)) $code = file_get_contents($_SERVER['DOCUMENT_ROOT'].$stringOrFile);
		else $code = $stringOrFile;
		if(count($data)>0) {
			preg_match_all("/".$regExForPlaceholders."/s", $code, $placeHolders);
			$i=0;
			foreach($placeHolders[0] as $markup) {
				$replace = $data[$placeHolders[1][$i]];
				$code = str_replace($markup, $replace, $code);
				$i++;
			}
		}

		$isInHeader = true;
		$firstBodyLine = true;
		foreach(split("\r?\n", $code) as $line) {
			$isInHeader = $isInHeader && trim($line)!="";
			if(!$isInHeader) {
				if(!$firstBodyLine) $body .= $line."\n";
				else $firstBodyLine = false;
			}
			else {
				$addHeaders = array();
				if(eregi("^To:(.*)", $line, $m)) $to = trim($m[1]);
				elseif(eregi("^From:(.*)", $line, $m)) $from = trim($m[1]);
				elseif(eregi("^Subject:(.*)", $line, $m)) $subject = trim($m[1]);
				else $addHeaders[] = $line;
			}
		}
		$r = new Email($to, $from, $subject, $body, false);
		return $r;
	}
	/**
	 * Generates E-Mail-Content with a width of 80 strings
	 *
	 * @param string $content
	 * @param int $width
	 * @param boolan $linefeedInTag
	 * @return string
	 */

	public static function setProperEmailTextWidth($content, $width=80, $linefeedInTag=false ) {
		$content = str_replace("><","> <",$content);
		$content = str_replace("<"," <",$content);
		$newLine = " "."\n";
		$leerzeichen = " ";
		$sonstigeTrenner = array("\n","\r");
		foreach ($sonstigeTrenner as $trenner) {
			$content = str_replace($trenner,$leerzeichen,$content);
		}
		$contents = explode($leerzeichen, $content);

		if ($linefeedInTag) {
			foreach ($contents as $content) {
				if ($content != "") {
					$newContents[] = $content;
				}
			}
		} else {
			// TAGS ZUSAMMENBAUEN
			$isTag=false;
			$tagJustEnded = false;
			foreach ($contents as $content) {
				if (strpos("XX".$content, "<")>0) {
					$isTag = true;
				}
				if (strpos("XX".$content, ">")>0) {
					$isTag = false;
					$tagJustEnded = true;
				}
				if($isTag) {
					$tag = $tag.$leerzeichen.$content;
				} else {
					if ($tagJustEnded) {
						$tag = $tag.$leerzeichen.$content;
						$newContents[] = $tag;
						$tagJustEnded = false;
						$tag = "";
					} else {
						if ($content != "") {
							$newContents[] = $content;
						}
					}
				}
			}
		}
		// newContent Zusammenbauen
		$line="";
		$returnContent = "";
		foreach ($newContents as $newContent) {
			if (strlen($line.$leerzeichen.$newContent) < $width-2 ) {
				$line = $line.$leerzeichen.$newContent;
			} else {
				$returnContent = $returnContent.$line.$newLine;
				$line = $newContent;
			}
		}
		return $returnContent;
	}
	public static function utf8Subject($subject) {
		return "=?utf-8?Q?=".quoted_printable_encode($subject)."?=";
	}

}
?>
