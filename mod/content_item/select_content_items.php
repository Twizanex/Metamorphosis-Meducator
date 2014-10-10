<?php
    // Load Elgg engine
    include_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
 
	echo '<link href="views/default/css.php" rel="stylesheet" type="text/css" />';
	//include_once(dirname(dirname(dirname(__FILE__))) . "/mod/content_item/views/default/css.php");
    // make sure only logged in users can see this page	
    gatekeeper();
 
    // set the title
    $title = "Select Parent Educational Resource";
 
    // start building the main column of the page
    $area2 = elgg_view_title($title);
	$area2 .= elgg_view("admin/user_opt/search_content_item_connect");
    // Add the form to this section
	
	global $CONFIG;
	
	if(!isset($_GET['object']))
	{
					$AR2 = "";
			
					$selected_item = $_POST['cont_it_id'];
					if ($selected_item == null)
					{
						$selected_item = $_GET['cont_it_id'];
					}

					// Get current user for now
					if ($user = page_owner()) {

						$user = page_owner_entity();
						
					} else {
						$user = $_SESSION['user'];

						if (!$user) $user = get_entity($_SESSION['id']);

						set_page_owner($user->getGUID());
					}
					
					
			//admin_gatekeeper();

			$query = "SELECT * FROM {$CONFIG->dbprefix}users_entity join {$CONFIG->dbprefix}_content_item_discrimination on {$CONFIG->dbprefix}users_entity.guid = {$CONFIG->dbprefix}_content_item_discrimination.guid"; 

				
			$result = get_data($query);

			//Page Navigation
				
				$total_users = count($result);
				$AR2 .= "  Select the Content items that your object is repurposed from";

			$AR2 .= "<div align=\"left\"><table  width=\"600\"><tr><td width=\"50\">&nbsp;</td><td  width=\"550\">";


			for($i=0;$i<$total_users;$i++)
			{
				$AR2 .= "<div align=\"left\"><table bgcolor=\"#FFFFFF\" width=\"460\">";
				$row = $result[$i];
				//$AR2 .= "<tr>";
				$j =$i + 1;
				$AR2 .= "<td width=\"50\"> ".$j.")  </td>";
				$AR2 .= "<td width=\"200\"><a href=\"{$CONFIG->wwwroot}pg/profile/".$row->username."/edit/\">".$row->name."</a> </td>";
				
				//echo "<td>Selected Item = ".$selected_item;
				//echo " All items = ".$row->guid."</td>";
				if ($selected_item != $row->guid) {
						
						$ts = time();
						$token = generate_action_token($ts);
								//$myent = get_entity	(	$row->guid	 );
						//print_r($myent);
						
						//$AA = user_is_friend($selected_item,$row->guid);
						//$AA = count_user_friends_objects(537);
						
			//elggentity_relationships			
							$query2 = "SELECT * FROM {$CONFIG->dbprefix}entity_relationships where guid_one = \"{$selected_item}\" 
																							AND guid_two = \"{$row->guid}\"
																							AND relationship = \"friend\"
																						
																						OR {$CONFIG->dbprefix}entity_relationships.guid_one = \"{$row->guid}\" 
																							AND guid_two = \"{$selected_item}\"
																							AND relationship = \"friend\""; 
							$result2 = get_data($query2);
						
						if ($result2 != null)
						{
							
							$AR2 .= "<td width=\"10\"><form name=\"form1\" method=\"post\" action=\"select_add_content_item.php\"> 
							<input type=\"hidden\" name=\"cont_it_added_id\" value=\"{$row->guid}\"/>
							<input type=\"hidden\" name=\"cont_it_id\" value=\"{$selected_item}\"/>
							<input type=\"hidden\" name=\"add\" value=\"0\"/></td>
							<td width=\"250\"><input type=\"submit\" name=\"Select\" value=\"".elgg_echo("Remove from parent resources")."\" /></form>&nbsp;&nbsp;&nbsp;</td>";
						} else {
							$AR2 .= "<td width=\"10\"><form name=\"form1\" method=\"post\" action=\"select_add_content_item.php\">
							<input type=\"hidden\" name=\"cont_it_added_id\" value=\"{$row->guid}\"/>
							<input type=\"hidden\" name=\"cont_it_id\" value=\"{$selected_item}\"/>
							<input type=\"hidden\" name=\"add\" value=\"1\"/>&nbsp;&nbsp;</td>
							<td width=\"250\"><input type=\"submit\" name=\"Select\" value=\"".elgg_echo("Define as parent resource")."\" /></form>&nbsp;&nbsp;&nbsp;</td>";

						}
					}
				
				
				$AR2 .= "</tr>";
				$AR2 .= "</table></div><br/>";
			}

			$AR2 .= "</td></tr></table></div>";

				$area2 .= $AR2;
 }
 else{
 
 				$AR2 = "";
			
					$selected_item = $_POST['cont_it_id'];
					if ($selected_item == null)
					{
						$selected_item = $_GET['cont_it_id'];
					}

					// Get current user for now
					if ($user = page_owner()) {

						$user = page_owner_entity();
						
					} else {
						$user = $_SESSION['user'];

						if (!$user) $user = get_entity($_SESSION['id']);

						set_page_owner($user->getGUID());
					}
					
					
			//admin_gatekeeper();

			$query = "SELECT * FROM {$CONFIG->dbprefix}users_entity join {$CONFIG->dbprefix}_content_item_discrimination on {$CONFIG->dbprefix}users_entity.guid = {$CONFIG->dbprefix}_content_item_discrimination.guid 
						where {$CONFIG->dbprefix}users_entity.name LIKE \"%".$_GET['tag']."%\"
								or {$CONFIG->dbprefix}users_entity.username LIKE \"%".$_GET['tag']."%\""; 

				
			$result = get_data($query);

			//Page Navigation
				
				$total_users = count($result);
				$AR2 .= "  Select the Content items that your object is repurposed from";

			$AR2 .= "<div align=\"left\"><table  width=\"600\"><tr><td width=\"50\">&nbsp;</td><td  width=\"550\">";


			for($i=0;$i<$total_users;$i++)
			{
				$AR2 .= "<div align=\"left\"><table bgcolor=\"#FFFFFF\" width=\"460\">";
				$row = $result[$i];
				//$AR2 .= "<tr>";
				$j =$i + 1;
				$AR2 .= "<td width=\"50\"> ".$j.")  </td>";
				$AR2 .= "<td width=\"200\"><a href=\"{$CONFIG->wwwroot}pg/profile/".$row->username."/edit/\">".$row->name."</a> </td>";
				
				//echo "<td>Selected Item = ".$selected_item;
				//echo " All items = ".$row->guid."</td>";
				if ($selected_item != $row->guid) {
						
						$ts = time();
						$token = generate_action_token($ts);
								//$myent = get_entity	(	$row->guid	 );
						//print_r($myent);
						
						//$AA = user_is_friend($selected_item,$row->guid);
						//$AA = count_user_friends_objects(537);
						
			//elggentity_relationships			
							$query2 = "SELECT * FROM {$CONFIG->dbprefix}entity_relationships where guid_one = \"{$selected_item}\" 
																							AND guid_two = \"{$row->guid}\"
																							AND relationship = \"friend\"
																						
																						OR {$CONFIG->dbprefix}entity_relationships.guid_one = \"{$row->guid}\" 
																							AND guid_two = \"{$selected_item}\"
																							AND relationship = \"friend\""; 
							$result2 = get_data($query2);
						
						if ($result2 != null)
						{
							
							$AR2 .= "<td width=\"10\"><form name=\"form1\" method=\"post\" action=\"select_add_content_item.php\"> 
							<input type=\"hidden\" name=\"cont_it_added_id\" value=\"{$row->guid}\"/>
							<input type=\"hidden\" name=\"cont_it_id\" value=\"{$selected_item}\"/>
							<input type=\"hidden\" name=\"add\" value=\"0\"/></td>
							<td width=\"250\"><input type=\"submit\" name=\"Select\" value=\"".elgg_echo("content_item:remove_connection")."\" /></form>&nbsp;&nbsp;&nbsp;</td>";
						} else {
							$AR2 .= "<td width=\"10\"><form name=\"form1\" method=\"post\" action=\"select_add_content_item.php\">
							<input type=\"hidden\" name=\"cont_it_added_id\" value=\"{$row->guid}\"/>
							<input type=\"hidden\" name=\"cont_it_id\" value=\"{$selected_item}\"/>
							<input type=\"hidden\" name=\"add\" value=\"1\"/>&nbsp;&nbsp;</td>
							<td width=\"250\"><input type=\"submit\" name=\"Select\" value=\"".elgg_echo("content_item:add_connection")."\" /></form>&nbsp;&nbsp;&nbsp;</td>";

						}
					}
				
				
				$AR2 .= "</tr>";
				$AR2 .= "</table></div><br/>";
			}

			$AR2 .= "</td></tr></table></div>";

				$area2 .= $AR2;
 
 
 
 
 
 }
  // layout the page
	 $body =elgg_view_layout('one_column', $area2);
    //$body = elgg_view_layout('one_column', $area2);
 
 	
    // draw the page
    page_draw($title, $body);
	
?>