<?php
/**
	 * Elgg flex profile edit action
	 * Allows user to edit profile
	 * 
	 * @package Elgg
	 * @subpackage Form
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Kevin Jardine <kevin@radagast.biz>
	 * @copyright Radagast Solutions 2008
	 * @link http://radagast.biz/
	 */

	 // Load flexprofile model
require_once(dirname(dirname(__FILE__)) . "/models/model.php");

if ($user = page_owner()) {
	$user = page_owner_entity();			
} else {
	$user = $_SESSION['user'];
	set_page_owner($user->getGUID());
}

$query = "SELECT * FROM {$CONFIG->dbprefix}users_entity join {$CONFIG->dbprefix}_content_item_discrimination on {$CONFIG->dbprefix}users_entity.guid = {$CONFIG->dbprefix}_content_item_discrimination.guid and {$CONFIG->dbprefix}_content_item_discrimination.creator_guid = \"".$_SESSION['id']."\""; 

	$result = get_data($query);

if ($user && $user->canEdit()) {

    $data = form_get_profile_data_from_form_post();
    form_set_data($user,$data);
    // Notify of profile update
	trigger_elgg_event('profileupdate',$user->type,$user);
	//add to river
	add_to_river('river/user/default/profileupdate','update',$user->guid,$user->guid);
	
	system_message(elgg_echo("profile:saved"));

	// Forward to the user's profile
	forward($user->getUrl());

} else {
    // If we can't, display an error
	
	register_error(elgg_echo("profile:cantedit"));
	forward();
}

?>