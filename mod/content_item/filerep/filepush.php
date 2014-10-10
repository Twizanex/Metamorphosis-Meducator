<?php

    set_time_limit(360000);

    // Load Elgg engine
    include_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/engine/start.php");
	global $CONFIG;
/*
		$items=get_entities_from_metadata('issimpleuser', 'no', 'user', '', '',10000);
	
		$i=1;
	foreach ($items as $item)
	{

//	echo $i."-".$item->guid."<br />";
	$file = file_get_contents("metadata".$item->guid.".rdf");



*/
	$file=file_get_contents("metadata838.rdf");
	$response=$file;
	$URL = "http://meducator.open.ac.uk/resourcesrestapi/rest/meducator/auth/";
 
 			$ch = curl_init($URL);
			curl_setopt($ch, CURLOPT_MUTE, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml','Authorization: Basic '.'bWV0YW1vcnBob3NpczptM3RhbTBycGgwc2lz'));
			curl_setopt($ch, CURLOPT_POSTFIELDS, "$response");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

			$output = curl_exec($ch);
			curl_close($ch);
			echo $output."<br />";
	
//		$i++;
	
//	}
//	echo "<br />".$i;
	
	
	
	
	
	
	
	
	
	
	
	
?>