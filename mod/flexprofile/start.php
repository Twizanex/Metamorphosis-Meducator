<?php

/**
 * Elgg flexprofile plugin
 * 
 * @package FlexProfile
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Kevin Jardine <kevin@radagast.biz>
 * @copyright Radagast Solutions 2008
 * @link http://radagast.biz/
 */


// Load flexprofile model
    require_once(dirname(__FILE__)."/models/model.php");
    
/**
 * Profile init function; sets up the profile functions
 *
 */
	function flexprofile_init() {

		// override profile views
		set_view_location("profile/userdetails", dirname(__FILE__).'/views/');
		set_view_location("profile/edit", dirname(__FILE__).'/views/');
		
		// Register a page handler, so we can have nice URLs
		register_page_handler('flexprofile','flexprofile_page_handler');
	}
	
	function flexprofile_pagesetup() {
		global $CONFIG;
		
		if (get_context() == 'profile') {
			$form = flexprofile_get_profile_form();
			if (!$form->profile_format || ($form->profile_format == 'default')) {		
				extend_view("profile/menu/actions","flexprofile/menu/actions");
			}
		}
	}
	
	/* Flexprofile page handler; allows the use of fancy URLs
	 *
	 * @param array $page From the page_handler function
	 * @return true|false Depending on success
	 */
	function flexprofile_page_handler($page) {
		
		// The first component of a flexprofile URL is the username
		if (isset($page[0])) {
			set_input('username',$page[0]);
		}
		
		@include(dirname(__FILE__) . "/extended.php");
		return true;
		
	}

	register_elgg_event_handler('pagesetup','system','flexprofile_pagesetup');
	
// Make sure the profile initialisation function is called on initialisation
	register_elgg_event_handler('init','system','flexprofile_init',2);
	
// Register actions
	global $CONFIG;
	register_action("flexprofile/edit",false,$CONFIG->pluginspath . "flexprofile/actions/edit.php");

?>