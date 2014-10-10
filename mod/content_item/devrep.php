<?php

    set_time_limit(360000);

    // Load Elgg engine
    include_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
    include_once(dirname(dirname(__FILE__)) . "/mmsearch/views/default/resources/properties_list.php");
    
    function mapToVocab($string, $properties_values)
    {
      foreach($properties_values as $vocab => $values)
        foreach($values as $key => $text)
          if($string == $key) return substr($vocab, 4);
          else if(strpos(strtolower($key), strtolower($string)) !== FALSE)
                  return substr($vocab, 4);
      return "";
    }
 
    // make sure only logged in users can see this page	
 
    // set the title
    $title = "Create and Download the XML of a resource";
 
    // start building the main column of the page
    $area2 = elgg_view_title($title);
  
    // Add the form to this section
	
	global $CONFIG;
		admin_gatekeeper();
	


	
	$items=get_entities_from_metadata('issimpleuser', 'no', 'user', '', '',10000);
//	$items=array(get_entity(1535)); 
// 	$members=get_entities_from_metadata('creatorg', 939, 'user', '', '',10000);  IF WE WANT ONLY THE RESOURCES OF A PERSON
// $members=get_entities_from_metadata('extparent', 1, 'user', '', '',10000);  IF WE WANT TO DELETE EXTERNAL PARENTS
//$items=get_entities_from_metadata('creatorg', 1299, 'user', '', '',10000);


 $rep_map = array();
		
 
		
	foreach ( $items as $item )

