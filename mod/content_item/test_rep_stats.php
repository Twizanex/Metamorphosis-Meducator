<?php

  // Load Elgg engine
  include_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
  //include_once(dirname(dirname(__FILE__)) . "/mmsearch/views/default/resources/properties_list.php");
  include_once(dirname(dirname(__FILE__)) . "/mmsearch/custom/MMSearchResults.php");
  
  $address = "searchall?properties=mdc:title;mdc:identifier;mdc:hasRepurposingContext";
  
  $address = $CONFIG->API_URL . $address;
  $result = connectToSesame($address);
  
  $searchResults = new MMSearchResults($result, true);
  
  print_r($searchResults->getResults());
  
  
?>
