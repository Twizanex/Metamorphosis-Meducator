<?php
    // Load Elgg engine
    include_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

    // make sure only logged in users can see this page
    gatekeeper();

    // get the form input
    $keyword = get_input('keyword');

    // set the title
    $title = "Search for educational resources";

    // start building the main column of the page
    $area2 = elgg_view_title($title);

    //display submenus
    $area1 = 

    // Add the list of results
    $area2 .= elgg_view("mmsearch/interface");

    // layout the page
    $body = elgg_view_layout('two_column_left_sidebar', '', $area2);

    // draw the page
    page_draw($title, $body);
?>