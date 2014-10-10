<?php
// Aggregative classification algorithm

function clusterize_aggregative($type,$cl_useold) {
	global $CONFIG;
	set_time_limit(0);  //this avoids timeouts
	include ($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/config.php");
	include_once ($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/classes.php");
	
	switch ($type) {
		case 'metadata' :
			$docterm = unserialize(file_get_contents($IOdir . 'metadata_dt'));
			$docdoc = unserialize(file_get_contents($IOdir . 'metadata_dd'));
			break;
		case 'tags' :
			$docterm = unserialize(file_get_contents($IOdir . 'tags_dt'));
			$docdoc = unserialize(file_get_contents($IOdir . 'tags_dd'));
			break;
		case 'uses' :
			$docterm = unserialize(file_get_contents($IOdir . 'uses_dt'));
			$docdoc = unserialize(file_get_contents($IOdir . 'uses_dd'));
			break;
		case 'replinks' :
			$docdoc = unserialize(file_get_contents($IOdir . 'replinks_dd'));
			break;
	}

	$docdoc = sort_matrix($docdoc);

	$clusters = array ();
	foreach ($docdoc as $g => $row) {
		$c = new Cluster($type,get_loggedin_user()->username,"aggregative",1);
		$c->array_docs[] = $g;
		$clusters[] = $c;
	}

	$def_cluster = array ();
	$it = 0;
	while (true) {
		$it++;
		$current = $clusters;

		$next = array ();
		$excluded = array ();
		$changes = false;
		for ($i = 0; $i < count($clusters); $i++) {
			if (in_array($current[$i]->id, $excluded))
				continue;
			$dist_v = array ();
			for ($j = 0; $j < count($clusters); $j++) {
				if (in_array($current[$j]->id, $excluded))
					continue;
				if ($i != $j) {
					$dist_v[$current[$j]->id] = correlation($current[$i], $current[$j], $docdoc);
				}
			}
			arsort($dist_v);
			foreach ($dist_v as $id => $correlation) {
				$fuse = find_cluster_by_id($id, $current);
				if (!$fuse)
					continue;

				$qi1 = quality_index($current[$i], $docdoc);
				$qi2 = quality_index($current[$fuse], $docdoc);

				if ($correlation == 0 || $correlation < $qi1 || $correlation < $qi2)
					break;

				$c = new Cluster($type,get_loggedin_user()->username,"aggregative",1);
				$c->array_docs = array_merge($current[$i]->array_docs, $current[$fuse]->array_docs);
				$next[] = $c;
				$excluded[] = $current[$fuse]->id;
				$excluded[] = $current[$i]->id;
				$changes = true;
				break;

			}
		}
		foreach ($current as $remaining) {
			if (!in_array($remaining->id, $excluded)) {
				$next[] = $remaining;
			}

		}
		//	usort($next,'sort_clusters');
		$clusters = $next;
		if ($it > 50 || !$changes) {
			$def_cluster = $next;
			break;
		}

	}
	//delete clusters that contain only one element
	foreach($def_cluster as $key => $cluster) {
		if(count($cluster->array_docs)==1) unset($def_cluster[$key]);
	}

	//sort clusters documents by guid
	foreach($def_cluster as $key => $cluster) {
		sort($def_cluster[$key]->array_docs);
	}
	return $def_cluster;
}

function correlation($c1, $c2, $docdoc) {
	global $CONFIG;
	set_time_limit(0);  //this avoids timeouts
	include_once ($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/classes.php");
	
	$tot_docs = count($c1->array_docs) + count($c2->array_docs);
	$point = 0;
	foreach ($c1->array_docs as $g1) {
		foreach ($c2->array_docs as $g2) {
			$point += $docdoc[$g1][$g2];
		}
	}
	$point /= $tot_docs;
	return $point;
}

function quality_index($c1, $docdoc) {
	global $CONFIG;
	set_time_limit(0);  //this avoids timeouts
	include_once ($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/classes.php");
	
	$tot_docs = count($c1->array_docs);
	$point = 0;
	foreach ($c1->array_docs as $g1) {
		foreach ($c1->array_docs as $g2) {
			$point += $docdoc[$g1][$g2];
		}
	}
	$point /= 2;
	$point /= $tot_docs;
	return $point;
}

function find_cluster_by_id($id, $current) {
	global $CONFIG;
	set_time_limit(0);  //this avoids timeouts
	include_once ($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/classes.php");
	
	for ($i = 0; $i < count($current); $i++) {
		if ($current[$i]->id == $id) {
			return $i;
		}
	}
	return false;
}

function sort_matrix($docdoc) {
	set_time_limit(0);  //this avoids timeouts

	$row_order = array ();
	foreach ($docdoc as $g1 => $row) {
		$max = 0;
		foreach ($row as $g2 => $point) {
			if ($point > $max)
				$max = $point;
		}
		$row_order[$g1] = $max;
	}
	asort($row_order);
	$new_dd = array ();
	foreach ($row_order as $g => $p) {
		$new_dd[$g] = $docdoc[$g];
	}
	return $new_dd;
}

function sort_clusters($a, $b) {
	global $CONFIG;
	set_time_limit(0);  //this avoids timeouts
	include_once ($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/classes.php");
	
	$ca = count($a->array_docs);
	$cb = count($b->array_docs);
	return (($ca < $cb) ? -1 : 1);
}

?>
