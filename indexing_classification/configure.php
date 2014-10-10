<?php
require_once 'config.php';

$new_indexing_required=0;  //if you change some important values, next time you want to do an indexing it must be a complete new indexing

$restart=0;

$config_values=processfileForOptions("config.php");   //get current values from config.php

//Elgg Path ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo "Insert the Elgg path [".substr($config_values['ElggPath'],1,-1)."] ";
$line = trim(fgets(STDIN));
if(($line!="") && ($line!=substr($config_values['ElggPath'],1,-1))) {
	$oldvalue=$config_values["ElggPath"];
	$config_values=substituteOption($config_values,"ElggPath",$line);
	if($config_values["ElggPath"]!=$oldvalue) $restart=1;
}

$config_values2=processfileForOptions(substr($config_values["ElggPath"],1,-1) . "mod/profile_manager/views/default/profile_manager/members/config.php");   //get current values from the web interface config.php

//indexing_classification directory ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo "Insert the indexing_classification directory [".substr($config_values['IndexingClassificationPath'],1,-1)."] ";
$line = trim(fgets(STDIN));
if(($line!="") && ($line!=substr($config_values['IndexingClassificationPath'],1,-1))) {
	$oldvalue=$config_values["IndexingClassificationPath"];
	$config_values=substituteOption($config_values,"IndexingClassificationPath",$line);
	$config_values2=substituteOption($config_values2,"IndexingClassificationPath",$line);
	if($config_values["IndexingClassificationPath"]!=$oldvalue) $restart=1;
}


//I/O directory ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo "Insert the I/O directory [".substr($config_values['IOdir'],1,-1)."] ";
$line = trim(fgets(STDIN));
if(($line!="") && ($line!=substr($config_values['IOdir'],1,-1))) {
	$oldvalue=$config_values["IOdir"];
	$config_values=substituteOption($config_values,"IOdir",$line);
	$config_values2=substituteOption($config_values2,"IOdir",$line);
	if($config_values["IOdir"]!=$oldvalue) $restart=1;
}

if($restart==1) {  //if you changed some fundamental fields, you will have to restart the configuration before proceeding
	echo "\nThe new settings will be saved, then you need to restart the program to continue with the configuration\n";
	echo "\nPress any key...";
	fgets(STDIN);
	write_config_values($config_values,"config.php");
	//it saves all the changes in the config file of the web interface
	write_config_values($config_values2, substr($config_values["ElggPath"],1,-1) . "mod/profile_manager/views/default/profile_manager/members/config.php");
	echo "\nDon't forget to change your IOdir path in the file docdoc.php\n";
	exit();
}

//RDF schema or database ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$suggest_default_fields=1;
while(true) {
	echo "\nDo you want to use the RDF schema or the database as source for data? (1 is recommended) [1,2]";
	$line = trim(fgets(STDIN));
	if($line=="1" || $line=="2"){
		if($line!=$config_values['data_source']) {  //if we chose a different source, don't suggest fields
			$config_values=substituteOption($config_values,"data_source",$line);
			$config_values2=substituteOption($config_values2,"data_source",$line);
			$suggest_default_fields=0;
		}
		break;
	}
}

if($config_values['data_source']==1) {
	require_once($ElggPath."mod/mmsearch/custom/MeducatorParser.php");
	require_once($ElggPath."mod/mmsearch/custom/MeducatorMetadata.php");
	require_once($ElggPath."engine/lib/mmplus.php");
}
require_once 'elgg_start.php';  //it gets some things from Elgg system (variables, database, metadata, etc.)

echo("\nPlease wait...\n");
$fields=getfields($config_values['data_source'],$suggest_default_fields);  //get fields from the chosen source

