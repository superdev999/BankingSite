<?php

$Betrag = 5000;//$_GET["betrag"];
$Laufzeit = 12;//$_GET["laufzeit"];
$Device = $_GET["device"];

$count=3;
$image_size=1;
$cachetime=10; // 10 Minuten

$url = "http://tools.financeads.net/webservice.php?calc=tagesgeldrechner&wf=13289&format=xml&anlagebetrag=".$Betrag."&laufzeit=".$Laufzeit."&c=".$count."&ubl=".$image_size."&es=0";

// caching for different inputs and cached outputs
$cachefile = file_get_contents($url);
$input = file_get_contents($url);
// end caching

$xml = new DomDocument();
$xml->loadXML($input);

$xsl = new DomDocument;
$xsl->load('tg.xsl');

$proc = new XSLTProcessor();

$proc->importStyleSheet($xsl);

echo($proc->transformToXML($xml));

?>