<?php
  // only logged in users can add blog posts
  gatekeeper();
 
  // get the form input
  $title = get_input('title');
  $body = get_input('body');
  $tags = string_to_tag_array(get_input('tags'));	
	$items=get_input('resources');

	
  // create a new blog object
  $compan = new ElggObject();
  $compan->title = $title;
  $compan->description = $body;
  $compan->identifiers=$items;
  $compan->subtype = "companion";
 
  // for now make all blog posts public
  $compan->access_id = ACCESS_PUBLIC;
 
  // owner is logged in user
  $compan->owner_guid = get_loggedin_userid();
 
  // save tags as metadata
  $compan->tags = $tags;
 
  // save to database
  $compan->save();
  foreach ($items as $item)
	add_entity_relationship ($item,"incoll",$compan->guid); 
 
  // forward user to a page that displays the post
  forward("mod/companion/");
?>