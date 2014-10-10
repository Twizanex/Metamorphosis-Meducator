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

	require_once(dirname(__FILE__) . '/functions_friends_to_friends.php');
	function friends_of_friends_init()
	{
		global $CONFIG;
		
		// Register a page handler, so we can have nice URLs
		register_page_handler('friendsoffriends','friends_of_friends_page_handler');
		
		// Extend CSS
				extend_view('css','friends_of_friends/css');
				
		//Read Settings
		$CONFIG->mod->friends_of_friends->config->hidefriendsof = get_plugin_setting('hidefriendsof', 'friends_of_friends');
		
		
		register_action("friends_of_friends/get_text",false,$CONFIG->pluginspath . "friends_of_friends/actions/get_text.php",true);
		register_action("friends_of_friends/translate",false,$CONFIG->pluginspath . "friends_of_friends/actions/translate.php",true);
	}
	
	function friends_of_friends_pagesetup()
	{
		global $CONFIG;
		
		if (get_context() == "friends" || 
				get_context() == "friendsof" ||
				get_context() == "collections") 
		{
				add_submenu_item(elgg_echo('friendsoffriends'),$CONFIG->wwwroot."pg/friendsoffriends/" . page_owner_entity()->username);
		}
		
		if (get_context() == "friendsoffriends")
		{
			add_submenu_item(elgg_echo('friends'),$CONFIG->wwwroot."pg/friends/" . page_owner_entity()->username);
			add_submenu_item(elgg_echo('friends:of'),$CONFIG->wwwroot."pg/friendsof/" . page_owner_entity()->username);
			add_submenu_item(elgg_echo('friendsoffriends'),$CONFIG->wwwroot."pg/friendsoffriends/" . page_owner_entity()->username);
		}
		
		//try delete menu item friendsof
		if($CONFIG->mod->friends_of_friends->config->hidefriendsof)
		{
			if(isset($CONFIG->submenu['a']))
				foreach ($CONFIG->submenu['a'] as $key => $item)
				{
					if(preg_match('/friendsof\//',$item->value))
						unset($CONFIG->submenu['a'][$key]);
				}
		}
	}
	
	function friends_of_friends_page_handler($page) 
	{
		global $CONFIG;
		
		if (isset($page[0]) && !empty($page[0]))
    	set_input('username',$page[0]);
    
    include($CONFIG->pluginspath . "friends_of_friends/index.php");  
	}
	
	register_elgg_event_handler('init','system','friends_of_friends_init');
	register_elgg_event_handler('pagesetup','system','friends_of_friends_pagesetup');
?>