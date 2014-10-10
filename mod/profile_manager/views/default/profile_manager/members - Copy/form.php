<?php
	require_once ($CONFIG->path . "mod/profile_manager/actions/members/config.php");
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
<script src="<?php echo $CONFIG->url ?>mod/profile_manager/actions/members/tools/autocomplete/jquery.autocomplete.js"></script>
<link type="text/css" rel="stylesheet" href="<?php echo $CONFIG->url ?>/mod/profile_manager/actions/members/tools/autocomplete/jquery.autocomplete.css" >
<script type="text/javascript">
	var formdata;
	var base_url ='<?php echo $CONFIG->url ?>';
	var num_checkboxes;
	var fields_loms= new Array();
	var fields_uses= new Array();
	var fields_tags= new Array();
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
	
	//it calls the PHP function saveIOdir, which saves the indicated IOdir path in the file config.php
	function iodir_click() {
		var txtValue=$('#text_iodir').val();
		var data2send= {
			'func' : 'saveIOdir',
			'file' : '<?php echo $CONFIG->path . "mod/profile_manager/actions/members/config.php" ?>',
			'txtValue' : txtValue
		};			
		$.post(base_url + "/pg/mod/profile_manager/views/default/profile_manager/members/rpc.php", data2send, function(data){
			if(data) alert("New path saved! Please reload the page in order for the changes to have effect!");
			else {
				alert("Path not valid! Please retry!");
				$('#text_iodir').val('<?php echo $IOdir ?>');
			}
		},'json');	
	}
	
	//it calls the PHP function set_dd_crosscheck, which sets the dd_crosscheck option in the file config.php
	function crosscheck_checkbox_click() {
		var data2send= {
			'func' : 'set_dd_crosscheck',
			'file' : '<?php echo $CONFIG->path . "mod/profile_manager/actions/members/config.php" ?>',
			'value' : $('#crosscheck_chkbx').attr('checked')
		};			
		$.post(base_url + "/pg/mod/profile_manager/views/default/profile_manager/members/rpc.php", data2send, function(data){
			if(data) alert("New value saved! Reload the page");
			else {
				alert("Something went wrong!");
			}
		},'json');
	}
	
	//it calls the PHP function update_data_snapshot, which creates a new snapshot of data in the folder IOdir, useful to start a new classification
	function update_data_snapshot() {
		$('#wait_div').fadeIn();
		var data2send= {
			'func' : 'update_data_snapshot'
		};			
		$.post(base_url + "/pg/mod/profile_manager/views/default/profile_manager/members/rpc.php", data2send, function(data){
			$('#wait_div').fadeOut();
			if(data) alert("Data saved successfully!\n\nNow go to 'indexing_classification' and execute 'php -f index.php'");
			else {
				alert("Error! Please retry!");
			}
		},'json');	
	}
	
	//it shows all the fields, so you can choose the ones you want to use for classification
	//fields already used (written in config.php) are checked
	function showfields() {
		var data2send= {
			'func' : 'showfields',
			'file' : '<?php echo $CONFIG->path . "mod/profile_manager/actions/members/config.php" ?>'
		};
		if($('#loms_fields').text()=="") {
			$('#wait_cf_div').fadeIn();
			$.post(base_url + "/pg/mod/profile_manager/views/default/profile_manager/members/rpc.php", data2send, function(data){
				$('#wait_cf_div').fadeOut();
				for(i=0;i<data.length;i++) {
					$('<input id="chk_loms_' +i+ '" type="checkbox" /><span id="text_loms_' +i+ '"> ' +data[i].value+ '</span><br />').appendTo($('#loms_fields'));
					if(data[i].loms) $('#chk_loms_'+i).attr('checked',true);
					$('<input id="chk_uses_' +i+ '" type="checkbox" /><span id="text_uses_' +i+ '"> ' +data[i].value+ '</span><br />').appendTo($('#uses_fields'));
					if(data[i].uses) $('#chk_uses_'+i).attr('checked',true);
					$('<input id="chk_tags_' +i+ '" type="checkbox" /><span id="text_tags_' +i+ '"> ' +data[i].value+ '</span><br />').appendTo($('#tags_fields'));
					if(data[i].tags) $('#chk_tags_'+i).attr('checked',true);
				}
				num_checkboxes=i;
			},'json');
		}
	}
	
	//it saves the checked fields in the file config.php
	function savefields() {
		for(i=0;i<num_checkboxes;i++) {
			if($('#chk_loms_' +i).attr("checked")) fields_loms[i] = $('#chk_loms_' +i).next().text();
			if($('#chk_uses_' +i).attr("checked")) fields_uses[i] = $('#chk_uses_' +i).next().text();
			if($('#chk_tags_' +i).attr("checked")) fields_tags[i] = $('#chk_tags_' +i).next().text();
		}
		
		fields_loms_json=array2json(fields_loms);
		fields_uses_json=array2json(fields_uses);
		fields_tags_json=array2json(fields_tags);
		
		var data2send= {
			'func' : 'savefields',
			'file' : '<?php echo $CONFIG->path . "mod/profile_manager/actions/members/config.php" ?>',
		        'fields_loms_json' : fields_loms_json,
			'fields_uses_json' : fields_uses_json,
			'fields_tags_json' : fields_tags_json
		};
		$.post(base_url + "/pg/mod/profile_manager/views/default/profile_manager/members/rpc.php", data2send, function(data){
			alert("Data saved!");
		},'json');
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
			//~ var pattern = /[A-z0-9aAaAaAaAaAaA??cCeEeEeEeEiIiIiIiInNoOoOoOoOoOoO?uUuUuUuUy]+/g;
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
		},'raw_html');
	}
	
	function toggle_img_sacp() {
		if($("#img_sacp").attr("src")=="<?php echo $CONFIG->url;?>mod/profile_manager/actions/members/expand.gif") $("#img_sacp").attr("src","<?php echo $CONFIG->url;?>mod/profile_manager/actions/members/expand-inverse.gif");
		else $("#img_sacp").attr("src","<?php echo $CONFIG->url;?>mod/profile_manager/actions/members/expand.gif");
	}
	
	function toggle_img_choosefields() {
		if($("#img_choosefields").attr("src")=="<?php echo $CONFIG->url;?>mod/profile_manager/actions/members/expand.gif") $("#img_choosefields").attr("src","<?php echo $CONFIG->url;?>mod/profile_manager/actions/members/expand-inverse.gif");
		else $("#img_choosefields").attr("src","<?php echo $CONFIG->url;?>mod/profile_manager/actions/members/expand.gif");
	}
	
	function initialize_type_radio() {
		$('#idclusterTxt').attr('disabled','');
		$('#guidresourceTxt').attr('disabled','disabled');
		$('#positiveFeaturesTxt').attr('disabled','disabled');
	}
	
	function initialize_crosscheck_chkbx() {
		var dd_crosscheck=<?php echo $dd_crosscheck;?>;
		if(dd_crosscheck==1) $('#crosscheck_chkbx').attr('checked','true');
		else $('#crosscheck_chkbx').attr('checked','');
	}
	
	function array2json(arr) {
		var parts = [];
		var is_list = (Object.prototype.toString.apply(arr) === '[object Array]');

		for(var key in arr) {
			var value = arr[key];
			if(typeof value == "object") { //Custom handling for arrays
				if(is_list) parts.push(array2json(value)); /* :RECURSION: */
				else parts[key] = array2json(value); /* :RECURSION: */
			} 
			else {
				var str = "";
				if(!is_list) str = '"' + key + '":';

				//Custom handling for multiple data types
				if(typeof value == "number") str += value; //Numbers
				else if(value === false) str += 'false'; //The booleans
				else if(value === true) str += 'true';
				else str += '"' + value + '"'; //All other things
				// :TODO: Is there any more datatype we should be in the lookout for? (Functions?)

				parts.push(str);
			}
		}
		var json = parts.join(",");
	    
		if(is_list) return '[' + json + ']';//Return numerical JSON
		return '{' + json + '}';//Return associative JSON
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
		
		initialize_crosscheck_chkbx();
		initialize_type_radio();
		toggle_profile_type_selection();
		perform_members_search("simplesearch");
		perform_cluster_search();
		$('#clusters_search_result').hide();
		$('#bs_tab').addClass('selected');
		$('#as').hide();
		$('#cs').hide();
		$('#loms_tab').addClass('selected');
		$('#uses_fields').hide();
		$('#tags_fields').hide();
		$('#choosefields_cw').hide();
	});

