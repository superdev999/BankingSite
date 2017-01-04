<?php
ini_set("include_path", $_SERVER['DOCUMENT_ROOT']."/");
include($_SERVER['DOCUMENT_ROOT']."/lib/php/inc/fullBootstrap.php");

$du = new DebugUtilities();

$test = new BCVotings(85, "tagesgeld");
$ts1 = new Timespan(Date::nextNMonth(-3), Date::now());
$test->showVotingsForTimespan($ts1);



//$du = new DebugUtilities();
//$du->startDuration();
//
//$dp = new DBToolsDrupal();
//$sql = "SELECT node.nid as nid, LOWER(node2.title) AS productType FROM node
//LEFT JOIN content_type_productitem ON  content_type_productitem.nid = node.nid
//LEFT JOIN node AS node2 ON field_prodmyproduct_nid = node2.nid
//WHERE node.type = 'productitem'
//";

//$node = node_load(array("nid"=>85));
//DebugUtilities::print_rInTextarea($node);
//$node->field_rating[0]["value"] = 	4.8;
//node_save($node);
//die();



//DebugUtilities::print_rInTextarea(node_load(array("nid"=>85)));

$du->endDurationAndPrint();
?>