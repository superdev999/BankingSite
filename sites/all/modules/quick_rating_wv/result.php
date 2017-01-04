<style>
table{
*border-collapse: collapse;
  border-spacing: 10px; 
}

th:first-child {
  border-radius: 6px 0 0 0px;
}

th:only-child{
    padding: 10px;
}

th:last-child {
  border-radius: 0 6px 0 0;
}


#bankingcheck th{
  background:lightgrey;
}   

#zeile0{
  background:white;
  text-align: center;
}

#zeile1{
  background:#DFE6F0;
  text-align: center;
}

a{
text-decoration: none;
color: black;
}

a:hover{
  text-decoration: none;
  color: white;
}

#zeile0:hover,#zeile1:hover {
background: #18609B;
color: white;
}

td.tooltip
{
  position: relative;
  text-decoration: none;
}

td.tooltip span
{
  display: none;
  width: 150px;
}

td.tooltip:hover span
{
  position: absolute;
  display: block;
  left: 15em;
  border: 1px solid black;
  background-color:grey;
  padding: 0.125em;

}

a.blueButton {
color: #FFFFFF;
background-color: #005191;
border-width: 0px;
border-radius: 5px;
padding: 5px;
padding-left: 15px;
padding-right: 15px;
box-shadow: 2px 2px 2px 2px #888888;
}

#desc{
width: 637px;
}

#pic{
content: url('https://www.bankingcheck.de/sites/default/files/News/header_bankingcheck_bewerten.jpg');
width:637px;
height:170px;
margin-top: 10px;
margin-bottom: 10px;
/*display: none;*/
}
h1{
font-family: arial;

}
</style>

<?php
error_reporting(1);
chdir($_SERVER['DOCUMENT_ROOT']);
require_once $_SERVER['DOCUMENT_ROOT'].'/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
mb_internal_encoding('UTF-8');


function get_data($url) {
	$ch = curl_init();
	$timeout = 5;
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}

$title = utf8_encode($_POST['title']);
$content = utf8_encode($_POST['description']);
$headline = utf8_encode($_POST['headline']);

$info = db_fetch_object(db_query("SELECT * FROM `node` WHERE `title`=\"$title\" and `type` = \"bank\""));
$nid = $info->nid;
$name = $info->title;

$bankItems = db_query("SELECT * FROM `content_type_productitem` WHERE `field_proditemmybank_nid` =".$nid);

//print_r($info);
echo "<h1>".utf8_decode($name." ".$headline)."</h1>";
echo "<div id='desc'>".utf8_decode($content)."</div>";
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
  $type = "baufinanzierung";
  break;
case 4085:
  $type = "mietkaution";
  break;
  default:
  $type = "default";
  break;
}

if($i == 0) {
$anker = "http://www.bankingcheck.de/node/add/bewertung-bank?itemId=".$bank_id;
$linkProvider = "<a href='".$anker."' class='blueButton' target='_blank'>Bewertung abgeben</a>";
}
$product_anker= "http://www.bankingcheck.de/produkte/$type/".strtolower(str_replace(" ", "-", $bank_name));
$linkA = "<a href='".$product_anker."' target='_blank'>$bank_name</a>";

$anker = "http://www.bankingcheck.de/node/add/bewertung-".$type."?itemId=".$product_id;
$linkB = "<a href='".$anker."' class='blueButton' target='_blank'>Bewertung abgeben</a>";

if($rating == 0){
echo "<tr id='zeile".($i%2)."'><td class='tooltip'>".utf8_decode($linkA)."<span>Hier koennten evtl weitere Infors zum Produkt stehen $type</span></td><td>/</td><td>$linkB</td></tr>";
}
else {
echo "<tr id='zeile".($i%2)."'><td class='tooltip'>".utf8_decode($linkA)."<span>Hier koennten evtl weitere Infors zum Produkt stehen $type</span></td><td>$rating</td><td>$linkB</td></tr>";
}
}
$anker = "http://www.bankingcheck.de/node/add/bewertung-bank?itemId=".$nid;
$linkProvider = "<a href='".$anker."' class='blueButton' target='_blank'>Bewertung abgeben</a>";
echo "<tr>
			<th>Bank</th>
			<th>Bewertung</th>
			<th></th>
		</tr>";
echo "<tr id='zeile".($i%2)."'><td class='tooltip'>".utf8_decode($linkBank)."<span>Hier koennten evtl weitere Infors zum Produkt stehen $type</span></td><td>-</td><td>$linkProvider</td></tr>
</table>";


//$content = get_data('http://www.bankingcheck.de/sites/all/modules/quick_rating_wv/result.php');
//$ htmlspecialchars($content);
?>




<form method='POST' action="">
<input type='submit' name='button' value='QuellCode' onClick='alert()'>
</form>
<?php
if(isset($_POST['submit']))
echo hier;

?> 



