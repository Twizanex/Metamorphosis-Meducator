<?php

	/**
	 * Elgg custom index
	 * 
	 * @package ElggCustomIndex
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	// Get the Elgg engine
		require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
		
 
	
    //display the contents in our new canvas layout
	$body = elgg_view_layout('new_index',$login, $files, $newest_members, $blogs, $groups, $bookmarks);
   
    page_draw($title, $body);
		
?>