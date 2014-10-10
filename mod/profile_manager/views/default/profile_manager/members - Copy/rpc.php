<?php
require_once 'procedures_retrieve.php';

$func = $_REQUEST['func'];
$type_request = $_REQUEST['type_request'];
if (function_exists($func)) {
	switch ($type_request) {
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
