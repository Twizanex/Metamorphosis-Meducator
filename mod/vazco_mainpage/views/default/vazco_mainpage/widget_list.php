<?php 
	/**
	 * Elgg vazco_mainpage plugin
	 * 
	 * @author Michal Zacher [michal.zacher@gmail.com]
	 */
	$mainpageWidgets = new mainpageWidgets(get_plugin_setting('show3columns','vazco_mainpage'));

	$widget = new mainpageWidget(
		'members_ajax'
		,elgg_echo("custom:members:ajax")
		,elgg_echo("custom:members:ajax:desc")
		,'vazco_mainpage/widgets/members_ajax'
		,'vazco_mainpage/widgets/narrow/members_ajax'
	);
	$mainpageWidgets->addWidget($widget);
	
	$widget = new mainpageWidget(
		'groups'
		,elgg_echo("custom:groups")
		,elgg_echo("custom:groups:desc")
		,'vazco_mainpage/widgets/groups'
		,'vazco_mainpage/widgets/narrow/groups'
	);
	$mainpageWidgets->addWidget($widget);
	
	$widget = new mainpageWidget(
		'search'
		,elgg_echo("custom:search")
		,elgg_echo("custom:search:desc")
		,'vazco_mainpage/widgets/search'
	);
	$mainpageWidgets->addWidget($widget);
	
		
	$widget = new mainpageWidget(
		'poll'
		,elgg_echo("custom:poll")
		,elgg_echo("custom:poll:desc")
		,'vazco_mainpage/widgets/poll'
	);
	$mainpageWidgets->addWidget($widget);
	
		
	$widget = new mainpageWidget(
		'event'
		,elgg_echo("custom:event")
		,elgg_echo("custom:event:desc")
		,'vazco_mainpage/widgets/event'
	);
	$mainpageWidgets->addWidget($widget);
	
	$widget = new mainpageWidget(
		'discussion'
		,elgg_echo("custom:groups:latestdiscussion")
		,elgg_echo("custom:groups:latestdiscussion:desc")
		,'vazco_mainpage/widgets/latestdiscussions'
		,'vazco_mainpage/widgets/narrow/latestdiscussions'
	);
	$mainpageWidgets->addWidget($widget);

	$widget = new mainpageWidget(
		'featuredgroups'
		,elgg_echo("custom:featuredgroups")
		,elgg_echo("custom:featuredgroups:desc")
		,'vazco_mainpage/widgets/featuredgroups'
		,'vazco_mainpage/widgets/narrow/featuredgroups'
	);
	$mainpageWidgets->addWidget($widget);
	
	$widget = new mainpageWidget(
		'groupicons'
		,elgg_echo("custom:groupicons")
		,elgg_echo("custom:groupicons:desc")
		,'vazco_mainpage/widgets/groupicons'
	);
	$mainpageWidgets->addWidget($widget);
	
	$widget = new mainpageWidget(
		'files'
		,elgg_echo("custom:files")
		,elgg_echo("custom:files:desc")
		,'vazco_mainpage/widgets/file'
		,'vazco_mainpage/widgets/narrow/file'
	);
	$mainpageWidgets->addWidget($widget);
	
	$widget = new mainpageWidget(
		'izapvideos'
		,elgg_echo("custom:izap_videos")
		,elgg_echo("custom:izap_videos:desc")
		,'vazco_mainpage/widgets/izap_videos'
		,'vazco_mainpage/widgets/narrow/izap_videos'
	);
	$mainpageWidgets->addWidget($widget);
		
	$widget = new mainpageWidget(
		'members'
		,elgg_echo("custom:members")
		,elgg_echo("custom:members:desc")
		,'vazco_mainpage/widgets/members'
	);
	$mainpageWidgets->addWidget($widget);
	
	$widget = new mainpageWidget(
		'blog'
		,elgg_echo("custom:blogs")
		,elgg_echo("custom:blogs:desc")
		,'vazco_mainpage/widgets/blog'
	);
	$mainpageWidgets->addWidget($widget);
	
	$widget = new mainpageWidget(
		'bookmarks'
		,elgg_echo("custom:bookmarks")
		,elgg_echo("custom:bookmarks:desc")
		,'vazco_mainpage/widgets/bookmarks'
	);
	$mainpageWidgets->addWidget($widget);
	
	$widget = new mainpageWidget(
		'quote'
		,elgg_echo("custom:quote")
		,elgg_echo("custom:quote:desc")
		,'vazco_mainpage/widgets/quote'
		,'vazco_mainpage/widgets/narrow/quote'
	);
	$mainpageWidgets->addWidget($widget);
	
	$widget = new mainpageWidget(
		'pages'
		,elgg_echo("custom:pages")
		,elgg_echo("custom:pages:desc")
		,'vazco_mainpage/widgets/pages'
		,'vazco_mainpage/widgets/narrow/pages'
	);
	$mainpageWidgets->addWidget($widget);

	$widget = new mainpageWidget(
		'activity'
		,elgg_echo("custom:activity")
		,elgg_echo("custom:activity:desc")
		,'vazco_mainpage/widgets/activity');
	$mainpageWidgets->addWidget($widget);	
	
	$widget = new mainpageWidget(
		'simplepie'
		,elgg_echo("custom:simplepie")
		,elgg_echo("custom:simplepie:desc")
		,'vazco_mainpage/widgets/simplepie'
	);
	$mainpageWidgets->addWidget($widget);

	$widget = new mainpageWidget(
		'tidypics'
		,elgg_echo("custom:tidypics")
		,elgg_echo("custom:tidypics:desc")
		,'vazco_mainpage/widgets/tidypics'
		,'vazco_mainpage/widgets/narrow/tidypics'
	);
	$mainpageWidgets->addWidget($widget);	
