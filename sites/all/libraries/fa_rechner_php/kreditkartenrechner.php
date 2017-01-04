<?php
	## KONFIGURATION #############################################	include("config.php");	###########################################################
?>	
<html>
<head>
	<title>Kreditkartenrechner</title>
	<link rel="stylesheet" href="fa_style.css" type="text/css">
</head>
<body>
	<?php
	## fincenceAds Rechner ########################################
	$fa_wfid 	= $werbeflaechenID;		// Werbeflächen ID
	$fa_rechner = 'kreditkarterechner';	// Rechner Art
	$fa_url = "";						//Pfad zum Dokument mit welchem Sie den Rechner includen.// (z.B. http://server.de/index.php?show=rechner)
	$fa_show_logos = $show_logos;
	$_POST["zahlungsart"] = "5";
	include("fa_inc_rechner.php");
	######################################################
	?>
</body>
</html>