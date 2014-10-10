<?php
	/**
	 * Friends of friends.
	 * 
	 * @package friends_of_friends
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Pedro Prez
	 * @copyright 2009
	 * @link http://www.pedroprez.com.ar/
 */

	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
  gatekeeper();
	
	set_context('friendsoffriends');
	
	// Get the current page's owner
	$page_owner = page_owner_entity();
	if ($page_owner === false || is_null($page_owner)) 
	{
		$page_owner = get_loggedin_user();
		set_page_owner(get_loggedin_userid());
	}
	
  $title = elgg_view_title(elgg_echo('friendsoffriends'));
  
  // Display main admin menu
  $body = friends_of_friends_list_entities($page_owner->getGUID(),10,true);
  page_draw(elgg_echo('friendsoffriends'),elgg_view_layout("two_column_left_sidebar", '', $title . $body));
?>