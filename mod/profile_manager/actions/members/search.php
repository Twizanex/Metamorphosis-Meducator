<?php	
	$exploratorysearch=0;  //initialization
	require_once ($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/config.php");
	require_once ($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/classes.php");
	if(file_exists($IOdir."clusters_metadata") && file_exists($IOdir."clusters_uses") && file_exists($IOdir."clusters_tags") && file_exists($IOdir."clusters_replinks")) $exploratorysearch=1;
	
	action_gatekeeper();
	
	$offset = get_input("offset", 0);
	$limit = get_input("limit", 10);
	
	$sorting = get_input("sorting", "newest");
	$user_data_partial_search_criteria = get_input("user_data_partial_search_criteria", false);
	$meta_data_array_search_criteria = get_input("meta_data_array_search_criteria", false, false); // no filtering because get_input does not support filtering of nested arrays
	$meta_data_partial_search_criteria = get_input("meta_data_partial_search_criteria", false);
	$meta_data_exact_search_criteria = get_input("meta_data_exact_search_criteria", false);
	$meta_data_between_search_criteria = get_input("meta_data_between_search_criteria", false);  
	
	$where = array();
	
	$meta_array = array();
	
	// meta_data_array contains all stuff that requires a complete (multi)hit on a metadata value
	if(!empty($meta_data_array_search_criteria)){
		foreach($meta_data_array_search_criteria as $field_name => $field_value){
			if(!empty($field_value)){
				foreach($field_value as $key => $value){
					$field_value[$key] = "'" . $value . "'";
				}
				$meta_name_id = get_metastring_id($field_name);
				$filter = implode(",", $field_value);
				$meta_array[$meta_name_id] = "IN (" . $filter . ")";
			}
		}	
	}
	
	// user partial hit
	if(!empty($user_data_partial_search_criteria)){
		foreach($user_data_partial_search_criteria as $field_name => $field_value){
			if(!empty($field_value)){
				$where[] = "u." . $field_name . " like '%" . $field_value . "%'";
			}
		}
	}
	
	// meta_data_partial_search_criteria
	if(!empty($meta_data_partial_search_criteria)){
		foreach($meta_data_partial_search_criteria as $field_name => $field_value){
			if(!empty($field_value)){
				$meta_name_id = get_metastring_id($field_name);
				$meta_array[$meta_name_id] = "like '%" . $field_value . "%'";
			}
		}
	}
	
	// meta_data_exact_search_criteria
	if(!empty($meta_data_exact_search_criteria)){
		foreach($meta_data_exact_search_criteria as $field_name => $field_value){
			if(!empty($field_value)){
				$meta_name_id = get_metastring_id($field_name);
				$meta_array[$meta_name_id] = "= '" . $field_value . "'";
			}
		}
	}
	
	// meta_data_between_search_criteria
	if(!empty($meta_data_between_search_criteria)){
		foreach($meta_data_between_search_criteria as $field_name => $field_value){
			if(!empty($field_value) && (!empty($field_value['FROM']) || !empty($field_value['TO']))) {
				$meta_name_id = get_metastring_id($field_name);
				$from = $field_value['FROM'];
				$to = $field_value['TO'];
				
				if(!empty($from) && !empty($to)){
					$record_filter = "BETWEEN " . $from . " AND " . $to; 
				} elseif(!empty($from)) {
					$record_filter = ">= " . $from;
				} else {
					$record_filter = "<= " . $to;
				}
				
				$meta_array[$meta_name_id] = $record_filter;
			}
		}
	}
	
	$mindex = 1;
	$join = "";
	$metawhere = array();
	if(count($meta_array) > 0){
		foreach($meta_array as $meta_name_id => $meta_value) { 
			$join .= " JOIN (SELECT subm{$mindex}.*, s{$mindex}.string FROM {$CONFIG->dbprefix}metadata subm{$mindex} JOIN {$CONFIG->dbprefix}metastrings s{$mindex} ON subm{$mindex}.value_id = s{$mindex}.id) AS m{$mindex} ON e.guid = m{$mindex}.entity_guid ";
			$metawhere[] = "(m{$mindex}.name_id='$meta_name_id' AND m{$mindex}.string " . $meta_value . ")";
			$mindex++;
		}
		$where[] = "(" . implode(" and ", $metawhere) . ")";
	}
	
	//sorting
	switch ($sorting ) {
	    case "alphabetic":
	    	$order = "u.name";
	        break;
	    case "popular":
	    	$select = "e.*, count(e.guid) as total"; 
	    	$join .= " JOIN {$CONFIG->dbprefix}entity_relationships r ON e.guid = r.guid_two";
			$where[] = "r.relationship='friend'";
	    	$group_by = " group by e.guid ";
			$order = "total desc";
			break;
	    case "online":
			$time = time() - 600;
			$where[] = "u.last_action >= {$time}";
			$order = "u.last_action desc";
			break;
	    default:
	    	$order = "e.time_created desc";
	    	break;
	}
	
	// build query
	$query = "from {$CONFIG->dbprefix}entities e join {$CONFIG->dbprefix}users_entity u on e.guid = u.guid {$join} where ";
	
	if(count($where) > 0){
		foreach ($where as $w){
	    	$query .= " $w and ";
		}
	} 
	$query .= get_access_sql_suffix("e"); // Add access controls

	for($mindex = 1; $mindex <= count($meta_array); $mindex++){
		$query .= ' and ' . get_access_sql_suffix("m{$mindex}"); // Add access controls
	}
	
	// execute query and retrieve entities
	$count = get_data_row("SELECT count(distinct e.guid) as total " . $query);
	$count = $count->total;
	
	if(!empty($order)){
		$order = " order by " . $order;
	}
	
	if(empty($select)){
		$select = "distinct e.*";
	}
	
	$query = "SELECT " . $select . " " . $query . " " . $group_by . $order;
	$entities = get_data($query, "entity_row_to_elggstar");
	
	foreach($entities as $entity) {
		if($entity->issimpleuser=="yes") $ent[$entity->getGUID()]="user";
		else $ent[$entity->getGUID()]="resource";
	}
	if(isset($_SESSION["ent"])) $_SESSION["ent_old"]=$_SESSION["ent"];   //it saves the previous search, in order to show the differences with the new one
	$_SESSION["ent"]=$ent;  //it saves the list of entities returned from the query in the session
	
	if(issuperadminloggedin()){   //visualize the query
		echo "<div class='contentWrapper'><h3 class='settings'>" . elgg_echo("profile_manager:members:searchresults:query") . "</h3>";
		echo $query;
		echo "</div>";
	}
	
	// present it
	echo "<div class='contentWrapper' id='main_search_results_cw'>";
	echo "<div id='main_search_results'>";
	echo "<h3 class='settings'>" . elgg_echo("profile_manager:members:searchresults:title") . "</h3>";
	echo "</div>";
	if($exploratorysearch) echo "Find additional results by clicking the checkbox of the items above you are most interested in";
	echo "</div>";
	
	echo "<script type='text/javascript'>setup_avatar_menu();</script>";
?>
	<script type="text/javascript">
		var base_url = '<?php echo $CONFIG->url ?>';
		var issuperadminloggedin;
		var exploratorysearch;
		if(<?php if(issuperadminloggedin()) echo 1; else echo 0;?>) issuperadminloggedin=1;
		else issuperadminloggedin=0;
		if(<?php if($exploratorysearch) echo 1; else echo 0;?>) exploratorysearch=1;
		else exploratorysearch=0;
	</script>
	<script src="<?php echo $CONFIG->url ?>mod/profile_manager/actions/members/jquery-1.4.2.js"></script>
	<script src="<?php echo $CONFIG->url ?>mod/profile_manager/actions/members/functions.js"></script>
	<script src="<?php echo $CONFIG->url ?>mod/profile_manager/actions/members/List.js"></script>

	<div id="wait"class="contentWrapper" style="text-align:center">
		<img src="<?php echo $CONFIG->url ?>mod/profile_manager/actions/members/wait.gif">
		<br /><br />Please wait...
	</div>
	<div id="secondary_search_results" class="contentWrapper"></div>
