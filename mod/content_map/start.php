<?php
	/**
	 * @package Elgg
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Tingxi Tan, Grid Research Centre [txtan@cpsc.ucalgary.ca]
	 * @link http://grc.ucalgary.ca/
	 */

	function content_map_init() {
		extend_view('metatags','content_map/metatags');	
		//this line can be removed if you are not using google-map plugin
		extend_view('metatags','google-map/metatags');
		extend_view('css','content_map/css');
	}

	register_elgg_event_handler('init','system','content_map_init');

?>
