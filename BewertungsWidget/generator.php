<html>
<head>
<body>

<?php
$productid=$_GET["productid"];

$votebutton="true";
if($_GET["votebutton"]=='false') $votebutton="false";
$votecount="true";
if($_GET["votecount"]=='false') $votecount="false";

$counter="true";
if($_GET["counter"]=='false') $counter="false";

$js="false";
if($_GET["js"]=='true') $js="true";
if($_GET["js"] =='true' AND $_GET["snippet"] =='true') $js="true"; $snippet="true";

$snippet = "false";
if($_GET["snippet"]=='true') $snippet="true";
//https???
$pp='http';
if($_GET["https"]=='yes')$pp='https';
//Popup Ja Nein?
$popup='false';
if($_GET["popup"]=='true') $popup="true";
//PopupClean Ja Nein?
$popupClean='false';
if($_GET["popupClean"]=='true') $popupClean="true";
//Anker Bewertung Ja Nein?
$ankervote='true';
if($_GET["ankervote"]=='false') $ankervote="false";
// Anzahl Bewertungen klickbar
$ankercount='true';
if($_GET["ankercount"]=='false') $ankercount="false";

$ankerimage='true';
if($_GET["ankerimage"]=='false') $ankerimage="false";

if($_GET['snippet']=='true')
{
  $jsPopup='';
}
else if($_GET['popup']=='true' || $_GET['popupClean']=='true')
{
  $jsPopup='<script type="text/javascript">
			function popup(url) 
			{
				fenster = window.open(url, "Popupfenster", "width=500,height=768,resizable=yes, scrollbars=yes");
				fenster.focus();
				return false;
			}
			</script>';
} 
else if($_GET['noanker']=='true')
{
  $jsPopup='';
}

// Spezial für Umstellung
if (isset($_GET['votecount'])) // aha - neuer Aufruf
 {
	//alles bleibt wie gehabt
 }
else
{
 if ($productid == 786)
 {
  $counter="true";
  $isWithSnippet = false;
 }
 else if ($productid == 10604)
 {
  $counter="false";
  $isWithSnippet = false;
 }
 else if ($productid == 687)
 {
  $counter="false";
  $isWithSnippet = false;
 }
 if ($productid == 682)
 {
  $counter="true";
  $isWithSnippet = false;
 }
}


$html = '
<div id="bankingcheck"></div>'
.$jsPopup.'
<script type="text/javascript">
	function load_script(path){
   		var head = document.getElementsByTagName("head")[0]; 
   		var script = document.createElement("script"); 
   		script.type = "text/javascript"; 
   		script.src = path; 
   		head.appendChild(script);	
	}
	
	if(typeof jQuery != "function"){
		load_script(document.location.protocol+"//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js");
	}
	
	var aktiv;
	aktiv = window.setInterval("bw()", 1000);
	
	function bw(){
		jQuery(document).ready(function(){
			var ssl="no";
			if(document.location.protocol=="https:") ssl="yes";
			jQuery.getJSON(document.location.protocol+"//www.bankingcheck.de/BewertungsWidget/bw_widget_JSON.php?ssl="+ssl+"&javascript-use='.$js.'&snippet='.$snippet.'&productid='.$productid.'&counter='.$counter.'&popup='.$popup.'&popupClean='.$popupClean.'&ankervote='.$ankervote.'&ankerimage='.$ankerimage.'&votebutton='.$votebutton.'&votecount='.$votecount.'&ankercount='.$ankercount.'&callback=?", function(data){
				jQuery("#bankingcheck").html(data.html);
			});
		});
		window.clearInterval(aktiv);
	}
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