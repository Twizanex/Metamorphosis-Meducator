<?php
	// Custom Topbar Link Plugin

	function customtopbarlink_init()
	{
		global $CONFIG;
		$CONFIG->customtopbarlink_config = array(
			'linktext' => true,
			'linkurl' => true
		);
	}
	
	register_elgg_event_handler('init','system','customtopbarlink_init');
        
?>
