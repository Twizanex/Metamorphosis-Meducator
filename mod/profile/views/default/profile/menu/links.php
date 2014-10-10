<?php

	/**
	 * Elgg profile icon hover over: passive links
	 * 
	 * @package ElggProfile
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 * 
	 * @uses $vars['entity'] The user entity. If none specified, the current user is assumed. 
	 */

?>


	<?php
		if (isloggedin())
		{
	?>
		<p class="user_menu_profile">
	<?php 
//	echo ("<a href=\"".$CONFIG->wwwroot."mod/profile/edit.php"."\">"."Edit profile icon"."</a>");  
?>
		</p>
	<?php
		}
	
	?>
	<?php 
			if ($user1 = page_owner()) {
			$selected_item = $user1;
		}
		
		//echo "Selected"+$selected_item;
		
		$query = "SELECT * FROM {$CONFIG->dbprefix}_content_item_discrimination WHERE guid = \"".$selected_item."\" and is_content_item = \"1\"";
		 
		$result = get_data($query);
		
		if($result != null)
		{
		if (isloggedin())
		echo "<p class=\"user_menu_friends\"><a href=\"".$vars['url']."pg/friends/".$vars['entity']->username."/\">".elgg_echo("friends")."</a></p>";
		echo "<p class=\"user_menu_friends_of\"><a href=\"".$vars['url']."pg/friendsof/".$vars['entity']->username."/\">".elgg_echo("friends:of")."</a></p>";
			if (isloggedin())
		echo "<p class=\"bookm\"><a href=\"javascript:location.href='http://metamorphosis.med.duth.gr/mod/bookmarks/add.php?address='+encodeURIComponent(location.href)+'&title='+encodeURIComponent(document.title)\">Bookmark this Resource</a></p>";
		if (isloggedin())
		echo "<p class=\"xml\"><a href=\"".$vars['url']."mod/content_item/create_xml.php?id=".$vars['entity']->guid."\">"."Create the xml for this resource"."</a></p>";
		}
		else
		{
			echo "<p class=\"user_menu_friends\"><a href=\"".$vars['url']."pg/friends/".$vars['entity']->username."/\">"."Friends"."</a></p>";
			echo "<p class=\"user_menu_friends_of\"><a href=\"".$vars['url']."pg/friendsof/".$vars['entity']->username."/\">"."Friend of"."</a></p>";

			}
	?>