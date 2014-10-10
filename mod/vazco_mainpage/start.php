<?php

	/**
	 * Elgg vazco_mainpage plugin
	 * 
	 * @author Michal Zacher [michal.zacher@gmail.com]
	 */
    require_once(dirname(__FILE__)."/models/model.php");
    
    function vazco_mainpage_custom_index() {
		if (!@include_once(dirname(__FILE__) . "/index.php")) return false;
		return true;
	}
		
	function vazco_mainpage_init() {
		global $CONFIG;

		register_page_handler ( 'vazco_mainpage', 'vazco_mainpage_page_handler' );
		if (get_context() =='admin')
			add_submenu_item ( elgg_echo ( 'vazco_mainpage:menu' ), $CONFIG->wwwroot . 'pg/vazco_mainpage/' );

		extend_view("canvas/layouts/vazco_index","vazco_mainpage/widget_list",1);
		extend_view("vazco_mainpage/mainpage_editor","vazco_mainpage/widget_list",1);
		extend_view("css","vazco_mainpage/css",800);
				
		//Replace the default index page
		register_plugin_hook('index','system','vazco_mainpage_custom_index');
		register_action ( "vazco_mainpage/update", false, $CONFIG->pluginspath . "vazco_mainpage/actions/update.php" );
		register_action ( "vazco_mainpage/upload", false, $CONFIG->pluginspath . "vazco_mainpage/actions/upload.php" );		
	}
	function vazco_mainpage_page_handler($page) {
		@include (dirname ( __FILE__ ) . "/editor.php");
	}
	
	register_elgg_event_handler('init','system','vazco_mainpage_init');
	
?>