//add 3 HTML widgets
	$widget = new mainpageWidget(
		'[html]'
		,elgg_echo("vazco_mainpage:html")
		,elgg_echo("vazco_mainpage:html:desc")
		);
	$mainpageWidgets->addWidget($widget);	
	
	$widget = new mainpageWidget(
		'[html]'
		,elgg_echo("vazco_mainpage:html")
		,elgg_echo("vazco_mainpage:html:desc")
		);
	$mainpageWidgets->addWidget($widget);

	$widget = new mainpageWidget(
		'[html]'
		,elgg_echo("vazco_mainpage:html")
		,elgg_echo("vazco_mainpage:html:desc")
		);
	$mainpageWidgets->addWidget($widget);	
	
	$widget = new mainpageWidget(
		'[html]'
		,elgg_echo("vazco_mainpage:html")
		,elgg_echo("vazco_mainpage:html:desc")
		);
	$mainpageWidgets->addWidget($widget);	

	$widget = new mainpageWidget(
		'[html]'
		,elgg_echo("vazco_mainpage:html")
		,elgg_echo("vazco_mainpage:html:desc")
		);
	$mainpageWidgets->addWidget($widget);	

	$widget = new mainpageWidget(
		'[html]'
		,elgg_echo("vazco_mainpage:html")
		,elgg_echo("vazco_mainpage:html:desc")
		);
	$mainpageWidgets->addWidget($widget);	

	$widget = new mainpageWidget(
		'[html]'
		,elgg_echo("vazco_mainpage:html")
		,elgg_echo("vazco_mainpage:html:desc")
		);
	$mainpageWidgets->addWidget($widget);	

	$widget = new mainpageWidget(
		'[html]'
		,elgg_echo("vazco_mainpage:html")
		,elgg_echo("vazco_mainpage:html:desc")
		);
	$mainpageWidgets->addWidget($widget);	
//$rc = $mainpageWidgets->getWidgetList();	
//print_r($rc[0]);die();
	$_SERVER['mainpageWidgets'] = $mainpageWidgets;
	
?>