<?php
	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

	global $CONFIG;
	
	$title = elgg_echo("Example");
	
	$body = elgg_view_title($title);
	
	$body .= '<div id="tag_cumulus_container">';
	$body .= elgg_view_title('Tags');
   	$body .= display_tag_cumulus(0,50,'tags','object','','','');
   	$body .= '</div>';
    
    $body = elgg_view_layout('one_column', $body);
	
	// Finally draw the page
	page_draw($title, $body);
?>