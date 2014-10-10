<?php

/**
 * Elgg flexfile plugin
 * 
 * @package flexfile
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Kevin Jardine <kevin@radagast.biz>
 * @copyright Radagast Solutions 2008
 * @link http://radagast.biz/
 */


// Load flexfile model
    require_once(dirname(__FILE__)."/models/model.php");
	
	register_elgg_event_handler('create','object','flexfile_add_fields');
	register_elgg_event_handler('update','object','flexfile_add_fields');	

?>