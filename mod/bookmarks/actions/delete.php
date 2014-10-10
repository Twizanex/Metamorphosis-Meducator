<?php

	/**
	 * Elgg bookmarks delete action
	 * 
	 * @package ElggBookmarks
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

		$guid = get_input('bookmark_guid',0);
		if ($entity = get_entity($guid)) {
			
			if ($entity->canEdit()) {
//ADDED BY GIACOMO FAZIO/////////////////////////////////////////////////////////////////////////////////////////////////
				//if a bookmark to a resource has been removed, we have to save its GUID in the file of the changes
				require_once($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/config.php");
				$query = "SELECT distinct e.* from elggentities e join elggusers_entity u on e.guid = u.guid JOIN (SELECT subm1.*, s1.string FROM elggmetadata subm1 JOIN elggmetastrings s1 ON subm1.value_id = s1.id) AS m1 ON e.guid = m1.entity_guid where ((m1.name_id='440' AND m1.string IN ('356'))) and ( (1 = 1) and e.enabled='yes') and ( (1 = 1) and m1.enabled='yes') order by e.time_created desc";
				$entities = get_data($query, "entity_row_to_elggstar");   //I've just extracted all the resources
				foreach($entities as $ent) {
					if($ent->getURL()==$entity->address) {   //if it is a bookmark to the current resource
						$changes=unserialize(file_get_contents($IOdir."changes"));   //I load the list of the changes since last classification; if the file doesn't exist, it doesn't matter
						if(!in_array($ent->getGUID(),$changes["new"]) && !in_array($ent->getGUID(),$changes["edited"]["tags"])) {   //if it is in the list of the new resources (created after last classification), don't put it in the list of the edited resources, neither if it is already present in the list of the edited resources
							$changes["edited"]["tags"][]=$ent->getGUID();
							file_put_contents($IOdir."changes",serialize($changes));
						}
						break;
					}
				}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

				if ($entity->delete()) {
					
					system_message(elgg_echo("bookmarks:delete:success"));
					forward("pg/bookmarks/");					
					
				}
				
			}
			
		}
		
		register_error(elgg_echo("bookmarks:delete:failed"));
		forward("pg/bookmarks/");

?>