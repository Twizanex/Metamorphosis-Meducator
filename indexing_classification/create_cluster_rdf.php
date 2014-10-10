<?php
//it must be executed from command line, using PHP-CLI: you can make sure you have it typing "php" in the shell
//execute with php -f create_cluster_rdf.php NOMEFILE
//it must be executed for each clusters file, so for 4 files

//!!!!!!!!!!!!!!!!!!it saves all the clusters present in the file in a unique RDF file!!!!!!!!!!!!!!!!!!!!!

//definition of the class
class Cluster {
	public $commonFeatures,$type,$array_docs,$id,$timestamp,$creator,$clusteringAlgorithm,$posfeaturesAlgorithm,$confidence,$associatedTo;
	
	public function Cluster($type,$creator,$clusteringAlgorithm,$confidence) {
		global $cluster_count;
		$this->type=$type;
		$this->id = ++$cluster_count;
		$date = new DateTime();
		$this->timestamp=$date->getTimestamp();
		unset($date);
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
	
	//it tells if an element is part of the cluster or not
	public function belongs($guid) {
		if(!is_array($this->array_docs[0])) {
			$res=array_search($guid,$this->array_docs);
			return $res;
		}
		foreach($this->array_docs as $key=>$value) {
			if($guid==$value["guid"]) return $key;
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


//function
function create_cluster_rdf($array_clusters) {
	//When you write strings in PHP, include them in " ", when you have to use " inside the string use the escape character \ as shown below
	//you can concatenate strings by using .
	//you can also write string including them in ' ', this will allow you not to use the escape character when you put " characters inside the string
	//when you include the string in " ", in some cases you can also write the variables inside the string and it recognizes it
	//if you are confused, just include your strings into " ", use . to concatenate and use escape character before " inside the strings :-)
	
	$type=$array_clusters[0]->type;
	
	$file = "clusters_$type.rdf";
	
	//I think this is the 1st line that has to be written in the RDF file, or maybe it is similar
	$head="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n <rdf:RDF xml:base=\"http://purl.org/meducator/instances/\" \n xmlns:mdc=\"http://www.purl.org/meducator/ns/\" \n xmlns:dc=\"http://purl.org/dc/elements/1.1/\" \n xmlns:dcterms=\"http://purl.org/dc/terms/\" \n xmlns:foaf=\"http://xmlns.com/foaf/0.1/\" \n xmlns:owl=\"http://www.w3.org/2002/07/owl#\" \n xmlns:rdf=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\" \n xmlns:rdfs=\"http://www.w3.org/2000/01/rdf-schema#\" \n xmlns:sioc=\"http://rdfs.org/sioc/ns#\" \n xmlns:skos=\"http://www.w3.org/2009/08/skos-reference/skos.rdf\" \n xmlns:xs=\"http://www.w3.org/2001/XMLSchema\" \n xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">\n ";

	//write to the file, it the file doesn't exist create it
	file_put_contents($file,$head);
	
	foreach($array_clusters as $cluster) {
		//for each cluster it takes all the parameters
		$id=$cluster->id;
		$timestamp=$cluster->timestamp;
		$creator=$cluster->creator;
		$clusteringAlgorithm=$cluster->clusteringAlgorithm;
		$posfeaturesAlgorithm=$cluster->posfeaturesAlgorithm;
		$confidence=$cluster->confidence;
		$commonFeatures=$cluster->commonFeatures;
		$array_docs=$cluster->array_docs;
		$array_associated=array($cluster->associatedTo["metadata"], $cluster->associatedTo["uses"], $cluster->associatedTo["tags"], $cluster->associatedTo["replinks"]);
		switch($clusteringAlgorithm) {
			case "YACA": 
				$description="This is YACA";
				break;
			case "kohonen":
				$description="This is Kohonen";
				break;
			case "aggregative":
				$description="This is Kohonen";
				break;
		}
		
		//start writing into the file in APPEND
		file_put_contents($file,"<mcc:Cluster rdf:about=\"http://metamorphosis.med.duth.gr/uid#".$id."\">\n\r",FILE_APPEND);  //I think the string to write is similar to this
		file_put_contents($file," <prv:createdBy>",FILE_APPEND);
		file_put_contents($file,"  <prv:DataCreation rdf:about=\"http://metamorphosis.med.duth.gr/uid#DataCreation".$id."\">\n\r",FILE_APPEND);
		file_put_contents($file,"  	<prv:performedAt>" . $timestamp . "</performedAt>" . "\n",FILE_APPEND);  //just substitute the "aaaa"
		file_put_contents($file,"	<prv:performedBy rdf:resource=\">" . $creator . "\"/>" . "\n",FILE_APPEND);
		file_put_contents($file," 	<prv:employedArtifact>",FILE_APPEND);
		file_put_contents($file," 	 <mcc:Algorithm rdf:about=\"http://metamorphosis.med.duth.gr/ClusteringAlgorithms#".$clusteringAlgorithm."\">",FILE_APPEND);
		file_put_contents($file," 	  <mcc:hasConfidenceLevel>".$confidence."</mcc:hasConfidenceLevel>",FILE_APPEND);
		file_put_contents($file," 	  <mcc:algorithmDescription>".$description."</mcc:algorithmDescription>",FILE_APPEND); 
		file_put_contents($file," 	  <mcc:usesFeatures>".""."</mcc:usesFeatures>",FILE_APPEND); // Qua devi mettere i features utilizzati
		file_put_contents($file," 	  <mcc:type rdf:resource=\"URI_DA_DECIDERE\"/>",FILE_APPEND);
		file_put_contents($file," 	 </mcc:Algorithm>",FILE_APPEND);
		file_put_contents($file," 	</prv:employedArtifact>",FILE_APPEND);
		file_put_contents($file,"  </prv:DataCreation>\n",FILE_APPEND); 
		file_put_contents($file," </prv:createdBy>",FILE_APPEND);
		file_put_contents($file," <mcc:hasCommonFeatures>",FILE_APPEND);
		file_put_contents($file,"  <mcc:CommonFeatures rdf:about=\"http://metamorphosis.med.duth.gr/uid#CommonFeatures".$id."\">\n\r",FILE_APPEND);
		file_put_contents($file," <prv:createdBy>",FILE_APPEND);
		file_put_contents($file,"  <prv:DataCreation rdf:about=\"http://metamorphosis.med.duth.gr/uid#CommonFeaturesDataCreation".$id."\">\n\r",FILE_APPEND);
		file_put_contents($file,"  	<prv:performedAt>" . $timestamp . "</performedAt>" . "\n",FILE_APPEND);  
		file_put_contents($file,"	<prv:performedBy rdf:resource=\">" . $creator . "\"/>" . "\n",FILE_APPEND);
		file_put_contents($file," 	<prv:employedArtifact>",FILE_APPEND);
		file_put_contents($file," 	 <mcc:Algorithm rdf:about=\"http://metamorphosis.med.duth.gr/ClusteringAlgorithms#".$posfeaturesAlgorithm."\">",FILE_APPEND);
		file_put_contents($file," 	  <mcc:hasConfidenceLevel>".$confidence."</mcc:hasConfidenceLevel>",FILE_APPEND);
		file_put_contents($file," 	  <mcc:algorithmDescription>".$description."</mcc:algorithmDescription>",FILE_APPEND); 
		file_put_contents($file," 	  <mcc:usesFeatures>".""."</mcc:usesFeatures>",FILE_APPEND);
		file_put_contents($file," 	  <mcc:type rdf:resource=\"URI_DA_DECIDERE\"/>",FILE_APPEND);
		file_put_contents($file," 	 </mcc:Algorithm>",FILE_APPEND);
		file_put_contents($file," 	</prv:employedArtifact>",FILE_APPEND);
		file_put_contents($file,"  </prv:DataCreation>\n",FILE_APPEND); 
		file_put_contents($file," </prv:createdBy>",FILE_APPEND);
		
		foreach($commonFeatures as $feature) {
			file_put_contents($file,"   <mcc:title>" . $feature . "</mcc:title>" . "\n",FILE_APPEND);
		}
		
		file_put_contents($file,"  </mcc:CommonFeatures>",FILE_APPEND);
		file_put_contents($file," </mcc:hasCommonFeatures>",FILE_APPEND);
		foreach($array_associated as $associated_cluster) {
			if($associated_cluster!="") file_put_contents($file," <mcc:associatedTo rdf:resource=\"" . $associated_cluster . "\"/>" . "\n",FILE_APPEND); //Qua devi mettere l'uri dei cluster associati
		}
		
		
		//now write all the resources
		foreach($array_docs as $resource) {
			if(is_array($resource)) {
				file_put_contents($file," <mcc:containsResource rdf:resource=\"" . $resource["guid"] . "\"/>" . "\n",FILE_APPEND); //Qua devi mettere l'uri delle risorse associate
				file_put_contents($file,"aaaaa---" . $resource["inherence"] . "----aaaaaa" ."\n",FILE_APPEND); 
			}
			else {
				file_put_contents($file," <mcc:containsResource rdf:resource=\"" . $resource . "\"/>" . "\n",FILE_APPEND); //Qua devi mettere l'uri delle risorse associate
				file_put_contents($file,"aaaaa---" . "0" . "----aaaaaa" ."\n",FILE_APPEND);  //in this case we put 0
			}
		}
		file_put_contents($file,"</mcc:Cluster>",FILE_APPEND);
		
		//now write all the associated clusters (when you will get rid of the clusters files and you use only the RDF also for clusters, you will have to change my code in the web interface to see the type of the associated cluster (now no need because I specify it in the cluster)
		
	}
	if(PHP_OS=="Linux" && getmyuid()==fileowner($file)) chmod($file,0666); //set rw permissions for everybody for this file
	
	//Now you have all the data in a file
	//Next step is to put it in the SESAME RDF
	
	$post_data = file_get_contents($file); //get data from the file
	//include_once("/var/www/elgg/engine/start.php");  //include the Elgg system, adjust the path, uncomment to use the function connectToSesame below
	
	//need to have and execute a function to delete all the clusters present on the SESAME repository, because they can't be updated, ask Stefan and Davide Taibi
	
	//the function to write on the SESAME RDF is similar to this (used for the resources, I don't know if it will work also for clusters)
	//uncomment it only when you are sure of what you are doing, otherwise you could cause damage to the SESAME RDF
	//$uuid=connectToSesame($CONFIG->API_URL,$post_data,"");
}

//main that takes the input parameter, retrieves data and calls the function
$file=$argv[1];
$array_clusters=unserialize(file_get_contents($file));
create_cluster_rdf($array_clusters);

?>