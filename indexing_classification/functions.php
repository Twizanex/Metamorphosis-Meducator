<?php

//compare the numbers returning them in decreasing order
function cmp_keys($a,$b) {
	foreach($a as $value1) {
		foreach($b as $value2) {
			if($value1 < $value2) return 1;
			elseif($value1==$value2) return 0;
			else return -1;
		}
	}
}

function get_values($vector,$field) {
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

//get an up-to-date snapshot of data from MetaMorphosis Sesame RDF or database
function get_snapshot() {
	global $IOdir;
	global $data_source;
	global $metadatas_fields,$usess_fields,$tagss_fields;
	
	//if we chose to use the Sesame RDF
	if($data_source==1) {
		global $CONFIG;
		$address_base =$CONFIG->API_URL . "searchall?properties=";
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
			echo "\n\nNo resources returned. There must be an error somewhere...";
			exit();
		}
	}
	//if we chose to use the Elgg database
	elseif($data_source==2) {
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
	file_put_contents($IOdir.'guids',serialize($guids));   //it saves resources in the file 'lr'
	if(PHP_OS=="Linux" && getmyuid()==fileowner($IOdir.'guids')) chmod($IOdir.'guids',0666); //set rw permissions for everybody for this file
	
	file_put_contents($IOdir.'lr',serialize($lr));   //it saves resources in the file 'lr'
	if(PHP_OS=="Linux" && getmyuid()==fileowner($IOdir.'lr')) chmod($IOdir.'lr',0666); //set rw permissions for everybody for this file
	return $lr;
}


function set_stop_words($loc_file) {
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
	require_once 'stemming.php';
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
	global $keywords_limit;
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
	global $context_limit;
	
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
	global $width_sliding_window,$IndexingClassificationPath, $context_limit, $IOdir;
	
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

function check_synonyms($w0, $w1) {
	require_once 'stemming.php';
	global $syn_db_wnet;
	if($w0==$w1) return true;
	$sql = "select distinct w2.lemma from senses as s1 
			left join words as w2 on w2.wordid=s1.wordid
			join senses as s2 on  s1.synsetid = s2.synsetid
			right JOIN words as w1 on w1.wordid=s2.wordid
			where 
			w1.lemma LIKE '$w0%'
			and w2.lemma NOT LIKE '$w0%'";
	$res = mysql_query($sql,$syn_db_wnet);
	$row = array();
	while($row = @mysql_fetch_assoc($res)){
		$w_new = PorterStemmer::Stem($row['lemma']);
		if($w1 == $w_new) return true;
	}
	return false;
}

//put all the docterm matrices together
function join_pieces($pieces) {
	global $IOdir,$keywords_limit,$context_limit;
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
function doctermMetadata($lr_array) {
		global $IOdir,$enable_synonyms, $enable_idf, $dt_new_indexing_required,$guids,$IndexingClassificationPath;
		
		echo 'Starting creating METADATA Doc-term matrix...' . "\n";
		$metadata_dt = array ();
		$stop_words=set_stop_words($IndexingClassificationPath . 'stop_words_eng.txt');
		if($dt_new_indexing_required==0 && file_exists($IOdir."old_lr") && file_exists($IOdir."old_metadata_dt_raw")) {
			$old_lr=unserialize(file_get_contents($IOdir."old_lr"));
			$old_metadata_dt_raw=unserialize(file_get_contents($IOdir."old_metadata_dt_raw"));
		}
		foreach($guids as $guid) {
			if(!empty($lr_array[$guid]->metadata)) {
				//we use old values where possible
				if($dt_new_indexing_required==0 && $enable_idf==0 && isset($old_lr[$guid]->metadata) && ($lr_array[$guid]->metadata)==($old_lr[$guid]->metadata)  && isset($old_metadata_dt_raw[$guid])) {
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
		file_put_contents($IOdir."metadata_dt_raw",serialize($metadata_dt));
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
						echo "\n$key and $key2 are synonyms\n";
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
		
		echo 'METADATA Doc-term matrix created' . "\n";
		return $metadata_dt;
}

//it processes Use Cases to create a matrix doc-term for them
function doctermUses($lr_array) {
		global $IOdir,$enable_synonyms, $enable_idf, $dt_new_indexing_required,$guids,$IndexingClassificationPath;
		
		echo 'Starting creating USES Doc-term matrix...' . "\n";
		$uses_dt = array ();
		$stop_words=set_stop_words($IndexingClassificationPath . 'stop_words_eng.txt');
		if($dt_new_indexing_required==0 && file_exists($IOdir."old_lr") && file_exists($IOdir."old_uses_dt_raw")) {
			$old_lr=unserialize(file_get_contents($IOdir."old_lr"));
			$old_uses_dt_raw=unserialize(file_get_contents($IOdir."old_uses_dt_raw"));
		}	
		foreach ($guids as $guid) {
			if(!empty($lr_array[$guid]->uses)) {
				if($dt_new_indexing_required==0 && isset($old_lr[$guid]->uses) && ($lr_array[$guid]->uses)==($old_lr[$guid]->uses)  && isset($old_uses_dt_raw[$guid])) {
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
		file_put_contents($IOdir."uses_dt_raw",serialize($uses_dt));
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
						echo "\n$key and $key2 are synonyms\n";
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
		
		echo 'USES Doc-term matrix created' . "\n";
		return $uses_dt;
}
	
//it processes Tags to create a matrix doc-term for them
function doctermTags($lr_array) {
		require_once 'stemming.php';
		global $IOdir, $enable_synonyms,$IndexingClassificationPath, $dt_new_indexing_required,$guids;
		echo 'Starting creating TAGS Doc-term matrix...' . "\n";
		
		$tags_dt=array();
		$stop_words=set_stop_words_tags($IndexingClassificationPath."stop_words_eng.txt");   //create an array containing "stop words", in order to eliminate them from the text
		if($dt_new_indexing_required==0 && file_exists($IOdir."old_lr") && file_exists($IOdir."old_tags_dt_raw")) {
			$old_lr=unserialize(file_get_contents($IOdir."old_lr"));
			$old_tags_dt_raw=unserialize(file_get_contents($IOdir."old_tags_dt_raw"));
		}
		
		//create an array containing all the tags of each document
		foreach($guids as $guid) {
			if(!empty($lr_array[$guid]->tags)) {
				if($dt_new_indexing_required==0 && isset($old_lr[$guid]->tags) && ($lr_array[$guid]->tags)==($old_lr[$guid]->tags) && isset($old_tags_dt_raw[$guid])) {
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
		file_put_contents($IOdir."tags_dt_raw",serialize($tags_dt));
		if(PHP_OS=="Linux") chmod($IOdir."tags_dt_raw",0666); //set rw permissions for everybody for this file
		
		if($enable_synonyms) {
			foreach($keys as $num=>$key) {
				if(!isset($keys[$num])) continue;  //since there is an unset on this array into the foreach, we have to check if the present key is still available or not
				foreach($keys as $num2=>$key2) {
					if(!isset($keys[$num2])) continue;  //since there is an unset on this array into the foreach, we have to check if the present key is still available or not
					if($key!=$key2 && check_synonyms($key,$key2)) {
						echo "\n$key and $key2 are synonyms\n";
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
		
		//print_r($tags_dt);
		echo 'TAGS Doc-term matrix created' . "\n";
		return $tags_dt;
}

//create an array containing "stop words", in order to eliminate them from the text
function set_stop_words_tags($loc_file) {
	$sw = file_get_contents($loc_file);
	$exc = explode("\n", $sw);
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
	
//it processes Repurposing Links to create a matrix doc-doc for them
function docdocReplinks($lr_array) {
		
		echo 'Starting creating REPLINKS Doc-doc matrix...' . "\n";
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
		echo 'REPLINKS doc-doc matrix created' . "\n";
		return $replinks;
}

//it generates doc-doc matrices from doc-term matrices for Metadata, Uses and Tags (Replinks is already a doc-doc matrix)
function generate_dd_matrices() {
	global $context_limit;
	global $IndexingClassificationPath;
	global $IOdir;
	global $DT_DD_method;
	global $num_processes;
	global $dd_new_indexing_required;
	if($context_limit==0) $use_contexts=0;
	else $use_contexts=1;
	if($DT_DD_method==1 || $DT_DD_method==2) $num_processes=1;   //num_processes will be 1 if DT_DD_method is 1 or 2
			
	if($DT_DD_method==1) $commandstring="php -f ".$IndexingClassificationPath."docdoc.php 1 1 $use_contexts $dd_new_indexing_required";
	elseif($DT_DD_method==2) $commandstring=$IndexingClassificationPath."docdoc/program 1 1 $use_contexts $dd_new_indexing_required";  //if the provided executable doesn't work you must have HipHop and you must have processed docdoc.php with it, substitute your executable to docdoc/program
	elseif($DT_DD_method==3) $commandstring="mpixec -n $num_processes ./docdocPar $use_contexts $dd_new_indexing_required";  //you must have configured a cloud or a cluster of computers with MPICH2, then you must have compiled docdocPar.c with mpicc and have it in a directory shared among the nodes; the directory IOdir must be shared

	passthru($commandstring);
	
	$arr_dd=array();
	
	$metadata_dd=array();
	//connect together all the pieces of the matrix
	for($i=1;$i<=$num_processes;$i++) {   //num_processes is 1 if DT_DD_method is 1 or 2
		$metadata_dd1=unserialize(file_get_contents($IOdir."metadata_dd".$i));
		$metadata_dd=$metadata_dd+$metadata_dd1;
		unlink($IOdir."metadata_dd".$i);
	}
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
	$arr_dd["metadata"]=$metadata_dd;
	
	$uses_dd=array();
	//connect together all the pieces of the matrix
	for($i=1;$i<=$num_processes;$i++) {   //num_processes is 1 if DT_DD_method is 1 or 2
		$uses_dd1=unserialize(file_get_contents($IOdir."uses_dd".$i));
		$uses_dd=$uses_dd+$uses_dd1;
		unlink($IOdir."uses_dd".$i);
	}
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
	$arr_dd["uses"]=$uses_dd;
	
	$tags_dd=array();
	//connect together all the pieces of the matrix
	for($i=1;$i<=$num_processes;$i++) {   //num_processes is 1 if DT_DD_method is 1 or 2
		$tags_dd1=unserialize(file_get_contents($IOdir."tags_dd".$i));
		$tags_dd=$tags_dd+$tags_dd1;
		unlink($IOdir."tags_dd".$i);
	}
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
	$arr_dd["tags"]=$tags_dd;
	
	return $arr_dd;
}

//it creates clusters from doc-term and doc-doc matrices, by using the chosen classification algorithm 
function create_clusters() {
	global $IndexingClassificationPath;
	global $classification_method_metadata;
	global $classification_method_tags;
	global $classification_method_uses;
	global $classification_method_replinks;
	require_once 'classes.php';
	
	$classification_methods=array("metadata" => $classification_method_metadata ,"uses" => $classification_method_uses ,"tags" => $classification_method_tags,"replinks" => $classification_method_replinks);
	foreach($classification_methods as $type => $value) {
		print("\nCreating clusters for $type...\n");
		if($value==1){  //if classification_method is Kohonen
			require_once 'kohonen.php';
			$array_clusters[$type]=run_kohonen($type);
		}
		elseif($value==2) { //if classification_method is aggregative
			require_once 'aggregative.php';
			$array_clusters[$type] = clusterize_aggregative($type);
		}
		else { //if classification_method is 3 (YACA)
			require_once 'yaca.php';
			$array_clusters[$type] = clusterize_yaca($type);
		}
		print("\nClusters for $type created\n");
	}
	
	return $array_clusters;
}


//it adds positive features to clusters
function get_positive_features($array_clusters,$m_dt,$type) {
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

//it creates associations among clusters
function associate_clusters($array_clusters) {
	global $minimum_association_threshold;
	$clusters_metadata = $array_clusters["metadata"];
	$clusters_uses = $array_clusters["uses"];
	$clusters_tags = $array_clusters["tags"];
	$clusters_replinks = $array_clusters["replinks"];
	
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
	$array_clusters["metadata"]=$clusters_metadata;
	unset($clusters_metadata);
	$array_clusters["uses"]=$clusters_uses;
	unset($clusters_uses);
	$array_clusters["tags"]=$clusters_tags;
	unset($clusters_tags);
	$array_clusters["replinks"]=$clusters_replinks;
	unset($clusters_replinks);
	return $array_clusters;
}

function reset_association($clusters) {
	foreach ($clusters as $cluster) {
		$cluster->associatedTo["metadata"]="";
		$cluster->associatedTo["uses"]="";
		$cluster->associatedTo["tags"]="";
		$cluster->associatedTo["replinks"]="";
	}
}

function associate($clusters_1,$clusters_2,$type,$threshold) {
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
	$cmp = array_uintersect($cluster1->array_docs,$cluster2->array_docs,"compare_cluster_element");
	return count($cmp);
}

function feature_assoc($cluster1,$cluster2){
	$resp = array();
	foreach($cluster1->commonFeatures as $feat1){
		foreach($cluster2->commonFeatures as $feat2){
			$resp[] = $feat1 . ' - ' . $feat2;
		}	
	}
	return $resp;
}

function create_cluster_rdf($array_clusters) {
	global $IOdir;
	
	foreach($array_clusters as $cluster) {
		$type=$cluster->type;
		break;
	}
	
	$file = "$IOdir"."clusters_$type.rdf";		
	$head="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n <rdf:RDF xml:base=\"http://purl.org/meducator/instances/\" \n xmlns:mdc=\"http://www.purl.org/meducator/ns/\" \n xmlns:dc=\"http://purl.org/dc/elements/1.1/\" \n xmlns:dcterms=\"http://purl.org/dc/terms/\" \n xmlns:foaf=\"http://xmlns.com/foaf/0.1/\" \n xmlns:owl=\"http://www.w3.org/2002/07/owl#\" \n xmlns:rdf=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\" \n xmlns:rdfs=\"http://www.w3.org/2000/01/rdf-schema#\" \n xmlns:sioc=\"http://rdfs.org/sioc/ns#\" \n xmlns:skos=\"http://www.w3.org/2009/08/skos-reference/skos.rdf\" \n xmlns:xs=\"http://www.w3.org/2001/XMLSchema\" \n xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">\n ";

	file_put_contents($file,$head,FILE_APPEND);
	file_put_contents($file,"<mdc:Resource rdf:about=\"http://metamorphosis.med.duth.gr/uid#".$nik."\">\n\r",FILE_APPEND);
	
	if(PHP_OS=="Linux" && getmyuid()==fileowner($file)) chmod($file,0666); //set rw permissions for everybody for this file
}

function reset_changes($old_changes=array()) {
	global $IOdir;
	
	//if there were changes during the indexing and classification process, the system will consider them by putting them in the "changes" file
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
			if(PHP_OS=="Linux" && getmyuid()==fileowner($IOdir.'changes')) chmod($IOdir.'changes',0666); //set rw permissions for everybody for this file
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
		if(PHP_OS=="Linux" && getmyuid()==fileowner($IOdir.'changes')) chmod($IOdir.'changes',0666); //set rw permissions for everybody for this file
	}
	
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
}

?>
