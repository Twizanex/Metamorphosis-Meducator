<?php

function autocomplete($req){
  $response = $req['params'];
  return $response;
}

//it returns the content of "ent", that is the file containing the query result (from the search executed in search.php)
function step0($req) {
	set_time_limit(0);  //this avoids timeouts
	$_SESSION["ent_checked"]=array();
	$ent=$_SESSION["ent"];
	if(isset($_SESSION["ent_old"])) {
		$ent_old=$_SESSION["ent_old"];
		$response=format($ent,$ent_old);
	}
	else $response=format($ent);
	return $response;
}

//function that compares two documents by their id
function compare_name($a, $b) {
	return strcmp($a['name'], $b['name']);
}

//function that compares two clusters by their relevance
function compare_relevance($a, $b) {
	return -(strcmp($a['relevance'], $b['relevance']));
}

//function that compares two documents by their relevance and then by ther name
function compare_relevance_name($a, $b) {
	$retval = -(strcmp($a['relevance'], $b['relevance']));
	if(!$retval) return strcmp($a['name'], $b['name']);
	return $retval;
}

function format($ent,$ent_old=array()) {  //it formats the results in a form useful to be printed on screen (the list built in List.js takes these parameters) and sees also if this is result is new if compared to last search
	global $CONFIG;
	set_time_limit(0);  //this avoids timeouts
	$i=0;
	foreach($ent as $guid => $type) {
		$response[$i]["guid"]=$guid;
		if($type=="user") {
			$entity=get_entity($guid);
			$response[$i]["name"] = $entity->get('name');
			$response[$i]["username"] = $entity->get('username');
			$response[$i]["type"] = "user";
		}
		elseif($type=="resource") {  //in the future it will be taken from somewhere else, right now it's the same than for users
			$entity=get_entity($guid);
			$response[$i]["name"] = $entity->get('name');
			$response[$i]["username"] = $entity->get('username');
			$response[$i]["type"] = "resource";
		}
		if(!array_key_exists($guid,$ent_old)) $response[$i]["newelement"]=1;
		else $response[$i]["newelement"]=0;
		$i++;
	}
	
	//sort the documents by their name
	usort($response,"compare_name");
	
	return $response;
}

