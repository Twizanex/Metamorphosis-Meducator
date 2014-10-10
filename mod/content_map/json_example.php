<?php
	/**
         * @package Elgg
         * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
         * @author Tingxi Tan, Grid Research Centre [txtan@cpsc.ucalgary.ca]
         * @link http://grc.ucalgary.ca/
         */

	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");


	//write JSON any way you like as long as the output is
	//{"content":[{"name":"","lat":,"lng":,"info":"",{},{},...,{}]}
	
	$user1['name'] = 'testuser1';
	$user1['lat'] = 50;
	$user1['lng'] = -60;
	//info is in html. Do not have to encode as it is decoded properly as 
	//json on the other side
	$user1['info'] = <<< EOT
		<div style='height:120px;width:160px'>
		<h3>Tip 1</h3>
		<p style='color:red'>Format this information window using standard HTML.</p>
		</div>
EOT;
	$user2['name'] = 'testuser2';
	$user2['lat'] = 40;
	$user2['lng'] = -60;
	$user2['info'] = <<< EOT
		<div style='height:120px;width:160px;'>
		<h3>Tip 2</h3>
		<p style='color:red'>As long as you have LAT and LNG information, any content can be displayed in this map.</p>
		</div>
EOT;
	$user3['name'] = 'testuser3';
	$user3['lat'] = 30;
	$user3['lng'] = -60;
	$user3['info'] = <<< EOT
		<div style='height:120px;width:200px;'>
		<h3>Tip 3</h3>
		<p style='color:red'>Format the JSON as such:
		{"content":[{"name":"","lat":,"lng":,"info":"","icon":""}{},{},...,{}]}
		</p>
		</div>

EOT;
		
		
	$obj['content'] = array($user1,$user2, $user3);
	echo json_encode($obj);
?>
