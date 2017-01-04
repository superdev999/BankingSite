<?php
ob_start();
require_once 'Swift/lib/swift_required.php';
$dataFile = $_SERVER['DOCUMENT_ROOT']."/sites/all/libraries/onvista/notified.txt";
$debugFile = $_SERVER['DOCUMENT_ROOT']."/sites/all/libraries/onvista/debug.txt";
$message = Swift_Message::newInstance()->setSubject('Neue Shortnews von Bankingcheck.de')->setFrom(array('shortnews@bankingcheck.de' => 'Bankingcheck.de'))->setTo(array());
$transport = Swift_MailTransport::newInstance();
$mailer = Swift_Mailer::newInstance($transport);

if (is_file($dataFile)) $serialized = file_get_contents($dataFile);
$trailer = "";
$deserialized = unserialize($serialized);
if ($deserialized !== false) {
	echo "Unserializing ".$serialized;
	$trailer .= print_r($deserialized, true);
	$alreadyNotifiedFor = $deserialized;
	echo "\nUnserialized: ";
	print_r($alreadyNotifiedFor);
} else {
	echo "New array created.\n";
	$alreadyNotifiedFor = array();
}
if ($_ENV["HTTP_HOST"] == "www.testsystem.de.bankingcheck.nova-web.de") $testsystem = true;
$rssLink = "http://www.bankingcheck.de/shortnews/".$context["node"]->nid."/rss.xml";
if ($context["node"]->type=="shortnews" && $context["node"]->status == 1 && !in_array($context["node"]->nid, $alreadyNotifiedFor)) {
	$alreadyNotifiedFor[] = $context["node"]->nid;
	if (!$testsystem) {
		//$message->setBcc(array('aktionen@orderrechner.de' => 'Orderrechner', 'brokernews@bankingcheck.de' => 'Brokernews BankingCheck Kontrollmail', 'godard+bankingcheck@nova-web.de' => 'Florin Godard, nova GmbH'));
    $message->setBcc(array('brokernews@bankingcheck.de' => 'Brokernews BankingCheck Kontrollmail'));
		$message->setBody($rssLink);
	}
	$mailer->send($message);
} else {
	if ($context["node"]->type!="shortnews") {
		$body = "Not a shortnews:\nContext:".print_r($context, true)."\n\nObject:".print_r($object, true)."\n\nNode:".print_r($object->node)."\n";
	} elseif ($context["node"]->status != 1) {
		$body = "Shortnews, but not published:\nContext:".print_r($context, true)."\n\nObject:".print_r($object, true)."\n\nNode:".print_r($object->node)."\n".$rssLink."\n";
	} elseif (in_array($context["node"]->nid, $alreadyNotifiedFor)) {
		$body = "Published shortnews, but already notified.\n".$rssLink."\n";
	}
	echo $body.$trailer;
}
$serialized = serialize($alreadyNotifiedFor);
file_put_contents($dataFile, $serialized);
file_put_contents($debugFile, ob_get_clean());
?>
