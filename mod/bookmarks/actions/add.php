<?php

	/**
	 * Elgg bookmarks add/save action
	 * 
	 * @package ElggBookmarks
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */
	
	gatekeeper();
	action_gatekeeper();

		$title = get_input('title');
		$guid = get_input('bookmark_guid',0);
		$description = get_input('description');
		$address = get_input('address');
		$access = get_input('access');
		$shares = get_input('shares',array());
		
		$tags = get_input('tags');
		$tagarray = string_to_tag_array($tags);
//ADDED BY GIACOMO FAZIO/////////////////////////////////////////////////////////////////////////////////////////////////
		//if a resource has been bookmarked, we have to save its GUID in the file of the changes
		if(!isset($_SESSION["original_tags"]) || (isset($_SESSION["original_tags"]) && $_SESSION["original_tags"]!=$tags)) {   //do the rest only if it is a new bookmark or the editing of an old bookmark with changes in the tags
			require_once($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/config.php");
			$query = "SELECT distinct e.* from elggentities e join elggusers_entity u on e.guid = u.guid JOIN (SELECT subm1.*, s1.string FROM elggmetadata subm1 JOIN elggmetastrings s1 ON subm1.value_id = s1.id) AS m1 ON e.guid = m1.entity_guid where ((m1.name_id='440' AND m1.string IN ('356'))) and ( (1 = 1) and e.enabled='yes') and ( (1 = 1) and m1.enabled='yes') order by e.time_created desc";
			$entities = get_data($query, "entity_row_to_elggstar");   //I've just extracted all the resources
			foreach($entities as $entity) {
				if($entity->getURL()==$address) {  //if it is a bookmark to the current resource
					$changes=unserialize(file_get_contents($IOdir."changes"));   //I load the list of the changes since last classification; if the file doesn't exist, it doesn't matter
					if(!in_array($entity->getGUID(),$changes["new"]) && !in_array($entity->getGUID(),$changes["edited"]["tags"])) {   //if the resource is in the list of the new resources (created after last classification), don't put it in the list of the edited resources, neither if it is already present in the list of the edited resources
						$changes["edited"]["tags"][]=$entity->getGUID();
						file_put_contents($IOdir."changes",serialize($changes));
					}
					break;
				}
			}
			unset($_SESSION["original_tags"]);
		}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
		if ($guid == 0) {
			
			$entity = new ElggObject;
			$entity->subtype = "bookmarks";
			$entity->owner_guid = $_SESSION['user']->getGUID();
			$entity->container_guid = (int)get_input('container_guid', $_SESSION['user']->getGUID());
			
		} else {
			
			$canedit = false;
			if ($entity = get_entity($guid)) {
				if ($entity->canEdit()) {
					$canedit = true;
				}
			}
			if (!$canedit) {
				system_message(elgg_echo('notfound'));
				forward("pg/bookmarks");
			}
			
		}
		
		$entity->title = $title;
		$entity->address = $address;
		$entity->description = $description;
		$entity->access_id = $access;
		$entity->tags = $tagarray;
		
		if ($entity->save()) {
			$entity->clearRelationships();
			$entity->shares = $shares;
		
			if (is_array($shares) && sizeof($shares) > 0) {
				foreach($shares as $share) {
					$share = (int) $share;
					add_entity_relationship($entity->getGUID(),'share',$share);
				}
			}
			system_message(elgg_echo('bookmarks:save:success'));
			//add to river
			add_to_river('river/object/bookmarks/create','create',$_SESSION['user']->guid,$entity->guid);
			forward($entity->getURL());
		} else {
			register_error(elgg_echo('bookmarks:save:failed'));
			forward("pg/bookmarks");
		}

?>