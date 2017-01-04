<?php
chdir($_SERVER['DOCUMENT_ROOT']);
require_once $_SERVER['DOCUMENT_ROOT'].'/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

$userPasswordArray = array("godard@nova-web.de" => '$1$8qP6QTGz$kL2yTNrwUEGCJM8tcyf5g0', "admin@nova-web.de" => '$1$rLVK3yQ9$BRwOMECrUjZGOZIG5HGWs.', "admin@bankingcheck.de" => '$1$1rDmpR8A$DyHEy2zg5M.3FcVSyS/D/1');
#print_r($_SESSION);

#print_r(session_get_cookie_params());
#if (user_access("edit any bewertung_autokredit content")) $loggedIn = true;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="de" xml:lang="de">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta http-equiv="Content-Style-Type" content="text/css" />
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link rel="shortcut icon" href="/themes/pixture_reloaded/favicon_check.ico" type="image/x-icon" />
  <title>BankingCheck.de | Schnellfreigabe | 500 Bewertungen </title>

<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.1/themes/base/jquery-ui.css" />
    <script src="http://code.jquery.com/jquery-1.8.2.js"></script>
    <script src="http://code.jquery.com/ui/1.9.1/jquery-ui.js"></script>
 <link type="text/css" rel="stylesheet" media="all" href="../../../../modules/node/node.css" />

<link type="text/css" rel="stylesheet" media="all" href="../../../../themes/pixture_reloaded/layout.css" />
<link type="text/css" rel="stylesheet" media="all" href="../../../../themes/pixture_reloaded/custom.css" />

  <script>
    $(function() {
        $( "#tabs" ).tabs();
    });
    </script>

<style type="text/css">
* {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
color: #535353;
}

td {
	padding-left: 5px;
	padding-right: 5px;
	border: 1px solid black;
}

tr:hover {
	background-color:lightgrey;
}

</style>
</head>
<body>
<a href="/sites/all/libraries/anonRatings/listAnonRatings.php"><h1 style="font-size:24px;font-weight:bold;text-align:center;">Bankingcheck.de | Schnellfreigabe | 500 Bewertungen</h1></a>
<?php 
if (isset($_POST["publishRatings_user"]) && isset($_POST["publishRatings_password"])) {

	$getUser = $_POST["publishRatings_user"];
	$getPassword = $_POST["publishRatings_password"];
	$hash = $userPasswordArray[$getUser];
	#echo "Versuche mit ".$getUser." und ".$getPassword." einzuloggen.<br>";
	if (crypt($getPassword, $hash) == $hash) {
		$loggedIn = true;
		$_SESSION["novaNodeSystemUser"] = $getUser;
		$_SESSION["novaNodeSystemHash"] = $hash;
		$message = "Eingeloggt.";
		#print_r($_SESSION);
	} else {
		echo "Falscher Benutzername oder falsches Passwort.<br>";
		echo crypt($getPassword)."<br>";
	}
} elseif($userPasswordArray[$_SESSION["novaNodeSystemUser"]] = $_SESSION["novaNodeSystemHash"]) {
	#print_r($_SESSION);
	$loggedIn = true;
} #else echo "not trying to log in";
if ($_POST["action"] == "logout") {
	session_destroy();
	$loggedIn = false;
	$location = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
	header('Location: '.$location);
	#echo "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
	$message = "Ausgeloggt.";	
}