</script>

<?php 
	if(issuperadminloggedin()){  //it creates the SuperAdmin Control Panel
		?>
		<h3 class='settings'>
		<?php echo elgg_view_title(elgg_echo("<a href='#'><img id='img_sacp' src='".$CONFIG->url."mod/profile_manager/actions/members/expand.gif' style='margin-right:20px' onclick='$(\"#superadmin_cp\").toggle(\"slow\",function() {toggle_img_sacp();});' /></a>"."SuperAdmin Control Panel")); ?>
		</h3>
		<div id='superadmin_cp' style="display:none">
			<table>
			<tr>
				<td width="30%">
					<div class='contentWrapper'>
						<h3 class='settings'><?php echo elgg_echo("Step 1 - Choose the path where input/output files are and must be stored:");?></h3>
						<input id="text_iodir" name="email" type="text" size="40" value='<?php echo $IOdir; ?>'  />
						<input id="button_iodir" name="" class="submit_button" value="Save" type="button" onClick="iodir_click();"/>
					</div>
				</td>
				<td>	
					<div class='contentWrapper'>
						<h3 class='settings'><?php echo elgg_echo("Step 3 - Update data");?></h3>
						<input id="button_updatedata" name="" class="submit_button" value="Update the data snapshot" type="button" onClick="update_data_snapshot();"/>
						<div id='wait_div' style="display:none"><img src="<?php echo $CONFIG->url ?>mod/profile_manager/actions/members/wait.gif"/><br /><br />Please wait...</div>
					</div>
				</td>
				<td>
					<div class='contentWrapper'>
						<h3 class='settings'><?php echo elgg_echo("Step 4 - See how data have been classified:");?></h3>
						<br><a href="#" onClick="var popup = window.open('<?php echo $CONFIG->url?>mod/profile_manager/actions/members/clusters_view.php','newpopupwindow','width=550,height=400,scrollbars=yes,resizable=no'); popup.moveTo(400,200);">See how data have been classified</a><br><br>
					</div>
				</td>
				<td>
					<div class='contentWrapper'>
						<h3 class='settings'><?php echo elgg_echo("Step 5 - When you show the results");?></h3>
						<?php echo elgg_view("input/checkboxes", array("internalname" => "crosscheck_chkbx", "internalid" => "crosscheck_chkbx", "options" => array(elgg_echo("do a crosscheck using doc-doc matrices")), "value" => elgg_echo("crosscheck") ,  "js" =>"onchange='crosscheck_checkbox_click();'")); ?>
					</div>
				</td>
			</tr>
			</table>
			<div class='contentWrapper'>
				<h3 class='settings'><?php echo elgg_echo("<a href='#'><img id='img_choosefields' src='".$CONFIG->url."mod/profile_manager/actions/members/expand.gif' style='margin-right:20px' onclick='$(\"#choosefields_cw\").toggle(\"slow\",function() {toggle_img_choosefields();}); showfields(); ' /></a>"."Step 2 - Choose which fields you want to use for classification"); ?></h3>
				<div id='choosefields_cw'>
					<div id="first_tabs">
						<div id="elgg_horizontal_tabbed_nav">
							<ul>
							<li id="loms_tab"><a href="javascript:void(0);" onclick="$('#first_tabs').find('li').removeClass('selected'); $('#loms_tab').addClass('selected'); $('#uses_fields').hide(); $('#tags_fields').hide(); $('#loms_fields').show();">Loms fields</a></li>
							<li id="uses_tab"><a href="javascript:void(0);" onclick="$('#first_tabs').find('li').removeClass('selected'); $('#uses_tab').addClass('selected'); $('#loms_fields').hide(); $('#tags_fields').hide(); $('#uses_fields').show();">Uses fields</a></li>
							<li id="tags_tab"><a href="javascript:void(0);" onclick="$('#first_tabs').find('li').removeClass('selected'); $('#tags_tab').addClass('selected'); $('#uses_fields').hide(); $('#loms_fields').hide(); $('#tags_fields').show();">Tags fields</a></li>
							</ul>
						</div>
					</div>
					<div class="res_box1" id="loms_fields"></div>
					<div class="res_box1" id="uses_fields"></div>
					<div class="res_box1" id="tags_fields"></div>
					<div id='wait_cf_div' style="display:none"><img src="<?php echo $CONFIG->url ?>mod/profile_manager/actions/members/wait.gif"/><br /><br />Please wait...</div>
					<input id="button_savefields" name="" class="submit_button" value="Save" type="button" onClick="savefields();"/>
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
			if(issuperadminloggedin()) {  //it adds the Cluster search tab
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
	
	if(issuperadminloggedin()) {  //it adds the Cluster search panel
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
			echo elgg_view("input/reset", array("value" => elgg_echo("reset")));
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
