<?php
	/**
	 * Elgg delete user
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @author Curverider Ltd
	 * @link http://elgg.org/
	 */

	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

	
	// block non-admin users
		global $CONFIG;	
                
              
		
	/*	
		
	$deladdress=$CONFIG->API_URL . "79131d4a-484a-4643-8577-5b7f1fe197f4";
	echo connectToSesame($deladdress,"","YES");
*/
	$nik=get_entity(1593);
	$nik->meducator15=NULL;
	?>