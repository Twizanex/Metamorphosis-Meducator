//this file contains what to do when the page search.php is fully loaded (show of the focused search results and show of hide of the exploratory search result)
var ajax_url = base_url + "pg/mod/profile_manager/actions/members/rpc.php";

var dynamic=1;  //1 is experimental, disable if it causes problems
$(document).ready(function(){
	var result0 = $('#main_search_results');
	
	$('#wait').hide();
	if(dynamic) {
		$('#main_search_results_cw').append('<input id="button_expand" name="" class="submit_button" value="Search" type="button" />');
		$('#button_expand').hide();
		$('#secondary_search_results').hide();
	}
	else {
		$('#main_search_results_cw').append(" and clicking on Search<br />");
		$('#main_search_results_cw').append('<input id="button_expand" name="" class="submit_button" value="Search" type="button" />');
		$('#secondary_search_results').hide();
	}
	//it builds the list of resources on the right by using the file 'ent' previously saved
	var data2send0= {
		'func' : 'step0'
	};			
	$.post(ajax_url, data2send0, function(data){
		//print_r(data);
		var container0 = $('<div></div></div>').appendTo(result0);
		var a0;
		a0 = $('<div class="res_box1" ></div>').appendTo(container0);
		var l_0 = new List({
			array_data : data,
			container : $('<div></div>').appendTo(a0),
			base_url : base_url,
			cck: 1
		});
	},'json');	
	
	var result = $('#secondary_search_results');
	
	//$('#wait').css('display','none');

	if(exploratorysearch) {  //if files necessary to use exploratory search are present
		if(!issuperadminloggedin) {
			$('#button_expand').click(function(){
				$('#main_search_results :input:checkbox').attr('disabled', true);
				$('#secondary_search_results').hide();
				$('#wait').show();
				$(window.location).attr('href', '#wait');
				
				//now we build the interface for the "related resources" (bottom-right) and call step1 to view the resources related to the ones selected
				var data2send = {
					'func' : 'step1'
				};			
				$.post(ajax_url, data2send, function(data){
					$(result).empty();
					$('<h3 class="settings">Possibly related resources</h3>').appendTo(result);
					var container = $('<div class="contentWrapper"><Resources related through:<br><br></div>').appendTo(result);
					var c_tabs = $('<div id="elgg_horizontal_tabbed_nav"></div></div>').appendTo(container).append('<ul></ul>');

					var a,b,c,d;
					
					var first = $('<a href="javascript:void(0);" >Global similarity</a>').appendTo($('<li></li>').addClass('selected').appendTo($(c_tabs).find('ul'))).click(function(){
						select_tab(c_tabs,$(this));
						$(b).hide();
						$(c).hide();
						$(d).hide();
						$(a).css('display','block');
					});
					$('<a href="javascript:void(0);" >Tags</a>').appendTo($('<li></li>').appendTo($(c_tabs).find('ul'))).click(function(){
						select_tab(c_tabs,$(this));
						$(a).hide();
						$(c).hide();
						$(d).hide();
						$(b).css('display','block');
					});
					$('<a href="javascript:void(0);" >Uses</a>').appendTo($('<li></li>').appendTo($(c_tabs).find('ul'))).click(function(){
						select_tab(c_tabs,$(this));
						$(a).hide();
						$(b).hide();
						$(d).hide();
						$(c).css('display','block');
					});
					$('<a href="javascript:void(0);" >Replinks</a>').appendTo($('<li></li>').appendTo($(c_tabs).find('ul'))).click(function(){
						select_tab(c_tabs,$(this));
						$(a).hide();
						$(b).hide();
						$(c).hide();
						$(d).css('display','block');
					});
					$(container).append('<div ><h3 class="settings">First order associations</h3></div>');
					
					a = $('<div class="res_box1" ></div>').appendTo(container);
					b = $('<div class="res_box1" ></div>').appendTo(container);
					c = $('<div class="res_box1" ></div>').appendTo(container);
					d = $('<div class="res_box1" ></div>').appendTo(container);
					
					//format_pos_fea(data.feat_metadata,a);
					var l_l = new List({
						array_data : data.metadata,
						container : $('<div></div>').appendTo(a),
						base_url : base_url
					});
					
					//format_pos_fea(data.feat_tags,b);
					var l_t = new List({
						array_data : data.tags,
						container : $('<div></div>').appendTo(b),
						base_url : base_url
					});
					
					//format_pos_fea(data.feat_uses,c);
					var l_u = new List({
						array_data : data.uses,
						container : $('<div></div>').appendTo(c),
						base_url : base_url
					});
					
					//format_pos_fea(data.feat_replinks,c);
					var l_r = new List({
						array_data : data.replinks,
						container : $('<div></div>').appendTo(d),
						base_url : base_url
					});
					
					//now we call the 'step2' function to view the second order associations
					var data2send = {
						'func' : 'step2',
						'metadata' : true,
						'tags' : true,
						'uses' : true,
						'replinks' : true
					};
					$.post( ajax_url, data2send, function(data_s2){
						
						$(a).append('<div ><h3 class="settings">Second order associations</h3></div>');
						
						
						$(b).append('<div ><h3 class="settings">Second order associations</h3></div>');
						$(c).append('<div ><h3 class="settings">Second order associations</h3></div>');
						$(d).append('<div ><h3 class="settings">Second order associations</h3></div>');
						
						var s_order_metadata = [];
						for(var elem in data_s2.metadata){
							var lista = data_s2.metadata[elem].docs;
							lista = remove_duplicates(data.metadata,lista);
							for(var i = 0 ; i < lista.length; i++ ){
								if(!contains(lista[i], s_order_metadata))
									s_order_metadata.push(lista[i]);
							}
						}
						new List({
							array_data : s_order_metadata,
							container : $('<div></div>').appendTo(a),
							base_url : base_url
						});
						
						var s_order_tags = [];
						for(var elem in data_s2.tags){
							var lista = data_s2.tags[elem].docs;
							lista = remove_duplicates(data.tags,lista);
							for(var i = 0 ; i < lista.length; i++ ){
								if(!contains(lista[i], s_order_tags))
									s_order_tags.push(lista[i]);
							}
						}
						new List({
							array_data : s_order_tags,
							container : $('<div></div>').appendTo(b),
							base_url : base_url
						});
						
						var s_order_uses = [];
						for(var elem in data_s2.uses){
							var lista = data_s2.uses[elem].docs;
							lista = remove_duplicates(data.uses,lista);
							for(var i = 0 ; i < lista.length; i++ ){
								if(!contains(lista[i], s_order_uses))
									s_order_uses.push(lista[i]);
							}
						}
						new List({
							array_data : s_order_uses,
							container : $('<div></div>').appendTo(c),
							base_url : base_url
						});
						
						var s_order_replinks = [];
						for(var elem in data_s2.replinks){
							var lista = data_s2.replinks[elem].docs;
							lista = remove_duplicates(data.replinks,lista);
							for(var i = 0 ; i < lista.length; i++ ){
								if(!contains(lista[i], s_order_replinks))
									s_order_replinks.push(lista[i]);
							}
						}
						new List({
							array_data : s_order_replinks,
							container : $('<div></div>').appendTo(d),
							base_url : base_url
						});


					},'json');
							
					$('#wait').hide();
					$(first).click();
					$('#secondary_search_results').show();
					$(window.location).attr('href', '#secondary_search_results');
					$('#main_search_results :input:checkbox').removeAttr('disabled');
				},'json');				
			});
		}
		
		//for the superadmin interface
		else {
			$('#button_expand').click(function(){
				$('#main_search_results :input:checkbox').attr('disabled', true);
				$('#secondary_search_results').hide();
				$('#wait').show();
				$(window.location).attr('href', '#wait');
				
				//now we build the interface for the "related resources" (bottom-right) and call step1 to view the resources related to the ones selected
				var data2send = {
					'func' : 'step1'
				};			
				$.post(ajax_url, data2send, function(data){
					$(result).empty();
					$('<h3 class="settings">Possibly related resources</h3>').appendTo(result);
					var container = $('<div class="contentWrapper">Resources related through:<br><br></div>').appendTo(result);
					var c_tabs = $('<div id="elgg_horizontal_tabbed_nav"></div></div>').appendTo(container).append('<ul></ul>');
					
					var a,b,c,d;
					
					var first = $('<a href="javascript:void(0);" >Global similarity</a>').appendTo($('<li></li>').addClass('selected').appendTo($(c_tabs).find('ul'))).click(function(){
						select_tab(c_tabs,$(this));
						$(b).hide();
						$(c).hide();
						$(d).hide();
						$(a).css('display','block');
					});
					$('<a href="javascript:void(0);" >Tags</a>').appendTo($('<li></li>').appendTo($(c_tabs).find('ul'))).click(function(){
						select_tab(c_tabs,$(this));
						$(a).hide();
						$(c).hide();
						$(d).hide();
						$(b).css('display','block');
					});
					$('<a href="javascript:void(0);" >Uses</a>').appendTo($('<li></li>').appendTo($(c_tabs).find('ul'))).click(function(){
						select_tab(c_tabs,$(this));
						$(a).hide();
						$(b).hide();
						$(d).hide();
						$(c).css('display','block');
					});
					$('<a href="javascript:void(0);" >Replinks</a>').appendTo($('<li></li>').appendTo($(c_tabs).find('ul'))).click(function(){
						select_tab(c_tabs,$(this));
						$(a).hide();
						$(b).hide();
						$(c).hide();
						$(d).css('display','block');
					});
					$(container).append('<div ><h3 class="settings">First order associations</h3></div>');
					
					a = $('<div class="res_box1" ></div>').appendTo(container);
					b = $('<div class="res_box1" ></div>').appendTo(container);
					c = $('<div class="res_box1" ></div>').appendTo(container);
					d = $('<div class="res_box1" ></div>').appendTo(container);
					
					if(data.metadata.length>0) format_pos_fea(data.feat_metadata,a);
					var l_l = new List({
						array_data : data.metadata,
						container : $('<div></div>').appendTo(a),
						base_url : base_url
					});
					
					if(data.tags.length>0) format_pos_fea(data.feat_tags,b);
					var l_t = new List({
						array_data : data.tags,
						container : $('<div></div>').appendTo(b),
						base_url : base_url
					});
					
					if(data.uses.length>0) format_pos_fea(data.feat_uses,c);
					var l_u = new List({
						array_data : data.uses,
						container : $('<div></div>').appendTo(c),
						base_url : base_url
					});
					
					if(data.replinks.length>0) format_pos_fea(data.feat_replinks,c);
					var l_r = new List({
						array_data : data.replinks,
						container : $('<div></div>').appendTo(d),
						base_url : base_url
					});
					
					//I build here the second order associations tabs
					var data2send = {
						'func' : 'step2',
						'metadata' : true,
						'tags' : true,
						'uses' : true,
						'replinks' : true
					};
					$.post( ajax_url, data2send, function(data_s2){
						//Global similarity (Metadata) second order associations
						$(a).append('<div ><h3 class="settings">Second order associations</h3></div>');
						var c_tabs_a = $('<div id="elgg_horizontal_tabbed_nav"></div></div>').appendTo(a).append('<ul></ul>');
						var a_a,b_a,c_a,d_a;
						var first_a = $('<a href="javascript:void(0);" >G.S.->G.S.</a>').appendTo($('<li></li>').appendTo($(c_tabs_a).find('ul'))).click(function(){
							select_tab(c_tabs_a,$(this));
							$(b_a).hide();
							$(c_a).hide();
							$(d_a).hide();
							$(a_a).css('display','block');
						});
						$('<a href="javascript:void(0);" >G.S.->Tags</a>').appendTo($('<li></li>').appendTo($(c_tabs_a).find('ul'))).click(function(){
							select_tab(c_tabs_a,$(this));
							$(c_a).hide();
							$(d_a).hide();
							$(a_a).hide();
							$(b_a).css('display','block');
						});
						$('<a href="javascript:void(0);" >G.S.->Uses</a>').appendTo($('<li></li>').appendTo($(c_tabs_a).find('ul'))).click(function(){
							select_tab(c_tabs_a,$(this));
							$(a_a).hide();
							$(b_a).hide();
							$(d_a).hide();
							$(c_a).css('display','block');
						});
						$('<a href="javascript:void(0);" >G.S.->Replinks</a>').appendTo($('<li></li>').appendTo($(c_tabs_a).find('ul'))).click(function(){
							select_tab(c_tabs_a,$(this));
							$(a_a).hide();
							$(b_a).hide();
							$(c_a).hide();
							$(d_a).css('display','block');
						});
						a_a = $('<div class="res_box1" ></div>').appendTo(a);
						b_a = $('<div class="res_box1" ></div>').appendTo(a);
						c_a = $('<div class="res_box1" ></div>').appendTo(a);
						d_a = $('<div class="res_box1" ></div>').appendTo(a);
						$(first_a).click();
						
						if(data_s2.metadata.metadata.docs.length>0) format_assoc_pos_fea(data_s2.metadata.metadata.features,a_a);
						var s_order_metadata_metadata = [];
						var lista = data_s2.metadata.metadata.docs;
						lista = remove_duplicates(data.metadata,lista);
						for(var i = 0 ; i < lista.length; i++ ){
							if(!contains(lista[i], s_order_metadata_metadata)) s_order_metadata_metadata.push(lista[i]);
						}
						new List({
							array_data : s_order_metadata_metadata,
							container : $('<div></div>').appendTo(a_a),
							base_url : base_url
						});
						
						if(data_s2.metadata.tags.docs.length>0) format_assoc_pos_fea(data_s2.metadata.tags.features,b_a);
						var s_order_metadata_tags = [];
						var lista = data_s2.metadata.tags.docs;
						lista = remove_duplicates(data.metadata,lista);
						for(var i = 0 ; i < lista.length; i++ ){
							if(!contains(lista[i], s_order_metadata_tags)) s_order_metadata_tags.push(lista[i]);
						}
						new List({
							array_data : s_order_metadata_tags,
							container : $('<div></div>').appendTo(b_a),
							base_url : base_url
						});
						
						if(data_s2.metadata.uses.docs.length>0) format_assoc_pos_fea(data_s2.metadata.uses.features,c_a);
						var s_order_metadata_uses = [];
						var lista = data_s2.metadata.uses.docs;
						lista = remove_duplicates(data.metadata,lista);
						for(var i = 0 ; i < lista.length; i++ ){
							if(!contains(lista[i], s_order_metadata_uses)) s_order_metadata_uses.push(lista[i]);
						}
						new List({
							array_data : s_order_metadata_uses,
							container : $('<div></div>').appendTo(c_a),
							base_url : base_url
						});
						
						if(data_s2.metadata.replinks.docs.length>0) format_assoc_pos_fea(data_s2.metadata.replinks.features,d_a);
						var s_order_metadata_replinks = [];
						var lista = data_s2.metadata.replinks.docs;
						lista = remove_duplicates(data.metadata,lista);
						for(var i = 0 ; i < lista.length; i++ ){
							if(!contains(lista[i], s_order_metadata_replinks)) s_order_metadata_replinks.push(lista[i]);
						}
						new List({
							array_data : s_order_metadata_replinks,
							container : $('<div></div>').appendTo(d_a),
							base_url : base_url
						});
						
						
						
						
						//Tags second order associations
						$(b).append('<div ><h3 class="settings">Second order associations</h3></div>');
						var c_tabs_b = $('<div id="elgg_horizontal_tabbed_nav"></div></div>').appendTo(b).append('<ul></ul>');
						var a_b,b_b,c_b,d_b;
						var first_b = $('<a href="javascript:void(0);" >Tags->Global similarity</a>').appendTo($('<li></li>').addClass('selected').appendTo($(c_tabs_b).find('ul'))).click(function(){
							select_tab(c_tabs_b,$(this));
							$(b_b).hide();
							$(c_b).hide();
							$(d_b).hide();
							$(a_b).css('display','block');
						});
						$('<a href="javascript:void(0);" >Tags->Tags</a>').appendTo($('<li></li>').appendTo($(c_tabs_b).find('ul'))).click(function(){
							select_tab(c_tabs_b,$(this));
							$(a_b).hide();
							$(d_b).hide();
							$(c_b).hide();
							$(b_b).css('display','block');
						});
						$('<a href="javascript:void(0);" >Tags->Uses</a>').appendTo($('<li></li>').appendTo($(c_tabs_b).find('ul'))).click(function(){
							select_tab(c_tabs_b,$(this));
							$(a_b).hide();
							$(d_b).hide();
							$(b_b).hide();
							$(c_b).css('display','block');
						});
						$('<a href="javascript:void(0);" >Tags->Replinks</a>').appendTo($('<li></li>').appendTo($(c_tabs_b).find('ul'))).click(function(){
							select_tab(c_tabs_b,$(this));
							$(a_b).hide();
							$(b_b).hide();
							$(c_b).hide();
							$(d_b).css('display','block');
						});
						a_b = $('<div class="res_box1" ></div>').appendTo(b);
						b_b = $('<div class="res_box1" ></div>').appendTo(b);
						c_b = $('<div class="res_box1" ></div>').appendTo(b);
						d_b = $('<div class="res_box1" ></div>').appendTo(b);
						$(first_b).click();
						
						if(data_s2.tags.metadata.docs.length>0) format_assoc_pos_fea(data_s2.tags.metadata.features,a_b);
						var s_order_tags_metadata = [];
						var lista = data_s2.tags.metadata.docs;
						lista = remove_duplicates(data.tags,lista);
						for(var i = 0 ; i < lista.length; i++ ){
							if(!contains(lista[i], s_order_tags_metadata)) s_order_tags_metadata.push(lista[i]);
						}
						new List({
							array_data : s_order_tags_metadata,
							container : $('<div></div>').appendTo(a_b),
							base_url : base_url
						});
						
						if(data_s2.tags.tags.docs.length>0) format_assoc_pos_fea(data_s2.tags.tags.features,b_b);
						var s_order_tags_tags = [];
						var lista = data_s2.tags.tags.docs;
						lista = remove_duplicates(data.tags,lista);
						for(var i = 0 ; i < lista.length; i++ ){
							if(!contains(lista[i], s_order_tags_tags)) s_order_tags_tags.push(lista[i]);
						}
						new List({
							array_data : s_order_tags_tags,
							container : $('<div></div>').appendTo(b_b),
							base_url : base_url
						});
						
						if(data_s2.tags.uses.docs.length>0) format_assoc_pos_fea(data_s2.tags.uses.features,c_b);
						var s_order_tags_uses = [];
						var lista = data_s2.tags.uses.docs;
						lista = remove_duplicates(data.tags,lista);
						for(var i = 0 ; i < lista.length; i++ ){
							if(!contains(lista[i], s_order_tags_uses)) s_order_tags_uses.push(lista[i]);
						}
						new List({
							array_data : s_order_tags_uses,
							container : $('<div></div>').appendTo(c_b),
							base_url : base_url
						});
						
						if(data_s2.tags.replinks.docs.length>0) format_assoc_pos_fea(data_s2.tags.replinks.features,d_b);
						var s_order_tags_replinks = [];
						var lista = data_s2.tags.replinks.docs;
						lista = remove_duplicates(data.tags,lista);
						for(var i = 0 ; i < lista.length; i++ ){
							if(!contains(lista[i], s_order_tags_replinks)) s_order_tags_replinks.push(lista[i]);
						}
						new List({
							array_data : s_order_tags_replinks,
							container : $('<div></div>').appendTo(d_b),
							base_url : base_url
						});
						
						
						
						
						//Uses second order associations
						$(c).append('<div ><h3 class="settings">Second order associations</h3></div>');
						var c_tabs_c = $('<div id="elgg_horizontal_tabbed_nav"></div></div>').appendTo(c).append('<ul></ul>');
						var a_c,b_c,c_c,d_c;
						var first_c = $('<a href="javascript:void(0);" >Uses->Global similarity</a>').appendTo($('<li></li>').addClass('selected').appendTo($(c_tabs_c).find('ul'))).click(function(){
							select_tab(c_tabs_c,$(this));
							$(b_c).hide();
							$(c_c).hide();
							$(d_c).hide();
							$(a_c).css('display','block');
						});
						$('<a href="javascript:void(0);" >Uses->Tags</a>').appendTo($('<li></li>').appendTo($(c_tabs_c).find('ul'))).click(function(){
							select_tab(c_tabs_c,$(this));
							$(a_c).hide();
							$(c_c).hide();
							$(d_c).hide();
							$(b_c).css('display','block');
						});
						$('<a href="javascript:void(0);" >Uses->Uses</a>').appendTo($('<li></li>').appendTo($(c_tabs_c).find('ul'))).click(function(){
							select_tab(c_tabs_c,$(this));
							$(a_c).hide();
							$(b_c).hide();
							$(d_c).hide();
							$(c_c).css('display','block');
						});
						$('<a href="javascript:void(0);" >Uses->Replinks</a>').appendTo($('<li></li>').appendTo($(c_tabs_c).find('ul'))).click(function(){
							select_tab(c_tabs_c,$(this));
							$(a_c).hide();
							$(b_c).hide();
							$(c_c).hide();
							$(d_c).css('display','block');
						});
						a_c = $('<div class="res_box1" ></div>').appendTo(c);
						b_c = $('<div class="res_box1" ></div>').appendTo(c);
						c_c = $('<div class="res_box1" ></div>').appendTo(c);
						d_c = $('<div class="res_box1" ></div>').appendTo(c);
						$(first_c).click();
						
						if(data_s2.uses.metadata.docs.length>0) format_assoc_pos_fea(data_s2.uses.metadata.features,a_c);
						var s_order_uses_metadata = [];
						var lista = data_s2.uses.metadata.docs;
						lista = remove_duplicates(data.uses,lista);
						for(var i = 0 ; i < lista.length; i++ ){
							if(!contains(lista[i], s_order_uses_metadata)) s_order_uses_metadata.push(lista[i]);
						}
						new List({
							array_data : s_order_uses_metadata,
							container : $('<div></div>').appendTo(a_c),
							base_url : base_url
						});
						
						if(data_s2.uses.tags.docs.length>0) format_assoc_pos_fea(data_s2.uses.tags.features,b_c);
						var s_order_uses_tags = [];
						var lista = data_s2.uses.tags.docs;
						lista = remove_duplicates(data.uses,lista);
						for(var i = 0 ; i < lista.length; i++ ){
							if(!contains(lista[i], s_order_uses_tags)) s_order_uses_tags.push(lista[i]);
						}
						new List({
							array_data : s_order_uses_tags,
							container : $('<div></div>').appendTo(b_c),
							base_url : base_url
						});
						
						if(data_s2.uses.uses.docs.length>0) format_assoc_pos_fea(data_s2.uses.uses.features,c_c);
						var s_order_uses_uses = [];
						var lista = data_s2.uses.uses.docs;
						lista = remove_duplicates(data.uses,lista);
						for(var i = 0 ; i < lista.length; i++ ){
							if(!contains(lista[i], s_order_uses_uses)) s_order_uses_uses.push(lista[i]);
						}
						new List({
							array_data : s_order_uses_uses,
							container : $('<div></div>').appendTo(c_c),
							base_url : base_url
						});
						
						if(data_s2.uses.replinks.docs.length>0) format_assoc_pos_fea(data_s2.uses.replinks.features,d_c);
						var s_order_uses_replinks = [];
						var lista = data_s2.uses.replinks.docs;
						lista = remove_duplicates(data.uses,lista);
						for(var i = 0 ; i < lista.length; i++ ){
							if(!contains(lista[i], s_order_uses_replinks)) s_order_uses_replinks.push(lista[i]);
						}
						new List({
							array_data : s_order_uses_replinks,
							container : $('<div></div>').appendTo(d_c),
							base_url : base_url
						});
						
						
						
						
						//Replinks second order associations
						$(d).append('<div ><h3 class="settings">Second order associations</h3></div>');
						var c_tabs_d = $('<div id="elgg_horizontal_tabbed_nav"></div></div>').appendTo(d).append('<ul></ul>');
						var a_d,b_d,c_d,d_d;
						var first_d = $('<a href="javascript:void(0);" >Replinks->G.S.</a>').appendTo($('<li></li>').addClass('selected').appendTo($(c_tabs_d).find('ul'))).click(function(){
							select_tab(c_tabs_d,$(this));
							$(b_d).hide();
							$(c_d).hide();
							$(d_d).hide();
							$(a_d).css('display','block');
						});
						$('<a href="javascript:void(0);" >Replinks->Tags</a>').appendTo($('<li></li>').appendTo($(c_tabs_d).find('ul'))).click(function(){
							select_tab(c_tabs_d,$(this));
							$(a_d).hide();
							$(c_d).hide();
							$(d_d).hide();
							$(b_d).css('display','block');
						});
						$('<a href="javascript:void(0);" >Replinks->Uses</a>').appendTo($('<li></li>').appendTo($(c_tabs_d).find('ul'))).click(function(){
							select_tab(c_tabs_d,$(this));
							$(a_d).hide();
							$(b_d).hide();
							$(d_d).hide();
							$(c_d).css('display','block');
						});
						$('<a href="javascript:void(0);" >Replinks->Replinks</a>').appendTo($('<li></li>').appendTo($(c_tabs_d).find('ul'))).click(function(){
							select_tab(c_tabs_d,$(this));
							$(a_d).hide();
							$(b_d).hide();
							$(c_d).hide();
							$(d_d).css('display','block');
							
						});
						a_d = $('<div class="res_box1" ></div>').appendTo(d);
						b_d = $('<div class="res_box1" ></div>').appendTo(d);
						c_d= $('<div class="res_box1" ></div>').appendTo(d);
						d_d= $('<div class="res_box1" ></div>').appendTo(d);
						$(first_d).click();
						
						if(data_s2.replinks.metadata.docs.length>0) format_assoc_pos_fea(data_s2.replinks.metadata.features,a_d);
						var s_order_replinks_metadata = [];
						var lista = data_s2.replinks.metadata.docs;
						lista = remove_duplicates(data.replinks,lista);
						for(var i = 0 ; i < lista.length; i++ ){
							if(!contains(lista[i], s_order_replinks_metadata)) s_order_replinks_metadata.push(lista[i]);
						}
						new List({
							array_data : s_order_replinks_metadata,
							container : $('<div></div>').appendTo(a_d),
							base_url : base_url
						});
						
						if(data_s2.replinks.tags.docs.length>0) format_assoc_pos_fea(data_s2.replinks.tags.features,b_d);
						var s_order_replinks_tags = [];
						var lista = data_s2.replinks.tags.docs;
						lista = remove_duplicates(data.replinks,lista);
						for(var i = 0 ; i < lista.length; i++ ){
							if(!contains(lista[i], s_order_replinks_tags)) s_order_replinks_tags.push(lista[i]);
						}
						new List({
							array_data : s_order_replinks_tags,
							container : $('<div></div>').appendTo(b_d),
							base_url : base_url
						});
						
						if(data_s2.replinks.uses.docs.length>0) format_assoc_pos_fea(data_s2.replinks.uses.features,c_d);
						var s_order_replinks_uses = [];
						var lista = data_s2.replinks.uses.docs;
						lista = remove_duplicates(data.replinks,lista);
						for(var i = 0 ; i < lista.length; i++ ){
							if(!contains(lista[i], s_order_replinks_uses)) s_order_replinks_uses.push(lista[i]);
						}
						new List({
							array_data : s_order_replinks_uses,
							container : $('<div></div>').appendTo(c_d),
							base_url : base_url
						});
						
						if(data_s2.replinks.replinks.docs.length>0) format_assoc_pos_fea(data_s2.replinks.replinks.features,d_d);
						var s_order_replinks_replinks = [];
						var lista = data_s2.replinks.replinks.docs;
						lista = remove_duplicates(data.replinks,lista);
						for(var i = 0 ; i < lista.length; i++ ){
							if(!contains(lista[i], s_order_replinks_replinks)) s_order_replinks_replinks.push(lista[i]);
						}
						new List({
							array_data : s_order_replinks_replinks,
							container : $('<div></div>').appendTo(d_d),
							base_url : base_url
						});

					},'json');
							
					$('#wait').hide();
					$(first).click();
					$('#secondary_search_results').show();
					$(window.location).attr('href', '#secondary_search_results');
					$('#main_search_results :input:checkbox').removeAttr('disabled');
				},'json');
			});
		}
	}	
});


