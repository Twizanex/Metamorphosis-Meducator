<?php
	/**
	 * Elgg delete user
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @author Curverider Ltd
	 * @link http://elgg.org/
	 */

	require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/engine/start.php");

	
	// block non-admin users
	gatekeeper();
	action_gatekeeper();
		global $CONFIG;
	// Get the user 
	$guid = get_input('guid');
	$obj = get_entity($guid);
	$sesid= get_input('sesid');
	
	$deladdress=$CONFIG->API_URL . $sesid;

	
	if (($obj instanceof ElggUser) && ($obj->canEdit()))
	{

			if($obj->issimpleuser=='no')
				if($sesid)
				connectToSesame($deladdress,"","YES");
	
		if ($obj->delete()) {
			system_message(elgg_echo('admin:user:delete:yes'));

	
		
		
		
		
		} else
			register_error(elgg_echo('admin:user:delete:no'));
	}
	else
		register_error(elgg_echo('admin:user:delete:no'));
		
	forward('http://metamorphosis.med.duth.gr');
	exit;
?>