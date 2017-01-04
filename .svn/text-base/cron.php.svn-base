#!/usr/local/pd-admin2/bin/php5 -c/home/bankidbk/php-ini-for-cron.ini
<?php 
/*
#!/usr/local/pd-admin2/bin/php5
#!/usr/local/pd-admin2/php5/bin/php-cli -c/home/bankidbk/php.ini
*/
/**
 * @file
 * Handles incoming requests to fire off regularly-scheduled tasks (cron jobs).
 */
if(!isset($_SERVER['DOCUMENT_ROOT'])){
	$_SERVER['DOCUMENT_ROOT'] = dirname(__FILE__);
}
if(!isset($_SERVER["REQUEST_URI"])){
	$_SERVER['REQUEST_URI'] = 'http://www.bankingcheck.de';
}
if(!isset($_SERVER['SERVER_NAME'])){
	$_SERVER['SERVER_NAME'] = 'www.bankingcheck.de';
}

ob_start();

include_once './includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

define("NOVA_CRON_DEBUG", false);

drupal_cron_run();


$out = ob_get_contents();
ob_end_clean();

$out =  date('Y-m-d H:i:s') . "\n" . $out . "\n\n"; 
file_put_contents(dirname(__FILE__) . '/../cron.log', $out, FILE_APPEND);