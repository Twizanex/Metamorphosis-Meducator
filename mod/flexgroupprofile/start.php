<?php

/**
 * Elgg flexgroupprofile plugin
 * 
 * @package FlexGroupProfile
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Kevin Jardine <kevin@radagast.biz>
 * @copyright Radagast Solutions 2008
 * @link http://radagast.biz/
 */

// Load flexgroupprofile model
require_once(dirname(__FILE__)."/models/model.php");
    
function flexgroupprofile_init() {
	register_page_handler('flexgroupprofile','flexgroupprofile_page_handler');
}

function flexgroupprofile_pagesetup() {
	global $CONFIG;
	
	$page_owner = page_owner_entity();
	
	// Group submenu option	
	if ($page_owner instanceof ElggGroup && get_context() == 'groups') {
		$form = flexgroupprofile_get_profile_form($page_owner,$page_owner->group_profile_category);
		if (!$form->profile_format || ($form->profile_format == 'default')) {
			$title = friendly_title($page_owner->name);
			add_submenu_item(elgg_echo('form:extended_profile_link_text'),$CONFIG->wwwroot.'pg/flexgroupprofile/'.$page_owner->getGUID().'/'.$title.'/','0extendedprofile');
		}
	}
}

/* Flexprofile page handler; allows the use of fancy URLs
 *
 * @param array $page From the page_handler function
 * @return true|false Depending on success
 */
function flexgroupprofile_page_handler($page) {
	
	// The first component of a flexgroupprofile URL is the group_guid
	if (isset($page[0])) {
		set_input('group_guid',$page[0]);
	}
	
	@include(dirname(__FILE__) . "/extended.php");
	return true;
	
}
	register_elgg_event_handler('init','system','flexgroupprofile_init');
	register_elgg_event_handler('pagesetup','system','flexgroupprofile_pagesetup');

	
// Register actions
	global $CONFIG;
	register_action("flexgroupprofile/edit",false,$CONFIG->pluginspath . "flexgroupprofile/actions/edit.php");

?>