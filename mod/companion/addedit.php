<?php
    // Load Elgg engine
    include_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
 
    // make sure only logged in users can see this page	
    gatekeeper();
 
    // set the title
    $title = "Add your resources in this Collection";
 
    // start building the main column of the page
    $area2 = elgg_view_title($title);
 
    // Add the form to this section
    $area2 .= elgg_view("companion/edit/addform");
 
    // layout the page
    $body = elgg_view_layout('two_column_left_sidebar', '', $area2);
 
    // draw the page
    page_draw($title, $body);
?>