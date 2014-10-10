<?php

$cluster_count = 0;

class LearningResource {
	public $metadata,$tags,$uses,$replinks,$name,$username,$guid;
	
	public function LearningResource() {
		//echo "<br><br>Hello from LR<br>";
		$this->metadata=array();
		$this->uses=array();
		$this->tags=array();
		$this->replinks["from"]=array();
		$this->replinks["to"]=array();
	}
	
	public function setName($n) {
		$this->name=$n;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function setUsername($u) {
		$this->username=$u;
	}
	
	public function getUsername() {
		return $this->username;
	}
	
	public function setGUID($u) {
		$this->guid=$u;
	}
	
	public function getGUID() {
		return $this->guid;
	}
	
	public function insertMetadata($name, $value) {
		global $IndexingClassificationPath,$metadatas_fields,$tagss_fields,$usess_fields;
		if(in_array($name,$tagss_fields)) {
			$this->tags[$name]=$value;
		}
		if(in_array($name,$usess_fields)) {
			$this->uses[$name]=$value;
		}
		if(in_array($name,$metadatas_fields)) {
			$this->metadata[$name]=$value;		
		}
	}
	
	public function insertReplinks($entity,$lr) {
		$rep_from = get_entities_from_relationship('friend', $entity->getGUID(), false,'user','',0,10,10);  //array which contains the resources
		if(is_array($rep_from)) {
			foreach($rep_from as $i => $resource) {
				$this->replinks["from"][$i]=$resource->guid;
			}
		}
		else $this->replinks["from"]=array();
		
		$rep_to = get_entities_from_relationship('friend', $entity->getGUID(), true,'user','',0,10,10);   //array which contains the resources
		if(is_array($rep_to)) {
			foreach($rep_to as $i => $resource) {
				$this->replinks["to"][$i]=$resource->guid;
			}
		}
		else $this->replinks["to"]=array();
	}
	
	public function repurposedToRelation($res,$resource,$lr,$distance=0) {
		if($this->guid==$resource->guid) $res[]=$distance;
		elseif(is_array($this->replinks["to"]) && !empty($this->replinks["to"])) {
			foreach($this->replinks["to"] as $rguid) {
				$res=$lr[$rguid]->repurposedToRelation($res,$resource,$lr,($distance+1));
			}
		}
		return $res;
	}
	
	public function repurposedFromRelation($res,$resource,$lr,$distance=0) {
		if($this->guid==$resource->guid) $res[]=$distance;
		elseif(is_array($this->replinks["from"]) && !empty($this->replinks["from"])) {
			foreach($this->replinks["from"] as $rguid) {
				$res=$lr[$rguid]->repurposedFromRelation($res,$resource,$lr,($distance+1));
			}
		}
		return $res;
	}
	
	public function isBrother($resource) {
		if($this->guid==$resource->guid) return false;
		$fathers1=$this->replinks["from"];
		$fathers2=$resource->replinks["from"];
		if(!empty($fathers1)) {
			foreach($fathers1 as $guid1) {
				if(!empty($fathers2)) {
					foreach($fathers2 as $guid2) {
						if($guid1==$guid2) return true;
					}
				}
			}
		}
		return false;
	}
	
	public function equals($resource) {
		if($this->guid==$resource->guid) return true;
		else return false;
	}

	public function printLR() {
		print_r($this);
	}
}

class Cluster {
	public $commonFeatures,$type,$array_docs,$id,$timestamp,$creator,$clusteringAlgorithm,$posfeaturesAlgorithm,$confidence,$associatedTo;
	
	public function Cluster($type,$creator,$clusteringAlgorithm,$confidence) {
		global $cluster_count;
		$this->type=$type;
		$this->id = ++$cluster_count;
		$this->timestamp=time();
		$this->creator=$creator;
		$this->clusteringAlgorithm=$clusteringAlgorithm;
		$this->posfeaturesAlgorithm="standard";
		$this->confidence=$confidence;
		$this->commonFeatures=array();
		$this->array_docs=array();
		$this->associatedTo["metadata"]="";
		$this->associatedTo["uses"]="";
		$this->associatedTo["tags"]="";
		$this->associatedTo["replinks"]="";
		
	}
	
	//inserts common features in the cluster
	public function insertCommon($common) {
		if($common != '')
			$this->commonFeatures[]=$common;
	}
	
	
	public function insertPositiveFeatures($m_dt,$params) {
		$num_docs = count($this->array_docs);
		if($num_docs == 1) return;
		
		$array_docs=$this->return_docs();
		$array_terms=array();
		
		if($num_docs < $params['n_med_docs']){
			$threshold=$params['p_max'];
			foreach($array_docs as $guid) {
				if(isset($m_dt[$guid])) {
					foreach($m_dt[$guid] as $term=>$element) {
						if(!isset($array_terms[$term])) $array_terms[$term]=1;
						else $array_terms[$term]+= 1;
					}
				}
			}
			ksort($array_terms);
			foreach($array_terms as $term=>$score) {
				if($score/$num_docs >= $threshold) $this->insertCommon(trim($term));
			}
			return;
		}
		if($num_docs < $params['n_max_docs']){
			$threshold = ( ($num_docs - $params['n_med_docs']) * ($params['p_min'] - $params['p_max']) )/ ($params['n_max_docs'] - $params['n_med_docs']) + $params['p_max'];
			foreach($array_docs as $guid) {
				if(isset($m_dt[$guid])) {
					foreach($m_dt[$guid] as $term=>$element) {
						if(!isset($array_terms[$term])) $array_terms[$term]=1;
						else $array_terms[$term]+= 1;
					}
				}
			}
			ksort($array_terms);
			foreach($array_terms as $term=>$score) {
				if($score/$num_docs >= $threshold) $this->insertCommon(trim($term));
			}
			return;
		}
		if($num_docs > $params['n_max_docs']){
			$threshold=$params['p_min'];
			foreach($array_docs as $guid) {
				if(isset($m_dt[$guid])) {
					foreach($m_dt[$guid] as $term=>$element) {
						if(!isset($array_terms[$term])) $array_terms[$term]=1;
						else $array_terms[$term]+= 1;
					}
				}
			}
			ksort($array_terms);
			foreach($array_terms as $term=>$score) {
				if($score/$num_docs >= $threshold) $this->insertCommon(trim($term));
			}
		}
	}
	
	//it tells if an element is part of the cluster or not and returns the position
	public function belongs($guid) {
		if(!is_array($this->array_docs[0])) {
			$pos=1+array_search($guid,$this->array_docs);
			return $pos;
		}
		foreach($this->array_docs as $key=>$value) {
			if($guid==$value["guid"]) return ($key+1);
		}
		return false;
	}
	
	public function return_docs() {
		if(!is_array($this->array_docs[0])) return $this->array_docs;
		foreach($this->array_docs as $value) {
			$return[]=$value["guid"];
		}
		return $return;
	}
}

?>