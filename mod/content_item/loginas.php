<?php
    // Load Elgg engine
    include_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
 
    // make sure only logged in users can see this page	
    gatekeeper();
 
    // set the title
    $title = "Assume the Identity of one of your resources";
 
    // start building the main column of the page
    $area2 = elgg_view_title($title);
  
    // Add the form to this section
	


	
	$nik=$_GET['id'];
	
	$entity=get_entity($nik);
		
		$query2 = "SELECT creator_guid FROM {$CONFIG->dbprefix}_content_item_discrimination WHERE guid = \"".$nik."\" and is_content_item = \"1\"";
		$result2 = mysql_query($query2);
		while($row = mysql_fetch_array($result2, MYSQL_ASSOC))
			$nikolas=$row['creator_guid'];
			$blah=get_entity($nikolas)->name;
	if(issuperadminloggedin())
	$area2 .= "ORIGINAL OWNER:".$blah."<br />";
						$query3 = "SELECT creator_guid FROM {$CONFIG->dbprefix}_content_item_discrimination WHERE guid = \"".$_SESSION['user']->guid."\" and is_content_item = \"1\"";
		$result3 = mysql_query($query3);
		while($row = mysql_fetch_array($result3, MYSQL_ASSOC))
			$nikola1=$row['creator_guid'];
			
if (($nikolas==$_SESSION['user']->guid)||issuperadminloggedin()||($nikolas==$nikola1)) 
{	
	
	if (login ($entity, FALSE))
			$area2 .= "You have succesfully logged in as:".$entity->name."<br />You can start socializing as this resource";
	else
		$area2 .= "Login failed";
	
}	
else
	$area2 .= "You don't have permission to assume the identity of this resource";
    // layout the page
	 $body =elgg_view_layout('one_column', $area2);
    //$body = elgg_view_layout('one_column', $area2);
 
 	
    // draw the page
    page_draw($title, $body);


?>