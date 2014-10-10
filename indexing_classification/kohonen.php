<?php

//it executes Kohonen algorithm
function run_kohonen($type_cluster) {
	global $output_file_kohonen,$IndexingClassificationPath,$IOdir;
	global $usern;
	
	$m_dt = '';
	$m_dd = '';
	$num_t = 0;
	switch($type_cluster){
		case 'tags':
			$m_dt = unserialize(file_get_contents($IOdir . 'tags_dt'));
			$m_dd = unserialize(file_get_contents($IOdir . 'tags_dd'));		
			break;
		case 'uses':
			$m_dt = unserialize(file_get_contents($IOdir . 'uses_dt'));
			$m_dd = unserialize(file_get_contents($IOdir . 'uses_dd'));		
			break;
		case 'metadata' :
		default: 
			$m_dt = unserialize(file_get_contents($IOdir . 'metadata_dt'));
			$m_dd = unserialize(file_get_contents($IOdir . 'metadata_dd'));
			break;
	}
	$kohonen_c = $IndexingClassificationPath . 'kohonen';
	$doc_term = '';
	foreach($m_dt as $guid => $row){
		$num_t = count($row);
		$j = 0;
		ksort($row);
		foreach($row as $k => $v){
			if($type_cluster=='tags') {
				if($j < $num_t-1){
					$doc_term .= "$v,";
				}
				else{
					$doc_term .= "$v-";
				}
			}
			else {
				if($j < $num_t-1){
					$doc_term .= "$v[$k],";
				}
				else{
					$doc_term .= "$v[$k]-";
				}
			}
			$j++;
		}
	}
	$doc_doc = '';
	$array_guid = array();
	foreach($m_dd as $guid => $row){
		$array_guid[] = $guid;
		$num_d = count($row);
		$j = 0;
		ksort($row);
		foreach($row as $k => $v){
			if($j < $num_d-1){
				$doc_doc .= "$v,";
			}
			else{
				$doc_doc .= "$v-";
			}
			$j++;
		}
	}
	$num_d = count($m_dt);
	
	echo $command_string = "$kohonen_c -r $num_d -c $num_t -t $doc_term -d $doc_doc";
	//file_put_contents("d","$command_string");
	shell_exec($command_string);
	$clusters_strings = file_get_contents($output_file_kohonen);
	$array_cluster = array();
	
	$info_cluster = split("\n",$clusters_strings);
	unset($info_cluster[count($info_cluster)-1]);
	for($i = 0 ; $i < count($array_guid); $i++){
		if(count($array_cluster[$info_cluster[$i] ]) == ''){
			$array_cluster[$info_cluster[$i] ] = new Cluster($type_cluster,$usern,"kohonen",1);
		}
		$array_cluster[$info_cluster[$i] ]->array_docs[] = $array_guid[$i];
	}
	
	echo "\nCalculating positive features for $type_cluster...\n";
	$array_cluster = get_positive_features($array_cluster,$m_dt,$type_cluster);  //adds positive features to the clusters
	
	foreach($array_cluster as $k => $obj){
		if(count($obj->array_docs) < 2){
			unset($array_cluster[$k]);
		}
	}
	
	return $array_cluster;
}