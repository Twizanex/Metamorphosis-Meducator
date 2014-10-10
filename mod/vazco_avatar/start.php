<?php

	/**
	 * Elgg vazco_mainpage plugin
	 * 
	 * @author Michal Zacher [michal.zacher@gmail.com]
	 */
    require_once(dirname(__FILE__)."/models/public.php");
    
    function vazco_avatar_init() {
		global $CONFIG;
		define('VAZCO_AVATAR_PATH','vazco_avatars');
		//set admin user ID to 2 - the default admin. TODO: do it cleaner. 
		// Since we use ElggFile here, the ID has to belong to the user that is in the system all the time. 
		define('VAZCO_AVATAR_ADMIN',2);
		
		extend_view('profile/editicon','vazco_avatar/select',601);
		extend_view('css','vazco_avatar/css',601);
		
		register_action("vazco_avatar/upload",false,$CONFIG->pluginspath . "vazco_avatar/actions/upload.php");
		register_action("vazco_avatar/select",false,$CONFIG->pluginspath . "vazco_avatar/actions/select.php");
		register_action("vazco_avatar/delete",false,$CONFIG->pluginspath . "vazco_avatar/actions/delete.php");

		register_plugin_hook('action', 'profile/cropicon', 'vazco_avatar_cropicon',600);
		
		if (isadminloggedin() && get_context() == 'admin' || get_context() == 'vazco_avatar')
			add_submenu_item ( elgg_echo ( 'vazco_avatar:menu' ), $CONFIG->wwwroot . 'pg/vazco_avatar/edit' );
		if (isadminloggedin() && get_context() == 'vazco_avatar')
			add_submenu_item ( elgg_echo ( 'avatars:upload' ), $CONFIG->wwwroot . 'pg/vazco_avatar/upload' );

		if (isloggedin()){
			//update current user's avatar for topbar and edit icon page
			setUserIcon($_SESSION['user'], 'topbar');
			setUserIcon($_SESSION['user'], 'medium');
		}
		
		
	}
		
	function vazco_avatar_cropicon($event, $object_type, $object){
		$metadata = get_metadata_byname(get_loggedin_userid(),'avatar');
		delete_metadata($metadata->id);
	}
	function vazco_avatar_page_handler($page) {
		
		global $CONFIG;
		
		if (isset($page[0])) 
		{
			switch($page[0]) 
			{
				case "upload":  //view list of albums owned by container
					include(dirname(__FILE__) . "/upload.php");
					break;
				case "edit":  //view list of albums owned by container
					include(dirname(__FILE__) . "/edit.php");
					break;
				case "select":  //view list of albums owned by container
					include(dirname(__FILE__) . "/select.php");
					break;
			}
		}
	}
	register_page_handler('vazco_avatar','vazco_avatar_page_handler');
	
	register_elgg_event_handler('init','system','vazco_avatar_init');
	
?>