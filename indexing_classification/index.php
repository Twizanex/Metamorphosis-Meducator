<?php
/*
	It launches the indexing and classification process
	Usage: php -f index.php
*/

//Your name
echo "\nWhat's your name? ";
$usern=trim(fgets(STDIN));

//Configuration options///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$new_configuration=1;
if(!file_exists("config.php")) {
	echo "\nBefore starting the process you have to configure your options";
	$new_configuration=1;
}
else {
	$cfg_file=file_get_contents("config.php");
	echo "\nThis is your current configuration:\n";
	echo $cfg_file;
	while(true) {
		echo "\nDo you want to reconfigure your options? [y/n]";
		$line = trim(fgets(STDIN));
		if(($line=="y") || ($line=="Y")) {
			$new_configuration=1;
			break;
		}
		if(($line=="n") || ($line=="N")) {
			$new_configuration=0;
			break;
		}
	}
}
if($new_configuration==1) {
	echo "\nPress any key...";
	trim(fgets(STDIN));
	passthru("php -f configure.php");
}

require_once 'config.php';
require_once 'classes.php';
require_once 'functions.php';
require_once 'elgg_start.php';  //it gets some things from Elgg system (variables, database, metadata, etc.)
if($data_source==1) require_once($ElggPath."mod/mmsearch/custom/MeducatorParser.php");

//get the fields we have to consider for the indexing process///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$tagss_fields=explode(";",$tags_fields);
sort($tagss_fields);
$usess_fields=explode(";",$uses_fields);
sort($usess_fields);
$metadatas_fields=explode(";",$metadata_fields);
sort($metadatas_fields);

//Use old data or not///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$dt_new_indexing_required=1;
$dd_new_indexing_required=1;
$new_classification_required=1;
if(file_exists($IOdir."changes")) {  //even if we don't take GUIDS of the modified/new resources from this file (we use old indexing results, we use it only in the classification), it is used to see if actually it's possible to use old results or a completely new indexing & classification is needed
	$changes=unserialize(file_get_contents($IOdir."changes"));
	if($changes["new_indexing_required"]==0) {  //if there weren't very important changes
		while(true) {
			echo "\nDo you want to use the old doc-term matrices values where possible to speedup the process? (Answer 'n' if you haven't done a complete new indexing from longtime) [y/n]";
			$line=trim(fgets(STDIN));
			if(($line=="y") || ($line=="Y")) {
				$dt_new_indexing_required=0;
				break;
			}
			if(($line=="n") || ($line=="N")) break;
		}
		
		while(true) {
			echo "\nDo you want to use the old doc-doc matrices values where possible to speedup the process? (Answer 'n' if you haven't done a complete new indexing from longtime) [y/n]";
			$line=trim(fgets(STDIN));
			if(($line=="y") || ($line=="Y")) {
				$dd_new_indexing_required=0;
				break;
			}
			if(($line=="n") || ($line=="N")) break;
		}
		
		while(true) {
			echo "\nDo you want to use the old clusters values where possible to speedup the process? (Answer 'n' if you haven't done a complete new classification from longtime) [y/n]";
			$line=trim(fgets(STDIN));
			if(($line=="y") || ($line=="Y")) {
				$new_classification_required=0;
				break;
			}
			if(($line=="n") || ($line=="N")) break;
		}
	}
}

echo "Old indexing and classification files will now be saved in the IOdir directory with the prefix old_ and will be deleted after the whole process is completed\n"; 
echo "If there are problems and the process aborts before the end, rename them deleting that prefix in order to recover old data!\n" ;
echo "Press any key... ";
trim(fgets(STDIN));

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


//Load data///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo "\nPress any key to get a new snapshot of data from MetaMorphosis...";
trim(fgets(STDIN));
$lr=get_snapshot();
//$lr=unserialize(file_get_contents($IOdir."old_lr"));
echo "\nGot snapshot...";
echo "\nPress any key to start the indexing and classification process...";
trim(fgets(STDIN));



//Indexing: Doc-term matrices creation////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if($enable_synonyms) {
	$syn_db_wnet = mysql_pconnect($syn_db_host,$syn_db_user,$syn_db_pass) or die("Unable to connect to the synonyms database\n");
	mysql_select_db($syn_db_name,$syn_db_wnet);
}

$guids=unserialize(file_get_contents($IOdir."guids"));

