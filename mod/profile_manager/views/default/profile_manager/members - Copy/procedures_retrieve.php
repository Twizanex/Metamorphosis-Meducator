<?php

//it creates a new snapshot of data in the folder IOdir, useful to start a new classification
function update_data_snapshot($req) {
	set_time_limit(0);
	global $CONFIG;
	include($CONFIG->path . "mod/profile_manager/actions/members/config.php");
	include($CONFIG->path . "mod/profile_manager/actions/members/classes.php");
	$query = "SELECT distinct e.* from elggentities e join elggusers_entity u on e.guid = u.guid JOIN (SELECT subm1.*, s1.string FROM elggmetadata subm1 JOIN elggmetastrings s1 ON subm1.value_id = s1.id) AS m1 ON e.guid = m1.entity_guid where ((m1.name_id='440' AND m1.string IN ('356'))) and ( (1 = 1) and e.enabled='yes') and ( (1 = 1) and m1.enabled='yes') order by e.time_created desc";
	$entities = get_data($query, "entity_row_to_elggstar");
	foreach($entities as $entity) {
		$name=$entity->getGUID();
		$lr[$name]=new LearningResource();
			
		$categorized_fields = profile_manager_get_categorized_fields($entity, true);
		//print_r("<br><br>$categorized_fields");
		$cats = $categorized_fields['categories'];
		$fields = $categorized_fields['fields'];
		//print_r($cats);
		if(!empty($fields)){
			foreach($cats as $cat_guid => $cat){
				foreach($fields[$cat_guid] as $field){
					$metadata_name = $field->metadata_name;
					// get options
					$options = $field->getOptions();
					
					
					// get type of field
					if($field->user_editable == "no"){
						$valtype = "non_editable";
					} else {
						$valtype = $field->metadata_type;	
					}
					// make title
					$title = $field->getTitle();

					// get value
					if($metadata = get_metadata_byname($entity->getGUID(), $metadata_name)) {
						//print_r($metadata);
						if (is_array($metadata)) {
							$value = '';
							foreach($metadata as $md) {
								if (!empty($value)) $value .= ', ';
								$value .= $md->value;
								$access_id = $md->access_id;
							}
						} else {
							$value = $metadata->value;
							$access_id = $metadata->access_id;
						}
					} else {
						$value = '';
						$access_id = ACCESS_DEFAULT;
					}
					//print_r("<br>$title=$value");
						
					$lr[$name]->insertMetadata($title,$value);
				}
			}
				
		}
		$lr[$name]->setName($entity->get('name'));
		$lr[$name]->setUsername($entity->get('username'));	
	}
				
	foreach($entities as $entity) {
		$name=$entity->getGUID();
		$lr[$name]->insertReplinks($entity,$lr);
	}
	file_put_contents($IOdir.'lr',serialize($lr));   //it saves resources in the file 'lr'
	return true;
}

//it saves the new path of IOdir
function saveIOdir($req) {
	if(!file_exists($req['txtValue']) || !file_exists($req['txtValue']."clusters_loms") || !file_exists($req['txtValue']."clusters_tags") || !file_exists($req['txtValue']."clusters_uses") || !file_exists($req['txtValue']."clusters_replinks")) return false;
	$config_values=processFileForOptions($req['file']);    //it reads an option from the file specified (it creates an array with form array["option"]=value)
	$config_values=substituteOption($config_values,"IOdir",$req['txtValue']);   //it substitutes the IOdir new path in the array
	write_config_values($config_values,$req['file']);  //it saves the options from the array to the file indicated
	return true;
}

//it saves the new value of dd_crosscheck
function set_dd_crosscheck($req) {
	$config_values=processFileForOptions($req['file']);    //it reads an option from the file specified (it creates an array with form array["option"]=value)
	if($req['value']=='true') $value=1;
	else $value=0;
	$config_values=substituteOption($config_values,"dd_crosscheck",$value);   //it substitutes the IOdir new path in the array
	write_config_values($config_values,$req['file']);  //it saves the options from the array to the file indicated
	return true;
}

function processFileForOptions($file) {    //it reads an option from the file specified (it creates an array with form array["option"]=value)
	$fp = fopen($file, "r");
	while (!feof($fp)) {
		$line = trim(fgets($fp));
		if($line!="<?php" && $line!="?>" && $line!="") {
			$pieces = explode("=", $line);
			$opt = substr(trim($pieces[0]),1);
			$val = substr(trim($pieces[1]),0,-1);
			$config_values[$opt] = $val;
		}
	}
	fclose($fp);
	return $config_values;
}

