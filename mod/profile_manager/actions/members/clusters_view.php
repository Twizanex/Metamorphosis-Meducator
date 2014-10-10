<head>

	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1"/>
	<title>Clusters treeView</title>

<style type="text/css">
	h3.settings {
		background: #E4E4E4;
		border-bottom-left-radius: 4px 4px;
		border-bottom-right-radius: 4px 4px;
		border-top-left-radius: 4px 4px;
		border-top-right-radius: 4px 4px;
		color: #333;
		font-size: 1,1em;
		line-height: 1em;
		margin: 10px 0px 4px;
		padding: 5px;
	}	
	.trees{
		margin-left: 0.3cm;
	}

</style>
 
<link type="text/css" rel="stylesheet" href="<?php echo $CONFIG->url ?>/mod/profile_manager/views/default/profile_manager/members/tools/autocomplete/jquery.autocomplete.css" > 
<link type="text/css" rel="stylesheet" href="<?php echo $CONFIG->url ?>/mod/profile_manager/actions/members/tools/jquery-treeview/jquery.treeview.css" />
<link type="text/css" rel="stylesheet" href="<?php echo $CONFIG->url ?>/mod/profile_manager/actions/members/tools/jquery-treeview/screen.css" />
  	
<script src="<?php echo $CONFIG->url ?>/mod/profile_manager/actions/members/tools/jquery-treeview/jquery.js" type="text/javascript"></script>
<script src="<?php echo $CONFIG->url ?>/mod/profile_manager/actions/members/tools/jquery-treeview/jquery.cookie.js" type="text/javascript"></script>
<script src="<?php echo $CONFIG->url ?>/mod/profile_manager/actions/members/tools/jquery-treeview/jquery.treeview.js" type="text/javascript"></script>
	 
<script type="text/javascript">
	$(document).ready(function(){
		$("#root_metadata").treeview({
			//prerendered: true,
			collapsed: true
		}).addClass('trees');

		$("#root_uses").treeview({
			//prerendered: true,
			collapsed: true
		}).addClass('trees');

		$("#root_tags").treeview({
			//prerendered: true,
			collapsed: true
		}).addClass('trees');

		$("#root_replinks").treeview({
			//prerendered: true,
			collapsed: true
		}).addClass('trees');

		search_id();
		$("#search_text").keyup(function(){ search_id(); });
		$("#search_select").change(function(){ search_id(); });

	});

	var search_id = function(){
		$('li[cluster_id]').hide();
		var id = $("#search_text").val();
		var ids_array = new Array();
		if(id!=""){
			ids_array = id.split(',');
			show_cluster(ids_array);
		}
		else{
			var option = $('#search_select').attr('value');
			$(option).find('li[cluster_id]').show();
		}
	}

	var show_cluster = function(arr){
		var option = $("#search_select").attr('value');
		for(var i=0; i<arr.length; i++){
			$(option).find('li[cluster_id="'+ arr[i] + '"]').show();
		}
	}
</script>
</head>
<body>