{
    	$nik=$item->guid;
	
	$ourFileName = "metadata".$nik.".rdf";		
	$ourFileHandle = fopen($ourFileName, 'w') or die("can't open file");
	
	$entity=get_entity($nik);
        
        //DEBUG
        /*$test_m = get_metadata_for_entity($item->guid);
          $debugFile = fopen(dirname(dirname(__FILE__)) . "/debug.txt", "a");
          fwrite($debugFile, "\n\n\nPROCESSING THE RESOURCE: ".$item->guid." \n");
          //fwrite($debugFile, "\nSTATUS = " . print_r($rep_map, true));
          fwrite($debugFile, "\nENTITY = " . print_r($test_m, true));
          fclose($debugFile);*/
	//$friends = $entity->getFriends("", $num=30, $offset = 0);
	
	$area2 .="<br \>";$area2 .="<br \>";
	$area2 .="The xml for $entity->name has been created.";
	
	
	$area2 .="<br \>";$area2 .="<br \>";
	$area2 .="<div class=\"filerepo_download\"><p><a href=\"";
	$area2 .=$vars['url'];
	$area2 .=$ourFileName;
	$area2 .="\">";
	$area2 .=elgg_echo("file:download");
	$area2 .="</a></p></div>";	

	
	$head="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n <rdf:RDF xml:base=\"http://purl.org/meducator/instances/\" \n xmlns:mdc=\"http://www.purl.org/meducator/ns/\" \n xmlns:dc=\"http://purl.org/dc/elements/1.1/\" \n xmlns:dcterms=\"http://purl.org/dc/terms/\" \n xmlns:foaf=\"http://xmlns.com/foaf/0.1/\" \n xmlns:owl=\"http://www.w3.org/2002/07/owl#\" \n xmlns:rdf=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\" \n xmlns:rdfs=\"http://www.w3.org/2000/01/rdf-schema#\" \n xmlns:sioc=\"http://rdfs.org/sioc/ns#\" \n xmlns:skos=\"http://www.w3.org/2009/08/skos-reference/skos.rdf\" \n xmlns:xs=\"http://www.w3.org/2001/XMLSchema\" \n xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">\n ";

	fwrite($ourFileHandle,$head);

		
		$nik1 ='meducator1';
	$metadata=get_metadata_byname($nik,$nik1);
	if (!isset($rep_map[$entity->guid])||($rep_map[$entity->guid]==NULL)) 
	fwrite($ourFileHandle,"<mdc:Resource rdf:about=\"http://metamorphosis.med.duth.gr/uid#".$nik."\">\n
	 <mdc:identifier><mdc:Identifier><rdfs:description>URI</rdfs:description><rdfs:label><![CDATA[".$metadata->value."]]></rdfs:label></mdc:Identifier></mdc:identifier> \r\n");
	 else
	 {
	 	fwrite($ourFileHandle,"<mdc:Resource rdf:about=\"http://purl.org/meducator/resources/".$rep_map[$entity->guid]."\">\n
	 <mdc:identifier><mdc:Identifier><rdfs:description>URI</rdfs:description><rdfs:label><![CDATA[".$metadata->value."]]></rdfs:label></mdc:Identifier></mdc:identifier> \r\n");
	 fwrite($ourFileHandle,"<rdfs:seeAlso>http://metamorphosis.med.duth.gr/uid#$nik</rdfs:seeAlso>\r\n");
	 }
	 
//	fwrite($ourFileHandle,"	<rdfs:seeAlso>http://http://metamorphosis.med.duth.gr/uid#$nik</rdfs:seeAlso>\n	
//	<mdc:identifier xsi:type=\"dcterms:URL\" rdf:resource=\"$metadata->value\"/>\r\n");

	
	$map['Attribution Non-commercial No Derivatives (by-nc-nd)']='http://purl.org/meducator/licenses#Attribution-Non-Commercial-No-Derivatives';
	$map['Attribution Non-commercial Share Alike (by-nc-sa)']='http://purl.org/meducator/licenses#Attribution-Non-Commercial-Share-Alike';
	$map['Attribution Non-commercial (by-nc)']='http://purl.org/meducator/licenses#Attribution-Non-Commercial';
	$map['Attribution No Derivatives (by-nd)']='http://purl.org/meducator/licenses#Attribution-No-Derivatives';
	$map['Attribution Share Alike (by-sa)']='http://purl.org/meducator/licenses#Attribution-Share-Alike';
	$map['Attribution (by)']='http://purl.org/meducator/licenses#Attribution';
	$map['book']='http://purl.org/meducator/resourceType#handbook';
	$map['lecture presentation']='http://purl.org/meducator/mediaType#multimediaSlidePresentation';
	$map['lecture notes']='http://purl.org/meducator/resourceType#lectureNotes';
	$map['image']='http://purl.org/meducator/mediaType#image';
	$map['video']='http://purl.org/meducator/mediaType#video';
	$map['sound']='http://purl.org/meducator/mediaType#audio';
	$map['diagram']='http://purl.org/meducator/resourceType#figureDiagramPicture';
	$map['figure']='http://purl.org/meducator/resourceType#figureDiagramPicture';
	$map['graph']='http://purl.org/meducator/mediaType#sketchGraphicalAnnotation';
	$map['index']='http://purl.org/meducator/resourceType#indexes';
	$map['table']='http://purl.org/meducator/resourceType#table';
	$map['narrative text']='http://purl.org/meducator/mediaType#text';
	$map['questionnaire']='http://purl.org/meducator/resourceType#problemsExercises';
	$map['exam questions']='http://purl.org/meducator/resourceType#problemsExercises';
	$map['practical']='http://purl.org/meducator/resourceType#practical';
	$map['anatomical atlas']='http://purl.org/meducator/resourceType#atlas';
	$map['teaching file']='http://purl.org/meducator/resourceType#teachingFile';
	$map['virtual patient']='http://purl.org/meducator/resourceType#virtualPatient';
	$map['didactic problem']='http://purl.org/meducator/resourceType#problemsExercises';
	$map['teaching case']='http://purl.org/meducator/resourceType#clinicalCase';
	$map['case study']='http://purl.org/meducator/resourceType#caseStudy';
	$map['scientific paper']='http://purl.org/meducator/resourceType#scientificJournalArticle';
	$map['algorithm']='http://purl.org/meducator/resourceType#diagnosticAlgorithm';
	$map['simulator']='http://purl.org/meducator/resourceType#simulation';
	$map['evidence based medicine form']='http://purl.org/meducator/resourceType#evidenceBasedMedicineForm';
	$map['objective structured clinical examination']='http://purl.org/meducator/resourceType#diagnosticExam';
	$map['clinical guidelines']='http://purl.org/meducator/resourceType#guideline';
	$map['wiki']='http://purl.org/meducator/mediaType#wiki';
	$map['blog']='http://purl.org/meducator/mediaType#blog';
	$map['discussion forum']='http://purl.org/meducator/mediaType#discussionForum';
	$map['serious game']='http://purl.org/meducator/resourceType#gameSeriousGame';
	$map['electronic traces of images']='http://purl.org/meducator/mediaType#image';
	$map['web trace']='http://purl.org/meducator/mediaType#image';
	$map['different language']='http://purl.org/meducator/repurposing#Different-Languages';
	$map['different culture']='http://purl.org/meducator/repurposing#Different-Cultures';
	$map['different pedagogy']='http://purl.org/meducator/repurposing#Different-Pedagogy';
	$map['different educational levels']='http://purl.org/meducator/repurposing#Different-Educational-Level';
	$map['different disciplines or professions']='http://purl.org/meducator/repurposing#Different-Disciplines';
	$map['different content type']='http://purl.org/meducator/repurposing#Different-Content-Types';
	$map['different technology']='http://purl.org/meducator/repurposing#Different-Technology';
	$map['different abilities of end-users']='http://purl.org/meducator/repurposing#Different-Abilities';
	
//	echo $nik.$entity->meducator3;	
	
	
	$nik1 ='meducator3';
	$metadata=get_metadata_byname($nik,$nik1);
	$metadatanik=$entity->meducator3;
        
	if ($metadata){
	fwrite($ourFileHandle,"<mdc:title><![CDATA[");
	fwrite($ourFileHandle,$metadata->value);
	fwrite($ourFileHandle," ]]></mdc:title>\r\n");
	}
	
	$nik1 =$entity->meducator2;
	if ($nik1) 
	fwrite($ourFileHandle,"<mdc:identifier><mdc:Identifier><rdfs:description>URI</rdfs:description><rdfs:label><![CDATA[".$nik1." ]]></rdfs:label></mdc:Identifier></mdc:identifier> \r\n");
	
	
	$nik1 =$entity->meducator28;
	if ($nik1) 
	fwrite($ourFileHandle,"<mdc:identifier><mdc:Identifier><rdfs:description>URI</rdfs:description><rdfs:label><![CDATA[".$nik1." ]]></rdfs:label></mdc:Identifier></mdc:identifier> \r\n");
	
	
		
	
	
	$nik1 =$entity->meducator18b;     /* NA GINEI I ANTISTOIXISI GIA TA KATALLILA NAMESPACES */
	if ($nik1){
	fwrite($ourFileHandle,"<mdc:rights ");
	$ipr="rdf:resource=\"".$map[$nik1]."\"";
	fwrite($ourFileHandle,$ipr." />\r\n");
	}
	
	$nik1 =$entity->meducator18a;
	if ($nik1){
	fwrite($ourFileHandle,"<mdc:rights><![CDATA[ ");
	fwrite($ourFileHandle,$nik1."]]></mdc:rights>\r\n");
	}
	
		$nik1=$entity->meducator19;
	if ($nik1) 	
	fwrite($ourFileHandle,"<mdc:quality><![CDATA[".$nik1." ]]></mdc:quality> \r\n");
		
		
		
	$nik1 ='meducator5';
	$metadata=get_metadata_byname($nik,$nik1);
		if ($metadata->value!=NULL){
	fwrite($ourFileHandle,"<mdc:language>");
	fwrite($ourFileHandle,$metadata->value);
	fwrite($ourFileHandle,"</mdc:language>\r\n");
	}
	
	$nik1 ='meducator24';
	$metadata=get_metadata_byname($nik,$nik1);
	fwrite($ourFileHandle,"<mdc:metadataLanguage>");
	fwrite($ourFileHandle,$metadata->value);
	fwrite($ourFileHandle,"</mdc:metadataLanguage>\r\n");
	
	$nik1 =$entity->meducator20;
	$nik2 =explode(',',$nik1);
        
	
	foreach ($nik2 as $nik22)
	{
	fwrite($ourFileHandle,"<mdc:creator> \r\n <foaf:Person> \r\n <foaf:name><![CDATA[");
	fwrite($ourFileHandle,$nik22);
	fwrite($ourFileHandle,"]]></foaf:name> \r\n </foaf:Person> \r\n  </mdc:creator> \r\n");
	}
	
	$nik1 =get_entity($entity->creatorg);
	fwrite($ourFileHandle,"	<mdc:metadataCreator> <foaf:Person>
			 <foaf:name><![CDATA[$nik1->name]]></foaf:name>
			 <mdc:profileURI>http://metamorphosis.med.duth.gr/pg/profile/$nik1->username?view=foaf </mdc:profileURI>
		   </foaf:Person>
		</mdc:metadataCreator>");
	

	
	
	$nik1 ='meducator21';
	$metadata=get_metadata_byname($nik,$nik1);
	fwrite($ourFileHandle,"<mdc:created>");
	$dat=date('Y-m-d', $metadata->value);
	fwrite($ourFileHandle,$dat);
	fwrite($ourFileHandle,"</mdc:created>\r\n");
	
	$time=$entity->getTimeCreated();
	$dat=date('Y-m-d', $time);
	fwrite($ourFileHandle,"<mdc:metadataCreated>");
	fwrite($ourFileHandle,$dat);
	fwrite($ourFileHandle,"</mdc:metadataCreated>\r\n");
	
	
	
	$nik1 ='meducator22';
	$metadata=get_metadata_byname($nik,$nik1);
	fwrite($ourFileHandle,"<mdc:citation><![CDATA[");
	fwrite($ourFileHandle,$metadata->value);
	fwrite($ourFileHandle,"]]></mdc:citation>\r\n");	
	
	
	$nik1 ='meducator4';
	$metadata=get_metadata_byname($nik,$nik1);
	$keys=explode(",",$metadata->value);
	foreach ($keys as $key)
	{
fwrite($ourFileHandle,"<mdc:subject><mdc:Subject rdf:about=\"http://meducator.open.ac.uk/metamorphosis#subject".mt_rand()."\"><rdfs:label><![CDATA[".$key." ]]></rdfs:label></mdc:Subject></mdc:subject> \r\n");
	}


	$nik1 ='meducator7';
	$metadata=get_metadata_byname($nik,$nik1);
	fwrite($ourFileHandle,"<mdc:description><![CDATA[");
	$str=str_replace("\r\n"," ",$metadata->value);
	fwrite($ourFileHandle,$str);
	fwrite($ourFileHandle,"]]></mdc:description>\r\n");		
	
	
	$nik1 ='meducator8';
	$metadata=get_metadata_byname($nik,$nik1);
	if ($metadata->value!=NULL){
	fwrite($ourFileHandle,"<mdc:technicalDescription><![CDATA[");
	$str=str_replace("\r\n"," ",$metadata->value);
	fwrite($ourFileHandle,$str);
	fwrite($ourFileHandle,"]]></mdc:technicalDescription>\r\n");		
	}
	

	
	$nik1=$entity->meducator6a;
	$nik3=$entity->meducator6;
	if (is_array($nik1)&&($nik3!=NULL))
	{
		$nik2=implode(',',$nik1);
		$nik4=$nik2.",";
		$nik5=$nik4.$nik3;
		$nik6=explode(',',$nik5);
		foreach($nik6 as $nik61)
		{
                  if(mapToVocab($map[$nik61], $properties_values) == "mediaType")
                    fwrite($ourFileHandle,"<mdc:mediaType ");
                  else
                    fwrite($ourFileHandle,"<mdc:resourceType ");
                  
                  $type="rdf:resource=\"".$map[$nik61]."\"";
                  fwrite($ourFileHandle,$type);
                  fwrite($ourFileHandle,"/>\r\n");	
		}
	}
	else if (is_array($nik1)&&($nik3==NULL)) {
	
		foreach($nik1 as $nik11)
		{
                  if(mapToVocab($map[$nik11], $properties_values) == "mediaType")
                    fwrite($ourFileHandle,"<mdc:mediaType ");
                  else
                    fwrite($ourFileHandle,"<mdc:resourceType ");
			
		$type="rdf:resource=\"".$map[$nik11]."\"";
		fwrite($ourFileHandle,$type);
		fwrite($ourFileHandle,"/>\r\n");		

		}
		
	}	
	else if (!is_array($nik1)&&($nik3!=NULL))
	{
		fwrite($ourFileHandle,"<mdc:resourceType> ");		
		fwrite($ourFileHandle,$nik3);
		fwrite($ourFileHandle,"</mdc:resourceType>\r\n");		
	}
	else if (!is_array($nik1)&&($nik3==NULL))
	{
          if(mapToVocab($map[$nik1], $properties_values) == "mediaType")
            fwrite($ourFileHandle,"<mdc:mediaType ");
          else
            fwrite($ourFileHandle,"<mdc:resourceType ");
          
		$type="rdf:resource=\"".$map[$nik1]."\"";		
		fwrite($ourFileHandle,$type);
		fwrite($ourFileHandle,"/>\r\n");		
	}
	
	
	$nik1 ='meducator16';
	$metadata=get_metadata_byname($nik,$nik1);
			if ($metadata->value!=NULL){
			fwrite($ourFileHandle,"<mdc:discipline><mdc:Discipline rdf:about=\"http://meducator.open.ac.uk/metamorphosis#discipline".mt_rand()."\"><rdfs:label><![CDATA[".$metadata->value." ]]></rdfs:label></mdc:Discipline></mdc:discipline> \r\n");

	}
	
	$nik1 ='meducator17';
	$metadata=get_metadata_byname($nik,$nik1);
			if ($metadata->value!=NULL){
			fwrite($ourFileHandle,"<mdc:disciplineSpeciality><mdc:DisciplineSpeciality rdf:about=\"http://meducator.open.ac.uk/metamorphosis#speciality".mt_rand()."\"><rdfs:label><![CDATA[".$metadata->value." ]]></rdfs:label></mdc:DisciplineSpeciality></mdc:disciplineSpeciality> \r\n");

		}
	
	
	$nik1 ='meducator15';
	$metadata=get_metadata_byname($nik,$nik1);
			if ($metadata->value!=NULL){
	fwrite($ourFileHandle,"<mdc:educationalLevel>");
		$str=str_replace("\r\n"," ",$metadata->value);
	fwrite($ourFileHandle,$str);
	fwrite($ourFileHandle,"</mdc:educationalLevel>\r\n");	
	}
	
	

	
	
	$nik1 ='meducator9';
	$metadata=get_metadata_byname($nik,$nik1);
			if ($metadata->value!=NULL){
	fwrite($ourFileHandle,"<mdc:educationalContext><![CDATA[");
		$str=str_replace("\r\n"," ",$metadata->value);
	fwrite($ourFileHandle,$str);
	fwrite($ourFileHandle,"]]></mdc:educationalContext>\r\n");	
	}


	$nik1 ='meducator10';
	$metadata=get_metadata_byname($nik,$nik1);
			if ($metadata->value!=NULL){
	fwrite($ourFileHandle,"<mdc:teachingLearningInstructions><![CDATA[");
		$str=str_replace("\r\n"," ",$metadata->value);
	fwrite($ourFileHandle,$str);
	fwrite($ourFileHandle,"]]></mdc:teachingLearningInstructions>\r\n");		
	}
	
	
	$nik1 ='meducator11';
	$metadata=get_metadata_byname($nik,$nik1);
			if ($metadata->value!=NULL){
	fwrite($ourFileHandle,"<mdc:educationalObjectives><![CDATA[");
		$str=str_replace("\r\n"," ",$metadata->value);
	fwrite($ourFileHandle,$str);
	fwrite($ourFileHandle,"]]></mdc:educationalObjectives>\r\n");	
	}
	
	$nik1 ='meducator12';
	$metadata=get_metadata_byname($nik,$nik1);
			if ($metadata->value!=NULL){
	fwrite($ourFileHandle,"<mdc:educationalOutcomes><![CDATA[");
		$str=str_replace("\r\n"," ",$metadata->value);
	fwrite($ourFileHandle,$str);
	fwrite($ourFileHandle,"]]></mdc:educationalOutcomes>\r\n");	
	}


	$nik1 ='meducator13';
	$metadata=get_metadata_byname($nik,$nik1);
			if ($metadata->value!=NULL){
	fwrite($ourFileHandle,"<mdc:assessmentMethods><![CDATA[");
	$str=str_replace("\r\n"," ",$metadata->value);
	fwrite($ourFileHandle,$str);
	fwrite($ourFileHandle,"]]></mdc:assessmentMethods>\r\n");	
	}
	
		
	$nik1 ='meducator14';
	$metadata=get_metadata_byname($nik,$nik1);
			if ($metadata->value!=NULL){
	fwrite($ourFileHandle,"<mdc:educationalPrerequisites><![CDATA[");
		$str=str_replace("\r\n"," ",$metadata->value);
	fwrite($ourFileHandle,$str);
	fwrite($ourFileHandle,"]]></mdc:educationalPrerequisites>\r\n");		
	}
	

	fwrite($ourFileHandle,"</mdc:Resource></rdf:RDF>");
	fclose($ourFileHandle);
	

	$response = file_get_contents($ourFileName);
	
	
	$URL = "http://meducator-dev.open.ac.uk/resourcesrestapi/rest/meducator/auth/";
 
 			$ch = curl_init($URL);
			curl_setopt($ch, CURLOPT_MUTE, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml','Authorization: Basic '.'bWV0YW1vcnBob3NpczptM3RhbTBycGgwc2lz'));
			curl_setopt($ch, CURLOPT_POSTFIELDS, "$response");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

			$output = curl_exec($ch);
			curl_close($ch);
                        
                        $rep_map[$entity->guid] = $output;

						
	$area2 .=$output;  
	//$area2 .= "THIS IS DONE";
      //forward ($vars['url']."mod/content_item/".$ourFileName);
	
    // layout the page
	 $body =elgg_view_layout('one_column', $area2);
    //$body = elgg_view_layout('one_column', $area2);
 
 }	
    // draw the page
    page_draw($title, $body);



?>
