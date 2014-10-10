<?php

	/**
	 * Save Userpoints settings
	 * 
	 */

	global $CONFIG;

	gatekeeper();
	action_gatekeeper();


	// Params array (text boxes and drop downs)
	$params = get_input('params');
	foreach ($params as $k => $v) {
		if (!set_plugin_setting($k, $v, 'fivestar')) {
			register_error(sprintf(elgg_echo('plugins:settings:save:fail'), 'fivestar'));
			forward($_SERVER['HTTP_REFERER']);
		}
	}

    if (is_array(get_input('change_vote'))) {
        set_plugin_setting('change_vote', 1, 'fivestar');
    } else {
        set_plugin_setting('change_vote', 0, 'fivestar');
    }


    $view = '';

    foreach ($_POST['views'] as $value) {
        $view .= $value . "\n";
    }

    set_plugin_setting('view', $view, 'fivestar');
    //set_plugin_setting('view', 0, 'fivestar');

	system_message(elgg_echo('fivestar:settings:save:ok'));
	
	forward($_SERVER['HTTP_REFERER']);
?>
