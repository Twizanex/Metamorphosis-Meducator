<?php

    /**
	 * Elgg Friends
	 * Friend widget options
	 * 
	 * @package ElggFriends
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */
	/*
	$res_count=0;
    //the page owner
	$owner = get_user($vars['entity']->owner_guid);

    //the number of files to display
	$num = (int) $vars['entity']->num_display;
	if (!$num)
		$num = 8;
		
	//get the correct size
	$size = (int) $vars['entity']->icon_size;
	if (!$size || $size == 1){
		$size_value = "small";
	}else{
    	$size_value = "tiny";
	}
		
    // Get the users friends
	$friends = $owner->getFriends("", $num, $offset = 0);
	$friendso= $owner->getFriendsOf("", $num, $offset = 0);
    					if ($user1 = page_owner()) 

						{
			$selected_item = $user1;
		}
		
		//echo "Selected"+$selected_item;
		
		$query = "SELECT * FROM {$CONFIG->dbprefix}_content_item_discrimination WHERE guid = \"".$selected_item."\" and is_content_item = \"1\"";
		 
		$result = get_data($query);
		
////////////CREATOR

				if($result != null)
		{
				echo "<b>Created By:</b><br />";
				$query2 = "SELECT creator_guid FROM {$CONFIG->dbprefix}_content_item_discrimination WHERE guid = \"".$selected_item."\" and is_content_item = \"1\"";
				$result2 = mysql_query($query2);
				while($row = mysql_fetch_array($result2, MYSQL_ASSOC))
					$nikolas=$row['creator_guid'];
					echo "<div id=\"widget_friends_list\">";
			echo "<div class=\"widget_friends_singlefriend\" >";
			echo elgg_view("profile/icon",array('entity' => get_user($nikolas), 'size' => $size_value));
			echo "</div>";
		
		echo "</div>";
			
		
		}
			else
		{
			$query3= "SELECT guid FROM {$CONFIG->dbprefix}_content_item_discrimination WHERE creator_guid = \"".$selected_item."\" and is_content_item = \"1\"";
			$result3 = mysql_query($query3);
			if($result3 != null)
			{

			

				while($row = mysql_fetch_array($result3, MYSQL_ASSOC))
				{
						$nikolas3=$row['guid'];
						if (get_entity($nikolas3))
						$res_count++;
				}
				if   ($res_count==0)
					echo "<b>Hasn't created any resources </b><br />";
				else if  ($res_count!=1 and $res_count!=0 )
						echo "<b>Has Created ".$res_count." resources: </b><br />";
				else
						echo "<b>Has Created ".$res_count." resource: </b><br />";
							echo "<div id=\"widget_friends_list\">";
			$query4= "SELECT guid FROM {$CONFIG->dbprefix}_content_item_discrimination WHERE creator_guid = \"".$selected_item."\" and is_content_item = \"1\"";
			$result4 = mysql_query($query4);						
												echo "<div class=\"contentWrapper\" >";
				while($row = mysql_fetch_array($result4, MYSQL_ASSOC))
				{	$nikolas4=$row['guid'];
							if (get_entity($nikolas4)){



							$nikob=get_user($nikolas4);
							$objur=$nikob->getURL();
							echo "<a href=\"".$objur."\">".$nikob->name."</a>";
							echo "<br /><br />";
							 }
				}
echo "</div>";
				echo "</div>";
			
			
			
			
			}
		
		
		
		}		
////////////FRIENDS OR REPURPOSED FROM
	if (is_array($friends) && sizeof($friends) > 0) {
		if($result != null)
		{
			echo "<b>Repurposed from:</b><br />";
		}
		else
		{
			echo "<b>Friends:</b>";

			}	
		echo "<div id=\"widget_friends_list\">";

		foreach($friends as $friend) {
			echo "<div class=\"widget_friends_singlefriend\" >";
			echo elgg_view("profile/icon",array('entity' => get_user($friend->guid), 'size' => $size_value));
			echo "</div>";
		}

		echo "</div>";
			
    }
	////////////FRIEND OF OR REPURPOSED TO

		if (is_array($friendso) && sizeof($friendso) > 0) {
			if($result != null)
		{
			echo "<b>Repurposed to:</b><br />";
		}
		else
		{
			echo "<b>Friend of:</b>";

			}	
		echo "<div id=\"widget_friends_list\">";
		
		foreach($friendso as $friendoa) {
			echo "<div class=\"widget_friends_singlefriend\" >";
			echo elgg_view("profile/icon",array('entity' => get_user($friendoa->guid), 'size' => $size_value));
			echo "</div>";
		}

		echo "</div>";
		

   }
				
*/
echo "FKA BOOKMARK WIDGET";
		
?>