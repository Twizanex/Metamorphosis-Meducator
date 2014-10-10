<?php

	global $CONFIG;

	// Get user id
	$access_status = access_get_show_hidden_status();
	access_show_hidden_entities(true);
	
	$user_guid = (int)get_input('u');
	$user = get_entity($user_guid);
	
	// And the code
	$code = sanitise_string(get_input('c'));
	
	if ( ($code) && ($user) )
	{
		if (siteaccess_validate_email($user_guid, $code)) {
			system_message(elgg_echo('siteaccess:confirm:success'));
            
            siteaccess_notify_user($user, 'validated');
		} else
			register_error(elgg_echo('siteaccess:confirm:fail'));
	}
	else
		register_error(elgg_echo('siteaccess:confirm:fail'));
		
	access_show_hidden_entities($access_status);
		
	forward();
	exit;

?>
