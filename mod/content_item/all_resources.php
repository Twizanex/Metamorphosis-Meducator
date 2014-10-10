<?php
    // Load Elgg engine
    include_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
 
    // make sure only logged in users can see this page	
 //   gatekeeper();
 
    // set the title
    $title = "Display all Educational Resources";
 
    // start building the main column of the page
    $area2 = elgg_view_title($title);
  
    // Add the form to this section
	
	global $CONFIG;

		$members=list_entities_from_metadata('issimpleuser', 'no', 'user', '', 0,10,false,false,true);
		$area2 .=$members;
//	$area2 .= $members."<br>";
//	$area2 .= "<div class='contentWrapper'>";
//	foreach ($members as $member)
//	$area2 .= $member->name."<br>";
//	$area2 .= "</div>";
	
	
		 $body =elgg_view_layout('one_column', $area2);
	     page_draw($title, $body);
?>