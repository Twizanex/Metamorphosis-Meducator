<?php

//it initializes the outputfile
function initialize_outputfile($req) {
	global $CONFIG;
	set_time_limit(0);  //this avoids timeouts
	$outputfile=$CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/output.log";
	file_put_contents($outputfile,"");  //initializes the output file
	if(PHP_OS=="Linux" && posix_getuid()==fileowner($outputfile)) chmod($outputfile,0666);
	return "OK";
}

//reads the output file continuously and returns the read data when it is different from the previous data
//it uses the Comet technique of the "long polling"
function readoutputfile($req) {
	global $CONFIG;
	set_time_limit(0);  //this avoids timeouts
	$outputfile=$CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/output.log";
	while(true) {
		$res=file_get_contents($outputfile);
		if($res!="" && (!isset($req["currenttext"]) || $res!=$req["currenttext"])) return $res;  //it returns only at the first read and when the read text is different from the previous one (something has been written in the file)
	}
}

//blocks the function readoutputfile
function stop_readoutputfile($req) {
	global $CONFIG;
	set_time_limit(0);  //this avoids timeouts
	$outputfile=$CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/output.log";
	file_put_contents($outputfile,"END",FILE_APPEND);  //put the string that tells the readoutputfile function to stop reading
	sleep(3); //wait 3 seconds to make sure the readoutputfile function has finished reading
	return "OK";
}

function get_values($vector,$field) {
	set_time_limit(0);  //this avoids timeouts
	$values=array();
	foreach($vector as $key2 => $vect2) {
		if($key2===$field)  $values[]=$vect2;
		if(is_array($vect2)) {
			$values2=get_values($vect2,$field);
			$values=array_merge($values,$values2);
		}
	}
	return $values;
}

