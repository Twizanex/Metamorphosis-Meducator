<?php

	/**
	 * Elgg ajax_new_mail_notificator plugin
	 * 
	 * @package
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Matthias Sutter email@matthias-sutter.de
	 * @copyright CubeYoo.de
	 * @link http://cubeyoo.de
	 */

		
		function ajax_new_mail_notificator_init() {
		//** VIEWS
		extend_view('page_elements/header_contents','ajax_new_mail_notificator/ajax_new_mail_notificator');

									


	
		}
		register_elgg_event_handler('init','system','ajax_new_mail_notificator_init');
				
		
?>