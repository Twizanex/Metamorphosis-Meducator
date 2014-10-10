



<?php
    // Load Elgg engine
    include_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
 
    // make sure only logged in users can see this page	
 //   gatekeeper();
 
    // set the title
    $title = "CREATE AND DISPLAY CSV";
 
    // start building the main column of the page
    $area2 = elgg_view_title($title);
  
    // Add the form to this section
	
	global $CONFIG;
	
	$ourFileName = "litsa.csv";		
	$ourFileHandle = fopen($ourFileName, 'w') or die("can't open file");

	$area2 .="<div class=\"filerepo_download\"><p><a href=\"";
	$area2 .=$vars['url'];
	$area2 .=$ourFileName;
	$area2 .="\">";
	$area2 .=elgg_echo("file:download");
	$area2 .="</a></p></div>";	
	
	
	$members=get_entities_from_metadata('creatorg', 939, 'user', '', '',10000);
	


	fwrite($ourFileHandle,"CREATOR|TITLE|URL|URN|OKKAM|IPR|QUALITY STAMP|RESOURCE LANGUAGE|METADATA LANGUAGE|AUTHOR|DATE|CITATION|KEYWORDS|EDUCATIONAL DESCRIPTION|TECHNICAL DESCRIPTION|RESOURCE TYPE|DISCIPLINE|SPECIALTY|EDUCATIONAL LEVEL|EDUCATIONAL CONTEXT|EDUCATIONAL INSTRUCTIONS|EDUCATIONAL OBJECTIVES|LEARNING OUTCOMES|ASSESSMENT METHODS|EDUCATIONAL PREREQUISITES|REPURPOSED FROM|REPURPOSING CONTEXT|REPURPOSING DESCRIPTION\r");

	foreach ($members as $nikolas)
{	
	$nik=$nikolas->guid;
	$nik1 ='creatorg';
	$metadata=get_metadata_byname($nik,$nik1);
	$creat=get_entity($metadata->value);
	fwrite($ourFileHandle,$creat->name);	
	fwrite($ourFileHandle,"|");





	$nik1 ='meducator3';
	$metadata=get_metadata_byname($nik,$nik1);
	fwrite($ourFileHandle,$metadata->value);
	fwrite($ourFileHandle,"|");

	$nik1 ='meducator1';
	$metadata=get_metadata_byname($nik,$nik1);
	fwrite($ourFileHandle,$metadata->value);
	fwrite($ourFileHandle,"|");
	
		$nik1 ='meducator2';
	$metadata=get_metadata_byname($nik,$nik1);
	fwrite($ourFileHandle,$metadata->value);
	fwrite($ourFileHandle,"|");
	
		$nik1 ='meducator28';
	$metadata=get_metadata_byname($nik,$nik1);
	fwrite($ourFileHandle,$metadata->value);
	fwrite($ourFileHandle,"|");
	
	$nik1 ='meducator18b';
	$metadata=get_metadata_byname($nik,$nik1);
	fwrite($ourFileHandle,$metadata->value);
	$nik1 ='meducator18a';
	$metadata=get_metadata_byname($nik,$nik1);
	fwrite($ourFileHandle,$metadata->value);
	fwrite($ourFileHandle,"|");
	
		$nik1 ='meducator19';
	$metadata=get_metadata_byname($nik,$nik1);
	fwrite($ourFileHandle,$metadata->value);
	fwrite($ourFileHandle,"|");
	
		$nik1 ='meducator5';
	$metadata=get_metadata_byname($nik,$nik1);
	fwrite($ourFileHandle,$metadata->value);
	fwrite($ourFileHandle,"|");
	
		$nik1 ='meducator24';
	$metadata=get_metadata_byname($nik,$nik1);
	fwrite($ourFileHandle,$metadata->value);
	fwrite($ourFileHandle,"|");
	
		$nik1 ='meducator20';
	$metadata=get_metadata_byname($nik,$nik1);
	fwrite($ourFileHandle,$metadata->value);
	fwrite($ourFileHandle,"|");
	
		$nik1 ='meducator21';
	$metadata=get_metadata_byname($nik,$nik1);
	$dat=date('Y-m-d', $metadata->value);	
	fwrite($ourFileHandle,$dat);
	fwrite($ourFileHandle,"|");
	
	$nik1 ='meducator22';
	$metadata=get_metadata_byname($nik,$nik1);
	fwrite($ourFileHandle,$metadata->value);
	fwrite($ourFileHandle,"|");
	
		$nik1 ='meducator4';
	$metadata=get_metadata_byname($nik,$nik1);
	fwrite($ourFileHandle,$metadata->value);
	fwrite($ourFileHandle,"|");
	
		$nik1 ='meducator7';
	$metadata=get_metadata_byname($nik,$nik1);
	fwrite($ourFileHandle,$metadata->value);
	fwrite($ourFileHandle,"|");
	
		$nik1 ='meducator8';
	$metadata=get_metadata_byname($nik,$nik1);
	fwrite($ourFileHandle,$metadata->value);
	fwrite($ourFileHandle,"|");
	
	
	$nik1=$nikolas->meducator6a;
	$nik3=$nikolas->meducator6;
	if (is_array($nik1)&&($nik3!=NULL))
	{
		$nik2=implode(',',$nik1);
		$nik4=$nik2.",";
		$nik5=$nik4.$nik3;
		fwrite($ourFileHandle,$nik5);
		fwrite($ourFileHandle,"|");		
		}
	
	else if (is_array($nik1)&&($nik3==NULL))
		{
		$nik2=implode(',',$nik1);
		fwrite($ourFileHandle,$nik2);
		fwrite($ourFileHandle,"|");		
		}
	else if (!is_array($nik1)&&($nik3!=NULL))
	{
		
		fwrite($ourFileHandle,$nik3);
		fwrite($ourFileHandle,"|");		
	}
	else if (!is_array($nik1)&&($nik3==NULL))
	{
		
		fwrite($ourFileHandle,$nik1);
		fwrite($ourFileHandle,"|");		
	}
	

	$nik1 ='meducator16';
	$metadata=get_metadata_byname($nik,$nik1);
	fwrite($ourFileHandle,$metadata->value);
	fwrite($ourFileHandle,"|");	
	
		$nik1 ='meducator17';
	$metadata=get_metadata_byname($nik,$nik1);
	fwrite($ourFileHandle,$metadata->value);
	fwrite($ourFileHandle,"|");
	
		$nik1 ='meducator15';
	$metadata=get_metadata_byname($nik,$nik1);
	fwrite($ourFileHandle,$metadata->value);
	fwrite($ourFileHandle,"|");
	
		$nik1 ='meducator9';
	$metadata=get_metadata_byname($nik,$nik1);
	$stri= $metadata->value;
	$str = str_replace("\n", "",$stri);
	fwrite($ourFileHandle,$str);
	fwrite($ourFileHandle,"|");
	
	
		$nik1 ='meducator10';
	$metadata=get_metadata_byname($nik,$nik1);
	$stri= $metadata->value;
	$str = str_replace("\n", "",$stri);
	fwrite($ourFileHandle,$str);
	fwrite($ourFileHandle,"|");
	
		$nik1 ='meducator11';
	$metadata=get_metadata_byname($nik,$nik1);
	$stri= $metadata->value;
	$str = str_replace("\n", "",$stri);
	fwrite($ourFileHandle,$str);
	fwrite($ourFileHandle,"|");
	
	
		$nik1 ='meducator12';
	$metadata=get_metadata_byname($nik,$nik1);
	$stri= $metadata->value;
	$str = str_replace("\n", "",$stri);
	fwrite($ourFileHandle,$str);
	fwrite($ourFileHandle,"|");
	
		$nik1 ='meducator13';
	$metadata=get_metadata_byname($nik,$nik1);
	$stri= $metadata->value;
	$str = str_replace("\n", "",$stri);
	fwrite($ourFileHandle,$str);
	fwrite($ourFileHandle,"|");
	
		$nik1 ='meducator14';
	$metadata=get_metadata_byname($nik,$nik1);
	$stri= $metadata->value;
	$str = str_replace("\n", "",$stri);
	fwrite($ourFileHandle,$str);
	fwrite($ourFileHandle,"|");
	
	
	
	
		$friends = $nikolas->getFriends("", $num=30, $offset = 0);
	if (is_array($friends) && sizeof($friends) > 0)
	{
		foreach($friends as $friend)
		{
			fwrite($ourFileHandle,$friend->name);
				fwrite($ourFileHandle,",");
				
		}
	}
	
		$nik1 ='meducator25';
	$metadata=get_metadata_byname($nik,$nik1);
	fwrite($ourFileHandle,$metadata->value);
	fwrite($ourFileHandle,"|");
	
	
	
	$nik1 =$nikolas->meducator26;
	$rep=implode(',',$nik1);
	fwrite($ourFileHandle,$rep);
	fwrite($ourFileHandle,"|");
	
	
		$nik1 ='meducator27';
	$metadata=get_metadata_byname($nik,$nik1);
	$stri= $metadata->value;
	$str = str_replace("\n", "",$stri);
	fwrite($ourFileHandle,$str);

	fwrite($ourFileHandle,"\r\n");
}	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
    // layout the page
	 $body =elgg_view_layout('one_column', $area2);
 	
    // draw the page
    page_draw($title, $body);
	fclose($ourFileHandle);

?>