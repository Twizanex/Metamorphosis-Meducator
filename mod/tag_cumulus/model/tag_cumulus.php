<?php
	
	/**
	 * Tag Cumulus
	 * 
	 * @package tag_cumulus
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Pedro Prez
	 * @copyright 2009
	 * @link http://www.pedroprez.com.ar/
 	*/

	function display_tag_cumulus($threshold = 1, $limit = 10, $metadata_name = "", $entity_type = "object", $entity_subtype = "", $owner_guid = "", $site_guid = -1) {
		
		return elgg_view("output/tagcumulus",array('value' => get_tags($threshold, $limit, $metadata_name, $entity_type, $entity_subtype, $owner_guid, $site_guid),'object' => $entity_type, 'subtype' => $entity_subtype));
		
	}
?>