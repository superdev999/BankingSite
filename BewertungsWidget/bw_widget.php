<?php
// ****************************************** //
// ****** php Include Version ******** //
// ****************************************** //
//1. Version,neue Version -> generator.php Code erzeugen

chdir($_SERVER['DOCUMENT_ROOT']);
require_once $_SERVER['DOCUMENT_ROOT'].'/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

//Kunden Zuordnungen einbetten - neue Kunden bitte in localconf.inc eintragen
require_once("localconf.inc");

//Get Parameter abfangen -> Kundenidentifizierung
// ID kann auch BankID sein
$productid=$_GET["productid"];

$counter="true";
if($_GET["counter"]=='false') $counter="false";


$votecount="true";
if($_GET["votecount"]=='false') $votecount="false";

$isWithSnippet = false;
if($_GET["snippet"]=='true') $isWithSnippet = "true";

//Popup Ja Nein?
$popup='false';
if($_GET["popup"]=='true') $popup="true";

//Popup Ja Nein?
$popupClean='false';
if($_GET["popupClean"]=='true') $popupClean="true";

//NoAnker Ja Nein?
$voteanker='true';
$voteanker='true';
if($_GET["voteanker"]=='false') $voteanker="false";
// Anzahl Bewertungen klickbar
$ankercount='true';
if($_GET["ankercount"]=='false') $ankercount="false";

$ankerimage='true';
if($_GET["ankerimage"]=='false') $ankerimage="false";

$host = $kunden[$productid];

if (isset($_GET["node"])) {
	$nid = intval($_GET["node"]);
	$pathalias= drupal_get_path_alias("node/".$nid);
	$host = "http://www.bankingcheck.de/".$pathalias;
}

$protocol='http';
if($_GET["ssl"]=='yes') $protocol='https';

// Spezial für Umstellung
if (isset($_GET['votecount'])) // aha - neuer Aufruf
 {
	//alles bleibt wie gehabt
 }
else
{
 if ($productid == 786)
 {
  $counter="true";
  $isWithSnippet = false;
 }
 else if ($productid == 10604)
 {
  $counter="false";
  $isWithSnippet = false;
 }
 else if ($productid == 687)
 {
  $counter="false";
  $isWithSnippet = false;
 }
 if ($productid == 682)
 {
  $counter="true";
  $isWithSnippet = false;
 }
}

//Code einbinden
require_once("bw_widget_common.php");

//Ausgabe (für Iframe)
echo $html;
?>
