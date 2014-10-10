<?php

	/**
	 * Elgg profile editor
	 * 
	 * @package ElggProfile
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */

	// Get the Elgg engine
		require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");	

	// If we're not logged on, forward the user elsewhere
		if (!isloggedin()) forward();
		
		


$selected_item;
///		
	// Get current user for now
		if ($user1 = page_owner()) {
			$selected_item = $user1;
			$user = page_owner_entity();
			
		} else {
			$user = $_SESSION['user'];
			if (!$user) $user = get_entity($_SESSION['id']);
			set_page_owner($user->getGUID());
		}
		
	// Get form, if we're allowed to edit
	//If user allowed to edit
	//  OR
	//If the content item belongs to the user
	
	$query = "SELECT * FROM {$CONFIG->dbprefix}users_entity join {$CONFIG->dbprefix}_content_item_discrimination on {$CONFIG->dbprefix}users_entity.guid = {$CONFIG->dbprefix}_content_item_discrimination.guid and {$CONFIG->dbprefix}_content_item_discrimination.creator_guid = \"".$_SESSION['id']."\""; 

	$result = get_data($query);
	//echo $query;
	
	$flag =false;
	$total_users = count($result);
	for($i=0;$i<$total_users;$i++)
	{
		$row = $result[$i];
				
		if($row->guid == $selected_item)
		{
			$flag = true;
		}
	}
	
		if ($user->canEdit() ) {
                    //$address = "http://meducator.open.ac.uk/resourcesrestapi/rest/meducator/eidsearch?id=" . $user->guid;
                    $address = $CONFIG->API_URL . "eidsearch?id=" . $user->guid;
                    $rdf_info = connectToSesame($address);
                    
                    require_once(dirname(dirname(__FILE__))."/mmsearch/custom/MeducatorParser.php");
                    $medParser = new MeducatorParser($rdf_info, true, true);
                    if(count($medParser->results) > 0)
                      foreach($medParser->results as $key => $value)
                      {
                        $resourceSesameID = $key;
                        $resourceData = $value;
                      }
                    else {
                      $resourceData = array();
                      $resourceSesameID = "";
                    }

                    $area2 = elgg_view_title(elgg_echo('profile:edit'));
                    $area2 .= elgg_view("profile/edit",array('entity' => $user, 'sesame_id' => $resourceSesameID, 'data' => $resourceData));
			 
		} else if($flag){
		
			$area2 = elgg_view_title(elgg_echo('profile:edit'));
			$area2 .= elgg_view("profile/edit",array('entity' => $user));
		
		}else {
			
			$area2 = elgg_echo("profile:noaccess");
			
		}
		
		$area1 = "";
		
    // get the required canvas area
        $body = elgg_view_layout("one_column",  $area2);
		
	// Draw the page
		page_draw(elgg_echo("profile:edit"),$body);

?>