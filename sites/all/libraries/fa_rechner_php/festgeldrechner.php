<?php
	## KONFIGURATION #############################################	include("config.php");	###########################################################?>	
<html>
<head>
<title>Festgeldrechner</title>
<link rel="stylesheet" href="fa_style.css" type="text/css">
</head>
<body>
	<?php
		## fincenceAds Rechner ########################################
		$fa_wfid 	= $werbeflaechenID;		// Werbeflächen ID
		$fa_rechner = 'festgeldrechner';	// Rechner Art
		$fa_url = "";						//Pfad zum Dokument mit welchem Sie den Rechner includen. // (z.B. http://server.de/index.php?show=rechner)
		$fa_show_logos = $show_logos;
		include("fa_inc_rechner.php");
		#######################################################	
	?>
	</body>
</html>	
	

