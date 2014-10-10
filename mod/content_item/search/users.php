<?php

	/**
	 * Generic search viewer
	 * Given a GUID, this page will try and display any entity
	 * 
	 * @package Elgg
	 * @subpackage Core

	 * @author Curverider Ltd

	 * @link http://elgg.org/
	 */

	// Load Elgg engine
	
	
		require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/engine/start.php");
	
	// Set context
		set_context('search');
		
	// Get input
		$tag = stripslashes(get_input('tag'));
		
		if (!empty($tag)) {
			$title = sprintf(elgg_echo('content_item:content_search_title'),$tag);
			$body = "";
			$body .= elgg_view_title($title); // elgg_view_title(sprintf(elgg_echo('searchtitle'),$tag));
			$body .= elgg_view('user/search/startblurb',array('tag' => $tag));
			
			

			$offset = (int) get_input('offset');
			$limit = 50;
			$count = (int) search_for_user($tag, 50, 0, '', true);
			$entities = search_for_user($tag, $limit, $offset);

			
			$body .= elgg_view_entity_list($entities, $count, $offset, $limit, $fullview, false);

			
			/// The above code instead of the line below
			//$body .= list_user_search($tag);
			
			
			
		} else {
			$title = elgg_echo('item:user');
			$body .= elgg_view_title($title);
			$body .= list_entities('user');
		}
		
		$body = elgg_view_layout('one_column', $body);
		page_draw($title,$body);

?>