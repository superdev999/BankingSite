<html>
	<head>
		<title>Kreditkartenrechner</title>
		<link rel="stylesheet" href="fa_style.css" type="text/css">
	</head>
	<body>
	<?php
	## fincenceAds Rechner ########################################
	include("fa_config.php");				// Konfigurationsdatei
	$fa_rechner = 'kreditkartenrechner';	// Rechner Art
	$fa_url = "";							//Pfad zum Dokument mit welchem Sie den Rechner includen. (z.B. http://server.de/index.php?show=rechner)
	$_POST["zahlungsart"] = "5";
	include("fa_inc_rechner.php");
	######################################################
	?>
	</body>
</html>	
	

