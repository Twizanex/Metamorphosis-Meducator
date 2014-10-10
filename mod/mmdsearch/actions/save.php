<?php
  // only logged in users can add blog posts
  gatekeeper();

  // get the form input
  $title = get_input('title');
  $body = get_input('id');
  $keyword = get_input('keyword');

  // create a new search object
  $search_obj = new ElggObject();
  $search_obj->title = $title;
  $search_obj->id = $id;
  $search_obj->keyword = $keyword;
  $search_obj->subtype = "mmdsearch";

  // for now make all blog posts public
  $search_obj->access_id = ACCESS_PUBLIC;

  // owner is logged in user
  $search_obj->owner_guid = get_loggedin_userid();

  // save to database
  $search_obj->save();

  // forward user to a page that displays the post
  forward($search_obj->getURL());
?>