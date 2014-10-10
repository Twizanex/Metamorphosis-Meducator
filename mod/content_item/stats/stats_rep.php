<?php
    // Load Elgg engine
    include_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/engine/start.php");
 
        require_once(dirname(dirname(dirname(__FILE__)))."/mmsearch/custom/MeducatorParser.php");
        require_once(dirname(dirname(dirname(__FILE__)))."/mmsearch/custom/MeducatorMetadata.php");

		
    // make sure only logged in users can see this page	
 //   admin_gatekeeper();
 
    // set the title
    $title = "CREATE AND DISPLAY CSV";
 
    // start building the main column of the page
    $area2 = elgg_view_title($title);
  
    // Add the form to this section
	
	global $CONFIG;
	
		
/*
	$area2 .="<div class=\"filerepo_download\"><p><a href=\"";
	$area2 .=$vars['url'];
	$area2 .=$ourFileName;
	$area2 .="\">";
	$area2 .=elgg_echo("file:download");
	$area2 .="</a></p></div>";	
*/	
	
	$members=get_entities_from_metadata('issimpleuser', 'no', 'user', '', '',10000);
	
        set_time_limit(360000);

	$stats_single="GUID|SESAME|CREATOR|AFFILIATION|TITLE|RESOURCE LANGUAGE|METADATA LANGUAGE|DATE|CITATION|EDUCATIONAL DESCRIPTION|TECHNICAL DESCRIPTION|EDUCATIONAL CONTEXT|EDUCATIONAL INSTRUCTIONS|EDUCATIONAL OBJECTIVES|ASSESSMENT METHODS|EDUCATIONAL PREREQUISITES\r\n";
	
	$stats_ident="GUID|COUNT|Description1|Description2|Description3|Description4|Description5|Description6|Description7|Description8|Description9|Description10 \r\n";
	
	$stats_rights="GUID|IPR1|IPR2 \r\n";
	
	$stats_qual="GUID|COUNT|VALUE1|VALUE2|VALUE3 \r\n";
	
	$stats_auth="GUID|COUNT|NAME1|AFFIL1|FOAF1|NAME2|AFFIL2|FOAF2|NAME3|AFFIL3|FOAF3|NAME4|AFFIL4|FOAF4|NAME5|AFFIL5|FOAF5 \r\n "; 
	
	$stats_media="GUID|COUNT|VALUE1|VALUE2|VALUE3|VALUE4|VALUE5|VALUE6|VALUE7|VALUE8|VALUE9|VALUE10 \r\n";
	$stats_outc="GUID|COUNT|VALUE1|VALUE2|VALUE3|VALUE4|VALUE5|VALUE6|VALUE7|VALUE8|VALUE9|VALUE10 \r\n";

	
	$stats_res="GUID|COUNT|VALUE1|VALUE2|VALUE3|VALUE4|VALUE5|VALUE6|VALUE7|VALUE8|VALUE9|VALUE10 \r\n";
	
	$stats_edulev="GUID|COUNT|VALUE1|VALUE2|VALUE3|VALUE4|VALUE5|VALUE6|VALUE7|VALUE8|VALUE9|VALUE10 \r\n";
	
	$stats_compan="GUID|COUNT|VALUE1|VALUE2|VALUE3|VALUE4|VALUE5|VALUE6|VALUE7|VALUE8|VALUE9|VALUE10 \r\n";
	
	$stats_key="GUID|COUNT|VALUE1|ONTOLOGY1|VALUE2|ONTOLOGY2|VALUE3|ONTOLOGY3|VALUE4|ONTOLOGY4|VALUE5|ONTOLOGY5|VALUE6|ONTOLOGY6|VALUE7|ONTOLOGY7|VALUE8|ONTOLOGY8|VALUE9|ONTOLOGY9|VALUE10|ONTOLOGY10|VALUE11|ONTOLOGY11|VALUE12|ONTOLOGY12 \r\n" ;
	
        $stats_disc="GUID|COUNT|VALUE1|ONTOLOGY1|VALUE2|ONTOLOGY2|VALUE3|ONTOLOGY3|VALUE4|ONTOLOGY4|VALUE5|ONTOLOGY5|VALUE6|ONTOLOGY6|VALUE7|ONTOLOGY7|VALUE8|ONTOLOGY8|VALUE9|ONTOLOGY9|VALUE10|ONTOLOGY10 \r\n" ;

        $stats_spec="GUID|COUNT|VALUE1|ONTOLOGY1|VALUE2|ONTOLOGY2|VALUE3|ONTOLOGY3|VALUE4|ONTOLOGY4|VALUE5|ONTOLOGY5|VALUE6|ONTOLOGY6|VALUE7|ONTOLOGY7|VALUE8|ONTOLOGY8|VALUE9|ONTOLOGY9|VALUE10|ONTOLOGY10 \r\n" ;

        $stats_par = "GUID|COUNT|PARENT1|PARENT2|PARENT3|PARENT4|PARENT5|PARENT6|PARENT7|PARENT8|PARENT9|PARENT10 \r\n";

        $stats_repc = "GUID|GUIDP|REPD|REPC1|REPC2|REPC3|REPC4|REPC5|REPC6|REPC7|REPC8|REPC9|REPC10 \r\n"; 

        $stats_child= "GUID|CHD COUNT|REPC COUNT \r\n";
	
	
	
	$counter=0;
	
        $members = array_reverse($members);
	foreach ($members as $nikolas)
{
  
        $address = $CONFIG->API_URL . "eidsearch?id=http://metamorphosis.med.duth.gr/uid%23" . $nikolas->guid;
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
			if (($mM->getID()!=NULL) &&($mM->getID()!=""))
			{
			$stats_single .=$nikolas->guid."|";
			$stats_single .=$mM->getData("ID")."|";
            $creaguid= $nikolas->creatorg;
            $crea=get_entity($creaguid);
			$stats_single.=$crea->name."|".$crea->Affiliation."|".$mM->getData("title")."|";
			$stats_single.=$mM->getData("language")."|";
			$stats_single.=$mM->getData("metadataLanguage")."|";
            $stats_single.=$mM->getData("created")."|";
			$stats_single.= ($mM->getData("citation") != "") ? "YES"."|" : "|";
			$stats_single.= ($mM->getData("description") != "") ? "YES"."|" : "|";
			$stats_single.= ($mM->getData("technicalDescription") != "") ? "YES"."|" : "|";
			$stats_single.= ($mM->getData("educationalContext") != "") ? "YES"."|" : "|";
			$stats_single.= ($mM->getData("teachingLearningInstructions") != "") ? "YES"."|" : "|";
			$stats_single.= ($mM->getData("educationalObjectives") != "") ? "YES"."|" : "|";
			$stats_single.= ($mM->getData("assessmentMethods") != "") ? "YES"."|" : "|";
			$stats_single.= ($mM->getData("educationalPrerequisites") != "") ? "YES"."|" : "|";
                        $stats_single .= "\r\n";
/*			
////////////////////		////////////////////////////////////////////////////////	
			$stats_ident .=$nikolas->guid."|";
			$uris=$mM->getData("identifier");
			if ($uris){
				$stats_ident .=count($uris)."|";
				foreach($uris as $uri)
					$stats_ident .=$uri[description]."|";
			}
			else
				$stats_ident .= "|";
                        
                        $itemsNr = (is_array($uris)) ? count($uris) : 0;
                        for($i = $itemsNr; $i<9; $i++)
                          $stats_ident .= "|";
			$stats_ident .= "\r\n";
/////////////////////////////////////////////////////////////////////////////////////////////			
			
			
			$stats_rights .= $nikolas->guid."|";
			$ipr=$mM->getData("rights");
			$stats_rights .= ($ipr[0]) ? $ipr[0] . "|" : "|";
			$stats_rights .= ($ipr[1]) ? $ipr[1] : "";
			$stats_rights .= "\r\n";
/////////////////////////////////////////////////////////////////////////////////////////////						
			
			
			$stats_qual .= $nikolas->guid."|";
			$qual = $mM->getData("quality");
			if ($qual){
				$stats_qual .= count($qual)."|";
				foreach($qual as $qa)
					$stats_qual .= $qa."|";
			}
			else
                            $stats_qual .= "|";
                        
                        $itemsNr = (is_array($qual)) ? count($qual) : 0;
                        for($i = $itemsNr; $i<2; $i++)
                          $stats_qual .= "|";
			$stats_qual .= "\r\n";
			
///////////////////////////////////////////////////////////////////////////////////////////////////

				$stats_auth .= $nikolas->guid."|";
				$auth=$mM->getData("creator");
				if($auth)
				{
					$stats_auth .= count($auth);
					foreach( $auth as $au)
							$stats_auth .= "|" . $au[name]."|".$au[memberOf]."|".$au[profileURI];	


				
				}
                                
                    for($i = count($auth); $i<5; $i++)
                          $stats_auth .= "|||";
			$stats_auth .= "\r\n";


/////////////////////////////////////////////////////////////////////////////////////////////			
                    $stats_media .= $nikolas->guid . "|";
                    $mTs = $mM->getData("mediaType");
                    if ($mTs) 
                    {
                      $stats_media .=count($mTs) . "|";
                      foreach ($mTs as $mT)
                        $stats_media .= $mT . "|";
                    }
                    else
                      $stats_media .= "|";

                    $itemsNr = (is_array($mTs)) ? count($mTs) : 0;
                    for ($i = $itemsNr; $i < 9; $i++)
                      $stats_media .= "|";
                    
                    $stats_media .= "\r\n";
			
			
/////////////////////////////////////////////////////////////////////////////////////////////			
                    $stats_res .= $nikolas->guid . "|";
                    $rTs = $mM->getData("resourceType");
                    if ($rTs) 
                    {
                      $stats_res .=count($rTs) . "|";
                      foreach ($rTs as $rT)
                        $stats_res .= $rT . "|";
                    }
                    else
                      $stats_res .= "|";
                    
                    $itemsNr = (is_array($rTs)) ? count($rTs) : 0;
                    for ($i = $itemsNr; $i < 9; $i++)
                      $stats_res .= "|";
                    
                    $stats_res .= "\r\n";
                    
                    
/////////////////////////////////////////////////////////////////////////////////////////////			
                    $stats_edulev .= $nikolas->guid . "|";
                    $eduLs = $mM->getData("educationalLevel");
                    $itemsNr = 0;
                    if ($eduLs) 
                    {
                      if(is_array($eduLs))
                      {
                        $stats_edulev .= count($eduLs) . "|";
                        $itemsNr = count($eduLs);
                        foreach ($eduLs as $eduL)
                          $stats_edulev .= $eduL . "|";
                      }
                      else
                      {
                        $itemsNr = 1;
                        $stats_edulev .= "1|".$eduLs."|";
                      }
                    }
                    else
                      $stats_edulev .= "|";

                    for ($i = $itemsNr; $i < 9; $i++)
                      $stats_edulev .= "|";
                    
                    $stats_edulev .= "\r\n";
                    
                    
/////////////////////////////////////////////////////////////////////////////////////////////	
                    $stats_compan .= $nikolas->guid . "|";
                    $comps = $mM->getData("isAccompaniedBy");
                    if ($comps) 
                    {
                      $stats_compan .=count($comps) . "|";
                      foreach ($comps as $comp)
                        $stats_compan .= $comp . "|";
                    }
                    else
                      $stats_compan .= "|";

                    $itemsNr = (is_array($comps)) ? count($comps) : 0;
                    for ($i = $itemsNr; $i < 9; $i++)
                      $stats_compan .= "|";
                    
                    $stats_compan .= "\r\n";
                    
                    
/////////////////////////////////////////////////////////////////////////////////////////////	
                    $stats_key .= $nikolas->guid . "|";
                    $subs = $mM->getData("subject");
                    $aux_stats_key = "";
                    $aux_stats_counter = 0;
                    if ($subs) {
                      foreach ($subs as $sub)
                      {
                        if(is_array($sub))
                        {
                          $doru = (strpos($sub["label"], "#")) ? "" : $sub["label"];
                          $aux_stats_key .= "|" . $doru . "|" . $sub["externalSource"];
                        }
                        else
                        {
                          $doru = (strpos($sub, "#")) ? "" : $sub;
                          $aux_stats_key .= "|" . $doru . "|";
                        }
                        if($doru != "") $aux_stats_counter++;
                      }
                    }
                    $stats_key .= $aux_stats_counter . $aux_stats_key;

                    $itemsNr = $aux_stats_counter;
                    for ($i = $itemsNr; $i < 12; $i++)
                      $stats_key .= "||";
                    $stats_key .= "\r\n";
                    
                    
/////////////////////////////////////////////////////////////////////////////////////////////	
                    $stats_disc .= $nikolas->guid . "|";
                    $discs = $mM->getData("discipline");
                    if ($discs) {
                      $stats_disc .= count($discs);
                      foreach ($discs as $disc)
                        if(is_array($disc))
                          $stats_disc .= "|" . $disc["label"] . "|" . $disc["externalSource"];
                        else
                          $stats_disc .= "|" . $disc . "|";
                    }

                    $itemsNr = (is_array($discs)) ? count($discs) : 0;
                    for ($i = $itemsNr; $i < 10; $i++)
                      $stats_disc .= "||";
                    $stats_disc .= "\r\n";
                    
/////////////////////////////////////////////////////////////////////////////////////////////

                    $stats_outc .= $nikolas->guid . "|";
                    $mouts = $mM->getData("educationalOutcomes");
                    if ($mTs) 
                    {
                      $stats_outc .=count($mouts) . "|";
                      foreach ($mouts as $mout)
                        $stats_outc .= $mout . "|";
                    }
                    else
                      $stats_outc .= "|";

                    $itemsNr = (is_array($mouts)) ? count($mouts) : 0;
                    for ($i = $itemsNr; $i < 9; $i++)
                      $stats_outc .= "|";
                    
                    $stats_outc .= "\r\n";
			











                    
/////////////////////////////////////////////////////////////////////////////////////////////	
                    $stats_spec .= $nikolas->guid . "|";
                    $specs = $mM->getData("disciplineSpeciality");
                    if ($specs) {
                      $stats_spec .= count($specs);
                      foreach ($specs as $spec)
                        if(is_array($spec))
                          $stats_spec .= "|" . $spec["label"] . "|" . $spec["externalSource"];
                        else
                          $stats_spec .= "|" . $spec . "|";
                    }

                    $itemsNr = (is_array($specs)) ? count($specs) : 0;
                    for ($i = $itemsNr; $i < 10; $i++)
                      $stats_spec .= "||";
                    $stats_spec .= "\r\n";
                  */  
                  
/////////////////////////////////////////////////////////////////////////////////////////////
//$stats_par = "GUID|COUNT|PARENT1|PARENT2|PARENT3|PARENT4|PARENT5|PARENT6|PARENT7|PARENT8|PARENT9|PARENT10";
                    $stats_par .= $nikolas->guid . "|";
                    
                    $repC = $mM->getData("hasRepurposingContext");
                    if ($repC) 
                    {
                      $stats_par .= count($repC) . "|";
                      foreach ($repC as $rep)
                      {
                        $auxKeys = array_keys($rep["repurposedFrom"]);
                        $auxKeys = $auxKeys[0];
                        $aux_gUID = explode("#", $rep["repurposedFrom"][$auxKeys]["seeAlso"]);
                        if(count($aux_gUID) == 2) $aux_gUID = $aux_gUID[1];
                        else $aux_gUID = "";
                        $stats_par .= $aux_gUID . "|";
                      }
                    }
                    else
                      $stats_par .= "|";

                    $itemsNr = (is_array($repC)) ? count($repC) : 0;
                    for ($i = $itemsNr; $i < 9; $i++)
                      $stats_par .= "|";
                    $stats_par .= "\r\n";
                    
                    
/////////////////////////////////////////////////////////////////////////////////////////////	
//$stats_repc = "GUID|GUIDP|REPD|REPC1|REPC2|REPC3|REPC4|REPC5|REPC6|REPC7|REPC8|REPC9 \r\n"; 
                    $repC = $mM->getData("hasRepurposingContext");
                    if ($repC) 
                    {
                      foreach ($repC as $rep)
                      {
                        $auxKeys = array_keys($rep["repurposedFrom"]);
                        $auxKeys = $auxKeys[0];
                        $aux_gUID = explode("#", $rep["repurposedFrom"][$auxKeys]["seeAlso"]);
                        if(count($aux_gUID) == 2) $aux_gUID = $aux_gUID[1];
                        else $aux_gUID = "";
                        
                        $stats_repc .= $nikolas->guid . "|";
                        $stats_repc .= $aux_gUID . "|";
                        $stats_repc .= ($rep["repurposingDescription"]) ? "YES|" : "|";
                        $itemsNr = 0;
                        if($rep["fromRepurposingContext"])
                        {
                          if(is_array($rep["fromRepurposingContext"]))
                          {
                            $itemsNr = count($rep["fromRepurposingContext"]);
                            foreach($rep["fromRepurposingContext"] as $singelRepC)
                            {
                              $singelRepC = explode("#", $singelRepC);
                              if(count($singelRepC) > 1) $singelRepC = str_replace("-", " ", $singelRepC[1]);
                              else $singelRepC = str_replace("-", " ", $singelRepC[0]);
                              $stats_repc .= $singelRepC . "|";
                            }
                          }  
                          else
                          {
                            $itemsNr = 1;
                            $singelRepC = explode("#", $rep["fromRepurposingContext"]);
                            if(count($singelRepC) > 1) $singelRepC = str_replace("-", " ", $singelRepC[1]);
                            else $singelRepC = str_replace("-", " ", $singelRepC[0]);
                            $stats_repc .= $singelRepC . "|";
                          }
                        }
                        
                        for ($i = $itemsNr; $i < 9; $i++)
                          $stats_repc .= "|";
                        
                        $stats_repc .= "\r\n";
                      }
                    }
                    else
                    {
                       $stats_repc .= $nikolas->guid . "|";
                       for ($i = 0; $i < 11; $i++)
                          $stats_repc .= "|";
                       $stats_repc .= "\r\n";
                    }
                    
                    
/////////////////////////////////////////////////////////////////////////////////////////////	                    
                 
                    
			$counter++;
			}
			if ($counter > 50000)
				break;
			

			
			
}	
/*

	echo $stats_single . "\r\n\r\n\r\n";
	echo $stats_ident . "\r\n\r\n\r\n";
	echo $stats_rights . "\r\n\r\n\r\n";
      echo $stats_qual . "\r\n\r\n\r\n";
      echo $stats_auth . "\r\n\r\n\r\n";	
      echo $stats_media . "\r\n\r\n\r\n";
      echo $stats_res . "\r\n\r\n\r\n";
      echo $stats_edulev . "\r\n\r\n\r\n";
      echo $stats_compan . "\r\n\r\n\r\n";
      echo $stats_key . "\r\n\r\n\r\n";
      echo $stats_disc . "\r\n\r\n\r\n";
      echo $stats_spec . "\r\n\r\n\r\n";
      echo $stats_par . "\r\n\r\n\r\n";
      echo $stats_repc . "\r\n\r\n\r\n";
	
	*/

	$f1="singlestats".date("dmY").".txt";
	$fp1=fopen($f1, 'w') or die("can't open file");
	fwrite($fp1,$stats_single);
	fclose($fp1);
	
	$f2="identifierstats".date("dmY").".txt";
	$fp2=fopen($f2, 'w') or die("can't open file");
	fwrite($fp2,$stats_ident);
	fclose($fp2);
	
	
	$f3="IPRstats".date("dmY").".txt";
	$fp3=fopen($f3, 'w') or die("can't open file");
	fwrite($fp3,$stats_rights);
	fclose($fp3);
	
	
	$f4="qualitystats".date("dmY").".txt";
	$fp4=fopen($f4, 'w') or die("can't open file");
	fwrite($fp4,$stats_qual);
	fclose($fp4);
	
	$f5="authorstats".date("dmY").".txt";
	$fp5=fopen($f5, 'w') or die("can't open file");
	fwrite($fp5,$stats_auth);
	fclose($fp5);
	
	$f6="mediastats".date("dmY").".txt";
	$fp6=fopen($f6, 'w') or die("can't open file");
	fwrite($fp6,$stats_media);
	fclose($fp6);
	
	$f7="restypestats".date("dmY").".txt";
	$fp7=fopen($f7, 'w') or die("can't open file");
	fwrite($fp7,$stats_res);
	fclose($fp7);
	
	$f8="edulevelstats".date("dmY").".txt";
	$fp8=fopen($f8, 'w') or die("can't open file");
	fwrite($fp8,$stats_edulev);
	fclose($fp8);
	
	$f9="companionstats".date("dmY").".txt";
	$fp9=fopen($f9, 'w') or die("can't open file");
	fwrite($fp9,$stats_compan);
	fclose($fp9);
	
	$f10="keywordstats".date("dmY").".txt";
	$fp10=fopen($f10, 'w') or die("can't open file");
	fwrite($fp10,$stats_key);
	fclose($fp10);
	
	$f11="disciplinestats".date("dmY").".txt";
	$fp11=fopen($f11, 'w') or die("can't open file");
	fwrite($fp11,$stats_disc);
	fclose($fp11);
	
	$f12="specialitystats".date("dmY").".txt";
	$fp12=fopen($f12, 'w') or die("can't open file");
	fwrite($fp12,$stats_spec);
	fclose($fp12);
	
	$f13="parentstats".date("dmY").".txt";
	$fp13=fopen($f13, 'w') or die("can't open file");
	fwrite($fp13,$stats_par);
	fclose($fp13);
	
	$f14="repcontextstats".date("dmY").".txt";
	$fp14=fopen($f14, 'w') or die("can't open file");
	fwrite($fp14,$stats_repc);
	fclose($fp14);
	
	$f15="eduoutcomesstats".date("dmY").".txt";
	$fp15=fopen($f15, 'w') or die("can't open file");
	fwrite($fp15,$stats_outc);
	fclose($fp15);
	
	
	    // layout the page
//	 $body =elgg_view_layout('one_column', $area2);
 	
    // draw the page
//    page_draw($title, $body);
//	fclose($ourFileHandle);
	
	
?>