echo 'Starting creation doc-term matrices...' . "\n";
$metadata_dt=doctermMetadata($lr);
file_put_contents($IOdir."metadata_dt",serialize($metadata_dt));
if(PHP_OS=="Linux" && getmyuid()==fileowner($IOdir.'metadata_dt')) chmod($IOdir.'metadata_dt',0666); //set rw permissions for everybody for this file
unset($metadata_dt);

$uses_dt=doctermUses($lr);
file_put_contents($IOdir."uses_dt",serialize($uses_dt));
if(PHP_OS=="Linux" && getmyuid()==fileowner($IOdir.'uses_dt')) chmod($IOdir.'uses_dt',0666); //set rw permissions for everybody for this file
unset($uses_dt);

$tags_dt=doctermTags($lr);
file_put_contents($IOdir."tags_dt",serialize($tags_dt));
if(PHP_OS=="Linux" && getmyuid()==fileowner($IOdir.'tags_dt')) chmod($IOdir.'tags_dt',0666); //set rw permissions for everybody for this file
unset($tags_dt);
echo 'Doc-term matrices created' . "\n";




//Indexing: Doc-term to doc-doc matrices conversion////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo 'Starting creation doc-doc matrices...' . "\n";
$arr_dd=generate_dd_matrices();

$metadata_dd=$arr_dd["metadata"];
file_put_contents($IOdir.'metadata_dd',serialize($metadata_dd));
if(PHP_OS=="Linux" && getmyuid()==fileowner($IOdir.'metadata_dd')) chmod($IOdir.'metadata_dd',0666); //set rw permissions for everybody for this file
unset($metadata_dd);

$uses_dd=$arr_dd["uses"];
file_put_contents($IOdir.'uses_dd',serialize($uses_dd));
if(PHP_OS=="Linux" && getmyuid()==fileowner($IOdir.'uses_dd')) chmod($IOdir.'uses_dd',0666); //set rw permissions for everybody for this file
unset($uses_dd);

$tags_dd=$arr_dd["tags"];
file_put_contents($IOdir.'tags_dd',serialize($tags_dd));
if(PHP_OS=="Linux" && getmyuid()==fileowner($IOdir.'tags_dd')) chmod($IOdir.'tags_dd',0666); //set rw permissions for everybody for this file
unset($tags_dd);
unset($arr_dd);

$replinks_dd=docdocReplinks($lr);
file_put_contents($IOdir.'replinks_dd',serialize($replinks_dd));
if(PHP_OS=="Linux" && getmyuid()==fileowner($IOdir.'replinks_dd')) chmod($IOdir.'replinks_dd',0666); //set rw permissions for everybody for this file
unset($replinks_dd);
echo 'Doc-doc matrices created' . "\n";
echo 'Indexing finished' . "\n";




//Classification: clusters creation////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo 'Starting classification...' . "\n";
echo 'Starting creation clusters...' . "\n";
$array_clusters=create_clusters();
echo 'Clusters created' . "\n";
echo 'Starting association clusters (this might require some time)...' . "\n";
$array_clusters=associate_clusters($array_clusters);
echo 'Clusters associated' . "\n";
file_put_contents($IOdir.'clusters_metadata',serialize($array_clusters["metadata"]));
if(PHP_OS=="Linux" && getmyuid()==fileowner($IOdir.'clusters_metadata')) chmod($IOdir.'clusters_metadata',0666); //set rw permissions for everybody for this file
file_put_contents($IOdir.'clusters_uses',serialize($array_clusters["uses"]));
if(PHP_OS=="Linux" && getmyuid()==fileowner($IOdir.'clusters_uses')) chmod($IOdir.'clusters_uses',0666); //set rw permissions for everybody for this file
file_put_contents($IOdir.'clusters_tags',serialize($array_clusters["tags"]));
if(PHP_OS=="Linux" && getmyuid()==fileowner($IOdir.'clusters_tags')) chmod($IOdir.'clusters_tags',0666); //set rw permissions for everybody for this file
file_put_contents($IOdir.'clusters_replinks',serialize($array_clusters["replinks"]));
if(PHP_OS=="Linux" && getmyuid()==fileowner($IOdir.'clusters_replinks')) chmod($IOdir.'clusters_replinks',0666); //set rw permissions for everybody for this file

//create_cluster_rdf($array_clusters["metadata"]);
//create_cluster_rdf($array_clusters["uses"]);
//create_cluster_rdf($array_clusters["tags"]);
//create_cluster_rdf($array_clusters["replinks"]);

echo 'Classification finished' . "\n";

//Reset changes////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo 'Reset changes file and clean up...'."\n";
if(isset($changes)) reset_changes($changes);
else reset_changes();
echo 'Done'."\n";
?>
