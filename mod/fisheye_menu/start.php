<?php

	/**
	 * fisheye menu
	 * 
	 * @fisheye menu
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Bubu <admin@colorpack.net>
	 * @link http://elgg.in.th/
	 */

	 
    function fisheye_menu_init() {
    	extend_view('css','fisheye_menu/css');

}

    // Make sure the
		    register_elgg_event_handler('init','system','fisheye_menu_init');

?>
