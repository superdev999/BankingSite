<?php
// ****************************************** //
// ****** JavaScript Include Version ******** //
// ****************************************** //
chdir($_SERVER['DOCUMENT_ROOT']);
  require_once $_SERVER['DOCUMENT_ROOT'].'/includes/bootstrap.inc';
  drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
//Kunden Zuordnungen einbetten - neue Kunden bitte in localconf.inc eintragen
require_once("localconf.inc");
header('Content-Type: text/javascript; charset=UTF-8');

//Get Parameter abfangen -> Kundenidentifizierung
$productid=$_GET["productid"];

$counter="true";
if($_GET["counter"]=='false') $counter="false";


$votebutton="true";
if($_GET["votebutton"]=='false') $votebutton="false";
$votecount="true";
if($_GET["votecount"]=='false') $votecount="false";

$isWithSnippet = false;
if($_GET["snippet"]=='true') $isWithSnippet = true;
$host = $kunden[$productid];
$protocol='http';
if($_GET["ssl"]=='yes') $protocol='https';
//Popup Ja Nein?
$popup='false';
if($_GET["popup"]=='true') $popup="true";
//Popup Ja Nein?
$popupClean='false';
if($_GET["popupClean"]=='true') $popupClean="true";
//NoAnker Ja Nein?
$ankervote='true';
if($_GET["ankervote"]=='false') $ankervote="false";
// Anzahl Bewertungen klickbar
$ankercount='true';
if($_GET["ankercount"]=='false') $ankercount="false";

$ankerimage='true';
if($_GET["ankerimage"]=='false') $ankerimage="false";


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

//JSON Ausgabe für Einbettcode Generierung(von generator.php)
echo $_REQUEST["callback"];
echo "(";
$html=array("html"=>$html);
echo json_encode($html);
echo ");";
?>