function display_pos_feat(array,target){
	var list = $('<ul></ul>').appendTo(target);
	if(array == null)
		return;
	for (var i = 0; i < array.length; i++){
		$('<li>' + array[i] + '</li>').appendTo(list);
	}
	
}

function select_tab(c_tabs,clicked){
	$(c_tabs).find('li').removeClass('selected');
	$(clicked).parent().addClass('selected');
}

function contains(obj, a) {
	var i = a.length;
	while (i--) {
		if (a[i].username == obj.username) return true;
	}
	return false;
}

function remove_duplicates(ar1,ar2){
	var res = [];
	for(var j = 0; j < ar2.length; j++){
		if(!contains(ar2[j], ar1)){
			res.push(ar2[j]);
		}
	}
	return res;	
}

function format_pos_fea(array,container){
	if(!array || array.length == 0)
		return;
	
	var str = '';
	for(var i = 0; i < array.length; i++){
		if(array[i])
			str += (i < array.length -1) ? array[i] + ', ' : array[i] + '. ';
	}
	if(str != ''){
		var box = $('<h3></h3>').appendTo(container).addClass('settings').css({
				'font-weight' : 'normal',
				'font-size' : '12px'
		});
		$(box).html('<b>Positive features: </b>'+str);
	}
}


function format_assoc_pos_fea(str,container){
	if(!str || str.length == 0)
		return;

	/*var str = '';
	for(var i = 0; i < array.length; i++){
		if(array[i])
			str += array[i] + '<br /> ';
	}*/
	if(str != ''){
		var box = $('<h3></h3>').appendTo(container).addClass('settings').css({
				'font-weight' : 'normal',
				'font-size' : '12px'
		});
		$(box).html('<b>Positive features: </b>'+str);
	}
}

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
			else document.write("<li>["+p+"] => "+theObj[p]+"</li>");
		}
		document.write("</ul>");
	}
}