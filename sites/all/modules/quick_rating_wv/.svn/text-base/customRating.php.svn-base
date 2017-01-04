<style>

/*Autosuggest*/

#result {
	height:20px;
	font-size:17px;
	font-family:Arial, Helvetica, sans-serif;
	color:#333;
	padding:5px;
	margin-bottom:10px;
	background-color:#FFFF99;
}
#borc, #headline, #description{
	padding:3px;
	border:1px #CCC solid;
	font-size:17px;
}
.suggestionsBox {
	position: absolute;
	left: 0px;
	top:40px;
	margin: 26px 0px 0px 0px;
	width: 200px;
	padding:0px;
	background-color: #000;
	border-top: 3px solid #000;
	color: #fff;
}
.suggestionList {
	margin: 0px;
	padding: 0px;
}
.suggestionList ul li {
	list-style:none;
	margin: 0px;
	padding: 6px;
	border-bottom:1px dotted #666;
	cursor: pointer;
}
.suggestionList ul li:hover {
	background-color: #FC3;
	color:#000;
}
ul {
	font-family:Arial, Helvetica, sans-serif;
	font-size:11px;
	color:#FFF;
	padding:0;
	margin:0;
}

.load{
background-image:url(img/loader.gif);
background-position:right;
background-repeat:no-repeat;
}

#suggest {
	position:relative;
}


</style>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.js"></script>
<script>
function suggest(inputString){
		if(inputString.length == 0) {
			$('#suggestions').fadeOut();
		} else {
		$('#borc').addClass('load');
			$.post("autosuggest.php", {queryString: ""+inputString+""}, function(data){
				if(data.length >0) {
					$('#suggestions').fadeIn();
					$('#suggestionsList').html(data);
					$('#borc').removeClass('load');
				}
			});
		}
	}

	function fill(thisValue) {
		$('#borc').val(thisValue);
		setTimeout("$('#suggestions').fadeOut();", 1000);
	}
</script>
<?php
error_reporting(0);
chdir($_SERVER['DOCUMENT_ROOT']);
require_once $_SERVER['DOCUMENT_ROOT'].'/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

?>
<form method='POST' id='form' action="<?php echo "result.php" ?>" autocomplete='off' target='_blank'>
<div id='suggest'>Namen der Bank oder des Kreditinstitutes</br>
	<input name='title' id='borc' onkeyup='suggest(this.value);' onblur='fill();' onMouseover='' type='text' value=''/>
		<div class='suggestionsBox' id='suggestions' style='display: none;'> 
			<div class='suggestionsList' id='suggestionsList'></div>
		</div>
</div>
<p>2. Teil der &Uuml;berschrift</br><input name='headline' id='headline' type='text' onMouseover=''/></p>
<p>Beschreibung</br><textarea name='description' cols='76' rows='10' id='description' value='Hier einige Zeilen zur Bank / Anbieter und zu den Produkten eintragen' onMouseover=''></textarea></p>
<input type='submit' id='sbt'>
</form>


<?php
/*
$title =$_POST['title'];
$content = $_POST['description'];
$headline = $_POST['headline'];

$info = db_fetch_object(db_query("SELECT * FROM `node` WHERE `title`=\"$title\" and `type` = \"bank\""));
$nid = $info->nid;
echo $nid;
$name = $info->title;
echo $name;
$bankItems = db_query("SELECT * FROM `content_type_productitem` WHERE `field_proditemmybank_nid` =".$nid);

echo "<h1>$name $headline</h1>";
echo "<div id='desc'>$content</div>";
echo "<div id='pic'></div>";

while($bankItem = db_fetch_object ($bankItems)) {
//print_r($bankItem);
$element=new StdClass;
$element->bank_id=$bankItem->field_proditemmybank_nid;
$element->product_id=$bankItem->nid;
$element->type_id=$bankItem->field_prodmyproduct_nid;
$element->rating=$bankItem->field_rating_value;
$returnValue[] = $element;
}

$bank_anker= "http://www.bankingcheck.de/banken/".strtolower(str_replace(" ", "-",$name));
$linkBank = "<a href='".$bank_anker."' target='_blank'>$name</a>";

echo "<table id='bankingcheck'>
		<tr>
			<th width='342px'>Produkt</th>
			<th width='100px'>durchschnittliche Bewertung</th>
			<th width='150px'></th>
		</tr>";

for($i=0;$i<=sizeof($returnValue)-1;$i++){
$infos = db_fetch_object(db_query('SELECT * FROM `node` WHERE `nid` ='.$returnValue[$i]->product_id));
$bank_name = $infos->title;
$bank_id = $returnValue[$i]->bank_id;
$product_id = $returnValue[$i]->product_id;
$rating = $returnValue[$i]->rating;
$type_id = $returnValue[$i]->type_id;

switch($type_id) {
case 15:
  $type = "tagesgeld";
  break;
case 16:
  $type = "festgeld";
  break;
case 17:
  $type = "girokonto";
  break;
case 18:
  $type = "kreditkarte";
  break;
case 19:
  $type = "depot";
  break;
case 20:
  $type = "ratenkredit";
  break;
case 21:
  $type = "autokredit";
  break;
case 4083:
  $type = "kreditkarte";
  break;
case 4085:
  $type = "mietkaution";
  break;
default:
  $type = "default";
  break;
}



$product_anker= "http://www.bankingcheck.de/produkte/$type/".strtolower(str_replace(" ", "-", $bank_name));
$linkA = "<a href='".$product_anker."' target='_blank'>$bank_name</a>";

$anker = "http://www.bankingcheck.de/node/add/bewertung-".$type."?itemId=".$product_id;
$linkB = "<a href='".$anker."' class='blueButton' target='_blank'>Bewertung abgeben</a>";

if($rating == 0){
echo "<tr id='zeile".($i%2)."'><td class='tooltip'>$linkA<span>Hier koennten evtl weitere Infors zum Produkt stehen $type</span></td><td>/</td><td>$linkB</td></tr>";
}
else {
echo "<tr id='zeile".($i%2)."'><td class='tooltip'>$linkA<span>Hier koennten evtl weitere Infors zum Produkt stehen $type</span></td><td>$rating</td><td>$linkB</td></tr>";
}
}
$anker = "http://www.bankingcheck.de/node/add/bewertung-bank?itemId=".$nid;
$linkProvider = "<a href='".$anker."' class='blueButton' target='_blank'>Bewertung abgeben</a>";
echo "<tr>
			<th>Bank</th>
			<th>Bewertung</th>
			<th></th>
		</tr>";
echo "<tr id='zeile".($i%2)."'><td class='tooltip'>$linkBank<span>Hier koennten evtl weitere Infors zum Produkt stehen $type</span></td><td>-</td><td>$linkProvider</td></tr>
</table>"; 


$hmtlCode = "";
*/
?>








