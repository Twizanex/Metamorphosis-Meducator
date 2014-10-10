<?php

	/**
	 * Friends of friends.
	 * 
	 * @package friends_of_friends
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Pedro Prez
	 * @copyright 2009
	 * @link http://www.pedroprez.com.ar/
 */


	function friends_of_friends_get_list($user_guid, $order_by = "", $limit = 10, $offset = 0, $count = false, $site_guid = 0)
	{
		global $CONFIG;
		
		$user_guid = (int)$user_guid;
		
		$friends = get_entities_from_relationship('friend',$user_guid,false,'user','',0,$limit);
		
		$friends_aux = array();
		if(is_array($friends))
			foreach($friends as $friend)
				$friends_aux[] = $friend->getGUID();

		$list_friends = 0;		
		if(!empty($friends_aux))
			$list_friends = implode(',',$friends_aux);
			
		if ($order_by == "") $order_by = "time_created desc";
		$order_by = sanitise_string($order_by);
		$limit = (int)$limit;
		$offset = (int)$offset;
		$site_guid = (int) $site_guid;
		if ($site_guid == 0)
			$site_guid = $CONFIG->site_guid;
			
		$where = array();
		$where[] = "r.relationship='friend'";
		
		if ($user_guid)
			$where[] = "r.guid_one IN ($list_friends)";
		
		if ($site_guid > 0)
			$where[] = "e.site_guid = {$site_guid}";
		
		// Select what we're joining based on the options
		$joinon = "e.guid = r.guid_two";	
			
		if ($count) {
			$query = "SELECT count(distinct e.guid) as total ";
		} else {
			$query = "SELECT distinct e.* ";
		}
		$query .= " from {$CONFIG->dbprefix}entity_relationships r JOIN {$CONFIG->dbprefix}entities e on $joinon where ";
		foreach ($where as $w)
			$query .= " $w and ";
		$query .= get_access_sql_suffix("e"); // Add access controls
		
		if (!$count) {
			$query .= " order by $order_by limit $offset, $limit"; // Add order and limit
			$data = get_data($query, "entity_row_to_elggstar");
			if ($data)
				foreach ($data as $id => $row)
					if($row->getGUID() == $user_guid)
						unset($data[$id]);
			return $data;
		} else {
			if ($count = get_data_row($query)) {
				return $count->total;
			}
		}
		return false;
	}
	function friends_of_friends_list_entities($user_guid, $limit = 10, $fullview = true, $viewtypetoggle = false, $pagination = true) {
		
		$limit = (int) $limit;
		$offset = (int) get_input('offset');
		$count = friends_of_friends_get_list($user_guid, "", $limit, $offset, true);
		$entities = friends_of_friends_get_list($user_guid);
		
		return elgg_view_entity_list($entities, $count, $offset, $limit, $fullview, $viewtypetoggle);
		
	}
	
	
?>