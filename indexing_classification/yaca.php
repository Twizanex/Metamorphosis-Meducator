<?php
// YACA algorithm (Yet Another Classification Algorithm)
//it gives "inherence" to each element of the cluster, given by the sum of the score it has with the other elements of the cluster, divided by the number of the other elements of the cluster

//function that sorts two documents by their guid
function compare_guid($a, $b) {
	return strcmp($a['guid'], $b['guid']);
}

//function that sorts two documents by their inherence in descending order and then by guid
function compare_inherence_guid($a, $b) {
	$retval = -strcmp($a['inherence'], $b['inherence']);
	if(!$retval) return strcmp($a['guid'], $b['guid']);
	return $retval;
}

function clusterize_yaca($type) {
	global $YACA_threshold;
	global $IOdir;
	global $usern;
	global $new_classification_required;
	$docdoc = unserialize(file_get_contents($IOdir . $type . "_dd"));
	if($type!="replinks") $docterm = unserialize(file_get_contents($IOdir . $type . "_dt"));
	
	$old_data_retrieved=false;
	//if possible, use old classification results: it is based on the file "changes", that is the log of edited and new documents 
	//the filce "changes" doesn't contain logs for replinks (so this "shortcut" is not possible for replinks)
	//the strategy is to use old clusters and make the needed changes instead of recalculating them from scratch
	if($new_classification_required==0 && $type!="replinks") {  //only if there were not very important changes and if it's not the case of replinks
		if(file_exists($IOdir."old_clusters_$type") && file_exists($IOdir."old_$type"."_dd")) {  //we need both the old clusters and the old doc-doc matrix
			$changes=unserialize(file_get_contents($IOdir."changes"));
			$old_clusters=unserialize(file_get_contents($IOdir."old_clusters_$type"));
			$old_clusters_values=array_values($old_clusters);
			$old_dd=unserialize(file_get_contents($IOdir."old_$type"."_dd"));
			if($old_clusters_values[0]->clusteringAlgorithm=="YACA") {  //we can continue only if the old clusters have been created with YACA
				//create new clusters that have the same documents of the old ones (we re-create them in order not to mess things up, especially the IDs, however it's a very quick process)
				foreach($old_clusters as $old_cluster) {
					$c = new Cluster($type,$usern,"YACA",1);
					$c->array_docs=$old_cluster->array_docs;
					//we don't put positive features right now
					$clusters[]=$c;
				}
				//for each edited element...
				foreach($changes["edited"][$type] as $guid) {
					//...for each cluster
					foreach($clusters as $num=>$cluster) {
						//...see if the element is contained in the cluster and, if so, delete it from the cluster and delete its inherence from each element of the cluster
						$pos=$cluster->belongs($guid); 
						if($pos!=false) {
							unset($clusters[$num]->array_docs[$pos-1]); //delete it (the key is given by (position - 1)
							foreach($cluster->array_docs as $numres=>$resource) {  //delete its inherence from each element
								$clusters[$num]->array_docs[$numres]["inherence"]=(($clusters[$num]->array_docs[$numres]["inherence"]*count($clusters[$num]->array_docs))-$old_dd[$guid][$resource["guid"]])/(count($clusters[$num]->array_docs)-1);
							}
						}
						$clusters[$num]->array_docs=array_values($clusters[$num]->array_docs);  //in order to preserve the normal sequence in the array
						//see if the element is now related to the cluster or not (if it is related to almost one other element, it is considered related)
						$related=false;
						foreach($cluster->array_docs as $resource) {
							if($docdoc[$guid][$resource["guid"]]>$YACA_threshold) {
								$related=true;
								break;
							}
						}
						//if the element is related to the cluster, add its inherence to each element of the cluster and add it to the cluster
						if($related) {
							$new_inherence=0;
							foreach($cluster->array_docs as $numres=>$resource) { //add its inherence to each element of the cluster
								$clusters[$num]->array_docs[$numres]["inherence"]=(($clusters[$num]->array_docs[$numres]["inherence"]*(count($clusters[$num]->array_docs)-1))+$docdoc[$guid][$resource["guid"]])/(count($clusters[$num]->array_docs));
								$new_inherence+=$docdoc[$guid][$resource["guid"]];
							}
							$clusters[$num]->array_docs[]=array("guid" => $guid, "inherence" => $new_inherence/(count($clusters[$num]->array_docs)));  //add it to the cluster
						}
					}
				}
				//for each new resource...
				foreach($changes["new"] as $guid) {
					//...for each cluster
					foreach($clusters as $num=>$cluster) {
						//see if the element is related to the cluster or not (if it is related to almost one other element, it is considered related)
						$related=false;
						foreach($cluster->array_docs as $resource) {
							if($docdoc[$guid][$resource["guid"]]>$YACA_threshold) {
								$related=true;
								break;
							}
						}
						//if the element is related to the cluster, add its inherence to each element of the cluster and add it to the cluster
						if($related) {
							$new_inherence=0;
							foreach($cluster->array_docs as $numres=>$resource) {
								$clusters[$num]->array_docs[$numres]["inherence"]=(($clusters[$num]->array_docs[$numres]["inherence"]*(count($clusters[$num]->array_docs)-1))+$docdoc[$guid][$resource["guid"]])/(count($clusters[$num]->array_docs));
								$new_inherence+=$docdoc[$guid][$resource["guid"]];
							}
							$clusters[$num]->array_docs[]=array("guid" => $guid, "inherence" => $new_inherence/(count($clusters[$num]->array_docs)));
						}
					}
					//moreover we have to create a new cluster starting from the new element
					$c = new Cluster($type,$usern,"YACA",1);
					$c->array_docs[] = array("guid" => $guid, "inherence" => 0);
					$guids=unserialize(file_get_contents($IOdir."guids"));
					foreach($guids as $guid2) {
						if($docdoc[$guid][$guid2]>=$YACA_threshold) {
							if($guid!=$guid2) {
								$new_inherence=0;
								foreach($c->array_docs as $numres=>$resource) {
									$c->array_docs[$numres]["inherence"]+=(($c->array_docs[$numres]["inherence"]*(count($c->array_docs)-1))+$docdoc[$guid2][$resource["guid"]])/(count($c->array_docs));
									$new_inherence+=$docdoc[$guid2][$resource["guid"]];
								}
								$c->array_docs[]=array("guid" => $guid2, "inherence" => $new_inherence/(count($c->array_docs)));
							}
						}
					}
					if(count($c->array_docs)==1) $c->array_docs[0]["inherence"]=-1; // -1 stands for "the maximum", since when a cluster has only an element, this is obviously totally inherent to the cluster
					$clusters[]=$c;
				}
				$old_data_retrieved=true;
			}
		}
	}
	
	//if it wasn't possible to use the old classification results, do the whole process
	if($old_data_retrieved==false) {
		$clusters = array ();
		foreach ($docdoc as $key => $row) {
			$c = new Cluster($type,$usern,"YACA",1);
			$c->array_docs[] = array("guid" => $key, "inherence" => 0);
			foreach($row as $key2 => $value) {
				if($docdoc[$key][$key2]>=$YACA_threshold) {
					if($key!=$key2) {
						$new_inherence=0;
						//add the element score to the other elements' inherence
						foreach($c->array_docs as $numres=>$resource) {
							$c->array_docs[$numres]["inherence"]=(($c->array_docs[$numres]["inherence"]*(count($c->array_docs)-1))+$docdoc[$key2][$resource["guid"]])/(count($c->array_docs));
							$new_inherence+=$docdoc[$key2][$resource["guid"]];
						}
						//we add now inherence to each element of the cluster
						$c->array_docs[]=array("guid" => $key2, "inherence" => $new_inherence/(count($c->array_docs)));
					}
				}
			}
			if(count($c->array_docs)==1) $c->array_docs[0]["inherence"]=-1; // -1 stands for "the maximum", since when a cluster has only an element, this is obviously totally inherent to the cluster
			$clusters[] = $c;
		}
	}
	
	$clusters_ok=delete_unnecessary_clusters($clusters);
	//sort each cluster's documents by inherence descending order and then by guid
	foreach($clusters_ok as $num => $cluster) {
		usort($cluster->array_docs,"compare_inherence_guid");
	}
	//add positive features to clusters
	if($type!='replinks') {
		echo "\nCalculating positive features for $type...\n";
		$clusters_pos = get_positive_features($clusters_ok, $docterm, $type);  //it's very slow for metadata
	}
	else $clusters_pos=$clusters_ok;
	return $clusters_pos;
}

