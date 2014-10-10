<?php
	/**
	 * Profile admin context links
	 * 
	 * @package ElggProfile
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 * 
	 * @uses $vars['entity'] The user entity
	 */
			if ($user1 = page_owner()) 
		{
			$selected_item = $user1;
		}
		//echo "Selected"+$selected_item;
		$query = "SELECT * FROM {$CONFIG->dbprefix}_content_item_discrimination WHERE guid = \"".$selected_item."\" and is_content_item = \"1\"";
		 
		$result = get_data($query);
		
	 
			if (isadminloggedin()){
				if ($_SESSION['id']!=$vars['entity']->guid){
					
					$ts = time();
					$token = generate_action_token($ts);
    					if ($user1 = page_owner()) {
			$selected_item = $user1;
		}
		
		
		$query = "SELECT * FROM {$CONFIG->dbprefix}_content_item_discrimination WHERE guid = \"".$selected_item."\" and is_content_item = \"1\"";
		 
		$result = get_data($query);		

		$query2 = "SELECT creator_guid FROM {$CONFIG->dbprefix}_content_item_discrimination WHERE guid = \"".$selected_item."\" and is_content_item = \"1\"";
		$result2 = mysql_query($query2);
		while($row = mysql_fetch_array($result2, MYSQL_ASSOC))
		$nikolas=$row['creator_guid'];
?>
				
				<a href="<?php echo $vars['url']; ?>pg/settings/user/<?php echo $vars['entity']->username; ?>/"><?php if (issuperadminloggedin())   echo "Edit Details (display name etc)"; ?></a>
				
				<?php 
				if (!$vars['entity']->isBanned()) {
	//				echo elgg_view('output/confirmlink', array('text' => elgg_echo("ban"), 'href' => "{$vars['url']}action/admin/user/ban?guid={$vars['entity']->guid}&__elgg_token=$token&__elgg_ts=$ts"));
				} else {
					echo elgg_view('output/confirmlink', array('text' => elgg_echo("unban"), 'href' => "{$vars['url']}action/admin/user/unban?guid={$vars['entity']->guid}&__elgg_token=$token&__elgg_ts=$ts")); 
				}
				
	//			 delete only for superadmin? 
				if(get_plugin_setting("deleteuser", "superadmin") == "yes" || issuperadminloggedin()){
							if($result != null||issuperadminloggedin())
								if ($nikolas==$vars['user']->guid||issuperadminloggedin())
					echo elgg_view('output/confirmlink', array('text' => elgg_echo("delete"), 'href' => "{$vars['url']}action/admin/user/delete?guid={$vars['entity']->guid}&__elgg_token=$token&__elgg_ts=$ts"));
				}
				if (issuperadminloggedin()){
				echo elgg_view('output/confirmlink', array('text' => elgg_echo("resetpassword"), 'href' => "{$vars['url']}action/admin/user/resetpassword?guid={$vars['entity']->guid}&__elgg_token=$token&__elgg_ts=$ts"));
					echo elgg_view('output/confirmlink', array('text' => elgg_echo("ban"), 'href' => "{$vars['url']}action/admin/user/ban?guid={$vars['entity']->guid}&__elgg_token=$token&__elgg_ts=$ts"));
				}
				// make admin only for superadmin?
				if(get_plugin_setting("makeadmin", "superadmin") == "yes" || issuperadminloggedin()){
					if (!$vars['entity']->admin) { 
						echo elgg_view('output/confirmlink', array('text' => elgg_echo("makeadmin"), 'href' => "{$vars['url']}action/admin/user/makeadmin?guid={$vars['entity']->guid}&__elgg_token=$token&__elgg_ts=$ts"));
					} else {
						echo elgg_view('output/confirmlink', array('text' => elgg_echo("removeadmin"), 'href' => "{$vars['url']}action/admin/user/removeadmin?guid={$vars['entity']->guid}&__elgg_token=$token&__elgg_ts=$ts"));
					}
				}
				
				// make superadmin only for superadmin!
				if(issuperadminloggedin()){
					if (!$vars['entity']->superadmin) { 
						echo elgg_view('output/confirmlink', array('text' => elgg_echo("superadmin:profile:adminmenu:makesuperadmin"), 'href' => "{$vars['url']}action/superadmin/makesuperadmin?guid={$vars['entity']->guid}&__elgg_token=$token&__elgg_ts=$ts"));
					} else {
						echo elgg_view('output/confirmlink', array('text' => elgg_echo("superadmin:profile:adminmenu:removesuperadmin"), 'href' => "{$vars['url']}action/superadmin/removesuperadmin?guid={$vars['entity']->guid}&__elgg_token=$token&__elgg_ts=$ts"));
					}
				}
			}
		}
?>