<?php
	$data=get_input("str");
	$type=get_input("type");
	if(empty($type)) $type="undefined";
	
	include($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/classes.php");
	include($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/config.php");
	
	$lr = unserialize(file_get_contents($IOdir . "lr"));
	$clusters_metadata = unserialize(file_get_contents($IOdir . 'clusters_metadata'));
	$clusters_uses = unserialize(file_get_contents($IOdir . 'clusters_uses'));
	$clusters_tags = unserialize(file_get_contents($IOdir . 'clusters_tags'));
	$clusters_replinks = unserialize(file_get_contents($IOdir . 'clusters_replinks'));

	$clusters = array($clusters_metadata, $clusters_uses, $clusters_tags, $clusters_replinks);
	$clusters_type = array("metadata","uses","tags","replinks");

	echo '<div class = "settings;">';
		echo '<h3  class = "settings">&nbsp;&nbsp;ID searched&nbsp;&nbsp;&nbsp;&nbsp;';
			echo '<input id="search_text" type="text" size="20" value="' . $data . '"></input>';
			$selected_metadata = '';
			$selected_uses = '';
			$selected_tags = '';
			$selected_replinks = '';
			$selected_all = '';

			echo '&nbsp;&nbsp;&nbsp;&nbsp;Cluster type&nbsp;&nbsp;&nbsp;&nbsp;';
			echo '<select id="search_select">';
				switch ($type) {
					case 'Metadata':
						$selected_metadata = 'selected="selected"';
						break;
					case 'Uses':
						$selected_uses = 'selected="selected"';
						break;
					case 'Tags':
						$selected_tags = 'selected="selected"';
						break;
					case 'Repl':
						$selected_replinks = 'selected="selected"';
						break;
					default:
						$selected_all = 'selected="selected"';
				}

				echo '<option ' . $selected_all . ' value="#all_clusters">All</option>';
				echo '<option ' . $selected_metadata . 'value="#metadata">Metadata</option>';
				echo '<option ' . $selected_uses . 'value="#uses">Uses</option>';
				echo '<option ' . $selected_tags . 'value="#tags">Tags</option>';
				echo '<option ' . $selected_replinks . 'value="#replinks">Replinks</option>';
			echo '</select>';
		echo '</h3>';

	echo '</div>';

	echo '<div id="all_clusters">';
		for($k = 0; $k< sizeOf($clusters_type); $k++){
			echo '<h4 class="trees">Cluster ' .  $clusters_type[$k] . '</h4>';	  
			echo '<div id="'. $clusters_type[$k] . '" class="trees">';
			echo '<ul id="root_' . $clusters_type[$k] . '" class="filetree">';

			for($i = 0; $i< sizeOf($clusters[$k]); $i++){
				echo '<li cluster_id="'.$clusters[$k][$i]->id.'">';
				echo '<span class="folder" > ['. $i .']Cluster Object (ID:'. $clusters[$k][$i]->id . ')</span>';
				echo '<ul>';

				//START POSITIVE FEATURES
				echo '<li>';
				echo '<span class="folder">Common Features</span>';
				echo '<ul>';
				for($j = 0; $j< sizeOf($clusters[$k][$i]->commonFeatures); $j++){
					echo '<li>';
					echo '<span class="file">'.$clusters[$k][$i]->commonFeatures[$j].'</span>';
					echo '</li>';
				}
				echo '</ul>';
				echo '</li>';
				//END POSITIVE FEATURES

				//START DOCUMENTS
				echo '<li>';
				echo '<span class="folder">Array Docs</span>';
				echo '<ul>';
				for($j = 0; $j< sizeOf($clusters[$k][$i]->array_docs); $j++){
					echo '<li>';
					if(is_array($clusters[$k][$i]->array_docs[$j])) echo '<span class="file">'.$clusters[$k][$i]->array_docs[$j]["guid"]. ' (' .$lr[$clusters[$k][$i]->array_docs[$j]["guid"]]->name. ')</span>';
					else echo '<span class="file">'.$clusters[$k][$i]->array_docs[$j]. ' (' .$lr[$clusters[$k][$i]->array_docs[$j]]->name. ')</span>';
					echo '</li>';
				}
				echo '</ul>';
				echo '</li>';
				//END DOCUMENTS


				//START ASSOCIATED TO

				$type = array($clusters[$k][$i]->type);
				$associatedTo_array = reorder_array($clusters_type);

				echo '<li>';
				echo '<span class="folder">Associated To</span>';
				echo '<ul>';

				for($j = 0; $j< sizeOf($associatedTo_array); $j++){
					echo '<li>';
					echo '<span class="file">'.$associatedTo_array[$j].':'.$clusters[$k][$i]->associatedTo[$associatedTo_array[$j]].'</span>';
					echo '</li>';
				}
				echo '</ul>';
				echo '</li>';
				//END ASSOCIATED TO 

				//----
				echo '</ul>';
				echo '</li>';
			}
			echo '</ul>';
			echo '</div>';
		}
	echo '</div>';

	function reorder_array($arr){
	$out = array();
	$index = 0;
	foreach($arr as $value){
	$out[$index]=$value;
	$index++;
	}
	return $out;
	}
?>	
</body>
</html>