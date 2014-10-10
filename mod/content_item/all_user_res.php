<?php

  require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
  require_once(dirname(dirname(__FILE__)) . "/mmsearch/custom/MMSearchResults.php");
  global $CONFIG;	

  //read all the resources for a specific user
  $items = get_entities_from_metadata('creatorg', 939, 'user', '', '',10000);
  //read all the resources from the SESAME
  $address = $CONFIG->API_URL . "searchall?properties=mdc:title";
  $searchResults = new MMSearchResults(connectToSesame($address), true);
  $SESAME_results = $searchResults->DisplayResourcesList();
  //identify the SESAME id for the wanted resources
  foreach($items as $item)
  {
    $doru = $item->guid; 
    for($i=0; ($i+1)<count($SESAME_results); $i++)
      if($doru == $SESAME_results[$i]->internalID)
      {
        echo $item->guid . " ----> " . str_replace("http://purl.org/meducator/resources/", "", $SESAME_results[$i]->ID) . "<br>";
        echo '<a href="'.$SESAME_results[$i]->ID.'">view</a><br><br>';
        //break;
			//$deladdress=$CONFIG->API_URL . str_replace("http://purl.org/meducator/resources/", "", $SESAME_results[$i]->ID);
			//echo connectToSesame($deladdress,"","YES");

      }
  }
  
?>
