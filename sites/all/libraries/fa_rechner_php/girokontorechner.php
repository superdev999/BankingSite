<html>
	<head>		<title>Girokontenrechner</title>		<link rel="stylesheet" href="fa_style.css" type="text/css">	</head>	<body>				<?php			
		## fincenceAds Rechner ########################################	
				include("fa_config.php");			
				// Konfigurationsdatei		
						$fa_rechner = 'girokontorechner';	
						// Rechner Art				
						$fa_url = "";			
									//Pfad zum Dokument mit welchem Sie den Rechner includen.	
									// (z.B. http://server.de/index.php?show=rechner)	
									$rechnerip = $_SERVER['REMOTE_ADDR'];	
												include("fa_inc_rechner.php?cip=".$rechnerip);				
												#######################################################			
														?>	</body></html>	
	

