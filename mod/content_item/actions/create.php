<?php

	/**
	 * Create Content Item action
	 * 
	 * @package 
	 * @author 
	 * @copyright 
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @link 
	 */

	//gatekeeper();
	//action_gatekeeper();
	if (isloggedin()) {
		echo elgg_view("welcome/logged_in");		
	} else {
	 	echo elgg_view("welcome/logged_out");
	}
	
	echo "<div class=\"contentWrapper\"><span class=\"contentIntro\">" . autop(elgg_echo("admin:contentItem:description")) . "</span></div>";
	
			echo elgg_view("views/default/admin/user_opt/adduser_Content_Item");

?>