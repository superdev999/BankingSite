<?php

$Betrag = 5000;//$_GET["betrag"];
$Laufzeit = 6;//$_GET["laufzeit"];
$Device = $_GET["device"];

$count=3;
$image_size=0;

$url = "http://tools.financeads.net/webservice.php?calc=tagesgeldrechner&wf=13289&format=xml&anlagebetrag=".$Betrag."&laufzeit=".$Laufzeit."&c=".$count."&ubl=".$image_size."&es=0";

$input = file_get_contents($url);

$xml = new DomDocument();
$xml->loadXML($input);

$xsl = new DomDocument;
$xsl->load('tg-onvista-quer.xsl');

$proc = new XSLTProcessor();

$proc->importStyleSheet($xsl);

echo($proc->transformToXML($xml));

?>