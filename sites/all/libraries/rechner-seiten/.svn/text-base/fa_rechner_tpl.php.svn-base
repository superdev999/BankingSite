<?php
$arrProductItem = array();
$sql = "SELECT * FROM {content_type_productitem}" ;
$reslut = db_query($sql);
while($data = db_fetch_object($reslut))	{
	$arrProductItem[$data->field_prodfinanceaddsid_value] = $data->nid;
}
// Config laden
if (file_exists("fa_config.php")) include("fa_config.php");
//	// Freitexte verwenden

 //  Anzahl der ausgegebenen Banken (max.200)
$_POST["c"] = 200;
$_POST["ip"] = $_SERVER["REMOTE_ADDR"];
// Verfügbare Rechner	und Datenstrukturen holen
$rechner_array = "http://tools.financeads.net/template_vars.php";
$rechner_array = trim(file_get_contents($rechner_array));
$rechner_array = unserialize($rechner_array);
$rechner = $fa_rechner;

if (array_key_exists($rechner, $rechner_array))	{
	// TEMPLATE EINLESEN
	$template = "./templates/".((isset($template)) ? $template : $rechner).".tpl";
	$template = trim(file_get_contents($template));
	// FORMULAR
	$form = explode("<%%endform%%>", trim($template));
	$form = trim($form[0]);
	$form = explode("<%%beginform%%>", trim($form));
	$form = trim($form[1]);
	// FORMULAR AUSWERTEN
	$post_data = serialize($_POST);
	$post_data = urlencode($post_data);

	// ERGEBNISSE	HOLEN
	$erg = "http://tools.financeads.net/".$rechner."_tpl.php?q=".$post_data;
	//echo $erg."<br>";
	//die();
	$erg = file_get_contents($erg);
	// WERTE AUSLESEN
	// Werte holen
	$pattern = '/\{(?s).*?\}/s';
	$werte = preg_match($pattern, $erg, $treffer);
	$werte = $treffer[0];

	// Werte aus den sonstigen daten entfernen
	$erg = preg_replace($pattern,"", $erg);
	// Werte in eine brauchbare Form bringen
	$werte = str_replace("{","", $werte);
	$werte = str_replace("}","", $werte);
	$werte = trim($werte);
	$werte = explode(",", $werte);
	if (is_array($werte))		{			foreach ($werte as $wert)
		{
			$wert = trim($wert);
			$wert = explode(":", $wert);
			$werte_array[$wert[0]] = $wert[1];
		} // ende foreach
	} // ende if is_array
	// Werte im Formular ersetzen
	if (is_array($werte_array))
	{
		foreach ($werte_array as $name => $value)
		{
			$form = str_replace("{feld:".$name."}", file_get_contents("http://tools.financeads.net/template_form.php?rechner=".$rechner."&feld=".$name."&wert=".$value."&q=".$post_data), $form);
		} // ende foreach
	} // ende if is_array

// FORMULAR AUSGEBEN
echo $form;

// ERGEBNISSE IN EIN ARRAY LADEN
$zeilen = explode("\n", trim($erg));
$nr = 1;

// Zeilen
foreach ($zeilen as $zeile)
{
	if (trim(str_replace("\n","",$zeile))=="") continue;				// Spalten
	$zeile = explode(";", $zeile);
	if (count($zeile)==0 || count($zeile)=="1") continue;				$data = array();
	// Assoziative Keys zuweisen
	foreach ($zeile as $key => $value)
	{
		$data[$rechner_array[$rechner][$key]] = $value;
		if (isset($freitext_array[$rechner]) && is_array($freitext_array[$rechner]) && $rechner_array[$rechner][$key]=="link")
		{
				$parsed = parse_url($value);
				parse_str($parsed["query"], $queryParams);
				$productId = $queryParams['product'];
				if (
					isset($freitext_array[$rechner][$productId]) &&
					is_array($freitext_array[$rechner][$productId])
				) {
					foreach($freitext_array[$rechner][$productId] as $freitextNr => $freitext) {
						$data['freitext'][$freitextNr] = $freitext;
					}
				} else {
					$data['freitext'] = $freitextDefault;
				}
			} // ende if freitext_array
		} // ende foreach

		$daten[$nr] = $data;

	$nr++;
	} // ende foreach


// ERGEBNISZEILEN	TEMPLATE
	$line = explode("<%%enddata%%>", trim($template));
	$line = trim($line[0]);
	$line = explode("<%%begindata%%>", trim($line));
	$line = trim($line[1]);


	$line2 = explode("<%%enddata2%%>", trim($template));
	$line2 = trim($line2[0]);
	$line2 = explode("<%%begindata2%%>", trim($line2));
	$line2 = trim($line2[1]);


// ERGEBNISSE AUSGEBEN
	$table_head = explode("<%%begintable_ende%%>", trim($template));
	$table_head = trim($table_head[0]);
	$table_head = explode("<%%begintable_start%%>", trim($table_head));
	$table_head = trim($table_head[1]);

	$table_foot = explode("<%%endtable_ende%%>", trim($template));
	$table_foot = trim($table_foot[0]);
	$table_foot = explode("<%%endtable_start%%>", trim($table_foot));
	$table_foot = trim($table_foot[1]);

echo $table_head;

if (is_array($daten))
{
	foreach ($daten as $nr => $data)
	{
		if ($nr%2==0) $zeile = $line;
		else $zeile = $line2;

		// Werte in das Template schreiben
		$zeile = str_replace("{nr}", $nr, $zeile);

		$productId="" ;
		$matches = null ;
		$returnValue = preg_match('|product=([0-9]+)|', $data["link"], $matches);
		if(isset($matches[1])) {
			$productId = $matches[1] ;
		}



		foreach ($daten[$nr] as $key => $value) {

			$value_backup = $value;

			if($key=="link" && isset($_POST["linkbase"])) {
				// $value = str_replace("&", "&amp;", $value);

				/*
				// Wir brauchen nur den T-String vom Link
				$fa_args = explode("?t=",$value);
				$fa_args = $fa_args[1];
				$fa_args = explode("&",$fa_args);
				$fa_args = $fa_args[0];

				$value = str_replace("%%FA-Args%%",urlencode($fa_args),$_POST["linkbase"]);

				// Produkt ID PLatzhalter ersetzen
				preg_match('/product=([0-9]+)$/',$value_backup,$treffer); // produktID = $treffer[1]
				$value = str_replace("%%Produkt-ID%%", $treffer[1], $value);
				*/

				$value = explode("?",$value);
				$value = $value[1];
				$value = $_POST["linkbase"].$value;

			}

			else if($key=="link") {
				$value = str_replace("&", "&amp;", $value);
			}

			if ($key=="freitext" && is_array($value))
			{
				foreach ($value as $key2 => $freitext)
				{
				$freitext_key = $key.$key2;
				if($freitext_key == "freitext2") {

					$rateLink = "#" ;
					$imgSrc = "";
					$rating = "0.0";

					if(isset($arrProductItem[$productId])) {
						$where = split("/",$_SERVER["REQUEST_URI"]);
						$rateLink = "/node/add/bewertung-".$where[2]."?itemId=".$arrProductItem[$productId] ;

						$novaBCProductitem = new NovaBCProductitem($arrProductItem[$productId]);

						$bcVotings = new BCVotings($arrProductItem[$productId], strtolower($novaBCProductitem->getProduct()->title));
						$rating = $bcVotings->getAverage();
						$imgSrc = $bcVotings->getSiegelUrl();
					} else {
						# Bank not on Bankingcheck
						$rating = "0.0" ;
					}

					if($rating == "0.0") {
						$rating = "-" ;
						$imgSrc="/sites/default/files/bcgrafiken/BankingCheck_Siegel_Usr_not.gif" ;
					}

					$freitext ='<div class="tooltip" style="text-align:left;padding-left:5px;margin:0;padding:0;position:absolute;margin-top:-15px;margin-left:0px;"><img style="cursor:pointer;" width="30px" class="smallRateImage" src="'.$imgSrc.'" /><span class="rate" style="position:absolute; top:9px;">&nbsp;'.$rating.'</span><div class="detailBox" style="border:1px solid #ccc;padding:10px;background:white;z-index:99;display: none;top:5px;left:5px;position:relative;">
			   		<img width="150px" class="mediumRateImage" src="'.$imgSrc.'" /><br />
			    	<a style="display:block;margin-top: 5px;" href="'.$rateLink.'"><img src="http://www.bankingcheck.de/sites/default/files/jetzt-bewerten.gif"></a>
						</div></div>' ;
				}
				$zeile = str_replace("{".$freitext_key."}", $freitext, $zeile);
				} // ende foreach
			} // ende if freitex

			$zeile = str_replace("{".$key."}", $value, $zeile);
		} // ende foreach
	// Jetzt noch die ungenutzten freitext platzhalter entfernen
	$zeile = preg_replace("/\{freitext([0-9]?)\}/i","",$zeile);
	//$zeile = str_replace("{imgSrc}", $imgSrc, $zeile);
	//$zeile = str_replace("{rateLink}", $rateLink, $zeile);
	// AUSGABE
	echo utf8_encode($zeile);
	} // ende foreach

} // ende if is_array(daten)

echo $table_foot;

} // ende if rechner ok
?>
