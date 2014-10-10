<?php

  //only logged in users can search
  gatekeeper();

  //include external scripts
  include_once(dirname(dirname(__FILE__)) . "/custom/MMDSearchResults.php");

  // get the data
  $searchID = get_input('searchID');
  $searchType = get_input('searchType');
  
  if($searchType == "services") {
    $lang = get_input('lang');
    $subject = urlencode(get_input('subject'));

    $address = $CONFIG->DISTRIBUTED_API_URL . "searchservices?lang=".$lang."&sub=".$subject;
  }
  elseif($searchType == "data") {
    $query = urlencode(get_input('query'));
    $service_uri = urlencode(str_replace("&amp;", "&", get_input('service_uri')));
    $service_lifting = urlencode(str_replace("&amp;", "&", get_input('service_lifting')));
   
    $lcms_address = get_input('lcms_address');
    $lifting_address = get_input('lifting_address');

    $address = $CONFIG->DISTRIBUTED_API_URL . "serviceresponse?query=".$query."&uri=".$service_uri."&lifting=".$service_lifting;
  }
  
  //echo $address . "\n";

  //link to the API
  $result = connectToSesame($address);
  //echo "result = ".$result;
  //exit();
 
  $searchResults = new MMDSearchResults($searchType, $result, true);
  $searchResults->service_uri = get_input('service_uri');

  //print_r($searchResults);
  //exit();

  header('Cache-Control: no-cache, must-revalidate');
  header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

  if($searchType == "services")
  {
    header('Content-type: text/html');
    echo $searchResults->displayServices();
  }
  else
  {
    $response = new stdClass();
    $response->searchID = $searchID;
    $response->searchType = $searchType;
    $response->__elgg_ts = time();
    $response->__elgg_token = elgg_view('ajax/securitytoken');
    $response->results = $searchResults->resultsDisplayList();
    header('Content-type: application/json');
    echo json_encode($response);
    //print_r($searchResults);
  }

  //AJAX patch for elgg
  exit();

?>
