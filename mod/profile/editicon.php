<?php

	/**
	 * Elgg upload new profile icon
	 * 
	 * @package ElggProfile
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */

	// Load the Elgg framework
		require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
		
	// Make sure we're logged in
		if (!isloggedin()) forward();

///////////////////////////////////////////////////////////// meducator		
		if ($user = page_owner()) {
			$selected_item = $user;
		}
		
		//echo "Selected"+$selected_item;
		
		$query = "SELECT * FROM {$CONFIG->dbprefix}_content_item_discrimination WHERE guid = \"".$selected_item."\" and is_content_item = \"1\"";
		 
		$result = get_data($query);
		
		if($result != null)
		{
			$area2 = elgg_echo("content_item_edit_icon_no_access");
		}
		else
		{
///////////////////////////////////////////////////////////////////
		
	$page_owner = page_owner_entity();
		if ($page_owner === false || is_null($page_owner)) {
			$page_owner = $_SESSION['user'];
			set_page_owner($page_owner->getGUID());
		}

	// set title
		$area2 = elgg_view_title(elgg_echo('profile:createicon:header'));
		$area2 .= elgg_view("profile/editicon", array('user' => $page_owner));
///////////////////////////////////////////////////////////// meducator
	}
//////////////////////////////////////////////////////////////	
	// Get the form and correct canvas area
		$body = elgg_view_layout("two_column_left_sidebar", '', $area2);
		
	// Draw the page
		page_draw(elgg_echo("profile:editicon"),$body);

?>