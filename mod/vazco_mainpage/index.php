<?php 
	/**
	 * Elgg vazco_mainpage plugin
	 * 
	 * @author Michal Zacher [michal.zacher@gmail.com]
	 */

	// Get the Elgg engine
	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
	require_once(dirname(__FILE__) . "/models/mainpage_widgets.php");

    //display the contents in our new canvas layout
	$body = elgg_view('canvas/layouts/vazco_index');

    page_draw($title, $body);
?>