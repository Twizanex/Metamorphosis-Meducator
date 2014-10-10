<?php
	/**
         * @package Elgg
         * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
         * @author Tingxi Tan, Grid Research Centre [txtan@cpsc.ucalgary.ca]
         * @link http://grc.ucalgary.ca/
         */


require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
global $CONFIG;

//set the json url, required parameter
$jsonurl = $CONFIG->wwwroot . 'mod/content_map/json_example.php';

//include some map options, only json_url is required, others are optional
$map_options = array('json_url'=>$jsonurl,'default_zoom'=>2,'centre'=>array(50,-100),'timer'=>4000,'pan'=>true,'mapcontrol'=>true);

//get the map
$map = elgg_view('content_map/map',$map_options);
$body = "<div style='width:640px;height:480px'>$map</div>";
		
page_draw(null,$body);

?>