$categories=array("metadata","uses","tags");
foreach($categories as $category) {
	while(true) {
		echo("\nChoose the fields you want to use for $category: "); 
		if($suggest_default_fields) {
			$default_fields=array();
		}
		$i=1;
		foreach($fields as $field) {
			if($suggest_default_fields) {
				if($field[$category]) $default_fields[]=$i;
			}
			echo("\n\t$i. $field[name]");
			$i++;
		}
		echo("\n\nMake your choice, using the form [num1,num2,num3...]");
		if($suggest_default_fields) {
			$default_nums=implode(",",$default_fields);
			echo ", or press Enter to choose the current selection [$default_nums]";
		}
		echo ": ";
		$line = trim(fgets(STDIN));
		if($line=="") $nums=$default_fields;
		else $nums=get_nums($line,count($fields));
		if(is_array($nums)) break;
	}
	$values=array();
	foreach($nums as $num) {
		$values[]=$fields[$num-1]["name"];
	}
	$res=implode(";",$values);
	if($res!=substr($config_values[$category."_fields"],1,-1)) {
		$new_indexing_required=1;  //since values changed, you will have to do a complete new indexing
		$config_values=substituteOption($config_values,$category."_fields",$res);
		$config_values2=substituteOption($config_values2,$category."_fields",$res);
	}
}

//Keywords limit ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

echo "Insert the keywords limit (-1 to consider all the keywords) [-1] ";
$line = trim(fgets(STDIN));
if(($line!="") && ($line!=$config_values['keywords_limit'])) {
	$config_values=substituteOption($config_values,"keywords_limit",$line);
	$config_values2=substituteOption($config_values2,"keywords_limit",$line);
	$new_indexing_required=1;
}

//Context limit ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo "Insert the context limit (-1 to consider all the contexts, 0 not to consider contexts) [-1] ";
$line = trim(fgets(STDIN));
if(($line!="") && ($line!=$config_values['context_limit'])) {
	$config_values=substituteOption($config_values,"context_limit",$line);
	$config_values2=substituteOption($config_values2,"context_limit",$line);
	$new_indexing_required=1;
}

//Sliding windows width ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if($config_values['context_limit']!=0) {   //if you are using contexts you have to choose the sliding window width, otherwise it isn't used
	echo "Insert the sliding window width [".$config_values['width_sliding_window']."] ";
	$line = trim(fgets(STDIN));
	if(($line!="") && ($line!=$config_values['width_sliding_window'])) {
		$config_values=substituteOption($config_values,"width_sliding_window",$line);
		$config_values2=substituteOption($config_values2,"width_sliding_window",$line);
		$new_indexing_required=1;
	}
}


//Synonyms support ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
while(true) {
	echo "Enable the synonyms (experimental, default is NO) [y/n] ";
	$line = trim(fgets(STDIN));
	if(($line=="y") || ($line=="Y")) {
		echo "Insert the WORDNET database host [".$config_values['syn_db_host']."] ";
		$s_host = trim(fgets(STDIN));
		if($s_host=="") $s_host=substr($config_values['syn_db_host'],1,-1);
		echo "Insert the WORDNET database username [".$config_values['syn_db_user']."] ";
		$s_user = trim(fgets(STDIN));
		if($s_user=="") $s_user=substr($config_values['syn_db_user'],1,-1);
		echo "Insert the WORDNET database password [".$config_values['syn_db_pass']."] ";
		$s_pass = trim(fgets(STDIN));
		if($s_pass=="") $s_pass=substr($config_values['syn_db_pass'],1,-1);
		echo "\nTrying to connect to the WORDNET database...";
		$link = mysql_connect($s_host,$s_user,$s_pass);
		if (!$link) {
			echo "Could not connect: " . mysql_error() ."\nUnable to enable synonyms...\n\n";
			$line="n";  //consider as you selected not to use synonyms, so go to the next if
		}
		else {
			if($s_host!=$config_values['syn_db_host']) {
				$config_values=substituteOption($config_values,"syn_db_host",$s_host);
				$config_values2=substituteOption($config_values2,"syn_db_host",$s_host);
			}
			if($s_user!=$config_values['syn_db_user']) {
				$config_values=substituteOption($config_values,"syn_db_user",$s_user);
				$config_values2=substituteOption($config_values2,"syn_db_user",$s_user);
			}
			if($s_pass!=$config_values['syn_db_pass']) {
				$config_values=substituteOption($config_values,"syn_db_pass",$s_pass);
				$config_values2=substituteOption($config_values2,"syn_db_pass",$s_pass);
			}
			if($config_values['enable_synonyms']!=1) {
				$config_values=substituteOption($config_values,"enable_synonyms",1);
				$config_values2=substituteOption($config_values2,"enable_synonyms",1);
			}
			break;
		}
	}
	if(($line=="n") || ($line=="N")) {
		if($config_values['enable_synonyms']!=0) {
			$config_values=substituteOption($config_values,"enable_synonyms",0);
			$config_values2=substituteOption($config_values2,"enable_synonyms",0);
		}
		break;
	}
}


