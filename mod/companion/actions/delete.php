<?php
        /**
         * @package Elgg
         * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
         * @author Roger Curry, Grid Research Centre [curry@cpsc.ucalgary.ca]
         * @author Tingxi Tan, Grid Research Centre [txtan@cpsc.ucalgary.ca]
         * @link http://grc.ucalgary.ca/
         */


	gatekeeper();
	action_gatekeeper();
	$guid = (int) get_input('compost');
		
	$publication = get_entity($guid);
	if ($publication->owner_guid==$_SESSION['guid'] || issuperadminloggedin()) {
		mysql_query("DELETE FROM elggentity_relationships WHERE guid_two='$guid' AND relationship='incoll' ");
		
		
		$rowsaffected = $publication->delete();
		if ($rowsaffected > 0) {
			system_message("Collection <b>DELETED</b>");
		} else {
			register_error("Collection cannot be deleted");
		}
		
		forward("mod/companion/");
	
	}
		
?>
