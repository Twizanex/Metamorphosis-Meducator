//this file contains code useful to build properly a list of document to show as result of both the focused and the exploratory search
var List = function(opt_in){
	
	var defaults = {
		base_url : base_url,
		array_data : [],
		second_order_data : {},
		page_elements : 10,
		container : null,
		cck: 0
	};
	var opts = $.extend(defaults,opt_in);
	var target;
	
	var pagination_data = {
		left_num : 2,
		right_num : 2,
		left_wing_num : 2,
		right_wing_num : 2,
		max_num : 9
	};

	var clusters_text = {
	  	0 : "GUID",
		1 : "Metadata",
		2 : "Uses",
		3 : "Tags",
		4 : "Replinks"		
	};
	
	var display_results = function(start,limit,array,cck){
		var result = target;
		$(result).empty();
		for(var i = start; i < limit; i++){  //shows each resource/user
			var guidd="GUID: "+array[i].guid;
			var cont = $('<div></div>').appendTo(result).addClass('search_listing');
			var search_l = $('<div></div>').appendTo(cont).addClass('search_listing_icon');
			var useric = $('<div></div>').appendTo(search_l).addClass('usericon');
			switch(array[i].type) {
				case 'user':
					var link = $('<a></a>').appendTo(useric).addClass('icon').attr('href',opts.base_url + 'pg/profile/'+ array[i].username).attr('target','_blank');
					var img = $('<img />').appendTo(link)
						.attr('src',opts.base_url + 'mod/profile/graphics/defaultsmall.gif')
						.attr('title', array[i].username)
						.attr('border','0');
					var search_i = $('<div></div>').appendTo(cont).addClass('search_listing_info');
					var p = $('<p></p>').appendTo(search_i);
					var b = $('<b></b>').appendTo(p);
					$('<a href="'+opts.base_url + 'pg/profile/' + array[i].username + '">' + array[i].name + '</a>').appendTo(b).attr('title',guidd).attr('target','_blank');
					if(array[i].newelement==1) $('<i>New result!</i>').css('margin-left','50px').appendTo(b);   //it's a new result if compared to last search
					break;
				
				case 'resource':
					var link = $('<a></a>').appendTo(useric).addClass('icon').attr('href',opts.base_url + 'pg/profile/'+ array[i].username).attr('target','_blank');
					var img = $('<img />').appendTo(link)
						.attr('src',opts.base_url + 'mod/vazco_avatar/avatar.php?file_guid=3039&size=small')
						.attr('title', array[i].username)
						.attr('border','0');
					var search_i = $('<div></div>').appendTo(cont).addClass('search_listing_info');
					var p = $('<p></p>').appendTo(search_i);
					var b = $('<b></b>').appendTo(p);
					$('<a href="'+opts.base_url + 'pg/profile/' + array[i].username + '">' + array[i].name + '</a>').appendTo(b).attr('title',guidd).attr('target','_blank');
					if(array[i].newelement==1) $('<i>New result!</i>').css('margin-left','50px').appendTo(b);   //it's a new result if compared to last search
					if(cck && exploratorysearch) {
						$('<input type="checkbox" guid="' + array[i].guid + '" />').appendTo(b).click(function(){
							var ck = $(this).attr('checked');
							var data = {
								'func' : 'save_doc',
								'guid' : $(this).attr('guid')
							}
							if(ck) data.act = 'add_doc';
							else data.act = 'remove_doc';
							$.post(opts.base_url + "pg/mod/profile_manager/actions/members/rpc.php",data,function(response){
								if(dynamic) $('#button_expand').click();
							});
						}).css('float','right').append('<div style="clear:both"></div>');
					}
					if(issuperadminloggedin && exploratorysearch) {
						$('<img title="Get info on the document"/>').attr('src',opts.base_url +'mod/profile_manager/actions/members/info.gif').appendTo(b).css({
							'float' : 'right',
							'cursor' : 'pointer'
						}).click(function(){
							var infos = $(this).next().next();
							if(infos.is(":hidden") ) {
								if(!infos.text()) {
									var gif = $('<img />').attr('src',opts.base_url +'mod/profile_manager/actions/members/wait.gif').appendTo(infos);
									infos.slideDown('fast');
									var data2send= {
										'func' : 'get_cluster_id_from_doc_guid',
										'guid' : infos.attr('guid')
									};			
									$.post(ajax_url, data2send, function(data){
										//view GUID
										var cluster_description = $('<div></div>').appendTo(infos);
										$('<span>' +clusters_text[0]+ ': ' +data[0]+ '</span>').appendTo(cluster_description);
										//view clusters info
										for(var i=1; i<5; i++){
											var cluster_description = $('<div></div>').appendTo(infos);
											$('<span>' + clusters_text[i] + ': </span>').appendTo(cluster_description);
											if(data[i]!="none"){
												var text = $('<span>part of Cluster(s) </span>').appendTo(cluster_description);
												for(var j=0;j<data[i].length;j++) {
													var out = $('<a>' + data[i][j] + '</a>').appendTo(cluster_description).attr('href','javascript:void(0);').click(function(){
														var _data = $(this).text();
														var parent = $(this).parent();
														var label = parent.text().substring(0,4);
														var popup = window.open(base_url + 'pg/mod/profile_manager/actions/members/clusters_view.php?str=' + _data +'&type=' + label,'newpopupwindow','width=550,height=400,scrollbars=yes,resizable=no');
														popup.moveTo(400,200);
													});
													if(j!=(data[i].length)-1) var add= $('<span>, </span>').appendTo(cluster_description);
												}
											}
											else var out = $('<span>' + data[i] + '</span>').appendTo(cluster_description).attr('href','javascript:void(0);');
											gif.hide();
										}
										infos.slideDown('fast');
									},'json');	
								}
								else infos.slideDown('fast'); 
							}
							else infos.slideUp('fast');
						});
					}
					if(!cck) {
						var threshold=array[0].relevance/4;
						if(array[i].relevance>(3*threshold)) {
							$('<img title="'+array[i].relevance+'" width="100px" />').attr('src',opts.base_url +'mod/profile_manager/actions/members/rank2.jpg').appendTo(b).css({
								'float' : 'right'
							});
						}
						else if(array[i].relevance<=(3*threshold) && array[i].relevance>(2*threshold)) {
							$('<img title="'+array[i].relevance+'" width="100px" />').attr('src',opts.base_url +'mod/profile_manager/actions/members/rank15.jpg').appendTo(b).css({
								'float' : 'right'
							});
						}
						else if(array[i].relevance<=(2*threshold) && array[i].relevance>threshold) {
							$('<img title="'+array[i].relevance+'" width="100px" />').attr('src',opts.base_url +'mod/profile_manager/actions/members/rank1.jpg').appendTo(b).css({
								'float' : 'right'
							});
						}
						else if(array[i].relevance<=threshold && array[i].relevance>0) {
							$('<img title="'+array[i].relevance+'" width="100px" />').attr('src',opts.base_url +'mod/profile_manager/actions/members/rank05.jpg').appendTo(b).css({
								'float' : 'right'
							});
						}
						else $('<div></div>').appendTo(b);
					}
					else $('<div></div>').appendTo(b);
					if(issuperadminloggedin && exploratorysearch) {
						$('<div id="ghostbox' + i + '" guid="' + array[i].guid + '"></div>').appendTo(b).hide();
					}
					break;
				
				case 'cluster':
					var link = $('<a></a>').appendTo(useric).addClass('icon').attr({ 'href' : 'javascript:void(0);', 'name': array[i].guid }).click(function(){
						var _data = $(this).attr('name');
						var popup = window.open(opts.base_url + 'pg/mod/profile_manager/actions/members/clusters_view.php?str=' + _data,'newpopupwindow','width=550,height=400,scrollbars=yes,resizable=no');
						popup.moveTo(400,200);
					});
					var img = $('<img />').appendTo(link)
						.attr('src',opts.base_url + 'mod/profile_manager/actions/members/defaultcluster.gif')
						.attr('title', array[i].username)
						.attr('border','0');
					var search_i = $('<div></div>').appendTo(cont).addClass('search_listing_info');
					var p = $('<p></p>').appendTo(search_i);
					var b = $('<b></b>').appendTo(p);
					$('<a href="javascript:void(0);">' + array[i].name + '</a>').appendTo(b).attr({'title' : guidd, 'name' : array[i].guid}).click(function(){
						var _data = $(this).attr('name');
						var popup = window.open(opts.base_url + 'pg/mod/profile_manager/actions/members/clusters_view.php?str=' + _data,'newpopupwindow','width=550,height=400,scrollbars=yes,resizable=no');
						popup.moveTo(400,200);
					});
					if(array[i].newelement==1) $('<i>New result!</i>').css('margin-left','50px').appendTo(b);   //it's a new result if compared to last search
					if(array[i].rank==2) {
						$('<img title="rank" width="100px" />').attr('src',opts.base_url +'mod/profile_manager/actions/members/rank2.jpg').appendTo(b).css({
							'float' : 'right'
						});
					}
					else if(array[i].rank<2 && array[i].rank>=1.3) {
						$('<img title="rank" width="100px" />').attr('src',opts.base_url +'mod/profile_manager/actions/members/rank15.jpg').appendTo(b).css({
							'float' : 'right'
						});
					}
					else if(array[i].rank<1.3 && array[i].rank>=0.8) {
						$('<img title="rank" width="100px" />').attr('src',opts.base_url +'mod/profile_manager/actions/members/rank1.jpg').appendTo(b).css({
							'float' : 'right'
						});
					}
					else if(array[i].rank<0.8 && array[i].rank>=0.3) {
						$('<img title="rank" width="100px" />').attr('src',opts.base_url +'mod/profile_manager/actions/members/rank05.jpg').appendTo(b).css({
							'float' : 'right'
						});
					}
					break;
				  
			}
		}
	};
	
	var pagination = function(data,cck){
		var tot_p = Math.ceil(data.length / opts.page_elements);
		var c_pag = $('<div></div>').appendTo(opts.container).addClass('pagination');
		
		for(var i = 0; i < tot_p + 2; i++){
			var text = page_text(i,tot_p);
	      
			var link = $('<a>' + text + '</a>').appendTo(c_pag).attr('href','javascript:void(0);').addClass('pagination_number').attr('id_page',i).click(function(){
					
		
				//var parent_container = $(this).parent();
				var this_page = parseInt($(this).attr('id_page'));
		
				var max = parseInt($(c_pag).find("a[id_page='1']").attr('my_max'))+1;
				var current = parseInt($(c_pag).find("a[class='pagination_number pagination_currentpage']").attr('id_page'));
		
				switch (this_page){
					case 0:
						$(c_pag).find('a[id_page=' + (current-1) + ']').click();
						break;
		  
					case max:
						$(c_pag).find('a[id_page=' + (current+1) + ']').click();
						break;
		  
					default:
						$(c_pag).find('a').removeClass('pagination_currentpage');
						$(this).addClass('pagination_currentpage');

						var dataa = {
							'func' : 'reset_ent2'
						}
						if(cck) {   //reset ent2 only when you click on the numbers of the first list (the one with checkboxes)
							$.post(opts.base_url + "pg/mod/profile_manager/actions/members/rpc.php",dataa,function(responsee){
								if(dynamic && $('#secondary_search').is(":visible")) $('#button_expand').click();
							});
						}
		    
						var start = ($(this).attr('id_page') -1) * opts.page_elements;
						var limit = start + opts.page_elements;
						if(limit > data.length){
							limit = data.length;
						}
						show_buttons(this_page,c_pag);
						display_results(start,limit,data,cck);
						break;
				}
			}).hide();
      
			var dots = $('<span>...</span>').appendTo(c_pag).attr('dots_id_page',i).removeClass('pagination_number').css({
				'color' : '#4690D6',
				'float' : 'left',
				'margin' : '0 6px 0 0'
			}).hide();
      
			if(i == 1) {
				$(c_pag).find("a[id_page='1']").attr('my_max', tot_p).addClass('pagination_currentpage');
				var start = ($(c_pag).find("a[id_page='1']").attr('id_page') -1)* opts.page_elements;
				var limit = start + opts.page_elements;
				if(limit > data.length){
					limit = data.length;
				}
				display_results(start,limit,data,cck);
			}
		}
		show_buttons(1,c_pag);
	}
  
	var page_text = function(str,max){
		var out;
		switch(str) {
			case 0:
				out = "Previous";
				break;
			case max+1:
				out = "Next";
				break;
			default:
				out = str;
				break;
		}
    
		return out;
	}
  
	var show_buttons = function(pos,container_id){
    
		var tot_elem = parseInt($(container_id).find("a[id_page='1']").attr('my_max'));
    
		if(tot_elem > pagination_data.max_num){   
    
			//SHOWING NUMBERS
			for(var i = 0; i<tot_elem; i++)
			$(container_id).find('a[id_page="' + i + '"]').hide();

			$(container_id).find('a[id_page="1"]').show();
			$(container_id).find('a[id_page="2"]').show();
			$(container_id).find('a[id_page="' + (pos-2) + '"]').show();
			$(container_id).find('a[id_page="' + (pos-1) + '"]').show();
			$(container_id).find('a[id_page="' + (pos) + '"]').show();
			$(container_id).find('a[id_page="' + (pos+1) + '"]').show();
			$(container_id).find('a[id_page="' + (pos+2) + '"]').show();
			$(container_id).find('a[id_page="' + (tot_elem-1) + '"]').show();
			$(container_id).find('a[id_page="' + (tot_elem) + '"]').show();
			show_dots(pos,tot_elem,container_id);
		}
		else $(container_id).find('a').show();
    
		//TOGGLING PREVIOUS/NEXT
		$(container_id).find('a[id_page="0"]').show();
		$(container_id).find('a[id_page="' + (tot_elem+1) + '"]').show();
		if(tot_elem!=1){
			switch(pos) {
				case 1:
					$(container_id).find('a[id_page="0"]').hide();
					break;
				case tot_elem:
					$(container_id).find('a[id_page="' + (tot_elem+1) + '"]').hide();
					break;
				default:
					$(container_id).find('a[id_page="0"]').show();
					$(container_id).find('a[id_page="' + (tot_elem+1) + '"]').show();
					//break;
			}
		}
		else{
			$(container_id).find('a[id_page="0"]').hide();
			$(container_id).find('a[id_page="' + (tot_elem+1) + '"]').hide();
		}
    
	}
  
	var show_dots = function(pos,max,container_id){
		$(container_id).find('span[dots_id_page]').hide();
		if(pos<max-(pagination_data.right_num + pagination_data.right_wing_num)) $(container_id).find('span[dots_id_page="' + (pos+2) + '"]').show();
		if(pos > 1 + (pagination_data.left_num + pagination_data.left_wing_num)) $(container_id).find('span[dots_id_page="2"]').show();
	}
	
	var build_structure = function(){
		if(opts.array_data.length == 0){
			$('<div>Empty List</div>').appendTo(opts.container).css({
				'color' : '#4690D6',
				'font-weight' : 'bold',
				'text-align' : 'center',
				'padding' : '10px'
			});
			return;
		}
			
		target = $('<div></div>').appendTo(opts.container);
		pagination(opts.array_data,opts.cck);
		$('<div><br /></div>').appendTo(opts.container).css('clear','both');
	}
	
	
	if(opts.container == null){
		throw "The list container is missing";
	}
	
	build_structure();
	
	this.toString = function(){
		var str = '';
		for(elem in this){
			str += elem + ': ' + this[elem];
		}
		return str;
	};

}