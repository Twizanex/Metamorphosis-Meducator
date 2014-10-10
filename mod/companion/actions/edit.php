<?php
  // only logged in users can add blog posts
  gatekeeper();
 
  // get the form input
  $title = get_input('title');
  $body = get_input('body');
  $tags = string_to_tag_array(get_input('tags'));	
	$items=get_input('resources');
	$orgu=get_input('orgu');
	$oriden=get_input('oriden');
  // create a new blog object
  $compan = get_entity($orgu);
  $compan->title = $title;
  $compan->description = $body;
	$nik1=explode(",",$oriden);
	if ($items!=NULL)
  $compan->identifiers=array_merge($nik1,$items);
	else
	$compan->identifiers=$nik1;
 
 
  // save tags as metadata
  $compan->tags = $tags;
 
  // save to database
  foreach ($items as $item)
	add_entity_relationship ($item,"incoll",$compan->guid); 	
 
  // forward user to a page that displays the post
  forward("mod/companion");
?>