//IDF ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
while(true) {
	echo "Enable the IDF (experimental, default is NO) [y/n] ";
	$line = trim(fgets(STDIN));
	if(($line=="y") || ($line=="Y")) {
		if($config_values['enable_idf']!=1) {
			$config_values=substituteOption($config_values,"enable_idf",1);
			$config_values2=substituteOption($config_values2,"enable_idf",1);
		}
		break;
	}
	if(($line=="n") || ($line=="N")) {
		if($config_values['enable_idf']!=0) {
			$config_values=substituteOption($config_values,"enable_idf",0);
			$config_values2=substituteOption($config_values2,"enable_idf",0);
		}
		break;
	}
}


//Doc-term to Doc-doc conversion method ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
while(true) {
	echo "Insert the method for the conversion of the matrix doc-term in doc-doc (1. PHP; 2. HipHop (C++); 3. Parallelization) [1, 2, 3] ";
	$line = trim(fgets(STDIN));
	if(($line=="1") || ($line=="2")  || ($line=="3")) {
		$config_values=substituteOption($config_values,"DT_DD_method",$line);
		$config_values=substituteOption($config_values,"num_processes",1);
		if($line==3) {
			echo "How many nodes? ";
			$line = trim(fgets(STDIN));
			$config_values=substituteOption($config_values,"num_processes",$line);
			if($line>1) echo "\n\nYou chose to use $line nodes, remember to set up properly your nodes\n\n";
		}
		break;
	}
}

//Classification method for Metadata ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
while(true) {
	echo "Insert the classification method for Metadata (1. Kohonen algorithm; 2. Aggregative algorithm; 3. YACA algorithm) [1, 2, 3] ";
	$line = trim(fgets(STDIN));
	if(($line=="1") || ($line=="2")  || ($line=="3")) {
		$config_values=substituteOption($config_values,"classification_method_metadata",$line);
		$config_values2=substituteOption($config_values2,"classification_method_metadata",$line);
		break;
	}
}

//Classification method for Tags ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
while(true) {
	echo "Insert the classification method for tags (1. Kohonen algorithm; 2. Aggregative algorithm; 3. YACA algorithm) [1, 2, 3] ";
	$line = trim(fgets(STDIN));
	if(($line=="1") || ($line=="2")  || ($line=="3")) {
		$config_values=substituteOption($config_values,"classification_method_tags",$line);
		$config_values2=substituteOption($config_values2,"classification_method_tags",$line);
		break;
	}
}


//Classification method for Use cases ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
while(true) {
	echo "Insert the classification method for Use Cases (1. Kohonen algorithm; 2. Aggregative algorithm; 3. YACA algorithm) [1, 2, 3] ";
	$line = trim(fgets(STDIN));
	if(($line=="1") || ($line=="2")  || ($line=="3")) {
		$config_values=substituteOption($config_values,"classification_method_uses",$line);
		$config_values2=substituteOption($config_values2,"classification_method_uses",$line);
		break;
	}
}


//Classification method for Replinks ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
while(true) {
	echo "Insert the classification method for Replinks (2. Aggregative algorithm; 3. YACA algorithm) [2, 3] ";
	$line = trim(fgets(STDIN));
	if(($line=="2")  || ($line=="3")) {
		$config_values=substituteOption($config_values,"classification_method_replinks",$line);
		$config_values2=substituteOption($config_values2,"classification_method_replinks",$line);
		break;
	}
}

