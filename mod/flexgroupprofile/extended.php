<?php

/**
 * Elgg flexprofile extended profile
 * 
 * @package FlexProfile
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Kevin Jardine <kevin@radagast.biz>
 * @copyright Radagast Solutions 2008
 * @link http://radagast.biz/
 */
 
// Load Elgg engine
require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

global $CONFIG;

// Define context
set_context('groups');

set_page_owner(get_input('group_guid',0));

$group = page_owner_entity();

$title = friendly_title($group->name);

add_submenu_item(elgg_echo('form:main_profile_link_text'),$group->getUrl(),'0extendedprofile');

$body = elgg_view('flexgroupprofile/extended',array('entity'=>$group));

$title = sprintf(elgg_echo('form:extended_profile_title'),$group->name);

page_draw($title,elgg_view_layout("two_column_left_sidebar", '', elgg_view_title($title) . $body));

?>