//it deletes clusters which are duplicated or subsets of bigger clusters or that contain only one element
function delete_unnecessary_clusters($clusters) {
	
	//1 ------------------------------   sort array documents
	foreach($clusters as $cluster) {
		usort($cluster->array_docs,"compare_guid");
	}
	
	//echo "\n------------------------cl (sorted)--------------------------\n";
	//print_r($clusters);
	
	//2 ---------------------------------  delete clusters whose array_docs is a subset of another cluster
	$clusters_2=array();
	foreach($clusters as $cluster) {
		if(count($cluster->array_docs)>1) {
			$i=0;
			foreach($clusters as $cluster2) {
				if($cluster!=$cluster2) {
					$a=$cluster->return_docs();
					sort($a);
					$b=$cluster2->return_docs();
					sort($b);
					$intersection=array_intersect($a,$b);
					sort($intersection);
					if($intersection==$a && $intersection!=$b) break;
				}
				$i++;
				if($i==count($clusters)) $clusters_2[]=$cluster;
			}
		}
	}
	unset($clusters);
	//echo "\n------------------------clusters_2--------------------------\n";
	//print_r($clusters_2);
	
	//3 ---------------------------------  delete clusters whose array_docs is the same of another cluster
	$clusters_3=array();
	foreach($clusters_2 as $cluster) {
		if(!present($cluster,$clusters_3)) $clusters_3[]=$cluster;
	}
	unset($clusters_2);
	//echo "\n------------------------clusters_3--------------------------\n";
	//print_r($clusters_3);
	return $clusters_3;
}

function present($c,$clusters) {
	foreach($clusters as $cluster) {
		$a=$c->return_docs();
		sort($a);
		$b=$cluster->return_docs();
		sort($b);
		if($a==$b) return true;
	}
	return false;
}

?>