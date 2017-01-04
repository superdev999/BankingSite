<?php



	// Verfügbare Rechner	und Datenstrukturen holen
	$rechner_array = "http://tools.financeads.net/template_vars.php";			
	$rechner_array = trim(file_get_contents($rechner_array));
	$rechner_array = unserialize($rechner_array);
	$fa_show_logos = 1;		
	$ignore_post[] = "submit";
	$ignore_post[] = "channel";
	$ignore_post[] = "sub";
	
	$postvars = "";
	
	foreach ($_POST as $key => $value)
	{
	if (! in_array ($key, $ignore_post))
		$postvars .= "&".$key."=".$value;
	} // ende foreach
	// echo "p: ".$postvars."<br>";
	
	// wurde alles wichtige übergeben?
	if (isset($fa_wfid) && is_numeric($fa_wfid) && strlen($fa_wfid)==5 && isset($fa_rechner) && is_array($rechner_array) && array_key_exists($fa_rechner, $rechner_array))	
	{	
		if (isset($anzahl_banken) && is_numeric($anzahl_banken)) $anzahl = $anzahl_banken;
		else $anzahl = 10;
		
		if ($fa_show_logos=="1") 	$show_logos = "1";
		else $show_logos = "0";
		
		if (isset($fa_show_all) && $fa_show_all=="1") 	$show_all = 1;		
		else $show_all = 0;
		
		// URL		
			if (isset($fa_url) && trim($fa_url)!="") $url = urlencode(trim($fa_url));
			else $url = urlencode($_SERVER["PHP_SELF"]);
			
		// CSS Prefix
			if (isset($fa_css_prefix) && trim($fa_css_prefix)!="") $cssprefix = trim($fa_css_prefix);
			else $cssprefix = "";
	
		// Rechner laden
		
		//Fallback-IP wenn keine vor dem Include uebergeben wurde
				//if (empty($rechnerip)) $rechnerip = $_SERVER['REMOTE_ADDR'];
				$rechnerip = $_GET["cip"];

		
			$rechner_url = "http://tools.financeads.net/".$fa_rechner.".php?wf=".$fa_wfid;
			$rechner_url .= ((isset($url)) 				? "&url=".$url : "");
			$rechner_url .= ((isset($anzahl)) 			? "&c=".$anzahl : "");
			$rechner_url .= ((isset($nc)) 				? "&nc=".$nc : "");
			$rechner_url .= ((isset($es)) 				? "&es=".$es : "");
			$rechner_url .= ((isset($postvars)) 		? $postvars : "");
			$rechner_url .= ((isset($width)) 			? "&width=".$width : "");
			$rechner_url .= "&hs=0";
			$rechner_url .= ((isset($show_logos)) 		? "&sl=".$show_logos : "");
			$rechner_url .= ((isset($css_prefix)) 		? "&css_prefix=".$cssprefix : "");
			$rechner_url .= "&ip=".$rechnerip;
				
			$output = file_get_contents($rechner_url);
			
		// Ausgabe
    
			echo $output;
	
	} // ende if
	
?>