function save_doc($req) {
	global $CONFIG;
	require_once ($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/classes.php");
	if($req["act"]=="add_doc") $_SESSION["ent_checked"][]=$req["guid"];  //when a checkbox is checked, it adds the correspondent guid to the file ent2
	if($req["act"]=="remove_doc") {
		$key=array_search($req["guid"], $_SESSION["ent_checked"]);
		unset($_SESSION["ent_checked"][$key]); //when a checkbox is unchecked, it removes the correspondent guid from the file ent2
	}
}

function reset_ent2($req) {
	$_SESSION["ent_checked"]=array();
}

 //it returns the clusters which contain the selected documents
function step1($req) {
	ini_set('memory_limit','500M');
	set_time_limit(0);  //this avoids timeouts
	global $CONFIG;
	require_once ($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/classes.php");
	include($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/config.php");
	$lr = unserialize(file_get_contents($IOdir . "lr"));
	$ent = $_SESSION["ent"];

	$clusters_metadata = unserialize(file_get_contents($IOdir . 'clusters_metadata'));
	$clusters_uses = unserialize(file_get_contents($IOdir . 'clusters_uses'));
	$clusters_tags = unserialize(file_get_contents($IOdir . 'clusters_tags'));
	$clusters_replinks = unserialize(file_get_contents($IOdir . 'clusters_replinks'));
	
	if($dd_crosscheck==1) {
		$metadata_dd = unserialize(file_get_contents($IOdir . 'metadata_dd'));
		$uses_dd = unserialize(file_get_contents($IOdir . 'uses_dd'));
		$tags_dd = unserialize(file_get_contents($IOdir . 'tags_dd'));
		$replinks_dd = unserialize(file_get_contents($IOdir . 'replinks_dd'));
	}
	
	$selected_ent=get_selected_entities($ent);   //it takes the checkbox selected documents
	
	//I look for the clusters (metadata, uses, tags, replinks) which contain the document
	$clusters_matching_metadata=array();
	$clusters_matching_uses=array();
	$clusters_matching_tags=array();
	$clusters_matching_replinks=array();
	foreach ($selected_ent as $key=>$type) {
		foreach ($clusters_metadata as $cluster_obj) {
			if ($cluster_obj->belongs($key) && count($cluster_obj->array_docs) > 1) {
				$clusters_matching_metadata[] = array("cluster" => $cluster_obj, "relevance" => 0);  //all the clusters containing the selected documents; we will use relevance later
			}
		}
		foreach ($clusters_uses as $cluster_obj) {
			if ($cluster_obj->belongs($key) && count($cluster_obj->array_docs) > 1) {
				$clusters_matching_uses[] =  array("cluster" => $cluster_obj, "relevance" => 0);  //all the clusters containing the selected documents; we will use relevance later
			}
		}
		foreach ($clusters_tags as $cluster_obj) {
			if ($cluster_obj->belongs($key) && count($cluster_obj->array_docs) > 1) {
				$clusters_matching_tags[] =  array("cluster" => $cluster_obj, "relevance" => 0);  //all the clusters containing the selected documents; we will use relevance later
			}
		}
		foreach ($clusters_replinks as $cluster_obj) {
			if ($cluster_obj->belongs($key) && count($cluster_obj->array_docs) > 1) {
				$clusters_matching_replinks[] =  array("cluster" => $cluster_obj, "relevance" => 0);  //all the clusters containing the selected documents; we will use relevance later
			}
		}
	}
	$clusters_matching_metadata = arrayUnique($clusters_matching_metadata);
	$clusters_matching_uses = arrayUnique($clusters_matching_uses);
	$clusters_matching_tags = arrayUnique($clusters_matching_tags);
	$clusters_matching_replinks = arrayUnique($clusters_matching_replinks);
	
	/* calculate relevance for each cluster returned in the previous step: it's the relevance of the cluster to the search done
	*  relevance of the cluster is given by the number of $selected_ent contained
	*/
	foreach ($selected_ent as $key=>$type) {
		foreach ($clusters_matching_metadata as $num => $cluster) {
			if ($cluster["cluster"]->belongs($key)) $clusters_matching_metadata[$num]["relevance"]++;
		}
		foreach ($clusters_matching_uses as $num => $cluster) {
			if ($cluster["cluster"]->belongs($key)) $clusters_matching_uses[$num]["relevance"]++;
		}
		foreach ($clusters_matching_tags as $num => $cluster) {
			if ($cluster["cluster"]->belongs($key)) $clusters_matching_tags[$num]["relevance"]++;
		}
		foreach ($clusters_matching_replinks as $num => $cluster) {
			if ($cluster["cluster"]->belongs($key)) $clusters_matching_replinks[$num]["relevance"]++;
		}
	}
	
	//sort the clusters by their relevance in descending order
	usort($clusters_matching_metadata,"compare_relevance");
	usort($clusters_matching_uses,"compare_relevance");
	usort($clusters_matching_tags,"compare_relevance");
	usort($clusters_matching_replinks,"compare_relevance");
	
	//it creates, for each type (metadata, uses, tags, replinks), a list of the documents contained in the clusters. Each document has a relevance, in order to visualize them by relevance descending order
	//the relevance is calculated this way: relevance of the cluster multiplied by a score of the document. 
	//this score, if we do a crosscheck with the doc-doc matrix, is given by that matrix, otherwise is the inherence (for the clusters given by the YACA algorithm) or 1 (for the other clusters)
	$list_documents_metadata=array();
	foreach($clusters_matching_metadata as $num => $cluster) {
		foreach($cluster["cluster"]->array_docs as $doc_block) {
			if(is_array($doc_block)) $guid=$doc_block["guid"];
			else $guid=$doc_block;
			if(array_key_exists($guid,$selected_ent)) continue;
			if(get_type($guid)!="resource") continue;  //don't consider eventual documents that are not part of the resources, for example documents that were present and so part of the classification, but that after have been deleted
			if(!array_key_exists($guid,$list_documents_metadata)) {
				if($dd_crosscheck==1) {
					$max=0;
					foreach($selected_ent as $key => $type) {
						if($metadata_dd[$guid][$key]>$max) $max=$metadata_dd[$guid][$key];
					}
					$list_documents_metadata[$guid]["relevance"]=($max+0.05)*$cluster["relevance"]; //we add 0.05 to avoid having a 0 value
				}
				elseif(is_array($doc_block)) $list_documents_metadata[$guid]["relevance"]=$doc_block["inherence"]*$cluster["relevance"];
				else $list_documents_metadata[$guid]["relevance"]=$cluster["relevance"];
			}
		}
	}
	$list_documents_uses=array();
	foreach($clusters_matching_uses as $num => $cluster) {
		foreach($cluster["cluster"]->array_docs as $doc_block) {
			if(is_array($doc_block)) $guid=$doc_block["guid"];
			else $guid=$doc_block;
			if(array_key_exists($guid,$selected_ent)) continue;
			if(get_type($guid)!="resource") continue;  //don't consider eventual documents that are not part of the resources, for example documents that were present and so part of the classification, but that after have been deleted
			if(!array_key_exists($guid,$list_documents_uses)) {
				if($dd_crosscheck==1) {
					$max=0;
					foreach($selected_ent as $key => $type) {
						if($uses_dd[$guid][$key]>$max) $max=$uses_dd[$guid][$key];
					}
					$list_documents_uses[$guid]["relevance"]=($max+0.05)*$cluster["relevance"]; //we add 0.05 to avoid having a 0 value
				}
				elseif(is_array($doc_block)) $list_documents_uses[$guid]["relevance"]=$doc_block["inherence"]*$cluster["relevance"];
				else $list_documents_uses[$guid]["relevance"]=$cluster["relevance"];
			}
		}
	}
	$list_documents_tags=array();
	foreach($clusters_matching_tags as $num => $cluster) {
		foreach($cluster["cluster"]->array_docs as $doc_block) {
			if(is_array($doc_block)) $guid=$doc_block["guid"];
			else $guid=$doc_block;
			if(array_key_exists($guid,$selected_ent)) continue;
			if(get_type($guid)!="resource") continue;  //don't consider eventual documents that are not part of the resources, for example documents that were present and so part of the classification, but that after have been deleted
			if(!array_key_exists($guid,$list_documents_tags)) {
				if($dd_crosscheck==1) {
					$max=0;
					foreach($selected_ent as $key => $type) {
						if($tags_dd[$guid][$key]>$max) $max=$tags_dd[$guid][$key];
					}
					$list_documents_tags[$guid]["relevance"]=($max+0.05)*$cluster["relevance"]; //we add 0.05 to avoid having a 0 value
				}
				elseif(is_array($doc_block)) $list_documents_tags[$guid]["relevance"]=$doc_block["inherence"]*$cluster["relevance"];
				else $list_documents_tags[$guid]["relevance"]=$cluster["relevance"];
			}
		}
	}
	$list_documents_replinks=array();
	foreach($clusters_matching_replinks as $num => $cluster) {
		foreach($cluster["cluster"]->array_docs as $doc_block) {
			if(is_array($doc_block)) $guid=$doc_block["guid"];
			else $guid=$doc_block;
			if(array_key_exists($guid,$selected_ent)) continue;
			if(get_type($guid)!="resource") continue;  //don't consider eventual documents that are not part of the resources, for example documents that were present and so part of the classification, but that after have been deleted
			if(!array_key_exists($guid,$list_documents_replinks)) {
				if($dd_crosscheck==1) {
					$max=0;
					foreach($selected_ent as $key => $type) {
						if($replinks_dd[$guid][$key]>$max) $max=$replinks_dd[$guid][$key];
					}
					$list_documents_replinks[$guid]["relevance"]=($max+0.05)*$cluster["relevance"]; //we add 0.05 to avoid having a 0 value
				}
				elseif(is_array($doc_block)) $list_documents_replinks[$guid]["relevance"]=$doc_block["inherence"]*$cluster["relevance"];
				else $list_documents_replinks[$guid]["relevance"]=$cluster["relevance"];
			}
		}
	}
	
	//sort the clusters by relevance and then by name
	uasort($list_documents_metadata,"compare_relevance_name");
	uasort($list_documents_uses,"compare_relevance_name");
	uasort($list_documents_tags,"compare_relevance_name");
	uasort($list_documents_replinks,"compare_relevance_name");
	
	$response = array ();
	
	$response['metadata'] = create_data($list_documents_metadata, $lr, $selected_ent);   //return the documents contained in the clusters
	$response['uses'] = create_data($list_documents_uses, $lr, $selected_ent);  //return the documents contained in the clusters
	$response['tags'] = create_data($list_documents_tags, $lr, $selected_ent);  //return the documents contained in the clusters
	$response['replinks'] = create_data($list_documents_replinks, $lr, $selected_ent);  //return the documents contained in the clusters
	
	//Now I get positive features from the clusters
	$response['feat_metadata'] = array();
	foreach($clusters_matching_metadata as $c){
		if(!empty($c["cluster"]->commonFeatures))
			$response['feat_metadata'] = array_merge($response['feat_metadata'],$c["cluster"]->commonFeatures);
	}
	$response['feat_metadata'] = array_unique($response['feat_metadata']);
	
	$response['feat_tags'] = array();
	foreach($clusters_matching_tags as $c){
		if(!empty($c["cluster"]->commonFeatures))
			$response['feat_tags'] = array_merge($response['feat_tags'],$c["cluster"]->commonFeatures);
	}
	$response['feat_tags'] = array_unique($response['feat_tags']);
	
	
	$response['feat_uses'] = array();
	foreach($clusters_matching_uses as $c){
		if(!empty($c["cluster"]->commonFeatures))
			$response['feat_uses'] = array_merge($response['feat_uses'],$c["cluster"]->commonFeatures);
	}
	$response['feat_uses'] = array_unique($response['feat_uses']);
	
	if ($req['save_cluster']) {
		$response['cluster'] = array ();
		$response['cluster']['metadata'] = $clusters_matching_metadata;
		$response['cluster']['uses'] = $clusters_matching_uses;
		$response['cluster']['tags'] = $clusters_matching_tags;
		$response['cluster']['replinks'] = $clusters_matching_replinks;
	}
	return $response;
}

function get_selected_entities($ent) {   //it takes the checkbox selected documents
	foreach($_SESSION["ent_checked"] as $guid) {
		$selected_ent[$guid]=$ent[$guid];
	}
	return $selected_ent;
}

//It returns the clusters related to the ones returned by step1
function step2($req) {
	global $CONFIG;
	set_time_limit(0);  //this avoids timeouts
	require_once ($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/classes.php");
	include($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/config.php");
	$lr = unserialize(file_get_contents($IOdir . "lr"));

	$ent = $_SESSION["ent"];

	$clusters_metadata = unserialize(file_get_contents($IOdir . 'clusters_metadata'));
	$clusters_uses = unserialize(file_get_contents($IOdir . 'clusters_uses'));
	$clusters_tags = unserialize(file_get_contents($IOdir . 'clusters_tags'));
	$clusters_replinks = unserialize(file_get_contents($IOdir . 'clusters_replinks'));

	//I call at first step1
	$selected_ent=get_selected_entities($ent);
	//file_put_contents('www3',$IOdir);
	$res_step1 = step1(array (
		'save_cluster' => true, 
		'iodir' => $IOdir
	));

	$clusters_matching_metadata = $res_step1['cluster']['metadata'];
	$clusters_matching_uses = $res_step1['cluster']['uses'];
	$clusters_matching_tags = $res_step1['cluster']['tags'];
	$clusters_matching_replinks = $res_step1['cluster']['replinks'];
	//file_put_contents('wwww',serialize($clusters_matching_metadata));

	$response = array ();
	$response['metadata'] = array ();
	$response['uses'] = array ();
	$response['tags'] = array ();
	$response['replinks'] = array ();

	$response['metadata']['metadata']['docs'] = return_associated_docs($clusters_matching_metadata, $clusters_metadata, "metadata", $lr, $selected_ent);
	$response['metadata']['metadata']['features'] = return_associated_features($clusters_matching_metadata, $clusters_metadata, "metadata");
	
	$response['metadata']['tags']['docs'] = return_associated_docs($clusters_matching_metadata, $clusters_tags, "tags", $lr, $selected_ent);
	$response['metadata']['tags']['features'] = return_associated_features($clusters_matching_metadata, $clusters_tags, "tags");

	$response['metadata']['uses']['docs'] = return_associated_docs($clusters_matching_metadata, $clusters_uses, "uses", $lr, $selected_ent);
	$response['metadata']['uses']['features'] = return_associated_features($clusters_matching_metadata, $clusters_uses, "uses");

	$response['metadata']['replinks']['docs'] = return_associated_docs($clusters_matching_metadata, $clusters_replinks, "replinks", $lr, $selected_ent);
	$response['metadata']['replinks']['features'] = return_associated_features($clusters_matching_metadata, $clusters_replinks, "replinks");
	
	$response['uses']['uses']['docs'] = return_associated_docs($clusters_matching_uses, $clusters_uses, "uses", $lr, $selected_ent);
	$response['uses']['uses']['features'] = return_associated_features($clusters_matching_uses, $clusters_uses, "uses");
	
	$response['uses']['tags']['docs'] = return_associated_docs($clusters_matching_uses, $clusters_tags, "tags", $lr, $selected_ent);
	$response['uses']['tags']['features'] = return_associated_features($clusters_matching_uses, $clusters_tags, "tags");

	$response['uses']['metadata']['docs'] = return_associated_docs($clusters_matching_uses, $clusters_metadata, "metadata", $lr, $selected_ent);
	$response['uses']['metadata']['features'] = return_associated_features($clusters_matching_uses, $clusters_metadata, "metadata");

	$response['uses']['replinks']['docs'] = return_associated_docs($clusters_matching_uses, $clusters_replinks, "replinks", $lr, $selected_ent);
	$response['uses']['replinks']['features'] = return_associated_features($clusters_matching_uses, $clusters_replinks, "replinks");
	
	$response['tags']['tags']['docs'] = return_associated_docs($clusters_matching_tags, $clusters_tags, "tags", $lr, $selected_ent);
	$response['tags']['tags']['features'] = return_associated_features($clusters_matching_tags, $clusters_tags, "tags");
	
	$response['tags']['metadata']['docs'] = return_associated_docs($clusters_matching_tags, $clusters_metadata, "metadata", $lr, $selected_ent);
	$response['tags']['metadata']['features'] = return_associated_features($clusters_matching_tags, $clusters_metadata, "metadata");

	$response['tags']['uses']['docs'] = return_associated_docs($clusters_matching_tags, $clusters_uses, "uses", $lr, $selected_ent);
	$response['tags']['uses']['features'] = return_associated_features($clusters_matching_tags, $clusters_uses, "uses");
	
	$response['tags']['replinks']['docs'] = return_associated_docs($clusters_matching_tags, $clusters_replinks, "replinks", $lr, $selected_ent);
	$response['tags']['replinks']['features'] = return_associated_features($clusters_matching_tags, $clusters_replinks, "replinks");
	
	$response['replinks']['replinks']['docs'] = return_associated_docs($clusters_matching_replinks, $clusters_replinks, "replinks", $lr, $selected_ent);
	$response['replinks']['replinks']['features'] = return_associated_features($clusters_matching_replinks, $clusters_replinks, "replinks");
	
	$response['replinks']['metadata']['docs'] = return_associated_docs($clusters_matching_replinks, $clusters_metadata, "metadata", $lr, $selected_ent);
	$response['replinks']['metadata']['features'] = return_associated_features($clusters_matching_replinks, $clusters_metadata, "metadata");

	$response['replinks']['uses']['docs'] = return_associated_docs($clusters_matching_replinks, $clusters_uses, "uses", $lr, $selected_ent);
	$response['replinks']['uses']['features'] = return_associated_features($clusters_matching_replinks, $clusters_uses, "uses");
	
	$response['replinks']['tags']['docs'] = return_associated_docs($clusters_matching_replinks, $clusters_tags, "tags", $lr, $selected_ent);
	$response['replinks']['tags']['features'] = return_associated_features($clusters_matching_replinks, $clusters_tags, "tags");

	return $response;
}

//returns the documents contained in the clusters
function create_data($documents, $lr, $ent) {
	set_time_limit(0);  //this avoids timeouts
	$response=array();
	$i = 0;
	foreach ($documents as $key => $doc) {
		$response[$i]["guid"] = $key;
		$response[$i]["name"] = $lr[$key]->name;
		$response[$i]["username"] =$lr[$key]->username;
		$response[$i]["type"] = "resource";
		$response[$i]["relevance"]=$doc["relevance"];
		$i++;
	}
	//file_put_contents('xxx',serialize($response));
	//sort($response);
	return $response;
}

//a array_unique replacement which works with multidimensional arrays
function arrayUnique($array, $preserveKeys = false) {
	set_time_limit(0);  //this avoids timeouts
	// Unique Array for return  
	$arrayRewrite = array ();
	// Array with the md5 hashes  
	$arrayHashes = array ();
	foreach ($array as $key => $item) {
		// Serialize the current element and create a md5 hash  
		$hash = md5(serialize($item));
		// If the md5 didn't come up yet, add the element to  
		// to arrayRewrite, otherwise drop it  
		if (!isset ($arrayHashes[$hash])) {
			// Save the current element hash  
			$arrayHashes[$hash] = $hash;
			// Add element to the unique Array  
			if ($preserveKeys) {
				$arrayRewrite[$key] = $item;
			} else {
				$arrayRewrite[] = $item;
			}
		}
	}
	return $arrayRewrite;
}

//returns the documents of $clusters2 associated to the ones contained in $clusters1
function return_associated_docs($clusters1, $clusters2, $type, $lr, $ent) {
	set_time_limit(0);  //this avoids timeouts
	$docs = array ();
	$i = 0;
	foreach ($clusters1 as $cluster) {
		if($cluster["cluster"]->associatedTo[$type]!="") $associatedCluster = getClusterById($cluster["cluster"]->associatedTo[$type], $clusters2);
		else $associatedCluster=null;
		if ($associatedCluster != null) {
			if(!is_array($associatedCluster->array_docs[0])) {
				foreach ($associatedCluster->array_docs as $guid) {
					$present=0;
					if(array_key_exists($guid,$ent)) $present=1;  //in this case we don't consider it because it's already in the documents returned by the 1st search
					if ($present==0) { //in this case we consider it
						if(get_type($guid)=="resource") {  //don't consider eventual documents that are not part of the resources, for example documents that were present and so part of the classification, but that after have been deleted
							$docs[$i]["guid"] = $guid;
							$docs[$i]["name"] = $lr[$guid]->name;
							$docs[$i]["username"] =$lr[$guid]->username;
							$docs[$i]["type"] = "resource";
							$i++;
						}
					}
				}
			}
			else {
				foreach ($associatedCluster->array_docs as $doc_pack) {
					$present=0;
					if(array_key_exists($doc_pack["guid"],$ent)) $present=1;  //in this case we don't consider it because it's already in the documents returned by the 1st search
					if ($present==0) { //in this case we consider it
						if(get_type($doc_pack["guid"])=="resource") {  //don't consider eventual documents that are not part of the resources, for example documents that were present and so part of the classification, but that after have been deleted
							$docs[$i]["guid"] = $doc_pack["guid"];
							$docs[$i]["name"] = $lr[$doc_pack["guid"]]->name;
							$docs[$i]["username"] =$lr[$doc_pack["guid"]]->username;
							$docs[$i]["type"] = "resource";
							$i++;
						}
					}
				}
			}
		}
	}
	
	//$docs = arrayUnique($docs);
	//sort($docs);
	//file_put_contents('xxx',serialize($ent));
	return $docs;
}

function return_associated_features($clusters1, $clusters2, $type) {
	set_time_limit(0);  //this avoids timeouts
	$features = array ();
	foreach ($clusters1 as $cluster) {
		$features=array_merge($features,$cluster["cluster"]->commonFeatures);
		$associatedCluster = getClusterById($cluster["cluster"]->associatedTo[$type], $clusters2);
		if(!is_null($associatedCluster)) $features=array_merge($features,$associatedCluster->commonFeatures);
	}
	$features=array_unique($features);
	$positivefeatures=implode(", ",$features);
	return $positivefeatures;
}

function getClusterById($id,$clusters) {
	foreach($clusters as $cluster) {
		if ($cluster->id==$id) return $cluster;
	}
	return null;
}

//it tells if a guid belongs to a resource or to a user
function get_type($guid) {
	global $CONFIG;
	$entity=get_entity($guid);
	if(!is_object($entity)) return "wrong";
	if($entity->issimpleuser=="yes") return "user";
	else return "resource";
}

//it gets the cluster(s) id from the guid of document that is part of it
function get_cluster_id_from_doc_guid($req) {
	global $CONFIG;
	set_time_limit(0);  //this avoids timeouts
	require_once ($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/classes.php");
	include($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/config.php");
	
	$clusters_metadata = unserialize(file_get_contents($IOdir . 'clusters_metadata'));
	$clusters_uses = unserialize(file_get_contents($IOdir . 'clusters_uses'));
	$clusters_tags = unserialize(file_get_contents($IOdir . 'clusters_tags'));
	$clusters_replinks = unserialize(file_get_contents($IOdir . 'clusters_replinks'));

	$guid = $req['guid'];
	$response[5];
  	$response[0] = $guid;

	$clusters = array($clusters_metadata, $clusters_uses, $clusters_tags, $clusters_replinks);
	$clusters_type = array("Metadata", "Uses", "Tags", "Replinks");
	$clusters_id = array();
  
	for ($i = 0; $i < 4; $i++) {
		$len = count($clusters[$i]);
		for ($j = 0; $j < $len; $j++) {
			if($clusters[$i][$j]->belongs($guid)) $clusters_id[$i][]=$clusters[$i][$j]->id;
		}
		if($clusters_id[$i]!=null) {
			foreach($clusters_id[$i] as $id) {
				$response[$i+1][]=$id;
			}
		}
		else $response[$i+1] = "none";    
	}
	return $response;
}

?>