//it gets a new data snapshot
function get_snapshot($req) {
	global $CONFIG;
	set_time_limit(0);  //this avoids timeouts
	$outputfile=$CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/output.log";
	require_once ($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/config.php");
	require_once ($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/classes.php");	
	
	//$lr=unserialize(file_get_contents($IOdir."lr"));
	//return "OK";
	
	file_put_contents($outputfile,"Old indexing and classification files will now be saved in the IOdir directory with the prefix old_ and will be deleted after the whole process is completed\nIf there are problems and the process aborts before the end, rename them deleting that prefix in order to recover old data!\n\n");
	
	//backup of the old files
	if(file_exists($IOdir."changes")) {
		copy($IOdir."changes",$IOdir."old_changes");
		if(PHP_OS=="Linux") chmod($IOdir.'old_changes',0666); //set rw permissions for everybody for this file
	}
	if(file_exists($IOdir."lr")) {
		copy($IOdir."lr",$IOdir."old_lr");
		if(PHP_OS=="Linux") chmod($IOdir.'old_lr',0666); //set rw permissions for everybody for this file
		unlink($IOdir."lr");
	}
	if(file_exists($IOdir."metadata_dt")) {
		copy($IOdir."metadata_dt",$IOdir."old_metadata_dt");
		if(PHP_OS=="Linux") chmod($IOdir.'old_metadata_dt',0666); //set rw permissions for everybody for this file
		unlink($IOdir."metadata_dt");
	}
	if(file_exists($IOdir."uses_dt")) {
		copy($IOdir."uses_dt",$IOdir."old_uses_dt");
		if(PHP_OS=="Linux") chmod($IOdir.'old_uses_dt',0666); //set rw permissions for everybody for this file
		unlink($IOdir."uses_dt");
	}
	if(file_exists($IOdir."tags_dt")) {
		copy($IOdir."tags_dt",$IOdir."old_tags_dt");
		if(PHP_OS=="Linux") chmod($IOdir.'old_tags_dt',0666); //set rw permissions for everybody for this file
		unlink($IOdir."tags_dt");
	}
	if(file_exists($IOdir."metadata_dt_raw")) {
		copy($IOdir."metadata_dt_raw",$IOdir."old_metadata_dt_raw");
		if(PHP_OS=="Linux") chmod($IOdir.'old_metadata_dt_raw',0666); //set rw permissions for everybody for this file
		unlink($IOdir."metadata_dt_raw");
	}
	if(file_exists($IOdir."uses_dt_raw")) {
		copy($IOdir."uses_dt_raw",$IOdir."old_uses_dt_raw");
		if(PHP_OS=="Linux") chmod($IOdir.'old_uses_dt_raw',0666); //set rw permissions for everybody for this file
		unlink($IOdir."uses_dt_raw");
	}
	if(file_exists($IOdir."tags_dt_raw")) {
		copy($IOdir."tags_dt_raw",$IOdir."old_tags_dt_raw");
		if(PHP_OS=="Linux") chmod($IOdir.'old_tags_dt_raw',0666); //set rw permissions for everybody for this file
		unlink($IOdir."tags_dt_raw");
	}
	if(file_exists($IOdir."tags_dd")) {
		copy($IOdir."tags_dd",$IOdir."old_tags_dd");
		if(PHP_OS=="Linux") chmod($IOdir.'old_tags_dd',0666); //set rw permissions for everybody for this file
		unlink($IOdir."tags_dd");
	}
	if(file_exists($IOdir."metadata_dd"))  {
		copy($IOdir."metadata_dd",$IOdir."old_metadata_dd");
		if(PHP_OS=="Linux") chmod($IOdir.'old_metadata_dd',0666); //set rw permissions for everybody for this file
		unlink($IOdir."metadata_dd");
	}
	if(file_exists($IOdir."uses_dd")) {
		copy($IOdir."uses_dd",$IOdir."old_uses_dd");
		if(PHP_OS=="Linux") chmod($IOdir.'old_uses_dd',0666); //set rw permissions for everybody for this file
		unlink($IOdir."uses_dd");
	}
	if(file_exists($IOdir."tags_dd")) {
		copy($IOdir."tags_dd",$IOdir."old_tags_dd");
		if(PHP_OS=="Linux") chmod($IOdir.'old_tags_dd',0666); //set rw permissions for everybody for this file
		unlink($IOdir."tags_dd");
	}
	if(file_exists($IOdir."replinks_dd")) {
		copy($IOdir."replinks_dd",$IOdir."old_replinks_dd");
		if(PHP_OS=="Linux") chmod($IOdir.'old_replinks_dd',0666); //set rw permissions for everybody for this file
		unlink($IOdir."replinks_dd");
	}
	if(file_exists($IOdir."clusters_metadata")) {
		copy($IOdir."clusters_metadata",$IOdir."old_clusters_metadata");
		if(PHP_OS=="Linux") chmod($IOdir.'old_clusters_metadata',0666); //set rw permissions for everybody for this file
		unlink($IOdir."clusters_metadata");
	}
	if(file_exists($IOdir."clusters_uses")) {
		copy($IOdir."clusters_uses",$IOdir."old_clusters_uses");
		if(PHP_OS=="Linux") chmod($IOdir.'old_clusters_uses',0666); //set rw permissions for everybody for this file
		unlink($IOdir."clusters_uses");
	}
	if(file_exists($IOdir."clusters_tags")) {
		copy($IOdir."clusters_tags",$IOdir."old_clusters_tags");
		if(PHP_OS=="Linux") chmod($IOdir.'old_clusters_tags',0666); //set rw permissions for everybody for this file
		unlink($IOdir."clusters_tags");
	}
	if(file_exists($IOdir."clusters_replinks")) {
		copy($IOdir."clusters_replinks",$IOdir."old_clusters_replinks");
		if(PHP_OS=="Linux") chmod($IOdir.'old_clusters_replinks',0666); //set rw permissions for everybody for this file
		unlink($IOdir."clusters_replinks");
	}

	//if you chose to use the Sesame RDF
	if($data_source==1) {
		require_once($CONFIG->path . "mod/mmsearch/custom/MeducatorParser.php");
		file_put_contents($outputfile,"Downloading a new snapshot of data from the SESAME RDF...\n",FILE_APPEND);
		$address_base =$CONFIG->API_URL . "searchall?properties=";
		$metadatas_fields=explode(";",$metadata_fields);
		sort($metadatas_fields);
		$usess_fields=explode(";",$uses_fields);
		sort($usess_fields);
		$tagss_fields=explode(";",$tags_fields);
		sort($tagss_fields);
		$fields=array_unique(array_merge($metadatas_fields,$usess_fields,$tagss_fields));
		if(!in_array("hasRepurposingContext",$fields)) $fields[]="hasRepurposingContext"; //in order to return the "repurposedFrom" part as well, used for Replinks
		$finalresults=array();
		foreach($fields as $field) {
			if($field=="seeAlso" || $field=="type") continue;  //the first one is included by default, the second causes error
			$address=$address_base . "mdc:$field";   //I can't put all the fields together right now, since it will return only the resources that have all those fields, so I do a query field after field and then I make the union of the results
			$rdf_info = connectToSesame($address);
			$medParser = new MeducatorParser($rdf_info, true);
			$results=$medParser->results;
			foreach($results as $key => $result) {
				if(array_key_exists($key,$finalresults)) $finalresults[$key]=array_merge($finalresults[$key],$result);
				else $finalresults[$key]=$result;
			}
		}
		
		//$b=print_r($finalresults,true);
		//file_put_contents("bbbb",$b);
		
		if(count($finalresults) > 0) {
			//we have to create a LearningResource object for each result and insert metadata into it
			foreach($finalresults as $id => $data) {
				$seeAlso=$data["seeAlso"];
				if(is_array($seeAlso)) {
					$values=array_unique(get_values($seeAlso,"seeAlso"));
					foreach($values as $value) {
						if(strrchr($value,"#")!=FALSE) {
							$guid=substr(strrchr($value,"#"),1); 
							break;
						}
					}
				}
				else $guid=substr(strrchr($seeAlso,"#"),1);
				$entity=get_entity($guid);
				//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				if(empty($entity)) continue;  //this is a temporary check from the database since we still need it in order not to have problem with the normal search that still takes info from it
				//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				$lr[$guid]=new LearningResource();
				$lr[$guid]->setName($entity->get('name'));
				$lr[$guid]->setUsername($entity->get('username'));
				$lr[$guid]->setGUID($guid);
				
				//we extract the important part from each field and insert in the $lr[$guid] object
				foreach($fields as $field) {
					$notused=array("type","hasRepurposingContext");
					if(in_array($field,$notused)) continue;   //"type" is more difficult to handle, anyway we don't need it; "hasRepurposingContext" is used later for Replinks
					
					$value="";
					if(!is_array($data[$field])) $value=$data[$field];  //if in this field we have directly the name
					elseif(isset($data[$field]["name"])) $value=$data[$field]["name"];  //for creator and other fields
					elseif(isset($data[$field]["label"])) $value=$data[$field]["label"];  //for subject and other fields
					else {  //if we have different elements
						foreach($data[$field] as $subfield) {
							if(!is_array($subfield))  $subvalue=$subfield; //if we have directly the name
							elseif(isset($subfield["name"])) $subvalue=$subfield["name"];  //for creator and other fields
							elseif(isset($subfield["label"])) $subvalue=$subfield["label"];  //for subject and other fields
							else continue;  //I think there are not other cases, but if so, don't handle them in order to avoid errors
							if(is_array($subvalue)) $subvalue=implode(";",$subvalue);  //if it is still an array, transform it in a string
							$value.=$subvalue.";";
						}
						$value=substr($value,0,-1);  //delete the last ;
					}
					if(is_array($value)) $value=implode(";",$value);  //if it is still an array, transform it in a string
					if(strrchr($value,"#")!=FALSE) $value=substr(strrchr($value,"#"),1);  //take only the important part
					
					if($value!="") $lr[$guid]->insertMetadata($field,$value);
				}
				
				//now I add to the tags of the resource eventual tags added by the users
				$objs=get_entities_from_metadata('', '', 'object', '', '',10000);
				foreach($objs as $obj) {
					if($obj->subtype=='28') {  //I get only the objects that are bookmarks
						$link=$CONFIG->url."pg/profile/".$entity->get('username');
						if($obj->address==$link) {  //I consider the bookmark only if it is a bookmark to the current resource
							if(!empty($obj->tags)) {
								if(is_array($obj->tags)) $lr[$guid]->tags=array_merge($lr[$guid]->tags,$obj->tags);
								else $lr[$guid]->tags[]=$obj->tags;
							}
						}
					}
				}
				
				//insert $replinks["from"]
				if(is_array($data["hasRepurposingContext"])) {
					$values=array_unique(get_values($data["hasRepurposingContext"],"seeAlso"));
					foreach($values as $value) {
						$guid_from=substr(strrchr($value,"#"),1); 
						//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
						$entity=get_entity($guid_from);
						if(empty($entity)) continue;  //this is a temporary check from the database since we still need it in order not to have problem with the normal search that still takes info from it
						//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
						$lr[$guid]->replinks["from"][]=$guid_from;
					}
				}
			}
			
			//insert replinks["to"]
			foreach($lr as $guid => $resource) {
				foreach($resource->replinks["from"] as $guid_from) {
					$lr[$guid_from]->replinks["to"][]=$guid;
				}
			}
		}
		else {
			file_put_contents($outputfile,"\n\nNo resources returned. There must be an error somewhere...",FILE_APPEND);
			exit();
		}
	}
	//if you chose to use the Elgg database
	elseif($data_source==2) {
		file_put_contents($outputfile,"Downloading a new snapshot of data from the Metamorphosis database...\n",FILE_APPEND);
		$metadatas_fields=explode(";",$metadata_fields);
		sort($metadatas_fields);
		$usess_fields=explode(";",$uses_fields);
		sort($usess_fields);
		$tagss_fields=explode(";",$tags_fields);
		sort($tagss_fields);
		$fields=array_unique(array_merge($metadatas_fields,$usess_fields,$tagss_fields));
		
		$query = "SELECT distinct e.* from elggentities e join elggusers_entity u on e.guid = u.guid JOIN (SELECT subm1.*, s1.string FROM elggmetadata subm1 JOIN elggmetastrings s1 ON subm1.value_id = s1.id) AS m1 ON e.guid = m1.entity_guid where ((m1.name_id='440' AND m1.string IN ('356'))) and ( (1 = 1) and e.enabled='yes') and ( (1 = 1) and m1.enabled='yes') order by e.time_created desc";
		$entities = get_data($query, "entity_row_to_elggstar");

		foreach($entities as $entity) {
			$guid=$entity->getGUID();
			$lr[$guid]=new LearningResource();
			$lr[$guid]->setName($entity->get('name'));
			$lr[$guid]->setUsername($entity->get('username'));
			$lr[$guid]->setGUID($guid);
				
			$categorized_fields = profile_manager_get_categorized_fields($entity, true);
			$cats = $categorized_fields['categories'];
			$db_fields = $categorized_fields['fields'];
			
			if(!empty($db_fields)){
				foreach($cats as $cat_guid => $cat){
					foreach($db_fields[$cat_guid] as $db_field){
						$metadata_name = $db_field->metadata_name;
						// get options
						$options = $db_field->getOptions();

						// get type of db_field
						if($db_field->user_editable == "no"){
							$valtype = "non_editable";
						} else {
							$valtype = $db_field->metadata_type;	
						}
						
						$field = $db_field->getTitle();
						
						if(in_array($field,$fields)) {
							// get value
							if($metadata = get_metadata_byname($entity->guid, $metadata_name)) {
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
								
							if($value!="") $lr[$guid]->insertMetadata($field,$value);
						}
					}
				}
			}
			
			//now I add to the tags of the resource eventual tags added by the users
			$objs=get_entities_from_metadata('', '', 'object', '', '',10000);
			foreach($objs as $obj) {
				if($obj->subtype=='28') {  //I get only the objects that are bookmarks
					$link=$CONFIG->url."pg/profile/".$entity->get('username');
					if($obj->address==$link) {  //I consider the bookmark only if it is a bookmark to the current resource
						if(!empty($obj->tags)) {
							if(is_array($obj->tags)) $lr[$guid]->tags=array_merge($lr[$guid]->tags,$obj->tags);
							else $lr[$guid]->tags[]=$obj->tags;
						}
					}
				}
			}
		}
		
		//insert replinks
		foreach($lr as $guid => $resource) {
			$entity=get_entity($guid);
			$lr[$guid]->insertReplinks($entity,$lr);
		}
	}
	krsort($lr);
	
	$guids=array_keys($lr);
	file_put_contents($IOdir.'guids',serialize($guids));   //it saves guids in the file 'guids'
	if(PHP_OS=="Linux" && posix_getuid()==fileowner($IOdir.'guids')) chmod($IOdir.'guids',0666); //set rw permissions for everybody for this file
	
	file_put_contents($IOdir.'lr',serialize($lr));   //it saves resources in the file 'lr'
	if(PHP_OS=="Linux" && posix_getuid()==fileowner($IOdir.'lr')) chmod($IOdir.'lr',0666); //set rw permissions for everybody for this file
	file_put_contents($outputfile,"Got snapshot...\n\n",FILE_APPEND);
	return "OK";
}

function set_stop_words($loc_file) {
	set_time_limit(0);  //this avoids timeouts
	$sw = file_get_contents($loc_file);
	$exc = explode("\n", $sw);
	$exc[] = '';
	foreach ($exc as $sw) {
		$exc[] = strtoupper($sw);
		$exc[] = strtoupper(substr($sw, 0, 1)) . strtolower(substr($sw, 1));
	}
	return $exc;
}

//given the text of the document (with this configuration it is only 1), for each word (excluding stop words) calculates statistics (position in the text) and saves them in the D object
function create_index($text,$stop_words) {
	global $CONFIG;
	set_time_limit(0);  //this avoids timeouts
	include ($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/config.php");
	include_once ($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/stemming.php");
	
	$text_info["positions"]=array();
	
	//delete strange characters
	$text = ' ' .preg_replace('/([!?.,;\*\"\[\]\-–_@~#§\^\:\(\)\{\}\/\'"“”’0-9°º\t\n\r]+)|<[^>]+>|&[^;]+;/', ' ', $text) . ' ';
	//delete stop words
	foreach ($stop_words as $sw) {
		$text = str_replace(" $sw ", ' ', $text);
	}
	//create an array of terms and stem each term
	$text_terms = explode(" ", $text);
	foreach($text_terms as $i => $term) {
		$text_terms[$i] = PorterStemmer::Stem(trim(strtolower($term)));
	}
	
	$text = implode(' ',$text_terms);
	//delete again stop words
	foreach ($stop_words as $sw) {
		$text = str_replace(" $sw ", ' ', $text);
	}
	$text_info["text"]=$text;
	$text_terms = array ();
	$text_terms = explode(" ", $text);
	// here is where the indexing of terms to document locations happens
	foreach($text_terms as $pos => $term) {
		if($term!=' ' && $term!='') $text_info["positions"][trim($term)][]=$pos;
	}
	
	return $text_info;
}

//most present keywords are extracted from the text, till the limit established by the variable keywords_limit
function extract_keywords($terms_positions) {
	global $CONFIG;
	set_time_limit(0);  //this avoids timeouts
	include ($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/config.php");

	$terms_score = array ();
	$keywords = array ();
	
	foreach ($terms_positions as $term => $positions) {
		$keywords[$term] = count($positions);
	}
	arsort($keywords);

	//terms limit
	$j=0;
	if($keywords_limit!=-1)  {
		foreach ($keywords as $term => $ric){
			$j++;
			if($j>$keywords_limit) unset($keywords[$term]);
		}
	}
	return $keywords;
}

//it applies the sliding window algorithm to each keyword to extract the contexts
function sliding_window_ri($keywords, $width,$text) {
	global $CONFIG;
	set_time_limit(0);  //this avoids timeouts
	include ($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/config.php");
	
	$vector_words = array_keys($keywords);
	$matrix = array ();
	$terms = array ();
	$words = explode(' ', $text);
	foreach ($words as $word) {
		if ($word != '') $terms[trim(strtolower($word))] = 0;
	}
	
	for ($j = 0; $j < count($vector_words); $j++) { //cycle in the keywords
		for ($i = 0; $i < count($words); $i++) {  //cycle in the list of the words contained in the text
			if (trim(strtolower($words[$i])) == $vector_words[$j]) {   //if it founds the current keyword among the list of the words contained in the text
				$current = $width +1;
				while (($current = $current -1) != 0) {
					if ($words[$i - $current] != $vector_words[$j]) $terms[trim(strtolower($words[$i - $current]))]++;
					if ($words[$i + $current] != $vector_words[$j]) $terms[trim(strtolower($words[$i + $current]))]++;
				}
			}
		}

		arsort($terms);

		$limit = 0;
		if(($vector_words[$j]) == '') continue;
		$matrix[$vector_words[$j]] = array();
		$matrix[$vector_words[$j]][$vector_words[$j]] = $keywords[$vector_words[$j]];
		foreach ($terms as $k => $v) {
			if($k == '') continue;
			if($vector_words[$j] == $k) continue;
			if($context_limit==-1 || $limit < $context_limit){
				if($v > 0) $matrix[$vector_words[$j]][$k] = $v;
				$limit++;
			}
			$terms[$k] = 0;
		}
	}
	return $matrix;
}

function extract_matrix($text,$stop_words) {
	global $CONFIG;
	set_time_limit(0);  //this avoids timeouts
	include ($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/config.php");
	
	 //given the text of the document (with this configuration it is only 1), for each word (excluding stop words) calculates statistics (position in the text) and saves them in the D object
	$text_info=create_index($text,$stop_words); 
	
	$keywords = extract_keywords($text_info["positions"]);   //most present keywords are extracted from the text, till the limit established by the variable keywords_limit
	
	$response = array();
	if($context_limit==0) {
		foreach($keywords as $keyword => $recurrence) {
			$response[$keyword][$keyword] = $recurrence;
		}
	}
	else $response = sliding_window_ri($keywords, $width_sliding_window,$text_info["text"]);  //it applies the sliding window algorithm to each keyword to extract the contexts
	return $response;
}

//it checks if two words are synonyms or not (VERY EXPERIMENTAL, DON'T USE IT)
function check_synonyms($w0, $w1) {
	global $CONFIG;
	set_time_limit(0);  //this avoids timeouts
	include ($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/config.php");
	include_once $CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/stemming.php";
	$outputfile=$CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/output.log";
	
	if($w0==$w1) return true;
	$syn_db_wnet = mysql_connect($syn_db_host,$syn_db_user,$syn_db_pass) or die("Unable to connect to the synonyms database\n");
	$res=mysql_select_db($syn_db_name,$syn_db_wnet);
	if (!$res) {
		file_put_contents($outputfile,"\n".mysql_error()."\n",FILE_APPEND);
		die;
	}
	$sql = "select distinct w2.lemma from senses as s1 
			left join words as w2 on w2.wordid=s1.wordid
			join senses as s2 on  s1.synsetid = s2.synsetid
			right JOIN words as w1 on w1.wordid=s2.wordid
			where 
			w1.lemma LIKE '$w0%'
			and w2.lemma NOT LIKE '$w0%'";
	$res = mysql_query($sql,$syn_db_wnet);
	if (!$res) {
		file_put_contents($outputfile,"\n".mysql_error()."\n",FILE_APPEND);
		die;
	}
	$row = array();
	while($row = @mysql_fetch_assoc($res)){
		$w_new = PorterStemmer::Stem($row['lemma']);
		if($w1 == $w_new) return true;
	}
	return false;
}

//compare the numbers returning them in decreasing order
function cmp_keys($a,$b) {
	set_time_limit(0);  //this avoids timeouts	
	foreach($a as $value1) {
		foreach($b as $value2) {
			if($value1 < $value2) return 1;
			elseif($value1==$value2) return 0;
			else return -1;
		}
	}
}

//put all the docterm matrices together
function join_pieces($pieces) {
	global $CONFIG;
	set_time_limit(0);  //this avoids timeouts
	include ($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/config.php");
	if(count($pieces)>1) {
		//extract all the keys
		$keys=array();
		foreach($pieces as $piece) {
			$keys=array_merge($keys,array_keys($piece));
		}
		$keys=array_unique($keys);
		
		//create a unique docterm matrix with all the keywords and their recurrence
		$matrix_dt=array();
		foreach($keys as $key) {
			foreach($pieces as $piece) {
				if(isset($piece[$key])) {
					if(!isset($matrix_dt[$key])) $matrix_dt[$key][$key]=$piece[$key][$key];
					else $matrix_dt[$key][$key]+=$piece[$key][$key];
				}
			}
		}
		uasort($matrix_dt,"cmp_keys");
		
		if($keywords_limit !=-1) {
			$i=1;
			foreach($matrix_dt as $key =>$info2) {
				if($i>$keywords_limit) unset($matrix_dt[$key]);
				$i++;
			}
		}
		
		//if we use contexts, add them to the matrix
		if($context_limit!=0) {
			foreach($matrix_dt as $key =>$info2) {
				foreach($pieces as $piece) {
					if(isset($piece[$key])) {
						foreach($piece[$key] as $context=>$cvalue) {
							if($context==$key) continue;
							if(!isset($matrix_dt[$key][$context])) $matrix_dt[$key][$context]=$cvalue;
							else $matrix_dt[$key][$context]+=$cvalue;
						}
					}
				}
				$matrix_dt[$key][$key]+=100000;  //temporary change to make this stays in the first position even after the following arsort
				arsort($matrix_dt[$key]);
				$matrix_dt[$key][$key]-=100000;   //we can undo the temporary change
				
				if($context_limit!=-1) {
					$i=1;
					foreach($matrix_dt[$key] as $context =>$cvalue) {
						if($context==$key) continue;
						if($i>$context_limit) unset($matrix_dt[$key][$context]);
						$i++;
					}
				}
			}
		}
	}
	else $matrix_dt=$pieces[0];
	return $matrix_dt;
}

//it processes Metadata to create a matrix doc-term for them
function doctermMetadata($req) {
	global $CONFIG;
	set_time_limit(0);  //this avoids timeouts
	require_once ($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/config.php");
	require_once ($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/classes.php");
	$outputfile=$CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/output.log";
	
	file_put_contents($outputfile,"Starting creating METADATA Doc-term matrix...\n",FILE_APPEND);
	$guids=unserialize(file_get_contents($IOdir."guids"));
	$lr_array=unserialize(file_get_contents($IOdir."lr"));
	$metadata_dt = array ();
	$stop_words=set_stop_words($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/stop_words_eng.txt");
	if($req["dt_useold"]=="true" && file_exists($IOdir."old_lr") && file_exists($IOdir."old_metadata_dt_raw")) {
		$old_lr=unserialize(file_get_contents($IOdir."old_lr"));
		$old_metadata_dt_raw=unserialize(file_get_contents($IOdir."old_metadata_dt_raw"));  //we need the raw version of the doc-term matrix, before applying IDF or synonyms
	}
	foreach($guids as $guid) {
		if(!empty($lr_array[$guid]->metadata)) {
			//we use old values where possible
			if($req["dt_useold"]=="true" && $enable_idf==0 && isset($old_lr[$guid]->metadata) && ($lr_array[$guid]->metadata)==($old_lr[$guid]->metadata)  && isset($old_metadata_dt_raw[$guid])) {
				$metadata_dt[$guid]=$old_metadata_dt_raw[$guid];
				continue;
			}
			$pieces=array();
			foreach ($lr_array[$guid]->metadata as $text) {
				$pieces[] = extract_matrix($text,$stop_words);  //create a docterm matrix for each metadata text
			}
				
			$metadata_dt[$guid]=join_pieces($pieces);  //put all the docterm matrices together
		}
	}
	file_put_contents($IOdir."metadata_dt_raw",serialize($metadata_dt));  //it saves the raw version of the doc-term matrix
	if(PHP_OS=="Linux") chmod($IOdir."metadata_dt_raw",0666); //set rw permissions for everybody for this file
		
	//extract all the keys
	$keys=array();
	foreach($metadata_dt as $doc) {
		foreach($doc as $keyword => $element) {
			if(!in_array($keyword,$keys)) $keys[]=$keyword;
		}
	}
		
	//creates the idf for each key: log(num_documents/num_documents_containing_the_key)
	if($enable_idf) {
		$idf=array();
		$num_docs=count($metadata_dt);
		foreach($keys as $key) {
			$num_docs_with_key=0;
			foreach($metadata_dt as $doc) {
				if(isset($doc[$key])) $num_docs_with_key++;
			}
			$idf[$key]=log($num_docs/$num_docs_with_key);
			
			//each key frequency of each document will be now given by its frequency multiplied by the key idf
			foreach($metadata_dt as $guid => $doc) {
				if(isset($metadata_dt[$guid][$key])) $metadata_dt[$guid][$key][$key]*=$idf[$key];
			}
		}
	}
		
	//if synonyms support is enabled, if keyword A and keyword B are synonyms, we add B frequency and contexts to A and delete B
	if($enable_synonyms) {
		foreach($keys as $num=>$key) {
			if(!isset($keys[$num])) continue;  //since there is an unset on this array into the foreach, we have to check if the present key is still available or not
			foreach($keys as $num2=>$key2) {
				if(!isset($keys[$num2])) continue;  //since there is an unset on this array into the foreach, we have to check if the present key is still available or not
				if($key!=$key2 && check_synonyms($key,$key2)) {
					file_put_contents($outputfile,"\n$key and $key2 are synonyms\n",FILE_APPEND);
					foreach($metadata_dt as $guid=>$element) {
						if(!isset($metadata_dt[$guid][$key2])) continue;
						if(!isset($metadata_dt[$guid][$key])) $metadata_dt[$guid][$key]=$metadata_dt[$guid][$key2];  //it is implicit that in this element $key2 is set, otherwise it would have executed the previous if
						else {  //if both key and key2 are present in this element
							$metadata_dt[$guid][$key][$key]+=$metadata_dt[$guid][$key2][$key2];  //add the frequency of key2 to key1
							foreach($element[$key2] as $context2 => $frequency2) {
								if($context2==$key2) continue;  //the first context is the keyword so we don't consider it
								else {  //if the current context of key2 is present also in key1, we add the values, otherwise we add the current context to key1
									if(isset($metadata_dt[$guid][$key][$context2])) $metadata_dt[$guid][$key][$context2]+=$metadata_dt[$guid][$key2][$context2];
									else $metadata_dt[$guid][$key][$context2]=$metadata_dt[$guid][$key2][$context2];
								}
							}
						}
						unset($metadata_dt[$guid][$key2]);  //delete key2 fron the current document
					}
					unset($keys[$num2]);  //delete key2
				}
			}
		}
	}
		
	file_put_contents($IOdir."metadata_dt",serialize($metadata_dt));
	if(PHP_OS=="Linux" && posix_getuid()==fileowner($IOdir.'metadata_dt')) chmod($IOdir.'metadata_dt',0666); //set rw permissions for everybody for this file
	file_put_contents($outputfile,"METADATA Doc-term matrix created\n\n",FILE_APPEND);
	return "OK";
}

//it processes Use Cases to create a matrix doc-term for them
function doctermUses($req) {
	global $CONFIG;
	set_time_limit(0);  //this avoids timeouts
	require_once ($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/config.php");
	require_once ($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/classes.php");
	$outputfile=$CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/output.log";
	
	file_put_contents($outputfile,"Starting creating USES Doc-term matrix...\n",FILE_APPEND);
	$guids=unserialize(file_get_contents($IOdir."guids"));
	$lr_array=unserialize(file_get_contents($IOdir."lr"));
	
	$uses_dt = array ();
	$stop_words=set_stop_words($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/stop_words_eng.txt");
	if($req["dt_useold"]=="true" && file_exists($IOdir."old_lr") && file_exists($IOdir."old_uses_dt_raw")) {
		$old_lr=unserialize(file_get_contents($IOdir."old_lr"));
		$old_uses_dt_raw=unserialize(file_get_contents($IOdir."old_uses_dt_raw"));  //we need the raw version of the doc-term matrix, before applying IDF or synonyms
	}	
	foreach ($guids as $guid) {
		if(!empty($lr_array[$guid]->uses)) {
			if($req["dt_useold"]=="true" && isset($old_lr[$guid]->uses) && ($lr_array[$guid]->uses)==($old_lr[$guid]->uses)  && isset($old_uses_dt_raw[$guid])) {
				$uses_dt[$guid]=$old_uses_dt_raw[$guid];
				continue;
			}
			$pieces=array();
			foreach ($lr_array[$guid]->uses as $text) {
				$pieces[] = extract_matrix($text,$stop_words);   //create a docterm matrix for each uses field text
			}
				
			$uses_dt[$guid]=join_pieces($pieces);  //put all the docterm matrices together
		}
	}
	file_put_contents($IOdir."uses_dt_raw",serialize($uses_dt));  //it saves the raw version of the doc-term matrix
	if(PHP_OS=="Linux") chmod($IOdir."uses_dt_raw",0666); //set rw permissions for everybody for this file
	
	//extract all the keys
	$keys=array();
	foreach($uses_dt as $doc) {
		foreach($doc as $keyword => $element) {
			if(!in_array($keyword,$keys)) $keys[]=$keyword;
		}
	}
		
	//creates the idf for each key: log(num_documents/num_documents_containing_the_key)
	if($enable_idf) {
		$idf=array();
		$num_docs=count($uses_dt);
		foreach($keys as $key) {
			$num_docs_with_key=0;
			foreach($uses_dt as $doc) {
				if(isset($doc[$key])) $num_docs_with_key++;
			}
			$idf[$key]=log($num_docs/$num_docs_with_key);
				
			//each key frequency of each document will be now given by its frequency multiplied by the key idf
			foreach($uses_dt as $guid => $doc) {
				if(isset($uses_dt[$guid][$key])) $uses_dt[$guid][$key][$key]*=$idf[$key];
			}
		}
	}
		
	//if synonyms support is enabled, if keyword A and keyword B are synonyms, we add B frequency and contexts to A and delete B
	if($enable_synonyms) {
		foreach($keys as $num=>$key) {
			if(!isset($keys[$num])) continue;  //since there is an unset on this array into the foreach, we have to check if the present key is still available or not
			foreach($keys as $num2=>$key2) {
				if(!isset($keys[$num2])) continue;  //since there is an unset on this array into the foreach, we have to check if the present key is still available or not
				if($key!=$key2 && check_synonyms($key,$key2)) {
					file_put_contents($outputfile,"\n$key and $key2 are synonyms\n",FILE_APPEND);
					foreach($uses_dt as $guid=>$element) {
						if(!isset($uses_dt[$guid][$key2])) continue;
						if(!isset($uses_dt[$guid][$key])) $uses_dt[$guid][$key]=$uses_dt[$guid][$key2];  //it is implicit that in this element $key2 is set, otherwise it would have executed the previous if
						else {  //if both key and key2 are present in this element
							$uses_dt[$guid][$key][$key]+=$uses_dt[$guid][$key2][$key2];  //add the frequency of key2 to key1
							foreach($element[$key2] as $context2 => $frequency2) {
								if($context2==$key2) continue;  //the first context is the keyword so we don't consider it
								else {  //if the current context of key2 is present also in key1, we add the values, otherwise we add the current context to key1
									if(isset($uses_dt[$guid][$key][$context2])) $uses_dt[$guid][$key][$context2]+=$uses_dt[$guid][$key2][$context2];
									else $uses_dt[$guid][$key][$context2]=$uses_dt[$guid][$key2][$context2];
								}
							}
						}
						unset($uses_dt[$guid][$key2]);  //delete key2 fron the current document
					}
					unset($keys[$num2]);  //delete key2
				}
			}
		}
	}
		
	file_put_contents($IOdir."uses_dt",serialize($uses_dt));
	if(PHP_OS=="Linux" && posix_getuid()==fileowner($IOdir.'uses_dt')) chmod($IOdir.'uses_dt',0666); //set rw permissions for everybody for this file
	file_put_contents($outputfile,"USES Doc-term matrix created\n\n",FILE_APPEND);
	return "OK";
}

//it processes Tags to create a matrix doc-term for them
function doctermTags($req) {
	global $CONFIG;
	set_time_limit(0);  //this avoids timeouts
	require_once ($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/config.php");
	require_once ($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/classes.php");
	require_once ($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/stemming.php");
	$outputfile=$CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/output.log";
	
	file_put_contents($outputfile,"Starting creating TAGS Doc-term matrix...\n",FILE_APPEND);
	$guids=unserialize(file_get_contents($IOdir."guids"));
	$lr_array=unserialize(file_get_contents($IOdir."lr"));
	
	$tags_dt=array();
	$stop_words=set_stop_words_tags($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/stop_words_eng.txt");   //create an array containing "stop words", in order to eliminate them from the text
	if($req["dt_useold"]=="true" && file_exists($IOdir."old_lr") && file_exists($IOdir."old_tags_dt_raw")) {
		$old_lr=unserialize(file_get_contents($IOdir."old_lr"));
		$old_tags_dt_raw=unserialize(file_get_contents($IOdir."old_tags_dt_raw"));  //we need the raw version of the doc-term matrix, before applying IDF or synonyms
	}
		
	//create an array containing all the tags of each document
	foreach($guids as $guid) {
		if(!empty($lr_array[$guid]->tags)) {
			if($req["dt_useold"]=="true" && isset($old_lr[$guid]->tags) && ($lr_array[$guid]->tags)==($old_lr[$guid]->tags) && isset($old_tags_dt_raw[$guid])) {
				$tags_dt[$guid]=$old_tags_dt_raw[$guid];
				continue;
			}
			
			//find all the tags for the current resource
			$tags=array();
			foreach($lr_array[$guid]->tags as $sentence) {
				if($sentence=="") continue;
				$sentence=strip_punctuation($sentence);  //strip punctuation
				$sentence_clean=str_replace($stop_words," ",$sentence);  //eliminate stop words
				$tags_sentence=explode(" ",$sentence_clean);
				$tags=array_merge($tags,$tags_sentence);
			}
			//stem each tag
			foreach($tags as $num => $element) {
				$tags[$num]=PorterStemmer::Stem(strtolower(trim($element)));  //stem elements
			}
			$tags=array_filter(array_unique($tags));  //delete duplicates and empty elements
				
			//create the entry for the current document in the doc-term tags matrix
			foreach($tags as $tag) {
				$tags_dt[$guid][$tag]=1;
			}
		}
	}
	file_put_contents($IOdir."tags_dt_raw",serialize($tags_dt));   //it saves the raw version of the doc-term matrix
	if(PHP_OS=="Linux") chmod($IOdir."tags_dt_raw",0666); //set rw permissions for everybody for this file
		
	if($enable_synonyms) {
		foreach($keys as $num=>$key) {
			if(!isset($keys[$num])) continue;  //since there is an unset on this array into the foreach, we have to check if the present key is still available or not
			foreach($keys as $num2=>$key2) {
				if(!isset($keys[$num2])) continue;  //since there is an unset on this array into the foreach, we have to check if the present key is still available or not
				if($key!=$key2 && check_synonyms($key,$key2)) {
					file_put_contents($outputfile,"\n$key and $key2 are synonyms\n",FILE_APPEND);
					foreach($tags_dt as $guid=>$element) {
						if(isset($tags_dt[$guid][$key2])) {
							unset($tags_dt[$guid][$key2]);
							$tags_dt[$guid][$key]=1;
						}
					}
					unset($keys[$num2]);
				}
			}
		}
	}
		
	file_put_contents($IOdir."tags_dt",serialize($tags_dt));
	if(PHP_OS=="Linux" && posix_getuid()==fileowner($IOdir.'tags_dt')) chmod($IOdir.'tags_dt',0666); //set rw permissions for everybody for this file
	file_put_contents($outputfile,"TAGS Doc-term matrix created\n\n",FILE_APPEND);
	return "OK";
}

//create an array containing "stop words", in order to eliminate them from the text
function set_stop_words_tags($loc_file) {
	set_time_limit(0);  //this avoids timeouts
	$sw = file_get_contents($loc_file);
	$exc = split("\n", $sw);
	foreach ($exc as $sw) {
		$exc[] = strtoupper($sw);
		$exc[] = strtoupper(substr($sw, 0, 1)) . strtolower(substr($sw, 1));
	}
	foreach($exc as $key => $sw) {
		$exc[$key]=" $sw ";
	}
	$exc[]=",";
	$exc[]=";";
	return $exc;
}

 //Strip punctuation from text
function strip_punctuation($text ) {
	set_time_limit(0);  //this avoids timeouts
	$urlbrackets    = '\[\]\(\)';
	$urlspacebefore = ':;\'_\*%@&?!' . $urlbrackets;
	$urlspaceafter  = '\.,:;\'\-_\*@&\/\\\\\?!#' . $urlbrackets;
	$urlall         = '\.,:;\'\-_\*%@&\/\\\\\?!#' . $urlbrackets;
	
	$specialquotes  = '\'"\*<>';
	
	$fullstop       = '\x{002E}\x{FE52}\x{FF0E}';
	$comma          = '\x{002C}\x{FE50}\x{FF0C}';
	$arabsep        = '\x{066B}\x{066C}';
	$numseparators  = $fullstop . $comma . $arabsep;
	 
	$numbersign     = '\x{0023}\x{FE5F}\x{FF03}';
	$percent        = '\x{066A}\x{0025}\x{066A}\x{FE6A}\x{FF05}\x{2030}\x{2031}';
	$prime          = '\x{2032}\x{2033}\x{2034}\x{2057}';
	$nummodifiers   = $numbersign . $percent . $prime;
	 
	return preg_replace(
		array(
		// Remove separator, control, formatting, surrogate,
		// open/close quotes.
		    '/[\p{Z}\p{Cc}\p{Cf}\p{Cs}\p{Pi}\p{Pf}]/u',
		// Remove other punctuation except special cases
		    '/\p{Po}(?<![' . $specialquotes .
			$numseparators . $urlall . $nummodifiers . '])/u',
		// Remove non-URL open/close brackets, except URL brackets.
		    '/[\p{Ps}\p{Pe}](?<![' . $urlbrackets . '])/u',
		// Remove special quotes, dashes, connectors, number
		// separators, and URL characters followed by a space
		    '/[' . $specialquotes . $numseparators . $urlspaceafter .
			'\p{Pd}\p{Pc}]+((?= )|$)/u',
		// Remove special quotes, connectors, and URL characters
		// preceded by a space
		    '/((?<= )|^)[' . $specialquotes . $urlspacebefore . '\p{Pc}]+/u',
		// Remove dashes preceded by a space, but not followed by a number
		    '/((?<= )|^)\p{Pd}+(?![\p{N}\p{Sc}])/u',
		// Remove consecutive spaces
		    '/ +/',
		),
		' ',
		$text );
}

//this is the doc-term to doc-doc process used by metadata and uses
function toDocDocMetadataUses($docterm,$old_docdoc=array(),$old_docterm=array()){
	global $CONFIG;
	set_time_limit(0);  //this avoids timeouts
	include ($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/config.php");

	$guids=unserialize(file_get_contents($IOdir."guids"));
	$return = array();
	$length=count($guids);
	$z=0;
	foreach($guids as $guid){
		$return[$guid] = array();
		$w=0;
		foreach($guids as $guid_cmp){
			if($z<$w) {   //we work only on some elements, since the doc-doc matrix will be triangular and so we have already the other elements
				if(!isset($docterm[$guid]) || !isset($docterm[$guid_cmp])) $return[$guid][$guid_cmp]=0; //if at least one of the two elements has no metadata/uses, the score between the two elements is 0
				elseif(!empty($old_docdoc) && !empty($old_docterm) && isset($old_docterm[$guid]) && isset($old_docterm[$guid_cmp]) && $docterm[$guid]==$old_docterm[$guid] && $docterm[$guid_cmp]==$old_docterm[$guid_cmp]) {   //if we have chosen not to do a new indexing and both the elements haven't changed since the old version, we can take the value from the old doc-doc matrix, saving so time
					$return[$guid][$guid_cmp]=$old_docdoc[$guid][$guid_cmp];
				}
				else {
					$return[$guid][$guid_cmp]=0; //initialization
					$common_keywords=array_intersect(array_keys($docterm[$guid]),array_keys($docterm[$guid_cmp]));  //it finds the keywords in common for the two documents
					foreach($common_keywords as $keyword){
						$context_add=0;
						$context2_add=0;
						if($context_limit!=0) {
							$common_contexts=array_intersect(array_keys($docterm[$guid][$keyword]),array_keys($docterm[$guid_cmp][$keyword])); //it finds the contexts in common for the current keyword for the two documents
							foreach($common_contexts as $context) {
								if($context==$keyword) continue;  //the first context is the keyword, so we don't consider it
								$context_add+=get_scoreLU_c($context,$docterm[$guid][$keyword]);  //we calculate the weight of the current context for the current keyword in the first document
								$context2_add+=get_scoreLU_c($context,$docterm[$guid_cmp][$keyword]); //we calculate the weight of the current context for the current keyword in the second document
							}
						}
						$return[$guid][$guid_cmp]+=(get_scoreLU($keyword,$docterm[$guid])*(1+$context_add))+(get_scoreLU($keyword,$docterm[$guid_cmp])*(1+$context2_add));  //with this formula we consider both the keywords and the contexts weights
					}
					//now we normalize the value in the range 0-5
					//element : max = x : 5
					//x = (element * 5) / max
					if($context_limit!=0) $max=4;
					else $max=2;
					$return[$guid][$guid_cmp]=($return[$guid][$guid_cmp]*5)/$max;
				}
			}
			elseif($z==$w) $return[$guid][$guid_cmp] = 0;
			$w++;
		}
		$z++;
	}
	return $return;
}

//this is the doc-term to doc-doc process used by tags
function toDocDocTags($docterm,$old_docdoc=array(),$old_docterm=array()){
	global $CONFIG;
	set_time_limit(0);  //this avoids timeouts
	include ($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/config.php");

	$guids=unserialize(file_get_contents($IOdir."guids"));
	$return = array();
	$length=count($guids);
	$z=0;
	foreach($guids as $guid){
		$return[$guid] = array();
		$w=0;
		foreach($guids as $guid_cmp){
			if($z<$w) {   //we work only on some elements, since the doc-doc matrix will be triangular and so we have already the other elements
				if(!isset($docterm[$guid]) || !isset($docterm[$guid_cmp])) $return[$guid][$guid_cmp]=0; //if at least one of the two elements has no tags, the score between the two elements is 0
				elseif(!empty($old_docdoc) && !empty($old_docterm) && isset($old_docterm[$guid]) && isset($old_docterm[$guid_cmp]) && $docterm[$guid]==$old_docterm[$guid] && $docterm[$guid_cmp]==$old_docterm[$guid_cmp]) {   //if we have chosen not to do a new indexing and both the elements haven't changed since the old version, we can take the value from the old doc-doc matrix, saving so time
					$return[$guid][$guid_cmp]=$old_docdoc[$guid][$guid_cmp];
				}
				else {
					$return[$guid][$guid_cmp]=0; //initialization
					$common_tags=array_intersect(array_keys($docterm[$guid]),array_keys($docterm[$guid_cmp]));  //it finds the tags in common for the two documents
					$return[$guid][$guid_cmp]+=(count($common_tags)/count($docterm[$guid]))+(count($common_tags)/count($docterm[$guid_cmp]));
					//now we normalize the value in the range 0-5
					//element : max = x : 5
					//x = (element * 5) / max
					$max=2;
					$return[$guid][$guid_cmp]=($return[$guid][$guid_cmp]*5)/$max;
				}
			}
			elseif($z==$w) $return[$guid][$guid_cmp] = 0;
			$w++;
		}
		$z++;
	}
	return $return;
}
	
 //we calculate the weight of the current keyword in the current document
function get_scoreLU($key,$element) {
	set_time_limit(0);  //this avoids timeouts
	$sum=0;
	foreach($element as $keyword => $keyword_context) {
		$sum+=$keyword_context[$keyword];
	}
	$score=$element[$key][$key]/$sum;
	return $score;
}
	
 //we calculate the weight of the current context for the current keyword in the current document
function get_scoreLU_c($cont,$element) {
	set_time_limit(0);  //this avoids timeouts
	$i=0;
	$sum=0;
	foreach($element as $frequency) {
		if($i!=0) $sum+=$frequency;  //avoid the first element because it is not a context (it is the keyword frequency)
		$i=1;
	}
	$score=$element[$cont]/$sum;
	return $score;
}

//it processes Metadata to create a doc-doc matrix for them
function docdocMetadata($req) {
	global $CONFIG;
	set_time_limit(0);  //this avoids timeouts
	require_once ($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/config.php");
	$outputfile=$CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/output.log";
	
	file_put_contents($outputfile,"Starting creating METADATA doc-doc matrix...\n",FILE_APPEND);
	$metadata_dt=unserialize(file_get_contents($IOdir."metadata_dt"));
	
	$metadata_dd=array();
	if($req["dd_useold"]=="true") {
		if(file_exists($IOdir."old_metadata_dd") && file_exists($IOdir."old_metadata_dt")) {
			file_put_contents($outputfile,"I'm using, where possible, the old METADATA Doc-doc matrix...\n",FILE_APPEND); 
			$old_metadata_dd=unserialize(file_get_contents($IOdir."old_metadata_dd"));
			$old_metadata_dt=unserialize(file_get_contents($IOdir."old_metadata_dt"));
			$metadata_dd=toDocDocMetadataUses($metadata_dt,$old_metadata_dd,$old_metadata_dt);
		}
		else $metadata_dd=toDocDocMetadataUses($metadata_dt);
	}
	else $metadata_dd=toDocDocMetadataUses($metadata_dt);
	
	//right now we have only half of the values (triangular matrix) but, since this must be a symmetric matrix, the other half of the values can be obtained from the present half
	$i=0;
	foreach($metadata_dd as $guid=> $row) {
		$j=0;
		foreach($metadata_dd as $guid2 => $row2) {
			if($i>$j) $metadata_dd[$guid][$guid2]=$metadata_dd[$guid2][$guid];
			$j++;
		}
		$i++;
	}
	krsort($metadata_dd);
	foreach($metadata_dd as $guid => $row) {
		krsort($metadata_dd[$guid]);
	}
		
	file_put_contents($IOdir."metadata_dd",serialize($metadata_dd));
	if(PHP_OS=="Linux") chmod($IOdir."metadata_dd",0666); //set rw permissions for everybody for this file
	file_put_contents($outputfile,"METADATA doc-doc matrix created\n\n",FILE_APPEND);
	return "OK";
}

//it processes Uses to create a doc-doc matrix for them
function docdocUses($req) {
	global $CONFIG;
	set_time_limit(0);  //this avoids timeouts
	require_once ($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/config.php");
	$outputfile=$CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/output.log";
		
	file_put_contents($outputfile,"Starting creating USES doc-doc matrix...\n",FILE_APPEND);
	$uses_dt=unserialize(file_get_contents($IOdir."uses_dt"));
		
	$uses_dd=array();
	if($req["dd_useold"]=="true") {
		if(file_exists($IOdir."old_uses_dd") && file_exists($IOdir."old_uses_dt")) {
			file_put_contents($outputfile,"I'm using, where possible, the old USES Doc-doc matrix...\n",FILE_APPEND); 
			$old_uses_dd=unserialize(file_get_contents($IOdir."old_uses_dd"));
			$old_uses_dt=unserialize(file_get_contents($IOdir."old_uses_dt"));
			$uses_dd=toDocDocMetadataUses($uses_dt,$old_uses_dd,$old_uses_dt);
		}
		else $uses_dd=toDocDocMetadataUses($uses_dt);
	}
	else $uses_dd=toDocDocMetadataUses($uses_dt);
		
	//right now we have only half of the values (triangular matrix) but, since this must be a symmetric matrix, the other half of the values can be obtained from the present half
	$i=0;
	foreach($uses_dd as $guid=> $row) {
		$j=0;
		foreach($uses_dd as $guid2 => $row2) {
			if($i>$j) $uses_dd[$guid][$guid2]=$uses_dd[$guid2][$guid];
			$j++;
		}
		$i++;
	}
	krsort($uses_dd);
	foreach($uses_dd as $guid => $row) {
		krsort($uses_dd[$guid]);
	}
		
	file_put_contents($IOdir."uses_dd",serialize($uses_dd));
	if(PHP_OS=="Linux") chmod($IOdir."uses_dd",0666); //set rw permissions for everybody for this file
	file_put_contents($outputfile,"USES doc-doc matrix created\n\n",FILE_APPEND);
	return "OK";
}

//it processes TAGS to create a doc-doc matrix for them
function docdocTags($req) {
	global $CONFIG;
	set_time_limit(0);  //this avoids timeouts
	require_once ($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/config.php");
	$outputfile=$CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/output.log";
		
	file_put_contents($outputfile,"Starting creating TAGS doc-doc matrix...\n",FILE_APPEND);
	$tags_dt=unserialize(file_get_contents($IOdir."tags_dt"));
	
	$tags_dd=array();
	if($req["dd_useold"]=="true") {
		if(file_exists($IOdir."old_tags_dd") && file_exists($IOdir."old_tags_dt")) {
			file_put_contents($outputfile,"I'm using, where possible, the old TAGS Doc-doc matrix...\n",FILE_APPEND); 
			$old_tags_dd=unserialize(file_get_contents($IOdir."old_tags_dd"));
			$old_tags_dt=unserialize(file_get_contents($IOdir."old_tags_dt"));
			$tags_dd=toDocDocTags($tags_dt,$old_tags_dd,$old_tags_dt);
		}
		else $tags_dd=toDocDocTags($tags_dt);
	}
	else $tags_dd=toDocDocTags($tags_dt);
		
	//right now we have only half of the values (triangular matrix) but, since this must be a symmetric matrix, the other half of the values can be obtained from the present half
	$i=0;
	foreach($tags_dd as $guid=> $row) {
		$j=0;
		foreach($tags_dd as $guid2 => $row2) {
			if($i>$j) $tags_dd[$guid][$guid2]=$tags_dd[$guid2][$guid];
			$j++;
		}
		$i++;
	}
	krsort($tags_dd);
	foreach($tags_dd as $guid => $row) {
		krsort($tags_dd[$guid]);
	}
		
	file_put_contents($IOdir."tags_dd",serialize($tags_dd));
	if(PHP_OS=="Linux") chmod($IOdir."tags_dd",0666); //set rw permissions for everybody for this file
	file_put_contents($outputfile,"TAGS doc-doc matrix created\n\n",FILE_APPEND);
	return "OK";
}

//it processes Repurposing Links to create a doc-doc matrix for them
function docdocReplinks($req) {
	global $CONFIG;
	set_time_limit(0);  //this avoids timeouts
	require_once ($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/config.php");
	require_once ($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/classes.php");
	$outputfile=$CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/output.log";
	
	file_put_contents($outputfile,"Starting creating REPLINKS doc-doc matrix...\n",FILE_APPEND);
	$guids=unserialize(file_get_contents($IOdir."guids"));
	$lr_array=unserialize(file_get_contents($IOdir."lr"));
		
	//replinks matrix initalization
	foreach($lr_array as $key1 => $resource1) {
		foreach($lr_array as $key2 => $resource2) {
			$replinks[$key1][$key2]=0;
		}
	}
		
	//exploit repurposedToRelation function to fill the replinks matrix
	foreach($lr_array as $key1 => $resource1) {
		foreach($lr_array as $key2 => $resource2) {
			$res=null;
			$value=0;
			$res=$resource1->repurposedToRelation($res,$resource2,$lr_array);
			if(!is_null($res)) {
				foreach($res as $i => $v) {
					if($v==1) $res[$i]=3;
					elseif($v==2) $res[$i]=2;
					elseif($v==3) $res[$i]=1;
					else $res[$i]=0;
					$value+=$res[$i];
				}
			}
			$replinks[$key1][$key2]=$value;
		}
	}
		
	//exploit repurposedFromRelation function to fill the replinks matrix
	foreach($lr_array as $key1 => $resource1) {
		foreach($lr_array as $key2 => $resource2) {
			$res=null;
			$value=0;
			$res=$resource1->repurposedFromRelation($res,$resource2,$lr_array);
			if(!is_null($res)) {
				foreach($res as $i => $v) {
					if($v==1) $res[$i]=3;
					elseif($v==2) $res[$i]=2;
					elseif($v==3) $res[$i]=1;
					else $res[$i]=0;
					$value+=$res[$i];
				}
			}
			$replinks[$key1][$key2]+=$value;
			if($resource1->isBrother($resource2)) $replinks[$key1][$key2]+=2;   //add 2 if brothers
		}
	}
	krsort($replinks);
	foreach($replinks as $guid => $row) {
		krsort($replinks[$guid]);
	}
		
	file_put_contents($IOdir."replinks_dd",serialize($replinks));
	if(PHP_OS=="Linux" && posix_getuid()==fileowner($IOdir.'replinks_dd')) chmod($IOdir.'replinks_dd',0666); //set rw permissions for everybody for this file
	file_put_contents($outputfile,"REPLINKS doc-doc matrix created\n\n",FILE_APPEND);
	file_put_contents($outputfile,"Doc-doc matrices created\n",FILE_APPEND);
	file_put_contents($outputfile,"Indexing finished\n\n",FILE_APPEND);
	return "OK";
}

//it adds positive features to clusters
function get_positive_features($array_clusters,$m_dt,$type) {
	global $CONFIG;
	set_time_limit(0);  //this avoids timeouts
	include_once ($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/classes.php");
	$num_total_docs = count($m_dt);

	$params = array(
		'n_min_docs' => 0.01 * $num_total_docs 
		,'n_med_docs' => 0.05 * $num_total_docs
		,'n_max_docs' => 0.50 * $num_total_docs
		,'p_min' => 0.1
		,'p_max' => 0.6
	);
	
	foreach($array_clusters as $num=>$cluster) {
		$array_clusters[$num]->insertPositiveFeatures($m_dt,$params);
	}
	return ($array_clusters);
}

//classification
function classify($req) {
	global $CONFIG;
	set_time_limit(0);  //this avoids timeouts
	require_once ($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/config.php");
	require_once ($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/classes.php");
	$outputfile=$CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/output.log";
	
	file_put_contents($outputfile,"Starting classification...\n",FILE_APPEND);
	
	$classification_methods=array("metadata" => $classification_method_metadata ,"uses" => $classification_method_uses ,"tags" => $classification_method_tags,"replinks" => $classification_method_replinks);
	foreach($classification_methods as $type => $value) {
		file_put_contents($outputfile,"Creating clusters for $type...\n",FILE_APPEND);
		if($value==1){  //if classification_method is Kohonen
			require_once $CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/kohonen.php";
			$array_clusters[$type]=run_kohonen($type,$req["cl_useold"]);
		}
		elseif($value==2) { //if classification_method is aggregative
			require_once $CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/aggregative.php";
			$array_clusters[$type] = clusterize_aggregative($type,$req["cl_useold"]);
		}
		else { //if classification_method is 3 (YACA)
			require_once $CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/yaca.php";
			$array_clusters[$type] = clusterize_yaca($type,$req["cl_useold"]);
		}
		
		//add positive features to clusters
		if($type!="replinks") {  //there are not positive features for replinks
			file_put_contents($outputfile,"Calculating positive features for $type...\n",FILE_APPEND);
			$dt_matrix=unserialize(file_get_contents($IOdir.$type."_dt"));
			$array_clusters[$type] = get_positive_features($array_clusters[$type], $dt_matrix, $type);
		}
	
		file_put_contents($IOdir."clusters_".$type,serialize($array_clusters[$type]));
		if(PHP_OS=="Linux" && posix_getuid()==fileowner($IOdir."clusters_".$type)) chmod($IOdir."clusters_".$type,0666); //set rw permissions for everybody for this file
		file_put_contents($outputfile,"Clusters for $type created\n\n",FILE_APPEND);
	}	
	return "OK";
}

//it creates associations among clusters
function associateClusters($req) {
	global $CONFIG;
	set_time_limit(0);  //this avoids timeouts
	require_once ($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/config.php");
	require_once ($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/classes.php");
	$outputfile=$CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/output.log";
	
	file_put_contents($outputfile,"Starting association clusters (this might require some time)...\n",FILE_APPEND);
	$clusters_metadata=unserialize(file_get_contents($IOdir."clusters_metadata"));
	$clusters_uses=unserialize(file_get_contents($IOdir."clusters_uses"));
	$clusters_tags=unserialize(file_get_contents($IOdir."clusters_tags"));
	$clusters_replinks=unserialize(file_get_contents($IOdir."clusters_replinks"));
	
	reset_association($clusters_metadata);
	reset_association($clusters_uses);
	reset_association($clusters_tags);
	reset_association($clusters_replinks);
	
	//associate metadata clusters to others
	associate($clusters_metadata,$clusters_metadata,"metadata",$minimum_association_threshold);
	associate($clusters_metadata,$clusters_uses,"uses",$minimum_association_threshold);
	associate($clusters_metadata,$clusters_tags,"tags",$minimum_association_threshold);
	associate($clusters_metadata,$clusters_replinks,"replinks",$minimum_association_threshold);
	associate($clusters_uses,$clusters_metadata,"metadata",$minimum_association_threshold);
	associate($clusters_uses,$clusters_uses,"uses",$minimum_association_threshold);
	associate($clusters_uses,$clusters_tags,"tags",$minimum_association_threshold);
	associate($clusters_uses,$clusters_replinks,"replinks",$minimum_association_threshold);
	associate($clusters_tags,$clusters_metadata,"metadata",$minimum_association_threshold);
	associate($clusters_tags,$clusters_uses,"uses",$minimum_association_threshold);
	associate($clusters_tags,$clusters_tags,"tags",$minimum_association_threshold);
	associate($clusters_tags,$clusters_replinks,"replinks",$minimum_association_threshold);
	associate($clusters_replinks,$clusters_metadata,"metadata",$minimum_association_threshold);
	associate($clusters_replinks,$clusters_uses,"uses",$minimum_association_threshold);
	associate($clusters_replinks,$clusters_tags,"tags",$minimum_association_threshold);
	associate($clusters_replinks,$clusters_replinks,"replinks",$minimum_association_threshold);
	
	file_put_contents($IOdir.'clusters_metadata',serialize($clusters_metadata));
	if(PHP_OS=="Linux" && posix_getuid()==fileowner($IOdir.'clusters_metadata')) chmod($IOdir.'clusters_metadata',0666); //set rw permissions for everybody for this file
	file_put_contents($IOdir.'clusters_uses',serialize($clusters_uses));
	if(PHP_OS=="Linux" && posix_getuid()==fileowner($IOdir.'clusters_uses')) chmod($IOdir.'clusters_uses',0666); //set rw permissions for everybody for this file
	file_put_contents($IOdir.'clusters_tags',serialize($clusters_tags));
	if(PHP_OS=="Linux" && posix_getuid()==fileowner($IOdir.'clusters_tags')) chmod($IOdir.'clusters_tags',0666); //set rw permissions for everybody for this file
	file_put_contents($IOdir.'clusters_replinks',serialize($clusters_replinks));
	if(PHP_OS=="Linux" && posix_getuid()==fileowner($IOdir.'clusters_replinks')) chmod($IOdir.'clusters_replinks',0666); //set rw permissions for everybody for this file
	file_put_contents($outputfile,"\nClusters associated\n",FILE_APPEND);
	file_put_contents($outputfile,"\nClassification finished\n\n",FILE_APPEND);
	return "OK";
}

function reset_association($clusters) {
	global $CONFIG;
	set_time_limit(0);  //this avoids timeouts
	include_once ($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/classes.php");
	
	foreach ($clusters as $cluster) {
		$cluster->associatedTo["metadata"]="";
		$cluster->associatedTo["uses"]="";
		$cluster->associatedTo["tags"]="";
		$cluster->associatedTo["replinks"]="";
	}
}

function associate($clusters_1,$clusters_2,$type,$threshold) {
	global $CONFIG;
	set_time_limit(0);  //this avoids timeouts
	include_once ($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/classes.php");
	
	foreach ($clusters_1 as $cluster1) {
		$max = count($cluster1->array_docs)*$threshold;
		foreach ($clusters_2 as $cluster2) {
			if($cluster1!==$cluster2) {
				$num=make_association($cluster1, $cluster2);
				if ($max<$num) {
					$max = $num;
					$cluster1->associatedTo[$type]=$cluster2->id;
				}
			}
		}
	}
}

//function that compares two documents
function compare_cluster_element($a, $b) {
	set_time_limit(0);  //this avoids timeouts
	
	if(is_array($a)) {
		if(is_array($b)) return strcmp($a['guid'], $b['guid']);
		else  return strcmp($a['guid'], $b);
	}
	else {
		if(is_array($b)) return strcmp($a, $b['guid']);
		else  return strcmp($a, $b);
	}
}

function make_association($cluster1,$cluster2){
	global $CONFIG;
	set_time_limit(0);  //this avoids timeouts
	include_once ($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/classes.php");
	
	$cmp = array_uintersect($cluster1->array_docs,$cluster2->array_docs,"compare_cluster_element");
	return count($cmp);
}

//it creates the RDF version of the clusters files, still WORK IN PROGRESS, RDF will substitute database and files
function create_clusters_rdf($req) {
	global $CONFIG;
	set_time_limit(0);  //this avoids timeouts
	require_once ($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/config.php");
	require_once ($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/classes.php");
	$outputfile=$CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/output.log";
	
	file_put_contents($outputfile,"Starting creating RDF files for clusters...\n",FILE_APPEND);
	$clusters_metadata=unserialize(file_get_contents($IOdir."clusters_metadata"));
	$clusters_uses=unserialize(file_get_contents($IOdir."clusters_uses"));
	$clusters_tags=unserialize(file_get_contents($IOdir."clusters_tags"));
	$clusters_replinks=unserialize(file_get_contents($IOdir."clusters_replinks"));
	create_cluster_rdf($clusters_metadata);
	file_put_contents($outputfile,"RDF for METADATA clusters created...\n",FILE_APPEND);
	create_cluster_rdf($clusters_uses);
	file_put_contents($outputfile,"RDF for USES clusters created...\n",FILE_APPEND);
	create_cluster_rdf($clusters_tags);
	file_put_contents($outputfile,"RDF for TAGS clusters created...\n",FILE_APPEND);
	create_cluster_rdf($clusters_replinks);
	file_put_contents($outputfile,"RDF for REPLINKS clusters created...\n",FILE_APPEND);
	file_put_contents($outputfile,"RDF files created for all clusters...\n\n",FILE_APPEND);
	return "OK";
}

//called by the function create_clusters_rdf
//right now it does nothing
function create_cluster_rdf($array_clusters) {
	set_time_limit(0);  //this avoids timeouts
}

//delete old unneeded files and reset the file "changes"
function reset_changes($req) {
	global $CONFIG;
	set_time_limit(0);  //this avoids timeouts
	require_once ($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/config.php");
	require_once ($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/classes.php");
	$outputfile=$CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/output.log";
	
	file_put_contents($outputfile,"Reset changes file and clean up...\n",FILE_APPEND);
	
	//reset the file "changes", but if during the process some resources have been changed (the new file "changes" is different from the one used at the beginning of the process), we have to consider it
	if(file_exists($IOdir."old_changes")) $old_changes=unserialize(file_get_contents($IOdir."old_changes"));
	else $old_changes=array();
	if(file_exists($IOdir."changes")) {
		if(!empty($old_changes)) {
			$new_changes=unserialize(file_get_contents($IOdir."changes"));
			$changes=array();
			$changes["new_indexing_required"]=0;
			$changes["new"]=array();
			$changes["new"]=array_diff($new_changes["new"],$old_changes["new"]);
			$changes["edited"]["metadata"]=array();
			$changes["edited"]["metadata"]=array_diff($new_changes["edited"]["metadata"],$old_changes["edited"]["metadata"]);
			$changes["edited"]["uses"]=array();
			$changes["edited"]["uses"]=array_diff($new_changes["edited"]["uses"],$old_changes["edited"]["uses"]);
			$changes["edited"]["tags"]=array();
			$changes["edited"]["tags"]=array_diff($new_changes["edited"]["tags"],$old_changes["edited"]["tags"]);
			file_put_contents($IOdir."changes",serialize($changes));
			if(PHP_OS=="Linux" && posix_getuid()==fileowner($IOdir.'changes')) chmod($IOdir.'changes',0666); //set rw permissions for everybody for this file
		}
		//if before the process no file "changes" was present, no need to do anything
	}
	else {  //if file "changes" doesn't exist, create it
		$changes=array();
		$changes["new_indexing_required"]=0;
		$changes["new"]=array();
		$changes["edited"]["metadata"]=array();
		$changes["edited"]["uses"]=array();
		$changes["edited"]["tags"]=array();
		file_put_contents($IOdir."changes",serialize($changes));
		if(PHP_OS=="Linux" && posix_getuid()==fileowner($IOdir.'changes')) chmod($IOdir.'changes',0666); //set rw permissions for everybody for this file
	}
	
	//delete old unneeded files
	if(file_exists($IOdir."old_lr")) unlink($IOdir."old_lr");
	if(file_exists($IOdir."old_metadata_dt")) unlink($IOdir."old_metadata_dt");
	if(file_exists($IOdir."old_uses_dt")) unlink($IOdir."old_uses_dt");
	if(file_exists($IOdir."old_tags_dt")) unlink($IOdir."old_tags_dt");
	if(file_exists($IOdir."old_metadata_dt_raw")) unlink($IOdir."old_metadata_dt_raw");
	if(file_exists($IOdir."old_uses_dt_raw")) unlink($IOdir."old_uses_dt_raw");
	if(file_exists($IOdir."old_tags_dt_raw")) unlink($IOdir."old_tags_dt_raw");
	if(file_exists($IOdir."old_metadata_dd")) unlink($IOdir."old_metadata_dd");
	if(file_exists($IOdir."old_uses_dd")) unlink($IOdir."old_uses_dd");
	if(file_exists($IOdir."old_tags_dd")) unlink($IOdir."old_tags_dd");
	if(file_exists($IOdir."old_replinks_dd")) unlink($IOdir."old_replinks_dd");
	if(file_exists($IOdir."old_clusters_metadata")) unlink($IOdir."old_clusters_metadata");
	if(file_exists($IOdir."old_clusters_uses")) unlink($IOdir."old_clusters_uses");
	if(file_exists($IOdir."old_clusters_tags")) unlink($IOdir."old_clusters_tags");
	if(file_exists($IOdir."old_clusters_replinks")) unlink($IOdir."old_clusters_replinks");
	if(file_exists($IOdir."old_changes")) unlink($IOdir."old_changes");
	
	file_put_contents($outputfile,"Done!\n\n",FILE_APPEND);
	return "OK";
}

//it saves the main configuration settings in the file config.php
function apply_mainconffields($req) {
	global $CONFIG;
	set_time_limit(0);  //this avoids timeouts
	
	$config_values=processFileForOptions($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/config.php");    //it reads an option from the config file (it creates an array with form array["option"]=value)
	$config_values=substituteOption($config_values,"IOdir",$req['text_iodir']);   //it substitutes the IOdir new path in the array
	$config_values=substituteOption($config_values,"dd_crosscheck",$req['crosscheck_chkbx']); 
	if(isset($req["text_oldinterface"])) $config_values=substituteOption($config_values,"IndexingClassificationPath",$req['text_oldinterface']);   //it substitutes the old interface path in the array
	else $config_values=substituteOption($config_values,"IndexingClassificationPath","undefined");
	write_config_values($config_values,$CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/config.php");  //it saves the options from the array to the config file
	
	//it saves all the changes in the config file of the old command-line interface
	if(isset($req["text_oldinterface"]) && file_exists($req["text_oldinterface"]."config.php")) {
		$config_values=array();
		$config_values=processFileForOptions($req["text_oldinterface"]."config.php");    //it reads an option from the config file (it creates an array with form array["option"]=value)
		$config_values=substituteOption($config_values,"IOdir",$req['text_iodir']);   //it substitutes the IOdir new path in the array
		$config_values=substituteOption($config_values,"IndexingClassificationPath",$req['text_oldinterface']);   //it substitutes the old interface path in the array
		write_config_values($config_values,$req["text_oldinterface"]."config.php");  //it saves the options from the array to the config file
	}
	return true;
}

//it gets all the fields from the selected source, so you can choose the ones you want to use for classification
function getfields($req) {
	global $CONFIG;
	set_time_limit(0);  //this avoids timeouts
	include($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/config.php");
	include($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/classes.php");
	
	if($req["datasource"]==$data_source) $suggest=1;
	else $suggest=0;
	
	if($req["datasource"]==1) {  //if we chose RDF as input
		include_once($CONFIG->path."mod/mmsearch/custom/MeducatorParser.php");
		include_once($CONFIG->path."mod/mmsearch/custom/MeducatorMetadata.php");
		include_once($CONFIG->path."engine/lib/mmplus.php");
		//$address = $CONFIG->API_URL . "eidsearch?id=" . "8163";
		//$address =$CONFIG->API_URL . "searchall?properties=mdc:creator";
		$address =$CONFIG->API_URL . "propertysearch?property=mdc:title&value=";
		$rdf_info = connectToSesame($address);
		$medParser = new MeducatorParser($rdf_info, true);
		$elements=$medParser->results;
		$values=array();
		foreach($elements as $element) {
			$values=array_merge($values,array_keys($element));
		}
		$values=array_unique($values);
		foreach($values as $value) {
			$fields[]= array("name" => trim($value), "metadata" => 0, "uses" => 0, "tags" => 0) ;
		}
	}
	elseif($req["datasource"]==2) {  //if we chose Elgg database as input
		$query = "SELECT distinct e.* from elggentities e join elggusers_entity u on e.guid = u.guid JOIN (SELECT subm1.*, s1.string FROM elggmetadata subm1 JOIN elggmetastrings s1 ON subm1.value_id = s1.id) AS m1 ON e.guid = m1.entity_guid where ((m1.name_id='440' AND m1.string IN ('356'))) and ( (1 = 1) and e.enabled='yes') and ( (1 = 1) and m1.enabled='yes')";
		$entities = get_data($query, "entity_row_to_elggstar");
		$entity=$entities[0];
		$fields=array();
		
		$categorized_fields = profile_manager_get_categorized_fields($entity, true);
		$cats = $categorized_fields['categories'];
		$db_fields = $categorized_fields['fields'];
		if(!empty($db_fields)){
			foreach($cats as $cat_guid => $cat){
				foreach($db_fields[$cat_guid] as $db_field){
					$metadata_name = $db_field->metadata_name;
					// get options
					$options = $db_field->getOptions();
					
					
					// get type of field
					if($db_field->user_editable == "no"){
						$valtype = "non_editable";
					} else {
						$valtype = $db_field->metadata_type;	
					}
					
					$fields[]= array("name" => trim($db_field->getTitle()), "metadata" => 0, "uses" => 0, "tags" => 0) ;
				}
			}
		}
	}
	
	if($suggest) {  //if we decided to suggest values, suggest the values of the previous configuration
		include_once($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/config.php");
		$fields_metadata=explode(";",$metadata_fields);
		$fields_uses=explode(";",$uses_fields);
		$fields_tags=explode(";",$tags_fields);
		$i=0;
		foreach($fields as $field) {
			if(in_array($field["name"],$fields_metadata)) $fields[$i]["metadata"]=1;
			if(in_array($field["name"],$fields_uses)) $fields[$i]["uses"]=1;
			if(in_array($field["name"],$fields_tags)) $fields[$i]["tags"]=1;
			$i++;
		}
	}
	return $fields;
}

//it saves the indexing and classification configuration settings in the file config.php
function apply_icconffields($req) {
	global $CONFIG;
	set_time_limit(0);  //this avoids timeouts
	
	include($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/config.php");
	$new_indexing_req=0;
	$fields_metadata_string=array2string(json_decode($req["fields_metadata"]));
	$fields_uses_string=array2string(json_decode($req["fields_uses"]));
	$fields_tags_string=array2string(json_decode($req["fields_tags"]));
	$classification_methods=array("Kohonen" => 1, "Aggregative" => 2, "YACA" => 3);
	$config_values=processFileForOptions($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/config.php");    //it reads an option from the config file (it creates an array with form array["option"]=value)
	
	if($req["datasource_select"]=="sesame") $config_values=substituteOption($config_values,"data_source",1);
	elseif($req["datasource_select"]=="elggdb") $config_values=substituteOption($config_values,"data_source",2);
	if(substr($config_values["metadata_fields"],1,-1)!=$fields_metadata_string) {
		$config_values=substituteOption($config_values,"metadata_fields",$fields_metadata_string);
		$new_indexing_req=1;
	}
	if(substr($config_values["uses_fields"],1,-1)!=$fields_uses_string) {
		$config_values=substituteOption($config_values,"uses_fields",$fields_uses_string);
		$new_indexing_req=1;
	}
	if(substr($config_values["tags_fields"],1,-1)!=$fields_tags_string) {
		$config_values=substituteOption($config_values,"tags_fields",$fields_tags_string);
		$new_indexing_req=1;
	}
	$config_values=substituteOption($config_values,"keywords_limit",$req["text_numkeywords"]);
	$config_values=substituteOption($config_values,"context_limit",$req["text_numcontexts"]);
	$config_values=substituteOption($config_values,"width_sliding_window",$req["text_slidingwindow_width"]);
	if($req["idf_chkbx"]=="true") $config_values=substituteOption($config_values,"enable_idf",1);
	else $config_values=substituteOption($config_values,"enable_idf",0);
	if($req["synonyms_chkbx"]=="true") $config_values=substituteOption($config_values,"enable_synonyms",1);
	else $config_values=substituteOption($config_values,"enable_synonyms",0);
	if(isset($req["text_syn_db_host"])) $config_values=substituteOption($config_values,"syn_db_host",$req["text_syn_db_host"]);
	if(isset($req["text_syn_db_user"])) $config_values=substituteOption($config_values,"syn_db_user",$req["text_syn_db_user"]);
	if(isset($req["text_syn_db_pass"])) $config_values=substituteOption($config_values,"syn_db_pass",$req["text_syn_db_pass"]);
	if(isset($req["text_syn_db_name"])) $config_values=substituteOption($config_values,"syn_db_name",$req["text_syn_db_name"]);
	$config_values=substituteOption($config_values,"classification_method_metadata",$classification_methods[$req["metadata_select"]]);
	$config_values=substituteOption($config_values,"classification_method_tags",$classification_methods[$req["tags_select"]]);
	$config_values=substituteOption($config_values,"classification_method_uses",$classification_methods[$req["uses_select"]]);
	$config_values=substituteOption($config_values,"classification_method_replinks",$classification_methods[$req["replinks_select"]]);
	if(isset($req["text_kohonen_output"])) $config_values=substituteOption($config_values,"output_file_kohonen",$req["text_kohonen_output"]);
	if(isset($req["text_yaca_threshold"])) $config_values=substituteOption($config_values,"YACA_threshold",$req["text_yaca_threshold"]);
	if(isset($req["text_min_threshold"])) $config_values=substituteOption($config_values,"minimum_association_threshold",$req["text_min_threshold"]);
	
	write_config_values($config_values,$CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/config.php");  //it saves the options from the array to the config file
	if($new_indexing_req==1) {
		$changes=unserialize(file_get_contents($IOdir."changes"));  //if the file doesn't exist, it doesn't matter
		$changes["new_indexing_required"]=1;  //mark the file new_indexing in order to force a new indexing
		file_put_contents($IOdir."changes",serialize($changes));
	}
	
	//it saves all the changes in the config file of the old command-line interface
	if($config_values["IndexingClassificationPath"]!="undefined" && file_exists(substr($config_values["IndexingClassificationPath"],1,-1) . "config.php")) {
		$IndexingClassificationPath=substr($config_values["IndexingClassificationPath"],1,-1);
		$config_values=array();
		$config_values=processFileForOptions($IndexingClassificationPath."config.php");    //it reads an option from the config file (it creates an array with form array["option"]=value)
		if($req["datasource_select"]=="sesame") $config_values=substituteOption($config_values,"data_source",1);
		elseif($req["datasource_select"]=="elggdb") $config_values=substituteOption($config_values,"data_source",2);
		if(substr($config_values["metadata_fields"],1,-1)!=$fields_metadata_string) {
			$config_values=substituteOption($config_values,"metadata_fields",$fields_metadata_string);
			$new_indexing_req=1;
		}
		if(substr($config_values["uses_fields"],1,-1)!=$fields_uses_string) {
			$config_values=substituteOption($config_values,"uses_fields",$fields_uses_string);
			$new_indexing_req=1;
		}
		if(substr($config_values["tags_fields"],1,-1)!=$fields_tags_string) {
			$config_values=substituteOption($config_values,"tags_fields",$fields_tags_string);
			$new_indexing_req=1;
		}
		$config_values=substituteOption($config_values,"keywords_limit",$req["text_numkeywords"]);
		$config_values=substituteOption($config_values,"context_limit",$req["text_numcontexts"]);
		$config_values=substituteOption($config_values,"width_sliding_window",$req["text_slidingwindow_width"]);
		if($req["idf_chkbx"]=="true") $config_values=substituteOption($config_values,"enable_idf",1);
		else $config_values=substituteOption($config_values,"enable_idf",0);
		if($req["synonyms_chkbx"]=="true") $config_values=substituteOption($config_values,"enable_synonyms",1);
		else $config_values=substituteOption($config_values,"enable_synonyms",0);
		if(isset($req["text_syn_db_host"])) $config_values=substituteOption($config_values,"syn_db_host",$req["text_syn_db_host"]);
		if(isset($req["text_syn_db_user"])) $config_values=substituteOption($config_values,"syn_db_user",$req["text_syn_db_user"]);
		if(isset($req["text_syn_db_pass"])) $config_values=substituteOption($config_values,"syn_db_pass",$req["text_syn_db_pass"]);
		if(isset($req["text_syn_db_name"])) $config_values=substituteOption($config_values,"syn_db_name",$req["text_syn_db_name"]);
		$config_values=substituteOption($config_values,"classification_method_metadata",$classification_methods[$req["metadata_select"]]);
		$config_values=substituteOption($config_values,"classification_method_tags",$classification_methods[$req["tags_select"]]);
		$config_values=substituteOption($config_values,"classification_method_uses",$classification_methods[$req["uses_select"]]);
		$config_values=substituteOption($config_values,"classification_method_replinks",$classification_methods[$req["replinks_select"]]);
		if(isset($req["text_kohonen_output"])) $config_values=substituteOption($config_values,"output_file_kohonen",$req["text_kohonen_output"]);
		if(isset($req["text_yaca_threshold"])) $config_values=substituteOption($config_values,"YACA_threshold",$req["text_yaca_threshold"]);
		if(isset($req["text_min_threshold"])) $config_values=substituteOption($config_values,"minimum_association_threshold",$req["text_min_threshold"]);
		
		write_config_values($config_values,$IndexingClassificationPath. "config.php");  //it saves the options from the array to the config file
	}
	
	return true;
}

function array2string($arr) {
	set_time_limit(0);  //this avoids timeouts
	foreach($arr as $value) {
		$value=trim($value);
		if($value!="") $str.="$value;";
	}
	$str=substr($str,0,-1);
	return $str;
}

function processFileForOptions($file) {    //it reads an option from the file specified (it creates an array with form array["option"]=value)
	set_time_limit(0);  //this avoids timeouts
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
	if(is_numeric($newvalue) || $newvalue=="true" || $newvalue=="false") $config_values[$option]=$newvalue;
	else $config_values[$option]="\"$newvalue\"";
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
	include($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/config.php");
	include($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/classes.php");
	$clusters_metadata = unserialize(file_get_contents($IOdir . 'clusters_metadata'));
	$clusters_uses = unserialize(file_get_contents($IOdir . 'clusters_uses'));
	$clusters_tags = unserialize(file_get_contents($IOdir . 'clusters_tags'));
	$clusters_replinks = unserialize(file_get_contents($IOdir . 'clusters_replinks'));
	
	$q = strtolower($req["q"]); 

	$response = array();
	
	$clusters = array($clusters_metadata, $clusters_uses, $clusters_tags, $clusters_replinks);
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
	include($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/config.php");
	include($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/classes.php");
	$clusters_metadata = unserialize(file_get_contents($IOdir . 'clusters_metadata'));
	$clusters_uses = unserialize(file_get_contents($IOdir . 'clusters_uses'));
	$clusters_tags = unserialize(file_get_contents($IOdir . 'clusters_tags'));
	$clusters_replinks = unserialize(file_get_contents($IOdir . 'clusters_replinks'));
	
	$q = strtolower($req["q"]); 

	$response = array();
	
	$clusters = array($clusters_metadata, $clusters_uses, $clusters_tags, $clusters_replinks);
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
	include($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/config.php");
	include($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/classes.php");
	$clusters_metadata = unserialize(file_get_contents($IOdir . 'clusters_metadata'));
	$clusters_uses = unserialize(file_get_contents($IOdir . 'clusters_uses'));
	$clusters_tags = unserialize(file_get_contents($IOdir . 'clusters_tags'));
	$clusters_replinks = unserialize(file_get_contents($IOdir . 'clusters_replinks'));

	$q = strtolower($req["q"]); 

	$response = array();
	
	$clusters = array($clusters_metadata, $clusters_uses, $clusters_tags, $clusters_replinks);
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

//it shows the number of edited and new resources
function get_statistics() {
	global $CONFIG;
	include($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/config.php");
	$changes=unserialize(file_get_contents($IOdir."changes"));  //if the file doesn't exist, it doesn't matter
	$data["new_elements"]=count($changes["new"]);
	$data["edited_elements"]=count_distinct_elements($changes["edited"]);
	return $data;
}

function count_distinct_elements($vect) {
	$v=array();
	foreach($vect as $group) {
		foreach($group as $elem) {
			if(!in_array($elem,$v)) $v[]=$elem;
		}
	}
	return count($v);
}


?>