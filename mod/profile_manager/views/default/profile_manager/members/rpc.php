<?php
ini_set('error_reporting', E_ALL);
error_reporting(E_ALL);
ini_set('log_errors',TRUE);
ini_set('error_log','C:/Progra~2/Apache~1/Apache2.2/htdocs/errorlog');
date_default_timezone_set('Europe/Athens');
require_once 'procedures_retrieve.php';
ini_set('display_errors', 0);

$func = $_REQUEST['func'];
if (function_exists($func)) {
	switch ($_REQUEST['type_request']) {
		case 'raw_html' :
			header('Content-Type: text/html; charset=utf-8');
			print $func ($_REQUEST);
			exit ();
		case 'json' :
		default :
			header('Content-Type: text/plain; charset=utf-8');
			print json_encode($func ($_REQUEST));
			exit ();
	}
} else {
	print json_encode(array (
		'status' => '404',
		'func' => $func
	));
}
?>
