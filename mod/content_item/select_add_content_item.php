<?php
    // Load Elgg engine
    include_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
 
    // make sure only logged in users can see this page	
    gatekeeper();
	$goto_url = "select_content_items.php";
	
	if($_POST['add'] == 1)
	{
		user_add_friend($_POST['cont_it_id'],$_POST['cont_it_added_id']);
	}
	else
	{
		user_remove_friend($_POST['cont_it_id'],$_POST['cont_it_added_id']);
	}
	$a =$_POST['cont_it_id'];
	
	echo'<meta HTTP-EQUIV="refresh"  CONTENT="0;URL='.$goto_url.'?cont_it_id='.$a.'">';
?>