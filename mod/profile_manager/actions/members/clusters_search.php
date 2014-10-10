<?php
	require_once($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/config.php");
	require_once($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/classes.php");
	
	if(isset($_SESSION["clustersearch"])) $clusters_old=$_SESSION["clustersearch"];  //load the results of the last search
	else $clusters_old=array(array(),array(),array(),array());
	
	$func=$_REQUEST['func'];
	$clusters=$func($_REQUEST);  //calls the right function (idclusterSearch or respfSearch)
	$_SESSION["clustersearch"]=$clusters; //it saves the list of clusters returned from the query in the session
	$clusters_type = array("Metadata", "Uses", "Tags", "Replinks");

	// Build the horizontal tabbed nav and fill it with list elements coming from $cluster_type records
	// then create div_container for search result
	echo "<div id='clusters_tabs' class='contentWrapper'>";
		//echo "<h3 class='settings'>Cluster Search</h3>";
		echo "<div id='elgg_horizontal_tabbed_nav'>";
			echo	"<ul>";
				foreach($clusters_type as $key=>$value){
				?>
					<li id='<?php echo strtolower($value);?>_tab_cs'><a href='javascript:void(0);' onclick="tab_on_click($(this).closest('li').attr('id'),'<?php echo strtolower($value);?>');"><?php echo $value;?></a></li>
				<?php		
				}
			echo	"</ul>";
		echo	"</div>";
		echo "<h3 class='settings'>Cluster search results";
		if($func=="respfSearch") echo "<span style='width:98px; float:right'>Relevance</span>";
		echo "</h3>";
		foreach($clusters_type as $key=>$value){
			echo "<div id=".strtolower($value)."_div class='div_container'></div>";
		}		
	echo	"</div>";
?>
<script src="<?php echo $CONFIG->url ?>mod/profile_manager/actions/members/List.js"></script>
<script type="text/javascript">
	
	// From thisId (id of clicked list element) and type of cluster this function adds the 'selected' class only to the clicked tab
	// and shows the proper results div
	
	function tab_on_click(thisId,type){
		var tabContainer = $('#'+thisId).closest('.contentWrapper').attr('id');
		$('#'+tabContainer+' li').removeClass('selected');
		$('#'+thisId).addClass('selected');
		$('#'+tabContainer+' .div_container').hide();
		$('#'+type+'_div').show();				
	};

	$(document).ready(function(){

		var clusters = new Array();
		var idrank_array = new Array();

		<?php
			function init(){
				echo ("idrank_array = { 'guid' : '','name' : '','username' : '','resource' : 0, 'type' : 'cluster', 'rank' : '', 'newelement' : ''};");
			}

			for($i=0; $i<count($clusters); $i++){
				echo ("clusters[" . $i . "] = new Array();\n");
				for($j=0; $j<count($clusters[$i]); $j++){
					init();          
					echo ("idrank_array.name = idrank_array.guid = '" . $clusters[$i][$j]["id"] . "';\n");
					echo ("idrank_array.rank = '" . $clusters[$i][$j]["rank"] . "';\n");
					echo ("idrank_array.newelement = 1;\n");
					foreach($clusters_old[$i] as $h => $c) {
						if($clusters[$i][$j]["id"]==$clusters_old[$i][$h]["id"]) echo ("idrank_array.newelement = 0;\n");
					}
					echo ("clusters[" . $i . "][" . $j . "]=idrank_array;\n");
				}
			}
			
			// Build List object for each type of cluster results and append them to proper div
			foreach($clusters_type as $key=>$value){
				echo "var ".strtolower($value)."_results = $('#".strtolower($value)."_div');\n";
				echo	"var container".$key." = $('<div class=res_box></div>').appendTo(".strtolower($value)."_results);\n";
				echo "var clusters_list = new List({\n";
				echo "array_data : clusters[".$key."],\n";
				echo "container : $('<div></div>').appendTo(container".$key."),\n";
				echo "base_url : base_url,\n";
				echo "cck: 0\n";
				echo "});\n";
			}
		?>
		// After loading this page these instruction will select metadata_tab_cs and hide all container except metadata_div
		$('#metadata_tab_cs').addClass('selected');
		$('.div_container').hide();
		$('#metadata_div').show();				

	});
</script>

<?php
function idclusterSearch($req) {
	global $CONFIG;	
	include ($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/config.php");
	require_once ($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/classes.php");
	$clusters_metadata = unserialize(file_get_contents($IOdir . 'clusters_metadata'));
	$clusters_uses = unserialize(file_get_contents($IOdir . 'clusters_uses'));
	$clusters_tags = unserialize(file_get_contents($IOdir . 'clusters_tags'));
	$clusters_replinks = unserialize(file_get_contents($IOdir . 'clusters_replinks'));
	$clusters = array($clusters_metadata, $clusters_uses, $clusters_tags, $clusters_replinks);

	$req_idcluster = $req['idcluster'];
	
	//create and format well the idclusterList array
	$idclusterList = array();
	$idclusterList = explode(',', rtrim($req_idcluster,', '));
	for($i=0; $i<count($idclusterList);$i++) {
		$idclusterList[$i]=(int)$idclusterList[$i];
	}

	$clusters1 = array();

	if ($req_idcluster!=""){
		for ($i = 0; $i < 4; $i++) {
			$index=0;
			for ($j = 0; $j < count($clusters[$i]); $j++) {
				if(in_array($clusters[$i][$j]->id,$idclusterList)) {
					$clusters1[$i][$index]["id"]=$clusters[$i][$j]->id;
					$clusters1[$i][$index]["commonFeatures"]=$clusters[$i][$j]->commonFeatures;
					$clusters1[$i][$index]["rank"]=-1;  //rank is a non sense if search is based on clusters ids (it must belong or not)
					$index++;
				}
			}
		}
	}
	else {
		for ($i = 0; $i < 4; $i++) {
			for ($j = 0; $j < count($clusters[$i]); $j++) {
				$clusters1[$i][$j]["id"]=$clusters[$i][$j]->id;
				$clusters1[$i][$j]["commonFeatures"]=$clusters[$i][$j]->commonFeatures;
				$clusters1[$i][$j]["rank"]=-1;  //rank is a non sense if search is based on clusters ids (it must belong or not)
			}
		}
	}
	
	//function that sorts two documents by their id
	function compare_id($a, $b) {
		return strcmp($a['id'], $b['id']);
	}
	
	//sort the documents by their id
	for ($i = 0; $i < 4; $i++) {
		usort($clusters1[$i],"compare_id");
	}
	
	//file_put_contents("cl1",serialize($clusters1));
	return $clusters1;
}

function respfSearch($req) {
	global $CONFIG;	
	include ($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/config.php");
	require_once ($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/classes.php");
	$clusters_metadata = unserialize(file_get_contents($IOdir . 'clusters_metadata'));
	$clusters_uses = unserialize(file_get_contents($IOdir . 'clusters_uses'));
	$clusters_tags = unserialize(file_get_contents($IOdir . 'clusters_tags'));
	$clusters_replinks = unserialize(file_get_contents($IOdir . 'clusters_replinks'));
	$clusters = array($clusters_metadata, $clusters_uses, $clusters_tags, $clusters_replinks);
	
	$req_guidresource = $req['guidresource'];
	$req_posfeatures = $req['posfeatures'];
	
	//create and format well the guidresourceList array
	$guidresourceList = array();
	$guidresourceList = explode(',', rtrim($req_guidresource,', '));
	for($i=0; $i<count($guidresourceList);$i++) {
		$guidresourceList[$i]=(int)$guidresourceList[$i];
	}
	
	//create and format well the positiveFeaturesList array
	$positiveFeaturesList = array();
	$positiveFeaturesList = explode(',', rtrim($req_posfeatures,', '));
	for($i=0; $i<count($positiveFeaturesList);$i++) {
		$positiveFeaturesList[$i]=strtolower(trim($positiveFeaturesList[$i]));
	}

	$clusters1 = array();
	$clusters2 = array();
	$clusters3 = array();
	
	//----------------------1st phase------------------------
	if ($req_guidresource!=''){
		for ($i = 0; $i < 4; $i++) {
			for ($j = 0; $j < count($clusters[$i]); $j++) {
				if(is_array($clusters[$i][$j]->array_docs[0])) {
					$intersection=array();
					foreach($clusters[$i][$j]->array_docs as $docpack) {
						if(in_array($docpack["guid"],$guidresourceList)) $intersection[]=$docpack["guid"];
					}
				}
				else $intersection = array_intersect($guidresourceList,$clusters[$i][$j]->array_docs);
				$clusters1[$i][$j]["id"]=$clusters[$i][$j]->id;
				$clusters1[$i][$j]["commonFeatures"]=$clusters[$i][$j]->commonFeatures;
				$clusters1[$i][$j]["rank"]=count($intersection)/count($guidresourceList);
			}
		}
	}
	else {
		for ($i = 0; $i < 4; $i++) {
			for ($j = 0; $j < count($clusters[$i]); $j++) {
				$clusters1[$i][$j]["id"]=$clusters[$i][$j]->id;
				$clusters1[$i][$j]["commonFeatures"]=$clusters[$i][$j]->commonFeatures;
				$clusters1[$i][$j]["rank"]=-2;   //if we haven't specified any resource guid, we give rank the value -2
			}
		}
	}
	
	//----------------------2nd phase------------------------
	if ($req_posfeatures!='null'){
		for ($i = 0; $i < 4; $i++) {
			$intersection = array();
			$difference = array();
			for ($j = 0; $j < count($clusters1[$i]); $j++) {
				$intersection = array_intersect($positiveFeaturesList,$clusters1[$i][$j]["commonFeatures"]);  //take, among the positive features specified, only the ones belonging to the current cluster
				$clusters2[$i][$j]=$clusters1[$i][$j];
				if($clusters2[$i][$j]["rank"]!=-2) $clusters2[$i][$j]["rank"]+=count($intersection)/count($positiveFeaturesList);  //if the first step hasn't -2 as result, we simply add the new rank
				else $clusters2[$i][$j]["rank"]=(count($intersection)/count($positiveFeaturesList))*2;   //if the first step has -2 as result, we consider doubly the second step rank
			}
		}
	}
	else {
		for ($i = 0; $i < 4; $i++) {
			for ($j = 0; $j < count($clusters[$i]); $j++) {
				$clusters2[$i][$j]=$clusters1[$i][$j];
				if($clusters2[$i][$j]["rank"]!=-2) $clusters2[$i][$j]["rank"]=$clusters1[$i][$j]["rank"]*2; //if the first step hasn't -2 as result, considering that we didn't put anything as positive features, we consider doubly the first step rank
				else $clusters2[$i][$j]["rank"]=2;  //if the first step has -2 as result, considering that we didn't put anything as positive features, we consider every document ok with maximum rank
			}
		}
	}
	
	//delete the documents with rank 0
	for ($i = 0; $i < 4; $i++) {
		for ($j = 0; $j < count($clusters2[$i]); $j++) {
			if($clusters2[$i][$j]["rank"]!=0) $clusters3[$i][]=$clusters2[$i][$j];
		}
	}

	//function that sorts two documents by their rank and then by their id
	function compare_rank_id($a, $b) {
		$retval = -(strcmp($a['rank'], $b['rank']));
		if(!$retval) return strcmp($a['id'], $b['id']);
		return $retval;
	}
	
	//sort the documents by their rank and then by their id
	for ($i = 0; $i < 4; $i++) {
		usort($clusters3[$i],"compare_rank_id");
	}
	
	return $clusters3;
}
?>
