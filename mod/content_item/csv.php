<?php
    // Load Elgg engine
    include_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
 
    // make sure only logged in users can see this page	
 //   gatekeeper();
 
    // set the title
    $title = "CREATE AND DISPLAY CSV OF USERand their items";
 
    // start building the main column of the page
    $area2 = elgg_view_title($title);
  
    // Add the form to this section
	
	global $CONFIG;

	
	
	
	/* CODE GIA NA VAZOUME METADATA APO TON PINAKA SE OLA TA RESOURCES MONO 

		$query1= "SELECT guid,creator_guid FROM {$CONFIG->dbprefix}_content_item_discrimination WHERE is_content_item = \"1\"";
				$result1 = mysql_query($query1);
				
				while($row = mysql_fetch_array($result1, MYSQL_ASSOC))
				{
						$nikolas1=$row['guid'];
						$nikolas2=$row['creator_guid'];
						$ent=get_entity($nikolas1);
						if ($ent) {
			//				$ent->creatorg=$nikolas2;
						echo $ent->name;
						$ent2=get_entity($nikolas2);
						echo " was created by ".$ent2->name."<br />";
						
						
						
						}
	
	}

	
*/



	//function gia ola ta items pou ekane create o 939
	//$members=get_entities_from_metadata('creatorg', 939, 'user', '', '',10000);
	//function gia ola ta resources mono
	//	$members=get_entities_from_metadata('issimpleuser', 'no', 'user', '', '',10000);


//	$members=list_entities_from_metadata('issimpleuser', 'no', 'user', '', 0,10,false,false,true);


//	$area2 .= $members."<br>";
//	$area2 .= "<div class='contentWrapper'>";
	foreach ($members as $member)
	$area2 .= $member->name."<br>";
//	$area2 .= "</div>";

	
	//$area2 .= "<iframe width=\"800\" height=\"600\" frameborder=\"0\" scrolling=\"no\" marginheight=\"0\" marginwidth=\"0\" src=\"http://maps.google.com/?ie=UTF8&amp;ll=52.855864,19.863281&amp;spn=32.110356,70.224609&amp;z=4&amp;output=embed\"></iframe><br /><small><a href=\"http://maps.google.com/?ie=UTF8&amp;ll=52.855864,19.863281&amp;spn=32.110356,70.224609&amp;z=4&amp;source=embed\" style=\"color:#0000FF;text-align:left\">View Larger Map</a></small>\n";
/*   GOOGLE MAPS 
	
		//include our class
	require_once(dirname(__FILE__) . '/class.googleHelper.php'); 
	
	//your Google Maps API key (you can get one for free from http://code.google.com/apis/maps/signup.html)
	$apiKey = 'ABQIAAAAQNUInQTwgn5VDDyl8bOByxSM5HRgLZx_FTvWflhOcXW1FpA9rhSc6ancYc0NEjrJi8yIkmEMFXzv5Q';
	
	//init our object
	$obj = new googleHelper($apiKey);
	
	//get coordinates and print the debug info
	$user=get_entity(2);
	$address=$user->Location.",".$user->city;
	//print '<pre>';
	//print_r($obj->getCoordinates($address ));
	$nik= $obj->getCoordinates($address);
	echo $nik[lat]."<br />";
	echo $nik[long];
*/



		/*
	
	
	$ourFileName = "users.csv";		
	$ourFileHandle = fopen($ourFileName, 'w') or die("can't open file");

	$query= "SELECT guid,creator_guid FROM {$CONFIG->dbprefix}_content_item_discrimination ORDER BY creator_guid";
	$result = mysql_query($query);
	$count=0;
	{
		while($row = mysql_fetch_array($result, MYSQL_ASSOC))
		{
			$item=$row['guid'];
			$person=$row['creator_guid'];
			if ($i=get_entity($item))
			{
			$count++;		
			$p=get_entity($person);
			$area2 .=$count.".";
			$area2 .=$p->name;
			$area2 .=" has created guid:";
			$area2 .= $item;
			$area2 .= "<br />";

			fwrite($ourFileHandle,$p->name."|".$item);
			fwrite($ourFileHandle,"\r\n");
			}
		}
	}
	$area2 .= "Total Resources=".$count; 
	$area2 .="<br />";
	$area2 .="<br />";
	

	fwrite($ourFileHandle,"<");

	
    // layout the page


 
 	
    // draw the page

	fclose($ourFileHandle);
*/
	 $body =elgg_view_layout('one_column', $area2);
	     page_draw($title, $body);
?>