//Kohonen output file ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if($config_values['classification_method_metadata']==1 || $config_values['classification_method_tags']==1 || $config_values['classification_method_uses']==1){
	echo "Insert the Kohonen output file [".substr($config_values['output_file_kohonen'],1,-1)."] ";
	$line =trim(fgets(STDIN));
	if(($line!="") && ($line!=substr($config_values['output_file_kohonen'],1,-1))) {
		$config_values=substituteOption($config_values,"output_file_kohonen",$line);
		$config_values2=substituteOption($config_values2,"output_file_kohonen",$line);
	}
}

//YACA threshold ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if($config_values['classification_method_metadata']==3 || $config_values['classification_method_tags']==3 || $config_values['classification_method_uses']==3 || $config_values['classification_method_replinks']==3){
	echo "Insert the YACA algorithm threshold [".$config_values['YACA_threshold']."] ";
	$line = trim(fgets(STDIN));
	if(($line!="") && ($line!=$config_values['YACA_threshold'])) {
		$config_values=substituteOption($config_values,"YACA_threshold",$line);
		$config_values2=substituteOption($config_values2,"YACA_threshold",$line);
	}
}

//Minimum association threshold ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo "Insert the minimum association threshold [".$config_values['minimum_association_threshold']."] ";
$line = trim(fgets(STDIN));
if(($line!="") && ($line!=$config_values['minimum_association_threshold'])) {
	$config_values=substituteOption($config_values,"minimum_association_threshold",$line);
	$config_values2=substituteOption($config_values2,"minimum_association_threshold",$line);
}


//View new values before saving them
print_r($config_values); 

//save configuration
while(true) {
	echo "Do you want to save the new configuration? [y/n] ";
	$line = trim(fgets(STDIN));
	if(($line=="y") || ($line=="Y")) {
		write_config_values($config_values,"config.php");
		if($new_indexing_required) {
			$changes=unserialize(file_get_contents($IOdir."changes"));  //if the file doesn't exist, it doesn't matter
			$changes["new_indexing_required"]=1;  //mark the file new_indexing in order to force a new indexing
			file_put_contents($IOdir."changes",serialize($changes));
			if(PHP_OS=="Linux") chmod($IOdir."changes",0666); //set rw permissions for everybody for this file
		}
		
		//it saves all the changes in the config file of the web interface
		write_config_values($config_values2, $ElggPath . "mod/profile_manager/views/default/profile_manager/members/config.php");
		
		echo "\nDon't forget to change your IOdir path in the file docdoc.php\n";
		break;
	}
	if(($line=="n") || ($line=="N")) break;
}


//it reads an option from the file specified (it creates an array with form array["option"]=value)
function processFileForOptions($file) {
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

//it substitutes an option from the array $config_values with a new value
function substituteOption($config_values,$option,$newvalue) {
	if(is_numeric($newvalue) || $newvalue=="true" || $newvalue=="false") $config_values[$option]=$newvalue;
	else $config_values[$option]="\"$newvalue\"";
	return $config_values;
}

//it writes the array $config_values on a file
function write_config_values($config_values,$file) {
	$fp = fopen($file, "w");
	fputs($fp,"<?php\n");
	foreach($config_values as $opt => $val) {
		fputs($fp,"$"."$opt = $val;\n");
	}
	fputs($fp,"?>\n");
	fclose($fp);
}

//get the various metadata names from RDF or database
function getfields($num,$suggest) {
	if($num==1) {  //if we chose RDF as input
		global $CONFIG;
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
	elseif($num==2) {  //if we chose database as input
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
		global $metadata_fields,$uses_fields,$tags_fields;
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

//get elements from a string formatted this way: [num1,num2,...]. It returns an array of elements or false
function get_nums($line,$limit) {
	if(substr($line,0,1)=="[" && substr($line,-1,1)=="]") {
		$nums=explode(",", substr($line,1,-1));
		if(is_array($nums) && !empty($nums)) {
			foreach($nums as $num) {
				if($num<1 || $num>$limit) return false;
			}
			return $nums;
		}
		return false;
	}
	return false;
}

?>