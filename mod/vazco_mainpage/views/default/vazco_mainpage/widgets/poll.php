<?php
	$limit = get_plugin_setting('polls_num_items','vazco_mainpage');
	if (!$limit)
		$limit = 3;

	$_REQUEST['poll_widget'] = 1;

	$context = get_context();
	
	$polls_fullview = get_plugin_setting('polls_fullview','vazco_mainpage');
	$fullview = false;
	if ($polls_fullview == 'yes' && isloggedin())
		$fullview = true;
		
	if ($fullview){
		set_context('poll');
	}
	
	$offset = 0;
	
	$polls = get_entities('object','poll',0,'time_created desc',$limit,$offset,false,0);
	$count = get_entities('object','poll',0,'time_created desc',999,0,true);

	if ($fullview){
		$pollsNew = array();
		foreach ($polls as $poll){
			$priorVote = checkForPreviousVote($poll, get_loggedin_userid());
			if (!$priorVote){
				$poll->priorVote = $priorVote;
				array_push($pollsNew,$poll);
			}
		}
		$polls = $pollsNew;
	}	
	
	$pollList = "";
	foreach ($polls as $poll){
		$pollList .= elgg_view('vazco_mainpage/polls/poll',array('entity' => $poll, 'showUser' => false));
	} 

	set_context($context);
?>
 <div class="index_box">
	<h2><?php echo elgg_echo("custom:poll"); ?></h2>
    	<div class="contentWrapper">
		<?php echo $pollList;?>
    <div class="clearfloat"></div>
	</div>
</div>
