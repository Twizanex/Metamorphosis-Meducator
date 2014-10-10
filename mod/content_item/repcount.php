<?php
    // Load Elgg engine
    include_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
 
    // make sure only logged in users can see this page	
 
    // set the title
    $title = "Create and Download the XML of a resource";
 
    // start building the main column of the page
    $area2 = elgg_view_title($title);
  
    // Add the form to this section
	$repc=0;
	global $CONFIG;
		$members=get_entities_from_metadata('issimpleuser', 'no', 'user', '', '',10000);
		foreach ($members as $member)
		{
		$friends = $member->getFriends("", $num=30, $offset = 0);
		$friendsof = $member->getFriendsof("", $num=30, $offset = 0);
			if ($friends!=FALSE)
			{	$repc++;
					echo $member->name."<br />";
				
			}
			else if ($member->meducator25)
			{
			$repc++;
					echo $member->name."<br />";
			}
		
		}
		echo $repc;
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	/*
			$query3= "SELECT distinct e.* from elggentities e join elggusers_entity u on e.guid = u.guid JOIN (SELECT subm1.*, s1.string FROM elggmetadata subm1 JOIN elggmetastrings s1 ON subm1.value_id = s1.id) AS m1 ON e.guid = m1.entity_guid where ((m1.name_id='925' AND m1.string like '%.%')) and ( (1 = 1) and e.enabled='yes') and ( (1 = 1) and m1.enabled='yes') order by e.time_created desc";
				$result3 = mysql_query($query3);
							while($row = mysql_fetch_array($result3, MYSQL_ASSOC))
				{
//						print_r($row);
						$nik=get_entity($row[guid]);
						echo $nik->name."<br />";
						$repc1++;
						
				}
		$query4="SELECT elgg_content_item_discrimination.guid FROM elgg_content_item_discrimination INNER JOIN elggentity_relationships ON elgg_content_item_discrimination.guid=elggentity_relationships.guid_two";	
			$result4 = mysql_query($query4);
										while($row = mysql_fetch_array($result4, MYSQL_ASSOC))
				{
						$nik=get_entity($row[guid]);
						echo $nik->name."<br />";
						$repc2++;
				}
				
				echo $repc1."-".$repc2;

		*/		
				
				?>