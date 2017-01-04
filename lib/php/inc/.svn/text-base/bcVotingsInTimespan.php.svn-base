<?php
include($_SERVER['DOCUMENT_ROOT']."/lib/php/inc/miniBootstrap.php");

foreach(array("nid","productType","m","t") as $param) {
	switch($param) {
		case "t":
		case "m":
		case "nid": if(!ereg("^[0-9]+$", $_POST[$param])) die("not allowed"); break;
		case "productType": if(!ereg("^[a-z]+$", $_POST[$param])) die("not allowed"); break;
	}
	$filtered[$param] = $_POST[$param];
	unset($_POST[$param]);
}
if(count($_POST)>0) die("too much params");

$test = new BCVotings($filtered["nid"], $filtered["productType"]);
$ts1 = NULL;
if($filtered["m"] != "0")
{
	if($filtered["m"] == "1")
  {
		$date = new Date();
		$ts1 = new Timespan($date->addIntvalDay(-30), Date::now());
	}
  // bei 13 wird Award 13 Zeitraum ausgegeben !!
  else if ($filtered["m"] == "13")
  {
    $ts1 = new Timespan(Date(mktime(0, 0, 0, 1, 1, 2013)), Date(mktime(23, 59, 59, 9, 30, 2013)));
  }
  else if ($filtered["m"] == "14")
  {
	$ts1 = new Timespan(Date(mktime(0, 0, 0, 1, 1, 2014)), Date(mktime(23, 59, 59, 5, 31, 2014)));
  }
  else if ($filtered["m"] == "15")
  {
	$ts1 = new Timespan(Date(mktime(0, 0, 0, 1, 1, 2015)), Date(mktime(23, 59, 59, 4, 30, 2015)));
  }
  else
  {
	$ts1 = new Timespan(Date::nextNMonth($filtered["m"] * -1), Date::now());
  }
}
$test->showVotingsForTimespan($ts1);
?>