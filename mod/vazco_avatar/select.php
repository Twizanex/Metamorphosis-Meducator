<?php 
	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

	$title = elgg_echo('avatars:select');
	$body = elgg_view('vazco_avatar/select');
	    
	page_draw($title,elgg_view_layout("two_column_left_sidebar", '', elgg_view_title($title) . $body));	
?>