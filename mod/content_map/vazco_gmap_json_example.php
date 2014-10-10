<?php
	/**
         * @package Elgg
         * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
         * @author Tingxi Tan, Grid Research Centre [txtan@cpsc.ucalgary.ca]
         * @link http://grc.ucalgary.ca/
         */

	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

	//an example to generate JSON with geolocation information
	//input from vazco_gmap plugin 
	$users = get_entities('user','',0,'',9999);
	foreach($users as $user){
		if($user->location){
		//check if location is set
			if(!is_array($user->location)){
			if(preg_match('/\((.*),(.*)\)/',$user->location,$match)){
				//check if location is input in (lat,lng) format
				$icon = get_entity_icon_url($user,'medium');
				$url = $user->getURL();
				//this is what will shown in the info bubble
				$info_html = "<div style='height:100px'><p><b>$user->name</b></p><a target='_blank' href='$url'/><img style='width:50px' src='$icon'/></a></div>";
				$batch[] = array('name'=>$user->name,'lat'=>$match[1],'lng'=>$match[2],'info'=>$info_html,'icon'=>$icon);
			}}
		}
	}
	$obj['content'] = $batch;
	echo json_encode($obj);
?>
