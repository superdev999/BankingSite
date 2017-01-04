<?php
	header("Content-Type: text/html; charset=utf-8");
	echo "<html><head><link type='text/css' rel='stylesheet' media='all' href='custom.css?p' />";
	
?>
      <script type="text/javascript" src="/themes/pixture_reloaded/lib/jquery-1.8.1.js"></script>

  <script type='text/javascript'>  
 var $jq = jQuery.noConflict();    
</script> 


<script type="text/javascript" src="/misc/jquery.js?p"></script>
<script type="text/javascript" src="/misc/drupal.js?p"></script>
<script type="text/javascript" src="/sites/default/files/languages/de_773e9f6c837d2e7988f13fa9b963d857.js?p"></script>
<script type="text/javascript" src="/sites/all/modules/ad_flash/scripts/AC_RunActiveContent.js?p"></script>
<script type="text/javascript" src="/sites/all/modules/fivestar/js/fivestar.js?p"></script>
<script type="text/javascript" src="/themes/pixture_reloaded/sf/js/superfish.js?p"></script>
<script type="text/javascript" src="/themes/pixture_reloaded/jquery.simplemodal.1.4.2.min.js?p"></script>
<script type="text/javascript" src="/themes/pixture_reloaded/jquery.socialshareprivacy.js?p"></script>

<script type="text/javascript" src="quickrating/functions.js?p"></script>


<script type="text/javascript" src="/themes/pixture_reloaded/lib/boxover.js?p"></script>
<script type="text/javascript" src="/themes/pixture_reloaded/lib/ga_script.js?p"></script>
<script type="text/javascript" src="/themes/pixture_reloaded/lib/modal_login.js?p"></script>
<script type="text/javascript" src="/themes/pixture_reloaded/socialshareprivacy/socialshareprivacyLoader.js?p"></script>

<?php

	
	error_reporting(1);
		chdir($_SERVER["DOCUMENT_ROOT"]);
	require_once $_SERVER["DOCUMENT_ROOT"] . "/includes/bootstrap.inc";
	drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
	$step = 1;
?>

</head>
<body>
<div style="text-align:center;font-size:20px;background-color:#DFE6F0;color:#005192;font-weight:bold">Hier können Sie eine Bank / Anbieter oder ein Produkt bewerten:</div>
          				<div id="content-area-left">

	<div id="node-4038">
<?php
	include("http://www.bankingcheck.de/sites/all/modules/quick_rating_wv/quickrating/create_rating.php");

?>
</div>
</div>
</body>
</html>