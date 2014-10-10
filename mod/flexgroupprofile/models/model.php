<?php
/**
	 * Elgg flex group profile model
	 * Functions to save and display profile data
	 * 
	 * @package Elgg
	 * @subpackage FlexGroupProfile
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Kevin Jardine <kevin@radagast.biz>
	 * @copyright Radagast Solutions 2008
	 * @link http://radagast.biz/
	 */

// Load form model
require_once(dirname(dirname(dirname(__FILE__))) . "/form/models/model.php");
// Load form profile model
require_once(dirname(dirname(dirname(__FILE__))) . "/form/models/profile.php");

function flexgroupprofile_get_profile_form($entity,$group_profile_category='') {
    return form_get_latest_public_profile_form(2,$group_profile_category);
}

// a stub for future use

function flexgroupprofile_get_profile_config($group_profile_category) {
	$group_config = new stdClass();
	return $group_config;
}

?>