function substituteOption($config_values,$option,$newvalue) {  //it substitutes an option in the array
	
	foreach($config_values as $opt => $val) {
		if($opt==$option) {
			if(is_numeric($newvalue) || $newvalue=="true" || $newvalue=="false") $config_values[$opt]=$newvalue;
			else $config_values[$opt]="\"$newvalue\"";
			break;
		}
		if(is_numeric($newvalue) || $newvalue=="true" || $newvalue=="false") $config_values[$option]=$newvalue;
		else $config_values[$option]="\"$newvalue\"";
	}
	return $config_values;
}

function write_config_values($config_values,$file) {  //it saves the options from the array to the file indicated
	$fp = fopen($file, "w");
	fputs($fp,"<?php\n");
	foreach($config_values as $opt => $val) {
		fputs($fp,"$"."$opt = $val;\n");
	}
	fputs($fp,"?>\n");
	fclose($fp);
}

//it shows all the fields, so you can choose the ones you want to use for classification
function showfields($req) {
	global $CONFIG;
	include($CONFIG->path . "mod/profile_manager/actions/members/config.php");
	include($CONFIG->path . "mod/profile_manager/actions/members/classes.php");
	$query = "SELECT distinct e.* from elggentities e join elggusers_entity u on e.guid = u.guid JOIN (SELECT subm1.*, s1.string FROM elggmetadata subm1 JOIN elggmetastrings s1 ON subm1.value_id = s1.id) AS m1 ON e.guid = m1.entity_guid where ((m1.name_id='440' AND m1.string IN ('356'))) and ( (1 = 1) and e.enabled='yes') and ( (1 = 1) and m1.enabled='yes') order by e.time_created desc";
	$entities = get_data($query, "entity_row_to_elggstar");
	$entity=$entities[0];
	$titles=array();
	$name=$entity->getGUID();
	$lr[$name]=new LearningResource();
	
	$categorized_fields = profile_manager_get_categorized_fields($entity, true);
	//print_r("<br><br>$categorized_fields");
	$cats = $categorized_fields['categories'];
	$fields = $categorized_fields['fields'];
	//print_r($cats);
	if(!empty($fields)){
		foreach($cats as $cat_guid => $cat){
			foreach($fields[$cat_guid] as $field){
				$metadata_name = $field->metadata_name;
				// get options
				$options = $field->getOptions();
				
				
				// get type of field
				if($field->user_editable == "no"){
					$valtype = "non_editable";
				} else {
					$valtype = $field->metadata_type;	
				}
				// make title
				$titles[]["value"] = trim($field->getTitle());
			}
		}
	}
	$config_values=processFileForOptions($req['file']);   //it reads an option from the file specified (it creates an array with form array["option"]=value)
	$fields_loms=explode(";",substr($config_values["loms_fields"],1,-1));
	$fields_uses=explode(";",substr($config_values["uses_fields"],1,-1));
	$fields_tags=explode(";",substr($config_values["tags_fields"],1,-1));
	$i=0;
	//if the field we are analyzing is part of the fields currently selected in 'config.php', we will select its checkbox
	foreach($titles as $title) {
		if(in_array($title["value"],$fields_loms)) $titles[$i]["loms"]=1;
		else $titles[$i]["loms"]=0;
		if(in_array($title["value"],$fields_uses)) $titles[$i]["uses"]=1;
		else $titles[$i]["uses"]=0;
		if(in_array($title["value"],$fields_tags)) $titles[$i]["tags"]=1;
		else $titles[$i]["tags"]=0;
		$i++;
	}
	return $titles;
}

//it saves the checked fields in the file config.php
function savefields($req) {
	$fields_loms_string=array2string(json_decode($req["fields_loms_json"]));
	$fields_uses_string=array2string(json_decode($req["fields_uses_json"]));
	$fields_tags_string=array2string(json_decode($req["fields_tags_json"]));
	$config_values=processFileForOptions($req['file']);    //it reads an option from the file specified (it creates an array with form array["option"]=value)
	$config_values=substituteOption($config_values,"loms_fields",$fields_loms_string);
	$config_values=substituteOption($config_values,"uses_fields",$fields_uses_string);
	$config_values=substituteOption($config_values,"tags_fields",$fields_tags_string);
	write_config_values($config_values,$req['file']);   //it saves the options from the array to the file indicated
	return true;
}

function array2string($arr) {
	foreach($arr as $value) {
		$value=trim($value);
		$str.="$value;";
	}
	$str=substr($str,0,-1);
	return $str;
}

//It adds the autocomplete for basic and advanced search
function autocomplete_elggusers_entity($req){
	$q = strtolower($req["q"]);
	if (!$q) return;
	$query= "SELECT distinct e.* from elggentities e join elggusers_entity u on e.guid = u.guid where u.name like '%$q%' and ( (1 = 1) and e.enabled='yes') order by e.time_created desc";
	//$query= "SELECT name FROM elggusers_entity where name LIKE '%".$q."%'";
	$items=get_data($query, "entity_row_to_elggstar");
	foreach($items as $val) {
		$result=$val->name;
		echo $result."\n";
	}
}

