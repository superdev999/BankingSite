<html>
<head>
<body>

<?php
$productid=$_GET["productid"];

$platzhalter='<div style="width:150px; font-family: arial,verdana,sans-serif; font-size: 13px;">
<div style="padding-bottom: 10px;"><a href="http://www.bankingcheck.de/produkte/" target="_blank"><img style="border:none;" height="150" width="150" src="http://www.bankingcheck.de/sites/default/files/bcgrafiken/BankingCheck_Siegel_Usr_not.gif"></a><br></div>
<div style="background:url(http://www.bankingcheck.de/themes/pixture_reloaded/images/bg-social-bewerten.png); background-repeat:no-repeat; padding-left:20px;margin-left:20px;"><a style="text-decoration: none; color: #2677B8;" href="http://www.bankingcheck.de/produkte/" target="_blank">Bewerten</a>
<a target="_blank" style="text-decoration: none; color: #000;" href="http://www.bankingcheck.de/produkte/"></a></div></div>';

$html = '
<div id="bankingcheck">'.$platzhalter.'</div>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script>
jQuery(document).ready(function(){
  jQuery.getJSON("http://www.bankingcheck.de/BewertungsWidget/bw_widget_JSON.php?productid='.$productid.'&callback=?", function(data){
   jQuery("#bankingcheck").html(data.html);
  });
});
</script>
';
?>
<pre>
<?php
	echo htmlspecialchars($html);
?>
</pre>
</body>
</html>