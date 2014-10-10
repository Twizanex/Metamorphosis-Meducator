<?php
    // Load Elgg engine
    include_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
    include_once(dirname(__FILE__) . "/actions/API_connection.php");
    include_once(dirname(__FILE__) . "/custom/MMDSearchResults.php");

    // make sure only logged in users can see this page
    gatekeeper();

    // get the form input
    $keyword = get_input('keyword');

    // set the title
    $title = "Search for distributed educational resources";

    // start building the main column of the page
    $area2 = elgg_view_title($title);

    //display submenus
    $area1 =

    //read the list of service providers from the API
    //$address = "http://smartlink.open.ac.uk/servicerestapi/restapi/searchservices";
    $address = $CONFIG->DISTRIBUTED_API_URL . "searchservices";
    $result = connectToSesame($address);
    $searchResults = new MMDSearchResults("services", $result, true);

    // Add the list of results
    $area2 .= elgg_view("mmdsearch/interface", array("services" => $searchResults->getServices()));

    // layout the page
    $body = elgg_view_layout('two_column_left_sidebar', '', $area2);

    // draw the page
    page_draw($title, $body);
?>