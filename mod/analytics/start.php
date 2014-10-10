<?php
	/**
	* Google Analytics startup file.
	* 
	* @package analytics
	* @author ColdTrick IT Solutions
	* @copyright ColdTrick IT Solutions 2009
	* @link http://www.coldtrick.com/
	*/

	function analytics_init()	{
		global $CONFIG;
		extend_view('page_elements/footer','analytics/footer', 499);
	}
	
	register_elgg_event_handler('init','system','analytics_init');
	
?>
