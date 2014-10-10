<?php

	/**
	 * Elgg Content Item page
	 * 
	 * @package 
	 * @author 
	 * @copyright 
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @link 
	 */
	 
	 global $CONFIG;
 
	register_action("content_item/save", false, $CONFIG->pluginspath . "content_item/actions/save.php");

/*
	function content_item_pagesetup() {
		
		// Menu options
			global $CONFIG;
			if (get_context() == "friends" || 
				get_context() == "friendsof" || 
				get_context() == "collections") {
					
					add_submenu_item(elgg_echo('content_item:create'),$CONFIG->wwwroot."mod/content_item/",'create');
					
				}
		
	}

	global $CONFIG;
	register_action('content_item/create', false, $CONFIG->pluginspath . 'content_item/actions/create.php');
	register_elgg_event_handler('pagesetup','system','content_item_pagesetup',1000);
*/
?>