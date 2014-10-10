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

// Load form model
require_once(dirname(dirname(dirname(__FILE__))) . "/form/models/model.php");
// Load form profile model
require_once(dirname(dirname(dirname(__FILE__))) . "/form/models/profile.php");

function flexfile_get_file_form($entity,$file_category='') {
    return form_get_latest_public_profile_form(3,$file_category);
}

function flexfile_add_fields($event,$object_type,$object) {
	if ((($event == 'create') || ($event == 'update')) && ($object_type == 'object') && ($object->getSubtype() == 'file')) {
		$form = flexfile_get_file_form($object,$object->file_category);
		if ($form) {
			$data = form_get_data_from_form_submit($form->getGUID());
			foreach ($data as $key => $value) {
				$object->$key = $value;
			}
		}
	}
	
	return $object;
}

?>