if ($loggedIn) {
?>
<div style="text-align:center;padding-bottom:6px;">
<?php echo $message; ?><br>
<form method="POST"><input type="hidden" name="action" value="logout" /><input type="submit" value="Ausloggen" /></form>
</div>
<div style="width:98%;margin-left:2%;">
<div id="tabs">
    <ul>
        <li><a href="#tabs-1">Zur Freigabe<br> (verifiziert)</a></li>
        <li><a href="#tabs-2">Nicht veröffentlicht<br> (unverifiziert)</a></li>
        <li><a href="#tabs-3">Veröffentlicht <br>(verifiziert)</a></li>
        <li><a href="#tabs-4">Veröffentlicht <br>(unverifiziert)</a></li>

	    </ul>	    
<?php
publishRequestedRating();
$sql = db_query('SELECT nid FROM `node` WHERE `type` LIKE "bewertung_%" AND uid=0 ORDER BY nid DESC LIMIT 0,500');
$unverifiedAndUnpublished = array();
$verifiedAndUnpublished = array();
$unverifiedAndPublished = array();
$verifiedAndPublished = array();
while ($ratingObject = db_fetch_object ($sql)) {
	$ratingNode = node_load(array("nid" => $ratingObject->nid));
	$novaRatingNode = new NovaBCVoting($ratingNode->nid);
	$mailApproved = $novaRatingNode->getMailApproved();
	if ($ratingNode->status == 1) {
		// Published
		if ($mailApproved == 1) {
			// Mail verified
			$verifiedAndPublished[] = $ratingNode;
		} else {
			// Mail not verified
			$unverifiedAndPublished[] = $ratingNode;
		}
	} else {
		if ($mailApproved == 1) {
			// Mail verified
			$verifiedAndUnpublished[] = $ratingNode;
		} else {
			// Mail not verified
			$unverifiedAndUnpublished[] = $ratingNode;
		}
	}
}
echo '<div id="tabs-1">';

printNodeArray($verifiedAndUnpublished);

echo '</div><div id="tabs-2">';

printNodeArray($unverifiedAndUnpublished);
echo '</div><div id="tabs-3">';


printNodeArray($verifiedAndPublished);
echo '</div><div id="tabs-4">';


printNodeArray($unverifiedAndPublished);
echo "</div>";

} else {
	?>
	<div style="text-align:center;width:300px;margin-left:auto;margin-right:auto;">
	<?php echo $message; ?><br>
	Bitte mit Bewertungsfreigabe-Login einloggen.<br>
	<form name="publishRatings_login" method="POST">
	<table style="width:300px;">
	<tr><td>Benutzername</td><td><input type="text" name="publishRatings_user" /></td></tr>
	<tr><td>Passwort</td><td><input type="password" name="publishRatings_password" /></td></tr>
	<tr><td colspan="2"><input type="submit" value="Login" /></td></tr>	
	</table></form></div>
	<?php
}
?>
</div>
</body></html>

<?php 
function printNodeArray($array) {
	echo '<table style="border:1px solid black;width:98%;margin-left:1%"><tr><th>Nid</th><th>Datum</th><th>Typ</th><th>Titel</th><th>Kommentar</th><th>Bewertungszahl</th><th>E-Mail</th><th>Freigeben</th></tr>';
	foreach ($array as $node) {
		echo printNode($node);
	}
	echo "</table>";
}

function printNode($node) {
	$novaBCRating = new NovaBCVoting($node->nid);
	#print_r($node);
	$output = '
	<tr>
	<td><a href="http://'.$_SERVER['SERVER_NAME'].'/node/'.$node->nid.'">'.$node->nid.'</a></td>
	<td>'.format_date($node->created, "middle").'</td>
	<td>'.$node->type.'</td>
	<td>'.$node->title.'</td>
	<td style="max-width:60%">'.wordwrap($novaBCRating->getComment(), 85, "\n", true).'</td>
	<td>'.$novaBCRating->getAverage().'</td>
	<td>'.$novaBCRating->getEmail().'</td>';
	if ($novaBCRating->getStatus() == 0) $output .= '<td><form name="publish_'.$node->nid.'" method="POST"><input type="hidden" name="publish_nid" value="'.$node->nid.'" /><input type="submit" value="Veröffentlichen" /></form></td>';
	else                             $output .= '<td>Bereits freigegeben</td>';
	$output .= '</tr>';
	return $output;
}

function publishRequestedRating() {
	if (isset($_POST["publish_nid"])) {
		$nid = intval($_POST["publish_nid"]);
		$node = node_load(array("nid" => $nid));
		$node->status = 1;
		node_save($node);
		?>
		<div style="background-color: lightgreen; width: 90%; margin-left: 5%; margin-top: 5px; border: 1px black solid; font-size: 18px; text-align: center;">Bewertung <a href="/node/<?php echo $nid; ?>" style="font-size: 18px;">#<?php echo $nid; ?></a> freigegeben</div>	
	<?php
	}
}
?>
