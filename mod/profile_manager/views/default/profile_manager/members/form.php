<?php

	function getBrowser()  { 
		$u_agent = $_SERVER['HTTP_USER_AGENT']; 
		$bname = 'Unknown';
		$platform = 'Unknown';
		$version= "";

		//First get the platform?
		if (preg_match('/linux/i', $u_agent)) {
			$platform = 'linux';
		}
		elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
			$platform = 'mac';
		}
		elseif (preg_match('/windows|win32/i', $u_agent)) {
			$platform = 'windows';
		}
	    
		// Next get the name of the useragent yes seperately and for good reason
		if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))  { 
			$bname = 'Internet Explorer'; 
			$ub = "MSIE"; 
		} 
		elseif(preg_match('/Firefox/i',$u_agent)) { 
			$bname = 'Mozilla Firefox'; 
			$ub = "Firefox"; 
		} 
		elseif(preg_match('/Chrome/i',$u_agent)) { 
			$bname = 'Google Chrome'; 
			$ub = "Chrome"; 
		} 
		elseif(preg_match('/Safari/i',$u_agent)) { 
			$bname = 'Apple Safari'; 
			$ub = "Safari"; 
		} 
		elseif(preg_match('/Opera/i',$u_agent))  { 
			$bname = 'Opera'; 
			$ub = "Opera"; 
		} 
		elseif(preg_match('/Netscape/i',$u_agent)) { 
			$bname = 'Netscape'; 
			$ub = "Netscape"; 
		} 
	    
		// finally get the correct version number
		$known = array('Version', $ub, 'other');
		$pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
		if (!preg_match_all($pattern, $u_agent, $matches)) {
			// we have no matching number just continue
		}
	    
		// see how many we have
		$i = count($matches['browser']);
		if ($i != 1) {
			//we will have two since we are not using 'other' argument yet
			//see if version is before or after the name
			if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
				$version= $matches['version'][0];
			}
			else {
				$version= $matches['version'][1];
			}
		}
		else {
			$version= $matches['version'][0];
		}
	    
		// check if we have a number
		if ($version==null || $version=="") {$version="?";}
	    
		return array(
			'userAgent' => $u_agent,
			'name'      => $bname,
			'version'   => $version,
			'platform'  => $platform,
			'pattern'    => $pattern
		);
	} 

	require_once ($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/config.php");
	if(file_exists($IOdir."clusters_metadata") && file_exists($IOdir."clusters_uses") && file_exists($IOdir."clusters_tags") && file_exists($IOdir."clusters_replinks") && file_exists($IOdir."metadata_dd") && file_exists($IOdir."uses_dd") && file_exists($IOdir."tags_dd") && file_exists($IOdir."replinks_dd")) {
		$browser = getBrowser();
		if($browser["name"]=="Internet Explorer") {
			?>
			<script type="text/javascript">
			alert("It seems you are using Internet Explorer. If you are using an old version, maybe the exploratory search won't work as intended. Please download the latest version or, better, a standards compliant browser, such as Google Chrome or Mozilla Firefox");
			</script>
			<?php
		}
		$exploratorysearch=1;
	}
	else {
		$exploratorysearch=0;
		if(issuperadminloggedin()){
			?>
			<script type="text/javascript">
			alert("It's not possible to use exploratory search! To make it work, do a new snapshot of data and the whole process of indexing and classification");
			</script>
			<?php
		}
		else {
			?>
			<script type="text/javascript">
			alert("It's not possible to use exploratory search in this moment! Try again later...");
			</script>
			<?php
		}
	}
	
	$ts = time();
	$token = generate_action_token($ts);
	$url_security = "?__elgg_ts=" . $ts . "&__elgg_token=" . $token;
	
	$default_search_criteria = "<tr><td colspan='2'>";
	//$default_search_criteria .= elgg_echo("name") . elgg_view("input/text", array("internalname" => "user_data_partial_search_criteria[name]"));
	$default_search_criteria .= "<div id='autocomplete_display'></div>";  //
	$default_search_criteria .= elgg_echo("name") . "<br /><input type='text' class='input-text' name='user_data_partial_search_criteria[name]' id='search_input' />";  //
	$default_search_criteria .= "</td></tr><tr>";
	
	$profile_type_count = get_entities_from_metadata("show_on_members", "yes", "object", CUSTOM_PROFILE_FIELDS_PROFILE_TYPE_SUBTYPE, null, null, null, null, null, true);
	if($profile_type_count > 0){
		$profile_types = get_entities_from_metadata("show_on_members", "yes", "object", CUSTOM_PROFILE_FIELDS_PROFILE_TYPE_SUBTYPE, null, $profile_type_count);

		foreach($profile_types as $profile_type){
			// label
			$title = $profile_type->getTitle();
			
			$options[$title] = $profile_type->guid;
		}
				 
		$default_search_criteria .=  "<td>";
		$default_search_criteria .=  elgg_echo("profile_manager:profile_types:list:title") . "<br />";
		$default_search_criteria .=  elgg_view("input/checkboxes", array("internalname" => "profile_all_selector", "options" => array(elgg_echo("all")), "value" => elgg_echo("all") ,  "js" => "onchange='toggle_profile_type_selection($(this).parents(\"form\").attr(\"id\"));'"));
		$default_search_criteria .=  elgg_view("input/checkboxes", array("internalname" => "meta_data_array_search_criteria[custom_profile_type]", "options" => $options));
		$default_search_criteria .=  "</td>";
	} else {
		$default_search_criteria .=  "<td></td>";
	}
	
	$default_search_criteria .= "<td>" . elgg_echo("profile_manager:members:searchform:sorting"). "<br />";
	//$default_search_criteria .= elgg_view("input/radio", array("internalname" => "sorting", "value" => "newest", "options" => array(elgg_echo("alphabetic") => "alphabetic", elgg_echo("newest") => "newest", elgg_echo("popular") => "popular", elgg_echo("online") => "online")));
	$default_search_criteria .= elgg_view("input/radio", array("id" => "sorting_id", "internalname" => "sorting", "value" => "newest", "options" => array(elgg_echo("alphabetic") => "alphabetic", elgg_echo("newest") => "newest", elgg_echo("popular") => "popular", elgg_echo("online") => "online")));
	$default_search_criteria .= "</td></tr>";
	
	$simple_search_criteria = "";
	
	$simple_search_fields_count = get_entities_from_metadata("simple_search", "yes", "object", CUSTOM_PROFILE_FIELDS_PROFILE_SUBTYPE, $CONFIG->site_guid, "", null, null,null, true);
	if($simple_search_fields_count > 0){
		$simple_search_fields = get_entities_from_metadata("simple_search", "yes", "object", CUSTOM_PROFILE_FIELDS_PROFILE_SUBTYPE, $CONFIG->site_guid, $simple_search_fields_count);
		
		foreach($simple_search_fields as $field){
			if($field->admin_only != "yes" || isadminloggedin()){
				$ordered_simple_search_fields[$field->order] = $field;
			}
		}
		ksort($ordered_simple_search_fields);
		
		foreach($ordered_simple_search_fields as $field){
			$metadata_name = $field->metadata_name;
			$metadata_type = $field->metadata_type;
			if($metadata_type == "longtext" || $metadata_type == "plaintext"){
				$metadata_type = "text";
			}
			// make title
			$title = $field->getTitle();
			
			// get options
			$options = $field->getOptions();
	
			// type of search
			$search_type = get_search_type($metadata_type);
			
			// output field row
			$simple_search_criteria .= "<tr><td colspan='2'>";
			$simple_search_criteria .= $title . "<br />";
			
			if($search_type == "meta_data_between_search_criteria"){
				$simple_search_criteria .= elgg_echo("profile_manager:members:searchform:date:from") . " ";
				$simple_search_criteria .= elgg_view("input/" . $metadata_type, array(
					"internalname" => $search_type . "[" . $metadata_name . "][FROM]"));
				$simple_search_criteria .= " " . elgg_echo("profile_manager:members:searchform:date:to") . " ";
				$simple_search_criteria .= elgg_view("input/" . $metadata_type, array(
					"internalname" => $search_type . "[" . $metadata_name . "][TO]"));
			} else {
				$simple_search_criteria .= elgg_view("input/" . $metadata_type, array(
						"internalname" => $search_type . "[" . $metadata_name . "]",
						"options" => $options));
			}
			$simple_search_criteria .= "</td></tr>";
		}
	}
	
	$advanced_search_criteria = "";
	
	$advanced_search_fields_count = get_entities_from_metadata("advanced_search", "yes", "object", CUSTOM_PROFILE_FIELDS_PROFILE_SUBTYPE, $CONFIG->site_guid, "", null, null,null, true);
	if($advanced_search_fields_count > 0){
		$advanced_search_fields = get_entities_from_metadata("advanced_search", "yes", "object", CUSTOM_PROFILE_FIELDS_PROFILE_SUBTYPE, $CONFIG->site_guid, $advanced_search_fields_count);
		
		foreach($advanced_search_fields as $field){
			if($field->admin_only != "yes" || isadminloggedin()){
				$ordered_advanced_search_fields[$field->order] = $field;
			}
		}
		ksort($ordered_advanced_search_fields);
		
		foreach($ordered_advanced_search_fields as $field){
			$metadata_name = $field->metadata_name;
			$metadata_type = $field->metadata_type;
			if($metadata_type == "longtext" || $metadata_type == "plaintext"){
				$metadata_type = "text";
			}
			// make title
			$title = $field->getTitle();

			// get options
			$options = $field->getOptions();
	
			// type of search
			$search_type = get_search_type($metadata_type);
			
			// output field row
			$advanced_search_criteria .= "<tr><td colspan='2'>";
			$advanced_search_criteria .= $title . "<br />";
			
			if($search_type == "meta_data_between_search_criteria"){
				$advanced_search_criteria .= elgg_echo("profile_manager:members:searchform:date:from") . " ";
				$advanced_search_criteria .= elgg_view("input/" . $metadata_type, array(
					"internalname" => $search_type . "[" . $metadata_name . "][FROM]"));
				$advanced_search_criteria .= " " . elgg_echo("profile_manager:members:searchform:date:to") . " ";
				$advanced_search_criteria .= elgg_view("input/" . $metadata_type, array(
					"internalname" => $search_type . "[" . $metadata_name . "][TO]"));
			} else {
				$advanced_search_criteria .= elgg_view("input/" . $metadata_type, array(
						"internalname" => $search_type . "[" . $metadata_name . "]",
						"options" => $options));
			}
			$advanced_search_criteria .= "</td></tr>";
		}
	}
	
	function get_search_type($metadata_type){
		$type = "meta_data_partial_search_criteria";
		if($metadata_type == "multiselect"){
			$type = "meta_data_array_search_criteria";
		} elseif($metadata_type == "pulldown" || $metadata_type == "radio") {
			$type = "meta_data_exact_search_criteria";
		} elseif($metadata_type == "datepicker" || $metadata_type == "calendar"){
			$type = "meta_data_between_search_criteria";
		} 
		return $type;
	}
	
?>
<style type="text/css">
	.hasDatepick {
		width: 100px !important;
	}
</style>
<script src="<?php echo $CONFIG->url ?>mod/profile_manager/views/default/profile_manager/members/tools/autocomplete/jquery.autocomplete.js"></script>
<link type="text/css" rel="stylesheet" href="<?php echo $CONFIG->url ?>/mod/profile_manager/views/default/profile_manager/members/tools/autocomplete/jquery.autocomplete.css" >
<script type="text/javascript">
	var formdata;
	var base_url ='<?php echo $CONFIG->url ?>';
	var stopprocess=0;
	var num_checkboxes;
	var fields_metadata= new Array();
	var fields_uses= new Array();
	var fields_tags= new Array();
	
	var issuperadminloggedin;
	if(<?php if(issuperadminloggedin()) echo 1; else echo 0;?>) issuperadminloggedin=1;
	else issuperadminloggedin=0;
	
	var exploratorysearch;
	if(<?php if($exploratorysearch) echo 1; else echo 0;?>) exploratorysearch=1;
	else exploratorysearch=0;
	
	function perform_members_search(formid){
		$("body").addClass("profile_manager_members_wait");

		formdata = $("#" + formid).serialize();
		
		$.post("<?php echo $vars['url'];?>action/profile_manager/members/search<?php echo $url_security;?>", formdata, function(data){
			$("#members_search_result").html(data);
			$("body").removeClass("profile_manager_members_wait");
		});
	}

	function navigate_members_search(offset){
		$("body").addClass("profile_manager_members_wait");
		$.post("<?php echo $vars['url'];?>action/profile_manager/members/search<?php echo $url_security;?>&offset=" + offset, formdata, function(data){
			$("#members_search_result").html(data);
			$("body").removeClass("profile_manager_members_wait");
		});
	}

	function toggle_profile_type_selection(formid){
		var status = "disabled";
		
		if(formid != undefined){
			formid = "#" + formid + " ";
		} else {
			var formid = "";
		}

		if(formid != ""){
			if($(formid + "input[name='profile_all_selector[]']").attr("checked") == false){
				status = "";
			}
		}

		$(formid + "input[name='meta_data_array_search_criteria[custom_profile_type][]']").attr("disabled", status);		
	}
	
	// This function uses an AJAX call to get data from cluster_search and writes them in #cluster_search_result div
	function perform_cluster_search(){
		$("body").addClass("profile_manager_members_wait");

		if(($('#idclusterTxt').attr('disabled'))==false) {  //if it's enabled
			var data2send = {
				'idcluster' : $("#idclusterTxt").val(),
				'func' : 'idclusterSearch'
			}
		}
		else {
			//~ var pattern = /[A-z0-9áÁàÀâÂåÅãÃäÄæÆçÇéÉèÈêÊëËíÍìÌîÎïÏñÑóÓòÒôÔøØõÕöÖßúÚùÙûÛüÜÿ]+/g;
			//~ if ($("#positiveFeaturesTxt").val().match(pattern)){
				//~ var formdata = $("#positiveFeaturesTxt").val().match(pattern).toString().replace(/,+/g,', ');
			if ($("#positiveFeaturesTxt").val()!=''){
				var positiveFeaturesTxtData = $("#positiveFeaturesTxt").val().toString().replace(/\,+/g,',').replace(/\, $/g,'');
				$("#positiveFeaturesTxt").val(positiveFeaturesTxtData);
			}
			else{
				$("#positiveFeaturesTxt").empty();
				var positiveFeaturesTxtData = 'null';
			}

			var data2send = {
				'guidresource' : $("#guidresourceTxt").val(),
				'posfeatures' : positiveFeaturesTxtData,
				'func' : 'respfSearch'
			}
		}

		$.post(base_url + "/pg/mod/profile_manager/actions/members/clusters_search.php", data2send, function(data){
			$("#clusters_search_result").html(data);
			$("body").removeClass("profile_manager_members_wait");
		});
	}
	
	function toggle_img_sacp() {
		if($("#img_sacp").attr("src")=="<?php echo $CONFIG->url;?>mod/profile_manager/views/default/profile_manager/members/expand.gif") $("#img_sacp").attr("src","<?php echo $CONFIG->url;?>mod/profile_manager/views/default/profile_manager/members/expand-inverse.gif");
		else $("#img_sacp").attr("src","<?php echo $CONFIG->url;?>mod/profile_manager/views/default/profile_manager/members/expand.gif");
	}
	
	function toggle_img_choosefields() {
		if($("#img_choosefields").attr("src")=="<?php echo $CONFIG->url;?>mod/profile_manager/views/default/profile_manager/members/expand.gif") $("#img_choosefields").attr("src","<?php echo $CONFIG->url;?>mod/profile_manager/views/default/profile_manager/members/expand-inverse.gif");
		else $("#img_choosefields").attr("src","<?php echo $CONFIG->url;?>mod/profile_manager/views/default/profile_manager/members/expand.gif");
	}
	
	function initialize_type_radio() {
		$('#idclusterTxt').attr('disabled','');
		$('#guidresourceTxt').attr('disabled','disabled');
		$('#positiveFeaturesTxt').attr('disabled','disabled');
	}
	
	//it shows the number of edited and new resources
	function get_statistics() {
		if(<?php if(file_exists($IOdir."changes")) echo 1; else echo 0;?>==0) return;
		var data2send= {
			'func' : 'get_statistics'
		};			
		$.post(base_url + "/pg/mod/profile_manager/views/default/profile_manager/members/rpc.php", data2send, function(data){
			if(data) {
				$('<span>There are<br><b id="new_elements">'+data.new_elements+'</b> new elements<br><b id="edited_elements">'+data.edited_elements+'</b> edited elements<br>since last classification</span><br /><br />').appendTo($('#statistics_div'));
				if(issuperadminloggedin==1 && ($('#new_elements').text()>'5' || $('#edited_elements').text()>'5')) {
					alert("There are "+$('#new_elements').text()+" new elements and "+$('#edited_elements').text()+" modified elements...\nPerhaps you should do a new snapshot of data and then a new indexing and classification");
				}
			}
		},'json');
	}
	
	//it calls the PHP function apply_icconffields, which saves the configuration settings in the file config.php
	function apply_icconffields() {
		if($('#datasource_select').val()=="nothing") {
			alert("You must first select a data source");
			return;
		}
		$('#wait_icconfigsettings_div').fadeIn();
		fields_metadata=new Array();
		fields_uses=new Array();
		fields_tags=new Array();
		for(i=0;i<num_checkboxes;i++) {
			if($('#chk_metadata_' +i).attr("checked")) fields_metadata[i] = $('#chk_metadata_' +i).next().text();
			if($('#chk_uses_' +i).attr("checked")) fields_uses[i] = $('#chk_uses_' +i).next().text();
			if($('#chk_tags_' +i).attr("checked")) fields_tags[i] = $('#chk_tags_' +i).next().text();
		}
		var data2send= {
			'func' : 'apply_icconffields',
			'datasource_select': $('#datasource_select').val(),
			'fields_metadata' : JSON.stringify(fields_metadata),
			'fields_uses' : JSON.stringify(fields_uses),
			'fields_tags' : JSON.stringify(fields_tags),
			'text_numkeywords' : $('#text_numkeywords').val(),
			'text_numcontexts' : $('#text_numcontexts').val(),
			'text_slidingwindow_width' : $('#text_slidingwindow_width').val(),
			'idf_chkbx' : $('#idf_chkbx').attr('checked'),
			'synonyms_chkbx' : $('#synonyms_chkbx').attr('checked'),
			'metadata_select' : $('#metadata_select').val(),
			'uses_select' : $('#uses_select').val(),
			'tags_select' : $('#tags_select').val(),
			'replinks_select' : $('#replinks_select').val()
		};
		if($('#synonyms_chkbx').attr('checked')==true) {
			data2send.text_syn_db_host=$('#text_syn_db_host').val();
			data2send.text_syn_db_user=$('#text_syn_db_user').val();
			data2send.text_syn_db_pass=$('#text_syn_db_pass').val();
			data2send.text_syn_db_name=$('#text_syn_db_name').val();
		}
		if($('#metadata_select').val()=="Kohonen" || $('#uses_select').val()=="Kohonen" || $('#tags_select').val()=="Kohonen") data2send.text_kohonen_output=$('#text_kohonen_output').val();
		if($('#metadata_select').val()=="YACA" || $('#uses_select').val()=="YACA" || $('#tags_select').val()=="YACA" || $('#replinks_select').val()=="YACA") {
			data2send.text_yaca_threshold=$('#text_yaca_threshold').val();
			data2send.text_min_threshold=$('#text_min_threshold').val();
		}
		$.post(base_url + "/pg/mod/profile_manager/views/default/profile_manager/members/rpc.php", data2send, function(data){
			$('#wait_icconfigsettings_div').fadeOut();
			if(data) alert("Configuration saved!");
			else alert("Error while saving the new configuration settings! Please retry!");
		},'json');	
	}
	
	//it gets all the fields, so you can choose the ones you want to use for indexing and classification
	//fields already used (written in config.php) are checked
	function getfields() {
		if($('#datasource_select').val()=="nothing") {
			alert("You must select a data source");
			return;
		}
		//disables buttons while it is getting fields
		$('#datasource_select').attr('disabled','disabled');
		var button_ic_confapply_onclick=$('#button_ic_confapply').attr('onClick');
		var button_mainconfapply_onclick=$('#button_mainconfapply').attr('onClick');
		var button_startprocess_onclick=$('#button_startprocess').attr('onClick');
		var button_stop_readoutputfile_onclick=$('#button_stop_readoutputfile').attr('onClick');
		$('#button_ic_confapply').attr('onClick','alert("Wait for the other actions to complete");');
		$('#button_mainconfapply').attr('onClick','alert("Wait for the other actions to complete");');
		$('#button_startprocess').attr('onClick','alert("Wait for the other actions to complete");');
		$('#button_stop_readoutputfile').attr('onClick','alert("Wait for the other actions to complete");');
		$('#metadata_fields').empty();
		$('#uses_fields').empty();
		$('#tags_fields').empty();
		$('#wait_datasource_div').fadeIn();
		$('#wait_fields_div').fadeIn();
		var datasource;
		if($('#datasource_select').val()=="sesame") datasource=1;
		else if($('#datasource_select').val()=="elggdb") datasource=2;
		
		var data2send= {
			'func' : 'getfields',
			'datasource' : datasource
		};
		$.post(base_url + "/pg/mod/profile_manager/views/default/profile_manager/members/rpc.php", data2send, function(data){
			//enable buttons again
			$('#datasource_select').attr('disabled','');
			$('#button_ic_confapply').attr('onClick',button_ic_confapply_onclick);
			$('#button_mainconfapply').attr('onClick',button_mainconfapply_onclick);
			$('#button_startprocess').attr('onClick',button_startprocess_onclick);
			$('#button_stop_readoutputfile').attr('onClick',button_stop_readoutputfile_onclick);
			$('#wait_datasource_div').hide();
			$('#wait_fields_div').hide();
			for(i=0;i<data.length;i++) {
				$('<input id="chk_metadata_' +i+ '" type="checkbox" /><span id="text_metadata_' +i+ '"> ' +data[i].name+ '</span><br />').appendTo($('#metadata_fields'));
				if(data[i].metadata) $('#chk_metadata_'+i).attr('checked',true);
				$('<input id="chk_uses_' +i+ '" type="checkbox" /><span id="text_uses_' +i+ '"> ' +data[i].name+ '</span><br />').appendTo($('#uses_fields'));
				if(data[i].uses) $('#chk_uses_'+i).attr('checked',true);
				$('<input id="chk_tags_' +i+ '" type="checkbox" /><span id="text_tags_' +i+ '"> ' +data[i].name+ '</span><br />').appendTo($('#tags_fields'));
				if(data[i].tags) $('#chk_tags_'+i).attr('checked',true);
			}
			num_checkboxes=i;
		},'json');
	}
	

	//it calls the PHP function apply_mainconffields, which saves the main configuration settings in the file config.php
	function apply_mainconffields() {
		if($('#useoldinterface_chkbx').attr('checked')==true && $('#text_oldinterface').val()=="") {
			alert("You have to configure your old command-line interface path");
			return;
		}
		$('#wait_mainconfigsettings_div').fadeIn();
		var crosscheck_chkbx= 0;
		if($('#crosscheck_chkbx').attr('checked')==true) crosscheck_chkbx=1; 
		var data2send= {
			'func' : 'apply_mainconffields',
			'text_iodir' : $('#text_iodir').val(),
			'crosscheck_chkbx' : crosscheck_chkbx,
		};
		if($('#useoldinterface_chkbx').attr('checked')==true) data2send.text_oldinterface=$('#text_oldinterface').val();
		$.post(base_url + "/pg/mod/profile_manager/views/default/profile_manager/members/rpc.php", data2send, function(data){
			$('#wait_mainconfigsettings_div').fadeOut();
			if(data) {
				alert("Configuration saved! Click to reload the page in order for the changes to have effect!");
				$(window.location).attr('href', '<?php echo $CONFIG->url;?>pg/members?sacp=1'); //reloads the page in order to let it includes files needed for exploratory search
			}
			else alert("Error while saving the new configuration settings! Please retry!");
		},'json');	
	}
	
	//depending on your choices, some checkboxes will be enabled or disabled
	function classification_select_change() {
		if($('#metadata_select').val()=="Kohonen" || $('#uses_select').val()=="Kohonen" || $('#tags_select').val()=="Kohonen") $('#text_kohonen_output').attr('disabled','');
		else $('#text_kohonen_output').attr('disabled','disabled');
		if($('#metadata_select').val()=="YACA" || $('#uses_select').val()=="YACA" || $('#tags_select').val()=="YACA" || $('#replinks_select').val()=="YACA") $('#text_yaca_threshold').attr('disabled','');
		else $('#text_yaca_threshold').attr('disabled','disabled');
	}
	
	//depending on your choices, some elements will be enabled or disabled
	function toggle_synonyms() {
		if($('#synonyms_chkbx').attr('checked')==true) { //enable the elements below
			$('#text_syn_db_host').attr('disabled','');
			$('#text_syn_db_user').attr('disabled','');
			$('#text_syn_db_pass').attr('disabled','');
			$('#text_syn_db_name').attr('disabled','');
		}
		else if($('#synonyms_chkbx').attr('checked')==false) {  //disable the elements below
			$('#text_syn_db_host').attr('disabled','disabled');
			$('#text_syn_db_user').attr('disabled','disabled');
			$('#text_syn_db_pass').attr('disabled','disabled');
			$('#text_syn_db_name').attr('disabled','disabled');
		}
	}
	
	//enable or disable the old command-line interface depending on your choice
	function toggle_oldinterface() {
		if($('#useoldinterface_chkbx').attr('checked')!=true) $('#text_oldinterface').attr('disabled','disabled');
		else $('#text_oldinterface').removeAttr('disabled');
	}
	
	//delete old unneeded files and reset the file "changes"
	function reset_changes() {
		var data2send= {
			'func' : 'reset_changes'
		};			
		$.post(base_url + "/pg/mod/profile_manager/views/default/profile_manager/members/rpc.php", data2send, function(data){
			if(data=="OK") {
				alert("Indexing and classification process has finished! You should reload the page now!");
				$('#see_results2').show(); //show the link that allows the superadmin to see how data have been classified
			}
			else {
				alert("Error while resetting changes!");
				alert("The process, despite the error, has finished, maybe there are some old files with the prefix old_, you can delete them manually. Moreover make sure the file 'changes' has been re-initialized");
			}
			stop_readoutputfile();
		},'json');
	}
	
	//it creates the RDF version of the clusters files, still WORK IN PROGRESS, RDF will substitute database and files
	function create_clusters_rdf() {
		var data2send= {
			'func' : 'create_clusters_rdf'
		};			
		$.post(base_url + "/pg/mod/profile_manager/views/default/profile_manager/members/rpc.php", data2send, function(data){
			if(data=="OK") {
				if(stopprocess==0) reset_changes();
				else alert("The process has not finished correctly! The old indexing and classification files have been renamed with the prefix old_. To recover them it's sufficient to rename them deleting that prefix!");
			}
			else {
				alert("Error while creating the RDF files for the clusters!");
				alert("The process has not finished correctly. The old indexing and classification files have been renamed with the prefix old_. To recover them it's sufficient to rename them deleting that prefix!");
				stop_readoutputfile();
			}
		},'json');
	}
	
	//it creates associations among clusters
	function associateClusters() {
		var data2send= {
			'func' : 'associateClusters'
		};			
		$.post(base_url + "/pg/mod/profile_manager/views/default/profile_manager/members/rpc.php", data2send, function(data){
			if(data=="OK") {
				if(stopprocess==0) create_clusters_rdf();
				else {
					alert("The process has not finished correctly! The old indexing and classification files have been renamed with the prefix old_. To recover them it's sufficient to rename them deleting that prefix!");
					stop_readoutputfile();
				}
			}
			else {
				alert("Error while associating the clusters!");
				alert("The process has not finished correctly. The old indexing and classification files have been renamed with the prefix old_. To recover them it's sufficient to rename them deleting that prefix!");
				stop_readoutputfile();
			}
		},'json');
	}
	
	//classification
	function classify() {
		var data2send= {
			'func' : 'classify',
			'cl_useold' : $('#cl_useold_chkbx').attr('checked')
		};			
		$.post(base_url + "/pg/mod/profile_manager/views/default/profile_manager/members/rpc.php", data2send, function(data){
			if(data=="OK") {
				if(stopprocess==0) associateClusters();
				else {
					alert("The process has not finished correctly! The old indexing and classification files have been renamed with the prefix old_. To recover them it's sufficient to rename them deleting that prefix!");
					stop_readoutputfile();
				}
			}
			else {
				alert("Error while creating the clusters!");
				alert("The process has not finished correctly. The old indexing and classification files have been renamed with the prefix old_. To recover them it's sufficient to rename them deleting that prefix!");
				stop_readoutputfile();
			}
		},'json');
	}
	
	//it converts the doc-term replinks matrix into doc-doc matrix
	function docdocReplinks() {
		var data2send= {
			'func' : 'docdocReplinks'
		};			
		$.post(base_url + "/pg/mod/profile_manager/views/default/profile_manager/members/rpc.php", data2send, function(data){
			if(data=="OK") {
				if(stopprocess==0) classify();
				else {
					alert("The process has not finished correctly! The old indexing and classification files have been renamed with the prefix old_. To recover them it's sufficient to rename them deleting that prefix!");
					stop_readoutputfile();
				}
			}
			else {
				alert("Error while creating the Replinks doc-doc matrix!");
				alert("The process has not finished correctly! The old indexing and classification files have been renamed with the prefix old_. To recover them it's sufficient to rename them deleting that prefix!");
				stop_readoutputfile();
			}
		},'json');
	}
	
	//it converts the doc-term tags matrix into doc-doc matrix
	function docdocTags() {
		var data2send= {
			'func' : 'docdocTags',
			'dd_useold' : $('#dd_useold_chkbx').attr('checked')
		};			
		$.post(base_url + "/pg/mod/profile_manager/views/default/profile_manager/members/rpc.php", data2send, function(data){
			if(data=="OK") {
				if(stopprocess==0) docdocReplinks();
				else {
					alert("The process has not finished correctly! The old indexing and classification files have been renamed with the prefix old_. To recover them it's sufficient to rename them deleting that prefix!");
					stop_readoutputfile();
				}
			}
			else {
				alert("Error while creating the Replinks doc-doc matrix!");
				alert("The process has not finished correctly. The old indexing and classification files have been renamed with the prefix old_. To recover them it's sufficient to rename them deleting that prefix!");
				stop_readoutputfile();
			}
		},'json');
	}
	
	//it converts the doc-term uses matrix into doc-doc matrix
	function docdocUses() {
		var data2send= {
			'func' : 'docdocUses',
			'dd_useold' : $('#dd_useold_chkbx').attr('checked')
		};			
		$.post(base_url + "/pg/mod/profile_manager/views/default/profile_manager/members/rpc.php", data2send, function(data){
			if(data=="OK") {
				if(stopprocess==0) docdocTags();
				else {
					alert("The process has not finished correctly! The old indexing and classification files have been renamed with the prefix old_. To recover them it's sufficient to rename them deleting that prefix!");
					stop_readoutputfile();
				}
			}
			else {
				alert("Error while creating the Uses doc-doc matrix!");
				alert("The process has not finished correctly. The old indexing and classification files have been renamed with the prefix old_. To recover them it's sufficient to rename them deleting that prefix!");
				stop_readoutputfile();
			}
		},'json');
	}
	
	//it converts the doc-term metadata matrix into doc-doc matrix
	function docdocMetadata() {
		var data2send= {
			'func' : 'docdocMetadata',
			'dd_useold' : $('#dd_useold_chkbx').attr('checked')
		};			
		$.post(base_url + "/pg/mod/profile_manager/views/default/profile_manager/members/rpc.php", data2send, function(data){
			if(data=="OK") {
				if(stopprocess==0) docdocUses();
				else {
					alert("The process has not finished correctly! The old indexing and classification files have been renamed with the prefix old_. To recover them it's sufficient to rename them deleting that prefix!");
					stop_readoutputfile();
				}
			}
			else {
				alert("Error while creating the Metadata doc-doc matrix!");
				alert("The process has not finished correctly. The old indexing and classification files have been renamed with the prefix old_. To recover them it's sufficient to rename them deleting that prefix!");
				stop_readoutputfile();
			}
		},'json');
	}
	
	//it processes Tags to create a matrix doc-term for them
	function doctermTags() {
		var data2send= {
			'func' : 'doctermTags',
			'dt_useold' : $('#dt_useold_chkbx').attr('checked')
		};			
		$.post(base_url + "/pg/mod/profile_manager/views/default/profile_manager/members/rpc.php", data2send, function(data){
			if(data=="OK") {
				if(stopprocess==0) docdocMetadata();
				else {
					alert("The process has not finished correctly! The old indexing and classification files have been renamed with the prefix old_. To recover them it's sufficient to rename them deleting that prefix!");
					stop_readoutputfile();
				}
			}
			else {
				alert("Error while creating the Tags doc-term matrix!");
				alert("The process has not finished correctly. The old indexing and classification files have been renamed with the prefix old_. To recover them it's sufficient to rename them deleting that prefix!");
				stop_readoutputfile();
			}
		},'json');
	}
	
	//it processes Use Cases to create a matrix doc-term for them
	function doctermUses() {
		var data2send= {
			'func' : 'doctermUses',
			'dt_useold' : $('#dt_useold_chkbx').attr('checked')
		};			
		$.post(base_url + "/pg/mod/profile_manager/views/default/profile_manager/members/rpc.php", data2send, function(data){
			if(data=="OK") {
				if(stopprocess==0) doctermTags();
				else {
					alert("The process has not finished correctly! The old indexing and classification files have been renamed with the prefix old_. To recover them it's sufficient to rename them deleting that prefix!");
					stop_readoutputfile();
				}
			}
			else {
				alert("Error while creating the Uses doc-term matrix!");
				alert("The process has not finished correctly. The old indexing and classification files have been renamed with the prefix old_. To recover them it's sufficient to rename them deleting that prefix!");
				stop_readoutputfile();
			}
		},'json');
	}
	
	//it processes Metadata to create a matrix doc-term for them
	function doctermMetadata() {
		var data2send= {
			'func' : 'doctermMetadata',
			'dt_useold' : $('#dt_useold_chkbx').attr('checked')
		};			
		$.post(base_url + "/pg/mod/profile_manager/views/default/profile_manager/members/rpc.php", data2send, function(data){
			if(data=="OK") {
				if(stopprocess==0) doctermUses();
				else {
					alert("The process has not finished correctly! The old indexing and classification files have been renamed with the prefix old_. To recover them it's sufficient to rename them deleting that prefix!");
					stop_readoutputfile();
				}
			}
			else {
				alert("Error while creating the Metadata doc-term matrix!");
				alert("The process has not finished correctly. The old indexing and classification files have been renamed with the prefix old_. To recover them it's sufficient to rename them deleting that prefix!");
				stop_readoutputfile();
			}
		},'json');
	}
	
	//it starts the indexing and classification process, starting with a new data snapshot
	function get_snapshot() {
		$('#res_textarea').val("");
		$('#dt_useold_chkbx').attr('disabled','disabled');
		$('#dd_useold_chkbx').attr('disabled','disabled');
		$('#cl_useold_chkbx').attr('disabled','disabled');
		var data2send= {
			'func' : 'initialize_outputfile'
		};			
		$.post(base_url + "/pg/mod/profile_manager/views/default/profile_manager/members/rpc.php", data2send, function(data){
			if(data=="OK") { //if the outputfile has been initialized correctly, start the function that reads continuously it and start the process with the PHP function get_snapshot
				readoutputfile();
				var data2send= {
					'func' : 'get_snapshot'
				};			
				$.post(base_url + "/pg/mod/profile_manager/views/default/profile_manager/members/rpc.php", data2send, function(data){
					if(data=="OK") {
						if(stopprocess==0) doctermMetadata();
						else {
							alert("Process stopped! The old indexing and classification files have been renamed with the prefix old_. To recover them it's sufficient to rename them deleting that prefix!");
							stop_readoutputfile();
						}
					}
					else {
						alert("Error in getting the snapshot!");
						alert("The old indexing and classification files have been renamed with the prefix old_. To recover them it's sufficient to rename them deleting that prefix!");
						stop_readoutputfile();
					}
				},'json');
			}
			else {
				alert("Error!");
				stop_readoutputfile();
			}
		},'json');
	}
	
	//it sets the stopprocess variable at 1, so the indexing and classification process doesn't continue after the current operation
	function stop_process() {
		stopprocess=1;
		alert("OK! Let me finish the current operation!");
	}
	
	//it calls the PHP function that reads the output file continuously and returns the read data when it is different from the previous data
	//it uses the Comet technique of the long polling
	function readoutputfile() {
		var data2send= {
			'func' : 'readoutputfile'
		};
		if($('#res_textarea').val()!="") data2send.currenttext=$('#res_textarea').val();		
		$.post(base_url + "/pg/mod/profile_manager/views/default/profile_manager/members/rpc.php", data2send, function(data) {
			if(!data) alert("Error in the output reader! Please retry!");
			else {
				$('#res_textarea').val(data);  //puts data into the textarea
				if(data.slice(-3)!="END") readoutputfile();  //if there isn't the "END" (which signals to finish reading), it keeps reading
			}
		},'json');
	}
	
	//it stops reading the output file by stopping the function readoutputfile
	function stop_readoutputfile() {
		var data2send= {
			'func' : 'stop_readoutputfile'
		};			
		$.post(base_url + "/pg/mod/profile_manager/views/default/profile_manager/members/rpc.php", data2send, function(data){
			if(data=="OK") {
				//do nothing
			}
			else {
				alert("Error in cleaning up!");
				alert("Please restart the webserver to stop the outputfile reader, that is still reading ans slowing down the system!");
			}
			//enable again the checkboxes disabled during the process
			$('#dt_useold_chkbx').attr('disabled','');
			$('#dd_useold_chkbx').attr('disabled','');
			$('#cl_useold_chkbx').attr('disabled','');
		},'json');
	}
	
	//Javascript function similar to the PHP print_r function
	function print_r(theObj){
		if(theObj.constructor == Array || theObj.constructor == Object){
			document.write("<ul>");
			for(var p in theObj){
				if(theObj[p].constructor == Array || theObj[p].constructor == Object){
					document.write("<li>["+p+"] => "+typeof(theObj)+"</li>");
					document.write("<ul>");
					print_r(theObj[p]);
					document.write("</ul>");
				} 
				else {
					document.write("<li>["+p+"] => "+theObj[p]+"</li>");
				}
			}
			document.write("</ul>");
		}
	}


	$(document).ready(function(){
		$.ajaxSetup( { type: "POST" } );
		$("input[name='user_data_partial_search_criteria[name]']").autocomplete("<?php echo $CONFIG->url ?>/pg/mod/profile_manager/views/default/profile_manager/members/rpc.php", {
			width: 300,
			selectFirst: false,
			scroll: true,
			autoFill: false,
			extraParams:{
				func:'autocomplete_elggusers_entity',
				type_request:'raw_html'
			},
			matchContains: true,
			/*highlight: function(value, term) {
				return value.replace(new RegExp("(?![^&;]+;)(?!<[^<>]*)(" + term.replace(/([\^\$\(\)\[\]\{\}\*\.\+\?\|\\])/gi, "\\$1") + ")(?![^<>]*>)(?![^&;]+;)", "gi"), "<strong>$1</strong>");
			}*/
			delay: 50
		});
		
		$("#search_input").result(function(event, data, formatted) {
			perform_members_search("simplesearch");
		});

		$("#idclusterTxt").autocomplete("<?php echo $CONFIG->url ?>/pg/mod/profile_manager/views/default/profile_manager/members/rpc.php", {
			width: 300,
			selectFirst: false,
			scroll: true,
			minChars:1,
			//autoFill: true,
			matchContains: true,
 			extraParams:{
				func: 'autocomplete_idcluster',
				type_request: 'raw_html'
			},
			multiple:true,
			delay: 50
			
		});
		
		$("#guidresourceTxt").autocomplete("<?php echo $CONFIG->url ?>/pg/mod/profile_manager/views/default/profile_manager/members/rpc.php", {
			width: 300,
			selectFirst: false,
			scroll: true,
			minChars:1,
			//autoFill: true,
			matchContains: true,
 			extraParams:{
				func: 'autocomplete_guidresource',
				type_request: 'raw_html'
			},
			multiple:true,
			delay: 50
			
		});
		
		$("#positiveFeaturesTxt").autocomplete("<?php echo $CONFIG->url ?>/pg/mod/profile_manager/views/default/profile_manager/members/rpc.php", {
			width: 300,
			selectFirst: false,
			scroll: true,
			minChars:1,
			//autoFill: true,
			matchContains: true,
 			extraParams:{
				func: 'autocomplete_positive_features',
				type_request: 'raw_html'
			},
			multiple:true,
			delay: 50
			
		});
		
		$('input[name=type]:radio').click(function(){
			if($(this).val()=='clusterid') {
				$('#idclusterTxt').attr('disabled','');
				$('#guidresourceTxt').attr('disabled','disabled');
				$('#positiveFeaturesTxt').attr('disabled','disabled');
			}
			else {
				$('#idclusterTxt').attr('disabled','disabled');
				$('#guidresourceTxt').attr('disabled','');
				$('#positiveFeaturesTxt').attr('disabled','');
			}
		});
		
		toggle_synonyms();
		classification_select_change();
		get_statistics();
		initialize_type_radio();
		toggle_profile_type_selection();
		perform_members_search("simplesearch");
		if(exploratorysearch) perform_cluster_search();
		$('#clusters_search_result').hide();
		$('#bs_tab').addClass('selected');
		$('#as').hide();
		$('#cs').hide();
		$('#metadata_tab').addClass('selected');
		$('#uses_fields').hide();
		$('#tags_fields').hide();
		$('#datasource_config_tab').addClass('selected');
		$('#indexing_config_fields').hide();
		$('#classification_config_fields').hide();
		$('#choosefields_cw').hide();
		switch(<? $sacp=get_input("sacp"); if(!empty($sacp) && issuperadminloggedin()) echo $sacp; else echo 0;?>) {
			case 1:
				$("#img_sacp").click(); 
				location.replace('#superadmin_cp');
				break;
			//other values could be added in the future
		}
	});

</script>

<?php 
//SuperAdmin Control Panel
	if(issuperadminloggedin()){
		?>
		<h3 class='settings'>
		<?php echo elgg_view_title(elgg_echo("<a href='javascript:void(0);'><img id='img_sacp' src='".$CONFIG->url."mod/profile_manager/views/default/profile_manager/members/expand.gif' style='margin-right:20px' onclick='$(\"#superadmin_cp\").toggle(\"slow\",function() {toggle_img_sacp();});' /></a>"."SuperAdmin Control Panel")); ?>
		</h3>
		<div id='superadmin_cp' style="display:none">
			<?php 
				if($exploratorysearch) {
					?>
					<div id="see_results" class='contentWrapper'>
						<a href="#" onClick="var popup = window.open('<?php echo $CONFIG->url?>pg/mod/profile_manager/actions/members/clusters_view.php','newpopupwindow','width=550,height=400,scrollbars=yes,resizable=no'); popup.moveTo(400,200);"><b>See how data have been classified</b></a><br>
					</div>
					<?php
				}
			?>
			<div id='mainconf_settings_div' class='contentWrapper'>
				<h3 class='settings'><?php echo elgg_echo("Main configuration settings (needed to enable exploratory search in the website)");?></h3>
				<table>
					<tr>
						<td>
							<b>I/O directory</b><br />
							<input id="text_iodir" name="text_iodir" type="text" size="40" value='<?php echo $IOdir; ?>'  />
							<br /><br />
							<?php 
								echo elgg_view("input/checkboxes", array("internalname" => "crosscheck_chkbx", "internalid" => "crosscheck_chkbx", "options" => array(elgg_echo("while showing results do a crosscheck with doc-doc matrices")), "value" => elgg_echo("crosscheck"))); 
								if($dd_crosscheck==1) {
									?>
									<script type="text/javascript">
									$('#crosscheck_chkbx').attr('checked','true');
									</script>
									<?php
								}
							?>
							<br /><br />
							<?php
								echo elgg_view("input/checkboxes", array("internalname" => "useoldinterface_chkbx", "internalid" => "useoldinterface_chkbx", "options" => array(elgg_echo("allow use of the old command-line interface")), "value" => elgg_echo("oldinterface"),  "js" => "onchange='toggle_oldinterface();'"));
								if($IndexingClassificationPath!="undefined") {
									?>
									<script type="text/javascript">
									$('#useoldinterface_chkbx').attr('checked','true');
									</script>
									<?php
								}
							?>
							<input id="text_oldinterface" name="text_oldinterface" type="text" size="40" value='<?php if($IndexingClassificationPath!="undefined") echo $IndexingClassificationPath; else echo ""; ?>'  <?php if($IndexingClassificationPath=="undefined") echo "disabled='disabled'"; ?>  />
						</td>
					</tr>
				</table>
				<div id='wait_mainconfigsettings_div' style="display:none"><img src="<?php echo $CONFIG->url ?>mod/profile_manager/views/default/profile_manager/members/wait.gif"/><br /><br />Please wait...</div>
				<input id="button_mainconfapply" name="" class="submit_button" value="Apply" type="button" onClick="apply_mainconffields();"/>
			</div>
			<div class='contentWrapper' id='ic_confsettings_cw'>
				<h3 class='settings'><?php echo elgg_echo("Indexing and classification configuration settings (needed if you want to start a new process)");?></h3>
				<div id="config_tabs">
					<div id="elgg_horizontal_tabbed_nav">
						<ul>
						<li id="datasource_config_tab"><a href="javascript:void(0);" onclick="$('#config_tabs').find('li').removeClass('selected'); $('#datasource_config_tab').addClass('selected'); $('#indexing_config_fields').hide(); $('#classification_config_fields').hide(); $('#datasource_config_fields').show();">Data Source</a></li>
						<li id="indexing_config_tab"><a href="javascript:void(0);" onclick="$('#config_tabs').find('li').removeClass('selected'); $('#indexing_config_tab').addClass('selected'); $('#datasource_config_fields').hide(); $('#classification_config_fields').hide(); $('#indexing_config_fields').show();">Indexing</a></li>
						<li id="classification_config_tab"><a href="javascript:void(0);" onclick="$('#config_tabs').find('li').removeClass('selected'); $('#classification_config_tab').addClass('selected'); $('#indexing_config_fields').hide(); $('#datasource_config_fields').hide(); $('#classification_config_fields').show();">Classification</a></li>
						</ul>
					</div>
				</div>
				<div class="res_box1" id="datasource_config_fields">
					<table>
					<tr>
						<td>
							<b>Source data</b><br />
							<select id='datasource_select' onchange="getfields();">
								<option value="nothing">--Choose data source--</option>
								<option value="sesame">Sesame RDF Repository</option>
								<option value="elggdb">Elgg database</option>
							</select>
							<div id='wait_datasource_div' style="display:none"><img src="<?php echo $CONFIG->url ?>mod/profile_manager/views/default/profile_manager/members/wait.gif"/><br /><br />Please wait...</div>
							<br /><br />
						</td>
					</tr>
					</table>
					<table>
					<tr>
						<td width="100%">
						<br />
						<a href='javascript:void(0);'><img id='img_choosefields' src='<?php echo $CONFIG->url."mod/profile_manager/views/default/profile_manager/members/expand.gif"; ?>' style='margin-right:20px' onclick='if($("#datasource_select").val()=="nothing") {alert("You must select first a data source"); return; } else $("#choosefields_cw").toggle("slow",function() {toggle_img_choosefields();}); ' /></a><b>Choose which fields you want to use for indexing and classification</b>
						<div id='choosefields_cw'>
							<div id="first_tabs">
								<div id="elgg_horizontal_tabbed_nav">
									<ul>
									<li id="metadata_tab"><a href="javascript:void(0);" onclick="$('#first_tabs').find('li').removeClass('selected'); $('#metadata_tab').addClass('selected'); $('#uses_fields').hide(); $('#tags_fields').hide(); $('#metadata_fields').show();">Metadata fields</a></li>
									<li id="uses_tab"><a href="javascript:void(0);" onclick="$('#first_tabs').find('li').removeClass('selected'); $('#uses_tab').addClass('selected'); $('#metadata_fields').hide(); $('#tags_fields').hide(); $('#uses_fields').show();">Uses fields</a></li>
									<li id="tags_tab"><a href="javascript:void(0);" onclick="$('#first_tabs').find('li').removeClass('selected'); $('#tags_tab').addClass('selected'); $('#uses_fields').hide(); $('#metadata_fields').hide(); $('#tags_fields').show();">Tags fields</a></li>
									</ul>
								</div>
							</div>
							<div class="res_box1" id="metadata_fields"></div>
							<div class="res_box1" id="uses_fields"></div>
							<div class="res_box1" id="tags_fields"></div>
							<div id='wait_fields_div' style="display:none"><img src="<?php echo $CONFIG->url ?>mod/profile_manager/views/default/profile_manager/members/wait.gif"/><br /><br />Please wait...</div>
						</div>
						</td>
					</tr>
					</table>
				</div>
				<div class="res_box1" id="indexing_config_fields">
					<table>
					<tr>
						<td>
							<b>Number of keywords (-1 to use all the keywords)</b>
							<br /><input id="text_numkeywords" name="text_numkeywords" type="text" size="20" value='<?php echo $keywords_limit; ?>' />
							<br /><br />
							<b>Number of contexts (-1 to use all the contexts and 0 not to use them)</b>
							<br /><input id="text_numcontexts" name="text_numcontexts" type="text" size="20" value='<?php echo $context_limit; ?>' />
							<br /><br />
							<b>Sliding window width</b>
							<br /><input id="text_slidingwindow_width" name="text_slidingwindow_width" type="text" size="20" value='<?php echo $width_sliding_window; ?>' />
							<br /><br />
							<?php 
								echo elgg_view("input/checkboxes", array("internalname" => "idf_chkbx", "internalid" => "idf_chkbx", "options" => array(elgg_echo("Enable IDF")), "value" => elgg_echo("crosscheck")));
								if($enable_idf==1) {
									?>
									<script type="text/javascript">
									$('#idf_chkbx').attr('checked','true');
									</script>
									<?php
								}
							?>
							<br /><br />
							<?php 
								echo elgg_view("input/checkboxes", array("internalname" => "synonyms_chkbx", "internalid" => "synonyms_chkbx", "options" => array(elgg_echo("Enable synonyms support (VERY EXPERIMENTAL)")), "value" => elgg_echo("crosscheck"),  "js" => "onchange='toggle_synonyms();'"));
								if($enable_synonyms==1) {
									?>
									<script type="text/javascript">
									$('#synonyms_chkbx').attr('checked','true');
									</script>
									<?php
								}
							?>
							<br /><br />
							<b>Synonyms DB hostname</b>
							<br /><input id="text_syn_db_host" name="text_syn_db_host" type="text" size="20" value='<?php echo $syn_db_host; ?>' />
							<br /><br />
							<b>Synonyms DB username</b>
							<br /><input id="text_syn_db_user" name="text_syn_db_user" type="text" size="20" value='<?php echo $syn_db_user; ?>' />
							<br /><br />
							<b>Synonyms DB password</b>
							<br /><input id="text_syn_db_pass" name="text_syn_db_pass" type="text" size="20" value='<?php echo $syn_db_pass; ?>' />
							<br /><br />
							<b>Synonyms DB name</b>
							<br /><input id="text_syn_db_name" name="text_syn_db_name" type="text" size="20" value='<?php echo $syn_db_name; ?>' />
							<br /><br />
						</td>
					</tr>
					</table>
				</div>
				<div class="res_box1" id="classification_config_fields">
					<table>
					<tr>
						<td>
							<b>Classification algorithm for METADATA</b><br />
							<select id="metadata_select" onchange="classification_select_change();">
								<option value="Kohonen" <?php if($classification_method_metadata==1) echo "selected"; ?>>Kohonen</option>
								<option value="Aggregative" <?php if($classification_method_metadata==2) echo "selected"; ?>>Aggregative</option>
								<option value="YACA" <?php if($classification_method_metadata==3) echo "selected"; ?>>YACA</option>
							</select>
							<br /><br />
							<b>Classification algorithm for USES</b><br />
							<select id="uses_select" onchange="classification_select_change();">
								<option value="Kohonen" <?php if($classification_method_uses==1) echo "selected"; ?>>Kohonen</option>
								<option value="Aggregative" <?php if($classification_method_uses==2) echo "selected"; ?>>Aggregative</option>
								<option value="YACA" <?php if($classification_method_uses==3) echo "selected"; ?>>YACA</option>
							</select>
							<br /><br />
							<b>Classification algorithm for TAGS</b><br />
							<select id="tags_select" onchange="classification_select_change();">
								<option value="Kohonen" <?php if($classification_method_tags==1) echo "selected"; ?>>Kohonen</option>
								<option value="Aggregative" <?php if($classification_method_tags==2) echo "selected"; ?>>Aggregative</option>
								<option value="YACA" <?php if($classification_method_tags==3) echo "selected"; ?>>YACA</option>
							</select>
							<br /><br />
							<b>Classification method for REPLINKS</b><br />
							<select id="replinks_select" onchange="classification_select_change();">
								<option value="Aggregative" <?php if($classification_method_replinks==2) echo "selected"; ?>>Aggregative</option>
								<option value="YACA" <?php if($classification_method_replinks==3) echo "selected"; ?>>YACA</option>
							</select>
							<br /><br />
							<b>Kohonen output file</b>
							<br /><input id="text_kohonen_output" name="text_kohonen_output" type="text" size="30" value='<?php echo $output_file_kohonen; ?>' />
							<br /><br />
							<b>YACA threshold</b>
							<br /><input id="text_yaca_threshold" name="text_yaca_threshold" type="text" size="10" value='<?php echo $YACA_threshold; ?>' />
							<br /><br />
							<b>Minimum threshold to consider two clusters associated</b>
							<br /><input id="text_min_threshold" name="text_min_threshold" type="text" size="10" value='<?php echo $minimum_association_threshold; ?>' />
							<br /><br />
						</td>
					</tr>
					</table>
				</div>
				<div id='wait_icconfigsettings_div' style="display:none"><img src="<?php echo $CONFIG->url ?>mod/profile_manager/views/default/profile_manager/members/wait.gif"/><br /><br />Please wait...</div>
				<input id="button_ic_confapply" name="" class="submit_button" value="Apply" type="button" onClick="apply_icconffields();"/>
			</div>
			<div class='contentWrapper'>
				<h3 class='settings'><?php echo elgg_echo("Get new snapshot and start the indexing and classification process");?></h3>
				<div id="statistics_div"></div>
				<table>
					<tr>
						<td>
							<?php 
								echo elgg_view("input/checkboxes", array("internalname" => "dt_useold_chkbx", "internalid" => "dt_useold_chkbx", "options" => array(elgg_echo("Speedup using data from last doc-term creation")), "value" => elgg_echo("crosscheck")));
								$selected=1;
								if(file_exists($IOdir."changes")) {  //even if we don't take GUIDS of the modified/new resources from this file (we use old indexing results, we use it only in the classification), it is used to see if actually it's possible to use old results or a completely new indexing & classification is needed
									$changes=unserialize(file_get_contents($IOdir."changes"));
									if($changes["new_indexing_required"]==1) $selected=0;
								}
								else $selected=0;
								if($selected==0) {
									?>
									<script type="text/javascript">
									$('#dt_useold_chkbx').attr('disabled','disabled');
									</script>
									<?php
								}
							?>
							<br />
							<?php 
								echo elgg_view("input/checkboxes", array("internalname" => "dd_useold_chkbx", "internalid" => "dd_useold_chkbx", "options" => array(elgg_echo("Speedup using data from last doc-doc creation")), "value" => elgg_echo("crosscheck")));
								$selected=1;
								if(file_exists($IOdir."changes")) {  //even if we don't take GUIDS of the modified/new resources from this file (we use old indexing results, we use it only in the classification), it is used to see if actually it's possible to use old results or a completely new indexing & classification is needed
									$changes=unserialize(file_get_contents($IOdir."changes"));
									if($changes["new_indexing_required"]==1) $selected=0;
								}
								else $selected=0;
								if($selected==0) {
									?>
									<script type="text/javascript">
									$('#dd_useold_chkbx').attr('disabled','disabled');
									</script>
									<?php
								}
							?>
							<br />
							<?php 
								echo elgg_view("input/checkboxes", array("internalname" => "cl_useold_chkbx", "internalid" => "cl_useold_chkbx", "options" => array(elgg_echo("Speedup using data from last classification (EXPERIMENTAL)")), "value" => elgg_echo("crosscheck")));
								$selected=1;
								if(file_exists($IOdir."changes")) {  //even if we don't take GUIDS of the modified/new resources from this file (we use old indexing results, we use it only in the classification), it is used to see if actually it's possible to use old results or a completely new indexing & classification is needed
									$changes=unserialize(file_get_contents($IOdir."changes"));
									if($changes["new_indexing_required"]==1) $selected=0;
								}
								else $selected=0;
								if($selected==0) {
									?>
									<script type="text/javascript">
									$('#cl_useold_chkbx').attr('disabled','disabled');
									</script>
									<?php
								}
							?>
						</td>
					</tr>
				</table>
				<br />
				<div id='wait_icprocess_div' style="display:none"><img src="<?php echo $CONFIG->url ?>mod/profile_manager/views/default/profile_manager/members/wait.gif"/><br /><br />Please wait...</div>
				<input id="button_startprocess" name="" class="submit_button" value="Start" type="button" onClick="get_snapshot();"/>
				<input id="button_stop_readoutputfile" name="" class="submit_button" value="Stop process" type="button" onClick="stop_process();" />
				<textarea class="input-textarea" id="res_textarea" name="" READONLY></textarea>
				<div id="see_results2" style="display:none">
					<br><a href="#" onClick="var popup = window.open('<?php echo $CONFIG->url?>pg/mod/profile_manager/actions/members/clusters_view.php','newpopupwindow','width=550,height=400,scrollbars=yes,resizable=no'); popup.moveTo(400,200);"><b>See how data have been classified</b></a><br>
				</div>
			</div>
		</div>
		<?php
	}
?>

<?php 
//Basic and advanced search
echo elgg_view_title(elgg_echo("profile_manager:members:searchform:title"));?>
<div id='profile_manager_members_search_form' class='contentWrapper'>
<div id="second_tabs">
<div id="elgg_horizontal_tabbed_nav">
	<ul>
		<li id="bs_tab" class="selected"><a href="javascript:void(0);" onclick="$('#second_tabs').find('li').removeClass('selected'); $('#bs_tab').addClass('selected'); $('#as').hide();$('#cs').hide();$('#bs').show(); $('#clusters_search_result').hide(); $('#members_search_result').show();">Basic search</a></li>
		<li id="as_tab"><a href="javascript:void(0);" onclick="$('#second_tabs').find('li').removeClass('selected'); $('#as_tab').addClass('selected'); $('#bs').hide();$('#cs').hide();$('#as').show(); $('#clusters_search_result').hide(); $('#members_search_result').show();">Advanced search</a></li>
		<?php
			if(issuperadminloggedin() && $exploratorysearch==1) {  //it adds the Cluster search tab
				?>
				<li id="cs_tab"><a href="javascript:void(0);" onclick="$('#second_tabs').find('li').removeClass('selected'); $('#cs_tab').addClass('selected'); $('#bs').hide();$('#as').hide();$('#cs').show(); $('#members_search_result').hide(); $('#clusters_search_result').show();">Cluster search</a></li>
				<?php
			}
		?>
	</ul>
</div>
</div>
<div class="res_box1" id="bs">
<form id="simplesearch" action="javascript:perform_members_search('simplesearch');" type="post" autocomplete="off">
<table width=100%>
	<?php 
		echo $default_search_criteria;
		echo $simple_search_criteria;
	?>	
</table>

<?php 
	echo elgg_view("input/submit", array("value" => elgg_echo("search")));
	echo " ";
	echo elgg_view("input/reset", array("value" => elgg_echo("reset")));
?>
</form>
</div>
<?php
	// advanced search 
	if(!empty($advanced_search_criteria)){
		?>

		<div class="res_box1" id="as" style="display:none">
		<form id="advancedsearch" action="javascript:perform_members_search('advancedsearch');" type="post">
		<table width=100%>
			<?php 
				echo $default_search_criteria;
				echo $advanced_search_criteria;
			?>
		</table>

		<?php 
			echo elgg_view("input/submit", array("value" => elgg_echo("search")));
			echo " ";
			echo elgg_view("input/reset", array("value" => elgg_echo("reset")));
		?>

		</form>
		</div>
		
		<?php 
	}
	
	if(issuperadminloggedin() && $exploratorysearch==1) {  //it adds the Cluster search panel
		?>
		<div class="res_box1" id="cs" style="display:none">
		<h3 class='settings'><?php echo elgg_echo("Cluster Search");?></h3>
		<form id="clustersearch" action="javascript:void(0);" autocomplete="off">
			<?php 
				echo elgg_echo("Research type");
				echo "<br />";
				echo elgg_view("input/radio", array("id" => "type_id", "internalname" => "type", "value" => "clusterid", "options" => array(elgg_echo("By cluster id") => "clusterid", elgg_echo("By resources contained and/or pos. features") => "respf")));
			?>
			<br />
			ID(s) cluster(s) (separated by ,)
			<input type="text" class="input-text" id="idclusterTxt"><br /><br />
			GUID(s) of contained resource(s) (separated by ,)
			<input type="text" class="input-text" id="guidresourceTxt"><br />
			Positive features (separated by ,)
			<input type="text" class="input-text" id="positiveFeaturesTxt"><br />
			<?php 
			echo elgg_view("input/button", array("value" => elgg_echo("search"), "js" => "onclick='perform_cluster_search();'"));
			echo " ";
			echo elgg_view("input/reset", array("value" => elgg_echo("reset"), "js" => "onclick='initialize_type_radio();'"));
			?>

		</form>
		</div>
		<?php
	}
?>



</div>

<div id="members_search_result"></div>
<div id="clusters_search_result" style="float:right;width: 650px;"></div>
<div class="clearfloat"></div>
