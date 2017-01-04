<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
          "http://www.w3.org/TR/html4/loose.dtd">
<html>

	<head>
		<title>Studentenkontorechner</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">		
		<link rel="stylesheet" href="fa_style.css" type="text/css">
		
	</head>


	<body>
	
				<?php
				## fincenceAds Rechner ########################################
				$fa_rechner = 'girokontorechner';	// Rechner Art													
				$template = 'studentenkontorechner';	// Template													
													
				$fa_url = "";		//Pfad zum Dokument mit welchem Sie den Rechner includen.
									// (z.B. http://server.de/index.php?show=rechner)
				
				$_POST["berufsgruppe"] = "ausbildung";
				
				include("fa_rechner_tpl.php");
				#######################################################	
				?>
	
	</body>
	
</html>	
	

