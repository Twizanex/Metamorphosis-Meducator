<?php
    // Load Elgg engine
    include_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
 
    // make sure only logged in users can see this page	
 
    // set the title
    $title = "Create and Download the XML of a resource";
 
    // start building the main column of the page
    $area2 = elgg_view_title($title);
  
    // Add the form to this section
	
	global $CONFIG;


	
	$nik=$_GET['id'];
	$put=$_GET['update'];
	$ourFileName = "metadata".$nik.".rdf";		
	$ourFileHandle = fopen($ourFileName, 'w') or die("can't open file");
	
	$entity=get_entity($nik);
	$friends = $entity->getFriends("", $num=30, $offset = 0);
	
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
	if ($put=="")
	fwrite($ourFileHandle,"<mdc:Resource rdf:about=\"http://metamorphosis.med.duth.gr/uid#".$nik."\">\n\r");
	else
	{	fwrite($ourFileHandle,"<mdc:Resource rdf:about=\"$put\">\n\r");
		fwrite($ourFileHandle,"<rdfs:seeAlso>http://metamorphosis.med.duth.gr/uid#$nik</rdfs:seeAlso>\r\n");
	}
	

			
	
	
	$nik1 ='meducator3';
	
	$metadata=get_metadata_byname($nik,$nik1);
	if ($metadata){
	fwrite($ourFileHandle,"<mdc:title><![CDATA[");
	fwrite($ourFileHandle,$metadata->value);
	fwrite($ourFileHandle," ]]></mdc:title>\r\n");
	}
	
	$nik1 =$entity->meducator2;
	$nik2=$entity->meducator2_type;
	if ($nik1) {
	if (is_array($nik1))
	{
		for($i=0;$i<count($nik1);$i++)
		{
		
			{
			fwrite($ourFileHandle,"<mdc:identifier><mdc:Identifier><rdfs:description>$nik2[$i]</rdfs:description><rdfs:label><![CDATA[".$nik1[$i]." ]]></rdfs:label></mdc:Identifier></mdc:identifier> \r\n");
			}
		
	}	}
	else {
			fwrite($ourFileHandle,"<mdc:identifier><mdc:Identifier><rdfs:description>$nik2</rdfs:description><rdfs:label><![CDATA[".$nik1." ]]></rdfs:label></mdc:Identifier></mdc:identifier> \r\n");
	}
	}
	
	
	
	$nik1 =$entity->meducator18b;     /* NA GINEI I ANTISTOIXISI GIA TA KATALLILA NAMESPACES */
	if ($nik1){
	fwrite($ourFileHandle,"<mdc:rights ");
	$ipr="rdf:resource=\"".$nik1."\"";
	fwrite($ourFileHandle,$ipr." />\r\n");
	}
	
	$nik1 =$entity->meducator18a;
	if ($nik1){
	fwrite($ourFileHandle,"<mdc:rights> ");
	$ipr="rdf:resource=\"".$nik1."\"";
	fwrite($ourFileHandle,$nik1." </mdc:rights>\r\n");
	}
	
	$nik1=$entity->meducator19;
	if ($nik1) {
	if (is_array($nik1))
	{
		foreach ($nik1 as $nik11)
			{
			fwrite($ourFileHandle,"<mdc:quality><![CDATA[".$nik11." ]]></mdc:quality> \r\n");
			}
	}
	else {
			fwrite($ourFileHandle,"<mdc:quality><![CDATA[".$nik1." ]]></mdc:quality> \r\n");
	}
	}
		
	$nik1=$entity->meducator5;
	if ($nik1) {
	if (is_array($nik1))
	{
		foreach ($nik1 as $nik11)
			{
			fwrite($ourFileHandle,"<mdc:language>".$nik11."</mdc:language> \r\n");
			}
	}
	else {
			fwrite($ourFileHandle,"<mdc:language>".$nik1."</mdc:language> \r\n");
	}
	}
	
	$nik1 ='meducator24';
	$metadata=get_metadata_byname($nik,$nik1);
	if ($metadata){
	fwrite($ourFileHandle,"<mdc:metadataLanguage>");
	fwrite($ourFileHandle,$metadata->value);
	fwrite($ourFileHandle,"</mdc:metadataLanguage>\r\n");
	}
	



		$nik1 =$entity->name_meducator20;
	$nik2=$entity->affil_meducator20;
	$nik3=$entity->foaf_meducator20;
	if ($nik1) {
	if (is_array($nik1))
	{
		for($i=0;$i<count($nik1);$i++)
		{
			
		
			{
			fwrite($ourFileHandle,"<mdc:creator><foaf:Person> \r\n");
			fwrite($ourFileHandle,"<foaf:name><![CDATA[$nik1[$i] ]]></foaf:name>\r\n");
			if ($nik2[$i])
			fwrite($ourFileHandle,"<sioc:memberOf><![CDATA[$nik2[$i] ]]></sioc:memberOf>\r\n");
			if ($nik3[$i])
			fwrite($ourFileHandle,"<mdc:profileURI><![CDATA[$nik3[$i] ]]></mdc:profileURI>\r\n");
		fwrite($ourFileHandle,"</foaf:Person></mdc:creator>");

			}
		
	}	}
	else {
			fwrite($ourFileHandle,"<mdc:creator><foaf:Person> \r\n");
			fwrite($ourFileHandle,"<foaf:name><![CDATA[$nik1 ]]></foaf:name> \r\n");
			fwrite($ourFileHandle,"<sioc:memberOf><![CDATA[$nik2 ]]></sioc:memberOf>\r\n");
			fwrite($ourFileHandle,"<mdc:profileURI><![CDATA[$nik3 ]]></mdc:profileURI>\r\n");
			fwrite($ourFileHandle,"</foaf:Person></mdc:creator>");


			
	}
	}
	
	$nik1 =get_entity($entity->creatorg);
	fwrite($ourFileHandle,"	<mdc:metadataCreator> <foaf:Person>
			 <foaf:name><![CDATA[$nik1->name ]]></foaf:name>
			 <mdc:profileURI>http://metamorphosis.med.duth.gr/pg/profile/$nik1->username?view=foaf </mdc:profileURI>
		   </foaf:Person>
		</mdc:metadataCreator>");
	

	
	
	$nik1 ='meducator21';
	$metadata=get_metadata_byname($nik,$nik1);
	if ($metadata) {
	fwrite($ourFileHandle,"<mdc:created>");
	$dat=date('Y-m-d', $metadata->value);
	fwrite($ourFileHandle,$dat);
	fwrite($ourFileHandle,"</mdc:created>\r\n");
	}
	
	$time=$entity->getTimeCreated();
	$dat=date('Y-m-d', $time);
	fwrite($ourFileHandle,"<mdc:metadataCreated>");
	fwrite($ourFileHandle,$dat);
	fwrite($ourFileHandle,"</mdc:metadataCreated>\r\n");
	
	
	
	$nik1 ='meducator22';
	$metadata=get_metadata_byname($nik,$nik1);
	if ($metadata){
	fwrite($ourFileHandle,"<mdc:citation><![CDATA[");
	fwrite($ourFileHandle,$metadata->value);
	fwrite($ourFileHandle," ]]></mdc:citation>\r\n");	
	}
	
	
	$nik1=explode("|",$entity->keywords);
	$nik2=explode("|",$entity->keyontos);
	$nik3=explode("|",$entity->keyuris);
	if ($entity->keywords) 
    {

		for ($i=0;$i<sizeof($nik1);$i++)
		{
//		srand ((double) microtime( )*1000000);
//		$fakeuri="http://meducator.open.ac.uk/ontology/".rand().md5(uniqid(mt_rand(), true));
		if (strpos($nik3[$i], "http://") === false)
		{
				$fakeuri="http://purl.org/meducator/external/".$nik3[$i];

		}
		else
		{
				$fakeuri = str_replace ("http://", "http://purl.org/meducator/external/", $nik3[$i]);

		}
		fwrite($ourFileHandle,"<mdc:subject>");
			fwrite($ourFileHandle,"<mdc:Subject rdf:about=\"$fakeuri\">");
			fwrite($ourFileHandle,"<rdfs:isDefinedBy>$nik3[$i]</rdfs:isDefinedBy>");
				fwrite($ourFileHandle,"<rdfs:label>$nik1[$i]</rdfs:label>");
				fwrite($ourFileHandle,"<mdc:externalSource>$nik2[$i]</mdc:externalSource>");
		fwrite($ourFileHandle,"</mdc:Subject>");		
		fwrite($ourFileHandle,"</mdc:subject>");
	
		}
	}
	
	
	$nik1 ='meducator7';
	$metadata=get_metadata_byname($nik,$nik1);
	if($metadata) {
	fwrite($ourFileHandle,"<mdc:description><![CDATA[");
	fwrite($ourFileHandle,$metadata->value);
	fwrite($ourFileHandle," ]]></mdc:description>\r\n");		
	}
	
	$nik1 ='meducator8';
	$metadata=get_metadata_byname($nik,$nik1);
	if ($metadata->value!=NULL){
	fwrite($ourFileHandle,"<mdc:technicalDescription><![CDATA[");
	fwrite($ourFileHandle,$metadata->value);
	fwrite($ourFileHandle," ]]></mdc:technicalDescription>\r\n");		
	}
	
	$nik1=$entity->meducator6b;
	if ($nik1) {
	if (is_array($nik1))
	{
		foreach ($nik1 as $nik11)
			{
			fwrite($ourFileHandle,"<mdc:mediaType rdf:resource=\"$nik11\" /> \r\n");
			}
	}
	else {
			fwrite($ourFileHandle,"<mdc:mediaType rdf:resource=\"$nik1\" /> \r\n");
	}
	}
	
	
	$nik1 ='meducator6b1';
	$metadata=get_metadata_byname($nik,$nik1);
	if ($metadata->value!=NULL){
	fwrite($ourFileHandle,"<mdc:mediaType><![CDATA[");
	fwrite($ourFileHandle,$metadata->value);
	fwrite($ourFileHandle," ]]></mdc:mediaType>\r\n");	
	}



	$nik1=$entity->meducator6a;
		if ($nik1) {
	if (is_array($nik1))
	{
		foreach ($nik1 as $nik11)
			{
			fwrite($ourFileHandle,"<mdc:resourceType rdf:resource=\"$nik11\" /> \r\n");
			}
	}
	else {
			fwrite($ourFileHandle,"<mdc:resourceType rdf:resource=\"$nik1\" /> \r\n");
	}
	}
	
	$nik1 ='meducator6';
	$metadata=get_metadata_byname($nik,$nik1);
	if ($metadata->value!=NULL){
	fwrite($ourFileHandle,"<mdc:resourceType><![CDATA[");
	fwrite($ourFileHandle,$metadata->value);
	fwrite($ourFileHandle," ]]></mdc:resourceType>\r\n");	
}






	
	$nik1=explode("|",$entity->disciplines);
	$nik2=explode("|",$entity->discontos);
	$nik3=explode("|",$entity->discurisuris);
	if ($entity->disciplines) 
    {

		for ($i=0;$i<sizeof($nik1);$i++)
		{
//		srand ((double) microtime( )*1000000);
//		$fakeuri="http://meducator.open.ac.uk/ontology/".rand().md5(uniqid(mt_rand(), true));
		if (strpos($nik3[$i], "http://") === false)
		{
				$fakeuri="http://purl.org/meducator/external/".$nik3[$i];

		}
		else
		{
				$fakeuri = str_replace ("http://", "http://purl.org/meducator/external/", $nik3[$i]);

		}
		fwrite($ourFileHandle,"<mdc:discipline>");
			fwrite($ourFileHandle,"<mdc:Discipline rdf:about=\"$fakeuri\">");
			fwrite($ourFileHandle,"<rdfs:isDefinedBy>$nik3[$i]</rdfs:isDefinedBy>");
				fwrite($ourFileHandle,"<rdfs:label>$nik1[$i]</rdfs:label>");
				fwrite($ourFileHandle,"<mdc:externalSource>$nik2[$i]</mdc:externalSource>");
		fwrite($ourFileHandle,"</mdc:Discipline>");		
		fwrite($ourFileHandle,"</mdc:discipline>");
	
		}
	}
	
	
	$nik1=explode("|",$entity->specialities);
	$nik2=explode("|",$entity->specontos);
	$nik3=explode("|",$entity->specuris);
	if ($entity->specialities) 
    {

		for ($i=0;$i<sizeof($nik1);$i++)
		{
//		srand ((double) microtime( )*1000000);
//		$fakeuri="http://meducator.open.ac.uk/ontology/".rand().md5(uniqid(mt_rand(), true));
		if (strpos($nik3[$i], "http://") === false)
		{
				$fakeuri="http://purl.org/meducator/external/".$nik3[$i];

		}
		else
		{
				$fakeuri = str_replace ("http://", "http://purl.org/meducator/external/", $nik3[$i]);

		}
		fwrite($ourFileHandle,"<mdc:disciplineSpeciality>");
			fwrite($ourFileHandle,"<mdc:DisciplineSpeciality rdf:about=\"$fakeuri\">");
			fwrite($ourFileHandle,"<rdfs:isDefinedBy>$nik3[$i]</rdfs:isDefinedBy>");
				fwrite($ourFileHandle,"<rdfs:label>$nik1[$i]</rdfs:label>");
				fwrite($ourFileHandle,"<mdc:externalSource>$nik2[$i]</mdc:externalSource>");
		fwrite($ourFileHandle,"</mdc:DisciplineSpeciality>");		
		fwrite($ourFileHandle,"</mdc:disciplineSpeciality>");
	
		}
	}
	
	
	
	$nik1=$entity->meducator15;
	if ($nik1) {
	if (is_array($nik1))
	{
		foreach ($nik1 as $nik11)
			{
			fwrite($ourFileHandle,"<mdc:educationalLevel>".$nik11."</mdc:educationalLevel> \r\n");
			}
	}
	else 
			fwrite($ourFileHandle,"<mdc:educationalLevel>".$nik1."</mdc:educationalLevel> \r\n");

	}
	
		
	
	$nik1 ='meducator9';
	$metadata=get_metadata_byname($nik,$nik1);
			if ($metadata->value!=NULL){
	fwrite($ourFileHandle,"<mdc:educationalContext><![CDATA[ ");
	fwrite($ourFileHandle,$metadata->value);
	fwrite($ourFileHandle," ]]></mdc:educationalContext>\r\n");	
	}


	$nik1 ='meducator10';
	$metadata=get_metadata_byname($nik,$nik1);
			if ($metadata->value!=NULL){
	fwrite($ourFileHandle,"<mdc:teachingLearningInstructions><![CDATA[ ");
	fwrite($ourFileHandle,$metadata->value);
	fwrite($ourFileHandle," ]]></mdc:teachingLearningInstructions>\r\n");		
	}
	
	
	$nik1 ='meducator11';
	$metadata=get_metadata_byname($nik,$nik1);
			if ($metadata->value!=NULL){
	fwrite($ourFileHandle,"<mdc:educationalObjectives><![CDATA[ ");
	fwrite($ourFileHandle,$metadata->value);
	fwrite($ourFileHandle," ]]></mdc:educationalObjectives>\r\n");	
	}
	
	$nik1=$entity->meducator12;
	if ($nik1) {
	if (is_array($nik1))
	{
		foreach ($nik1 as $nik11)
			{
			fwrite($ourFileHandle,"<mdc:educationalOutcomes>".$nik11."</mdc:educationalOutcomes> \r\n");
			}
	}
	else 
			fwrite($ourFileHandle,"<mdc:educationalOutcomes>".$nik1."</mdc:educationalOutcomes> \r\n");

	}


	$nik1 ='meducator13';
	$metadata=get_metadata_byname($nik,$nik1);
			if ($metadata->value!=NULL){
	fwrite($ourFileHandle,"<mdc:assessmentMethods><![CDATA[ ");
	fwrite($ourFileHandle,$metadata->value);
	fwrite($ourFileHandle," ]]></mdc:assessmentMethods>\r\n");	
	}
	
		
	$nik1 ='meducator14';
	$metadata=get_metadata_byname($nik,$nik1);
			if ($metadata->value!=NULL){
	fwrite($ourFileHandle,"<mdc:educationalPrerequisites><![CDATA[ ");
	fwrite($ourFileHandle,$metadata->value);
	fwrite($ourFileHandle," ]]></mdc:educationalPrerequisites>\r\n");		
	}
	
	
	$nik1=$entity->meducator28;
	if ($nik1) {
	if (is_array($nik1))
	{
		foreach ($nik1 as $nik11)
			{
			fwrite($ourFileHandle,"<mdc:isAccompaniedBy><mdc:Resource rdf:about=\"".$nik11."\"/></mdc:isAccompaniedBy> \r\n");
			}
	}
	else 
			fwrite($ourFileHandle,"<mdc:isAccompaniedBy><mdc:Resource rdf:about=\"".$nik1."\"/></mdc:isAccompaniedBy> \r\n");

	}
	
	
	
	
	$nik1=explode("|||",$entity->parentdescs);
	$nik2=explode("|||",$entity->parentits);	
	$nik3=explode("|||",$entity->parentidents);
	$nik4=explode("|||",$entity->parenttypes);
	if ($entity->parentids!="")
	$nik5=explode("|||",$entity->parentids);
	else
	$nik5=NULL;
	$nik6=explode("|||",$entity->parentses);

	

	
	
	
	
	if ($nik5 && is_array($nik5))  
	{
	
	
	for ($i=0; $i < sizeof($nik5) ; $i++ )
{	
		fwrite($ourFileHandle,"<mdc:hasRepurposingContext>");

		
	fwrite($ourFileHandle,"<mdc:RepurposingContext>");
	fwrite($ourFileHandle,"<mdc:repurposedFrom rdf:resource=\"$nik6[$i]\" />");
//	fwrite($ourFileHandle,"<mdc:RepurposingParent rdf:about=\"$fakeuri\">");
//	fwrite($ourFileHandle,"<mdc:title>");
//	fwrite($ourFileHandle,"<![CDATA[$nik2[$i]]]>");
//	fwrite($ourFileHandle,"</mdc:title>\r\n");
//	fwrite($ourFileHandle,"<mdc:identifier><mdc:Identifier><rdfs:description>URI</rdfs:description><rdfs:label>");
//		fwrite($ourFileHandle,"$nik3[$i]");
//	fwrite($ourFileHandle,"</rdfs:label></mdc:Identifier></mdc:identifier>\r\n");
	//SEEALSO TEST
//	fwrite($ourFileHandle,"<rdfs:seeAlso>");
//		fwrite($ourFileHandle,"$nik5[$i]");
//	fwrite($ourFileHandle,"</rdfs:seeAlso>\r\n");
	
//		fwrite($ourFileHandle,"</mdc:RepurposingParent>\r\n");
//		fwrite($ourFileHandle,"</mdc:repurposedFrom>\r\n");
	fwrite($ourFileHandle,"<mdc:repurposingDescription>");
			fwrite($ourFileHandle,"<![CDATA[$nik1[$i]]]>");
	fwrite($ourFileHandle,"</mdc:repurposingDescription>\r\n");
	$cont= explode(";",$nik4[$i]);
	if (is_array ($cont))
	{
		foreach ($cont as $con) 
		{
	fwrite($ourFileHandle,"<mdc:fromRepurposingContext rdf:resource=\"");
	fwrite($ourFileHandle,$con);
	fwrite($ourFileHandle,"\" />\r\n");
		}
	}
	else
	{
	fwrite($ourFileHandle,"<mdc:fromRepurposingContext rdf:resource=\"");
	fwrite($ourFileHandle,$cont);
	fwrite($ourFileHandle,"\" />\r\n");
	}
	fwrite($ourFileHandle,"</mdc:RepurposingContext>");
		fwrite($ourFileHandle,"</mdc:hasRepurposingContext>\r\n");

} 

        //DEBUG
        /*$debugFile = fopen(dirname(dirname(__FILE__)) . "/debug.txt", "a");
        fwrite($debugFile, "\n\n\nCREATE RDF\n");
        fwrite($debugFile, "\nDESCRIPTIONS = " . print_r($nik1, true));
        fwrite($debugFile, "\nTITLES = " . print_r($nik2, true));
        fclose($debugFile);*/
	}

		if ($nik5 && !is_array($nik5))  
	{
				if (strpos($nik3, "http://") === false)
		{
				$fakeuri="http://purl.org/meducator/parents/".$nik3;

		}
		else
		{
		$fakeuri = str_replace ("http://", "http://purl.org/meducator/parents/", $nik3);
		}
	fwrite($ourFileHandle,"<mdc:hasRepurposingContext>");

	fwrite($ourFileHandle,"<mdc:RepurposingContext>");
		fwrite($ourFileHandle,"<mdc:repurposedFrom rdf:resource=\"$nik6\"/>");

//	fwrite($ourFileHandle,"<mdc:RepurposingParent rdf:about=\"$fakeuri\">");
//	fwrite($ourFileHandle,"<mdc:title>");
//	fwrite($ourFileHandle,"<![CDATA[$nik2]]>");
//	fwrite($ourFileHandle,"</mdc:title>\r\n");
//	fwrite($ourFileHandle,"<mdc:identifier><mdc:Identifier><rdfs:description>URI</rdfs:description><rdfs:label>");
//		fwrite($ourFileHandle,"$nik3");
//	fwrite($ourFileHandle,"</rdfs:label></mdc:Identifier></mdc:identifier>\r\n");
	//SEEALSO TEST
//	fwrite($ourFileHandle,"<rdfs:seeAlso>");
//		fwrite($ourFileHandle,"$nik5");
//	fwrite($ourFileHandle,"</rdfs:seeAlso>\r\n");
	
//		fwrite($ourFileHandle,"</mdc:RepurposingParent>\r\n");
//		fwrite($ourFileHandle,"</mdc:repurposedFrom>\r\n");
	fwrite($ourFileHandle,"<mdc:repurposingDescription>");
			fwrite($ourFileHandle,"<![CDATA[$nik1]]>");
	fwrite($ourFileHandle,"</mdc:repurposingDescription>\r\n");
	$cont= explode(";",$nik4);
	if (is_array ($cont))
	{
		foreach ($cont as $con) 
		{
	fwrite($ourFileHandle,"<mdc:fromRepurposingContext rdf:resource=\"");
	fwrite($ourFileHandle,$con);
	fwrite($ourFileHandle," \" />\r\n");
		}
	}
	else
	{
	fwrite($ourFileHandle,"<mdc:fromRepurposingContext rdf:resource=\"");
	fwrite($ourFileHandle,$cont);
	fwrite($ourFileHandle,"\" />\r\n");
	}
	fwrite($ourFileHandle,"</mdc:RepurposingContext>");
	fwrite($ourFileHandle,"</mdc:hasRepurposingContext>\r\n");
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	fwrite($ourFileHandle,"</mdc:Resource></rdf:RDF>");
	fclose($ourFileHandle);
	

	$response = file_get_contents($ourFileName);

        //the output for external parent call
	if(($entity->extparent==1)&&($put == ""))
        {
          $repID = connectToSesame($CONFIG->API_URL,$response,"");
          $ajaxResp = new stdClass();
          $ajaxResp->__elgg_ts = time();
          $ajaxResp->__elgg_token = elgg_view('ajax/securitytoken');
          
          $ajaxResp->data = new stdClass();
          $ajaxResp->data->ID = "http://purl.org/meducator/resources/" . $repID;
          $ajaxResp->data->internalID = $entity->guid;
          $ajaxResp->data->resourceIdentifier = $entity->meducator2;
          $ajaxResp->data->title = $entity->meducator3;
          
          header('Cache-Control: no-cache, must-revalidate');
          header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
          header('Content-type: application/json');
          echo json_encode($ajaxResp);
          
          exit;
        }
	
	if ($put=='')
	$area2 .="INSTANCE ID FROM SESAME= ".connectToSesame($CONFIG->API_URL,$response,"");
	else {
//ADDED BY GIACOMO FAZIO///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once($CONFIG->path . "mod/profile_manager/views/default/profile_manager/members/config.php");
		$changes=array();
		$changes=unserialize(file_get_contents($IOdir."changes"));   //I load the list of the changes since last classification; if the file doesn't exist, it doesn't matter
		if(empty($changes)) {  //if it doesn't exist, create it
			$changes=array();
			$changes["new_indexing_required"]=0;
			$changes["new"]=array();
			$changes["edited"] ["metadata"]=array();
			$changes["edited"] ["uses"]=array();
			$changes["edited"] ["tags"]=array();
			file_put_contents($IOdir."changes",serialize($changes));
			if(PHP_OS=="Linux" && getmyuid()==fileowner($IOdir.'changes')) chmod($IOdir.'changes',0666); //set rw permissions for everybody for this file
		}
		if(!in_array($nik,$changes["new"])) {   //if it is in the list of the new resources (created after last classification), don't put it in the list of the edited resources
			require_once($CONFIG->path."mod/mmsearch/custom/MeducatorParser.php");
			$rdf_old=connectToSesame($CONFIG->API_URL ."eidsearch?id=$nik");
			$medParser = new MeducatorParser($rdf_old, true);
			$val=array_values($medParser->results);
			$old_data=$val[0];
		}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//apply the modifications
		$area2 .="INSTANCE ID FROM SESAME= ".connectToSesame($CONFIG->API_URL,$response,"","YES");
		//forward ($vars['url']."mod/content_item/".$ourFileName);

//ADDED BY GIACOMO FAZIO///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if(!in_array($nik,$changes["new"])) {   //if it is in the list of the new resources (created after last classification), don't put it in the list of the edited resources
			$rdf_new=connectToSesame($CONFIG->API_URL ."eidsearch?id=$nik");
			$medParser = new MeducatorParser($rdf_new, true);
			$val=array_values($medParser->results);
			$new_data=$val[0];
			//var_dump($old_data);
			//var_dump($new_data);
			$metadatas_fields=explode(";",$metadata_fields);
			$tagss_fields=explode(";",$tags_fields);
			$usess_fields=explode(";",$uses_fields);
			if(!in_array($nik,$changes["edited"]["metadata"])) {  //if it is already in the list, no need to continue
				foreach($metadatas_fields as $field) {
					if($old_data[$field]!=$new_data[$field]) {
						$changes["edited"]["metadata"][]=$nik;
						break;
					}
				}
			}
			if(!in_array($nik,$changes["edited"]["tags"])) {  //if it is already in the list, no need to continue
				foreach($tagss_fields as $field) {
					if($old_data[$field]!=$new_data[$field]) {
						$changes["edited"]["tags"][]=$nik;
						break;
					}
				}
			}
			if(!in_array($nik,$changes["edited"]["uses"])) {  //if it is already in the list, no need to continue
				foreach($usess_fields as $field) {
					if($old_data[$field]!=$new_data[$field]) {
						$changes["edited"]["uses"][]=$nik;
						break;
					}
				}
			}
			file_put_contents($IOdir."changes",serialize($changes));
		}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	}	

    // layout the page
	 $body =elgg_view_layout('one_column', $area2);
    //$body = elgg_view_layout('one_column', $area2);
	forward ($vars['url']."pg/profile/".$entity->username);
 	
    // draw the page
	
		
	
    page_draw($title, $body);



?>