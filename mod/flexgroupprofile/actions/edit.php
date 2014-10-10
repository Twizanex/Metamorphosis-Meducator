<?php
/**
 * Elgg flex group profile edit action
 * Allows user to edit profile
 * 
 * @package Elgg
 * @subpackage FlexGroupProfile
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Kevin Jardine <kevin@radagast.biz>
 * @copyright Radagast Solutions 2008
 * @link http://radagast.biz/
 */

// Load form profile model
require_once(dirname(dirname(dirname(__FILE__))) . "/form/models/profile.php");

// Load flexgroupprofile model
require_once(dirname(dirname(__FILE__)) . "/models/model.php");

$user_guid = get_input('user_guid');
$user = NULL;
if (!$user_guid) $user = $_SESSION['user'];
else
$user = get_entity($user_guid);

$group_guid = get_input('group_guid','');
$group = new ElggGroup($group_guid); // load if present, if not create a new group
if (($group_guid) && (!$group->canEdit()))
{
	register_error(elgg_echo("groups:cantedit"));
	
	forward($_SERVER['HTTP_REFERER']);
	exit;
}

$group_profile_category = get_input('group_profile_category',null);
if (isset($group_profile_category)) {
	$group->group_profile_category = $group_profile_category;
}

$group->name = get_input('name','');
$group->description = get_input('description','');

// Validate create
if (!$group->name)
{
	register_error(elgg_echo("groups:notitle"));
	
	forward($_SERVER['HTTP_REFERER']);
	exit;
}

// Group membership
switch (get_input('membership'))
{
	case 2: $group->membership = ACCESS_PUBLIC; break;
	default: $group->membership = ACCESS_PRIVATE; 
}

// Get access
$group->access_id = ACCESS_PUBLIC;

// Set group tool options
if (isset($CONFIG->group_tool_options)) {
	foreach($CONFIG->group_tool_options as $group_option) {
		$group_option_toggle_name = $group_option->name."_enable";
		if ($group_option->default_on) {
			$group_option_default_value = 'yes';
		} else {
			$group_option_default_value = 'no';
		}
		$group->$group_option_toggle_name = get_input($group_option_toggle_name, $group_option_default_value);
	}
}	

$data = form_get_profile_data_from_form_post();

form_set_data($group,$data);

$group->save();

if (($group_owner_username = get_input('group_owner_username',''))
	&& ($user = get_user_by_username($group_owner_username) )) {
		$new_group_owner_guid = $user->getGUID();
		$group->owner_guid = $new_group_owner_guid;
		$group->container_guid = $new_group_owner_guid;
}

if (!$group->isMember($user))
	$group->join($user); // Creator always a member


// Now see if we have a file icon
if ((isset($_FILES['icon'])) && (substr_count($_FILES['icon']['type'],'image/')))
{
	$prefix = "groups/".$group->guid;
	
	$filehandler = new ElggFile();
	$filehandler->owner_guid = $group->owner_guid;
	$filehandler->setFilename($prefix . ".jpg");
	$filehandler->open("write");
	$filehandler->write(get_uploaded_file('icon'));
	$filehandler->close();
	
	$thumbtiny = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(),25,25, true);
	$thumbsmall = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(),40,40, true);
	$thumbmedium = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(),100,100, true);
	$thumblarge = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(),200,200, false);
	if ($thumbtiny) {
		
		$thumb = new ElggFile();
		$thumb->setMimeType('image/jpeg');
		
		$thumb->setFilename($prefix."tiny.jpg");
		$thumb->open("write");
		$thumb->write($thumbtiny);
		$thumb->close();
		
		$thumb->setFilename($prefix."small.jpg");
		$thumb->open("write");
		$thumb->write($thumbsmall);
		$thumb->close();
		
		$thumb->setFilename($prefix."medium.jpg");
		$thumb->open("write");
		$thumb->write($thumbmedium);
		$thumb->close();
		
		$thumb->setFilename($prefix."large.jpg");
		$thumb->open("write");
		$thumb->write($thumblarge);
		$thumb->close();
			
	}
}

trigger_elgg_event('groupprofileupdate','group',$group);

system_message(elgg_echo("groups:saved"));

// Forward to the group's profile
forward($group->getUrl());
exit;

?>