<?php
////////////script to delete maurice's stuff
	  set_time_limit(360000);
    // Load Elgg engine
    include_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
	
	admin_gatekeeper();
	
	global $CONFIG;
	
	
	$members=get_entities_from_metadata('issimpleuser', 'no', 'user', '', '',100000);
	$counter=0;
	foreach ($members as $member)
	{
		if ( (substr_count($member->name, 'jmeter') >= 1))
//		{		$counter++; echo $member->name; }
			$member->delete();
	
	}
		echo $counter;
	
	
?>	