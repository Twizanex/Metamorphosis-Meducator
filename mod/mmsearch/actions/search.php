<?php

  //only logged in users can search
  gatekeeper();

  //include external scripts
  include_once(dirname(dirname(__FILE__)) . "/custom/MMSearchResults.php");

  // get the data
  $searchID = get_input('searchID');
  $searchType = get_input('searchType');
  
  if($searchType == "basic") {
    $keyword = get_input('keyword');
    $address = "keywordsearch?keyword=".$keyword;
  }
  elseif($searchType == "advanced") {
    $address = "propertysearch?";
    $first = true;
    foreach($_POST as $key=>$value)
      if((substr($key, 0, 3) == "mdc") || (substr($key, 0, 4) == "foaf"))
      {
        if(!$first) $address.= "&";
        $address .= "property=" . $key . "&value=" . $value;
        $first = false;
      }
  }
  elseif($searchType == "details") {
    $resourceID = get_input('resourceID');
    $address = "idsearch?id=" . $resourceID;
  }
  elseif($searchType == "specific") {
    $address = "search?";
    $idList = "";
    $propertyList = "";
    $firstID = true;
    $firstProperty = true;
    foreach($_POST as $key=>$value)
    {
      if(substr($key, 0, 3) == "id_")
      {
        if(!$firstID) $idList .= ";";
        $idList .= $value;
        $firstID = false;
      }
      elseif(substr($key, 0, 9) == "property_")
      {
        if(!$firstProperty) $propertyList .= ";";
        $propertyList .= $value;
        $firstProperty = false;
      }
    }
    $address .= "ids=" . $idList . "&properties=" . $propertyList;
    //echo $address;
    //exit();
  }
  elseif($searchType == "get_resources_list") {
      //TO DO
      $propertyList = "";
      $address = "searchall?properties=mdc:title;mdc:identifier";
  }

  $address = $CONFIG->API_URL . $address;
  //echo "link = " . $address;
  $result = connectToSesame($address);
  
  //echo "result = " . $result;

  $searchResults = new MMSearchResults($result, true);
  //print_r($searchResults);

  header('Cache-Control: no-cache, must-revalidate');
  header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

  if($searchType == "details")
  {
    echo $searchResults->displayDetails();
    header('Content-type: text/html');
  }
  else
  {
    $response = new stdClass();
    $response->searchID = $searchID;
    $response->searchType = $searchType;
    $response->__elgg_ts = time();
    $response->__elgg_token = elgg_view('ajax/securitytoken');
    if($searchType == "specific")
      $response->results = $searchResults->displaySpecificResultsList($propertyList);
    elseif($searchType == "get_resources_list")
      $response->results = $searchResults->DisplayResourcesList();
    else
      $response->results = $searchResults->resultsDisplayList();
      
    //exit();
    header('Content-type: application/json');
    echo json_encode($response);
  }

  //AJAX patch for elgg
  exit();

?>
