<?php

	/**
	 * Elgg profile icon hover over: actions
	 * 
	 * @package ElggProfile
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 * 
	 * @uses $vars['entity'] The user entity. If none specified, the current user is assumed. 
	 */

	if (isloggedin()) {
		if ($_SESSION['user']->getGUID() != $vars['entity']->getGUID()) {
			
			$ts = time();
			$token = generate_action_token($ts);


///////////////////////////////////////////meducator
		if ($user1 = page_owner()) {
			$selected_item = $user1;
		}
		
		//echo "Selected"+$selected_item;
		
		$query = "SELECT * FROM {$CONFIG->dbprefix}_content_item_discrimination WHERE guid = \"".$selected_item."\" and is_content_item = \"1\"";
		 
		$result = get_data($query);
		
		if($result != null)
		{
		echo "<p><a class='example6' href=\"".$vars['url']."mod/content_item/eval.php?id=".$vars['entity']->guid."\">"."<b>View current rating and/or rate this Resource</b>"."</a></p>";
		echo "<p class=\"user_menu_addfriend\"><a href=\"{$CONFIG->wwwroot}mod/content_item/show_history_content_item.php?content_item=".$selected_item."\">" . elgg_echo("content_item_show_repurposed_history") . "</a></p>";
		echo "<a name=\"fb_share\" type=\"button_count\" href=\"http://www.facebook.com/sharer.php\">Share</a><script src=\"http://static.ak.fbcdn.net/connect.php/js/FB.Share\" type=\"text/javascript\"></script>";
		}
		
		else
		{
/////////////////////////////////////////////
			
			if ($vars['entity']->isFriend()) {
				echo "<p class=\"user_menu_removefriend\"><a href=\"{$vars['url']}action/friends/remove?friend={$vars['entity']->getGUID()}&__elgg_token=$token&__elgg_ts=$ts\">" . elgg_echo("friend:remove") . "</a></p>";
			} else {
					

			echo "<p class=\"user_menu_addfriend\"><a href=\"{$vars['url']}action/friends/add?friend={$vars['entity']->getGUID()}&__elgg_token=$token&__elgg_ts=$ts\">" . elgg_echo("friend:add") . "</a></p>";

			}
///////////////////////////////////////////meducator
		} 
////////////////////////////////////////////////////		
			
		}
	}

?>