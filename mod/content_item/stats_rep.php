<?php
    // Load Elgg engine
    include_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
 
        require_once(dirname(dirname(dirname(dirname(__FILE__))))."/mod/mmsearch/custom/MeducatorParser.php");
        require_once(dirname(dirname(dirname(dirname(__FILE__))))."/mod/mmsearch/custom/MeducatorMetadata.php");

		
    // make sure only logged in users can see this page	
    admin_gatekeeper();
 
    // set the title
    $title = "CREATE AND DISPLAY CSV";
 
    // start building the main column of the page
    $area2 = elgg_view_title($title);
  
    // Add the form to this section
	
	global $CONFIG;
	
	$ourFileName = "stats_rep.txt";		
	$ourFileHandle = fopen($ourFileName, 'w') or die("can't open file");

	$area2 .="<div class=\"filerepo_download\"><p><a href=\"";
	$area2 .=$vars['url'];
	$area2 .=$ourFileName;
	$area2 .="\">";
	$area2 .=elgg_echo("file:download");
	$area2 .="</a></p></div>";	
	
	
	$members=get_entities_from_metadata('issimpleuser', 'no', 'user', '', '',10000);
	


	fwrite($ourFileHandle,"GUID|CREATOR|AFFILIATION|TITLE|URL|IPR_CC|IPR_OTHER|QUALITY STAMP|RESOURCE LANGUAGE|METADATA LANGUAGE|AUTHOR|DATE|CITATION|KEYWORDS|EDUCATIONAL DESCRIPTION|TECHNICAL DESCRIPTION|RESOURCE TYPE|MEDIA TYPE|DISCIPLINE|SPECIALTY|EDUCATIONAL LEVEL|EDUCATIONAL CONTEXT|EDUCATIONAL INSTRUCTIONS|EDUCATIONAL OBJECTIVES|LEARNING OUTCOMES|ASSESSMENT METHODS|EDUCATIONAL PREREQUISITES|IDS OF PARENTS|COMPANION\r");

	foreach ($members as $nikolas)
{
        $address = $CONFIG->API_URL . "eidsearch?id=" . $nikolas->guid;
        $rdf_info = connectToSesame($address);

        $medParser = new MeducatorParser($rdf_info, true);	
		if(count($medParser->results) > 0)
          foreach($medParser->results as $key => $value)
          {
            $resourceSesameID = $key;
            $resourceData = $value;
          } else  {
              $resourceData = array();
              $resourceSesameID = "";
            }
            $mM = new MeducatorMetadata($resourceSesameID, $resourceData);
			
			$stats=$nikolas->guid."|";
            $creaguid= $nikolas->creatorg;
            $crea=get_entity($creaguid);
			$stats.=$crea->name."|".$crea->Affiliation."|".$mM->getData("title");
     //       fwrite($ourFileHandle,$crea->name);
      //      fwrite($ourFileHandle,"|".$crea->Affiliation);
            
      //      fwrite($ourFileHandle,"|".$mM->getData("title"));
            
          $uris=$mM->getData("identifier");
														if ($uris)
														foreach($uris as $uri)
														{
															$statsiden= $uri[description];
																								
														}
			
			
			
}	
	
	
	
	
	
	
	
	    // layout the page
	 $body =elgg_view_layout('one_column', $area2);
 	
    // draw the page
    page_draw($title, $body);
	fclose($ourFileHandle);
	
	
?>