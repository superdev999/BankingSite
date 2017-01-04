?<?php

error_reporting(1);
chdir($_SERVER['DOCUMENT_ROOT']);
require_once $_SERVER['DOCUMENT_ROOT'].'/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

		
		if(isset($_POST['queryString'])) {
			$queryString = $_POST['queryString'];
			
			if(strlen($queryString) >0) {
				$query = db_query("SELECT title FROM node WHERE title like '%{$queryString}%' and `type` = \"bank\"");
				if($query) {
				echo '<ul>';
					while ($result = db_fetch_object($query)) {
	         			echo '<li onClick="fill(\''.addslashes($result->title).'\');">'.$result->title.'</li>';
	         		}
				echo '</ul>';
					
				} else {
					echo 'OOPS we had a problem :(';
				}
			} else {
				
			}
		} else {
			echo 'There should be no direct access to this script!';
		}
	
?>