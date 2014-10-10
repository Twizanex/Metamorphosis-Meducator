<?php
/**
 * Extended profile view
 *
 * @package Flexprofile
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Kevin Jardine <kevin@radagast.biz>
 * @copyright Radagast Solutions 2008
 * @link http://radagast.biz/
 */

// Load flexgroupprofile model
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/models/model.php");

if (!$vars['embedded']) {
	echo '<div class="contentWrapper">';
}
$group = $vars['entity'];
$form = flexgroupprofile_get_profile_form($group,$group->group_profile_category);
if ($form) {
	$tab_data = form_get_data_for_profile_tabbed_display($form, $group);

	echo elgg_view('form/forms/display_form_content',array('tab_data'=>$tab_data,'description'=>'','preview'=>0,'form'=>$form,'form_data_id'=>0));
} else {
	echo elgg_echo('form:error_no_group_profile_form');
}

if (!$vars['embedded']) {
	echo '</div>';
}