function autocomplete_idcluster($req){
	global $CONFIG;
	include($CONFIG->path . "mod/profile_manager/actions/members/config.php");
	include($CONFIG->path . "mod/profile_manager/actions/members/classes.php");
	$clusters_loms = unserialize(file_get_contents($IOdir . 'clusters_loms'));
	$clusters_uses = unserialize(file_get_contents($IOdir . 'clusters_uses'));
	$clusters_tags = unserialize(file_get_contents($IOdir . 'clusters_tags'));
	$clusters_replinks = unserialize(file_get_contents($IOdir . 'clusters_replinks'));
	
	$q = strtolower($req["q"]); 

	$response = array();
	
	$clusters = array($clusters_loms, $clusters_uses, $clusters_tags, $clusters_replinks);
	for ($i = 0; $i < 4; $i++) {
		$len = count($clusters[$i]);
		$index = 0;
		for ($j = 0; $j < $len; $j++) {
			$response[] = $clusters[$i][$j]->id;
		}		
	}
	
	$response = my_select_like($q,array_unique($response));
	sort($response);
	
	foreach ($response as $key=>$value) {	
		//Data format accepted by the autocomplete plug-in
		$output.= "$value|$key\n";
	}
	return $output;
}

function autocomplete_guidresource($req){
	global $CONFIG;
	include($CONFIG->path . "mod/profile_manager/actions/members/config.php");
	include($CONFIG->path . "mod/profile_manager/actions/members/classes.php");
	$clusters_loms = unserialize(file_get_contents($IOdir . 'clusters_loms'));
	$clusters_uses = unserialize(file_get_contents($IOdir . 'clusters_uses'));
	$clusters_tags = unserialize(file_get_contents($IOdir . 'clusters_tags'));
	$clusters_replinks = unserialize(file_get_contents($IOdir . 'clusters_replinks'));
	
	$q = strtolower($req["q"]); 

	$response = array();
	
	$clusters = array($clusters_loms, $clusters_uses, $clusters_tags, $clusters_replinks);
	for ($i = 0; $i < 4; $i++) {
		$len = count($clusters[$i]);
		$index = 0;
		for ($j = 0; $j < $len; $j++) {
			if(is_array($clusters[$i][$j]->array_docs[0])) {  //if it's a cluster obtained by the yaca algorithm
				foreach($clusters[$i][$j]->array_docs as $docpack) {
					$response[] = $docpack["guid"];
				}
			}
			else $response = array_merge($response,(array)$clusters[$i][$j]->array_docs);
		}		
	}
	
	$response = my_select_like($q,array_unique($response));
	sort($response);
	
	foreach ($response as $key=>$value) {	
		//Data format accepted by the autocomplete plug-in
		$output.= "$value|$key\n";
	}
	return $output;
}

//it adds the autocomplete by positive features for Cluster search
function autocomplete_positive_features($req){
	global $CONFIG;
	include($CONFIG->path . "mod/profile_manager/actions/members/config.php");
	include($CONFIG->path . "mod/profile_manager/actions/members/classes.php");
	$clusters_loms = unserialize(file_get_contents($IOdir . 'clusters_loms'));
	$clusters_uses = unserialize(file_get_contents($IOdir . 'clusters_uses'));
	$clusters_tags = unserialize(file_get_contents($IOdir . 'clusters_tags'));
	$clusters_replinks = unserialize(file_get_contents($IOdir . 'clusters_replinks'));

	$q = strtolower($req["q"]); 

	$response = array();
	
	$clusters = array($clusters_loms, $clusters_uses, $clusters_tags, $clusters_replinks);
	for ($i = 0; $i < 4; $i++) {
		$len = count($clusters[$i]);
		$index = 0;
		for ($j = 0; $j < $len; $j++) {
			$response = array_merge($response,(array)$clusters[$i][$j]->commonFeatures);
		}		
	}
	//$response = array_unique($response);
	$response = my_select_like($q,array_unique($response));
	sort($response);
	
	foreach ($response as $key=>$value) {	
		//Data format accepted by the autocomplete plug-in
		$output.= "$value|$key\n";
	}
	return $output;
}

// This function emulates the behaviour of  SELECT .. WHERE $array_source elements LIKE  % $sub_str %, and puts results on $out_array
function my_select_like($sub_str, $array_source){
	$out_array = array();
	foreach($array_source as $ref){
		if(substr_count($ref, $sub_str)){
			array_push($out_array, $ref);
		}
	}
	return $out_array;
} 


?>