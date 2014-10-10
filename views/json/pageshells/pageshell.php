<?php
	/**
	 * Elgg JSON output pageshell
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @author Curverider Ltd
	 * @link http://elgg.org/
	 * 
	 */

	if(stristr($_SERVER["HTTP_ACCEPT"],"application/json")) { 
		header("Content-Type: application/json");		
	} else {
		header("Content-Type: application/javascript");
	}
	// echo $vars['body'];
	
	global $jsonexport;
	echo json_encode($jsonexport);
?>