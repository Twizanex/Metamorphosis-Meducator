<?php
    // Load Elgg engine
    include_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
 
    // make sure only logged in users can see this page	
    gatekeeper();
 
    // set the title
    $title = "CREATE AND DISPLAY XML  (((((TEST VERSION)))))";
 
    // start building the main column of the page
    $area2 = elgg_view_title($title);
  
    // Add the form to this section
	
	global $CONFIG;


	
	$nik=$_GET['id'];
	
	$ourFileName = "metadata".$nik.".xml";		
	$ourFileHandle = fopen($ourFileName, 'w') or die("can't open file");
	
	$entity=get_entity($nik);
	$friends = $entity->getFriends("", $num=30, $offset = 0);
	
	
	
	$nik1 ='meducator3';
	$metadata=get_metadata_byname($nik,$nik1);
	$area2 .="<br \>";$area2 .="<br \>";
	$area2 .="<div class=\"filerepo_download\"><p><a href=\"";
	$area2 .=$vars['url'];
	$area2 .=$ourFileName;
	$area2 .="\">";
	$area2 .=elgg_echo("file:download");
	$area2 .="</a></p></div>";	

	

	fwrite($ourFileHandle,"<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n");
	fwrite($ourFileHandle,"<Educational Resource>\r\n");
	$area2 .="<b>";
	$area2 .="meducator3";
	$area2 .=":";
	$area2 .="</b>";
	$area2 .=$metadata->value;
	$area2 .="<br />";
	$area2 .="<br />";
	

	fwrite($ourFileHandle,"<".$metadata->name.">");
	fwrite($ourFileHandle,$metadata->value);
	fwrite($ourFileHandle,"</".$metadata->name.">\r\n");

	
	$area2 .=$vars['user']->username;
	$nik1 ='meducator20';
	$metadata=get_metadata_byname($nik,$nik1);
	$area2 .="<b>";
	$area2 .=$metadata->name;
	$area2 .=":";
	$area2 .="</b>";
	$area2 .=$metadata->value;
	$area2 .="<br />";
	$area2 .="<br />";
	fwrite($ourFileHandle,"<".$metadata->name.">");
	fwrite($ourFileHandle,$metadata->value);
	fwrite($ourFileHandle,"</".$metadata->name.">\r\n");
	
	$nik1 ='meducator1';
	$metadata=get_metadata_byname($nik,$nik1);
	$area2 .="<b>";
	$area2 .=$metadata->name;
	$area2 .=":";
	$area2 .="</b>";
	$area2 .=$metadata->value;
	$area2 .="<br />";
	$area2 .="<br />";
	fwrite($ourFileHandle,"<".$metadata->name.">");
	fwrite($ourFileHandle,$metadata->value);
	fwrite($ourFileHandle,"</".$metadata->name.">\r\n");	
	
	$nik1 ='meducator21';
	$metadata=get_metadata_byname($nik,$nik1);
	$area2 .="<b>";
	$area2 .=$metadata->name;
	$area2 .=":";
	$area2 .="</b>";
	$area2 .=$metadata->value;
	$area2 .="<br />";
	$area2 .="<br />";
	fwrite($ourFileHandle,"<".$metadata->name.">");
	fwrite($ourFileHandle,$metadata->value);
	fwrite($ourFileHandle,"</".$metadata->name.">\r\n");	
	
	$nik1 ='meducator4';
	$metadata=get_metadata_byname($nik,$nik1);
	$area2 .="<b>";
	$area2 .=$metadata->name;
	$area2 .=":";
	$area2 .="</b>";
	$area2 .=$metadata->value;
	$area2 .="<br />";
	$area2 .="<br />";
	fwrite($ourFileHandle,"<".$metadata->name.">");
	fwrite($ourFileHandle,$metadata->value);
	fwrite($ourFileHandle,"</".$metadata->name.">\r\n");	
	
	$nik1 ='meducator5';
	$metadata=get_metadata_byname($nik,$nik1);
	$area2 .="<b>";
	$area2 .=$metadata->name;
	$area2 .=":";
	$area2 .="</b>";
	$area2 .=$metadata->value;
	$area2 .="<br />";
	$area2 .="<br />";
	fwrite($ourFileHandle,"<".$metadata->name.">");
	fwrite($ourFileHandle,$metadata->value);
	fwrite($ourFileHandle,"</".$metadata->name.">\r\n");	

	$nik1 ='meducator6a';
	$metadata=get_metadata_byname($nik,$nik1);
	$area2 .="<b>";
	$area2 .="meducator6a";
	$area2 .=":";
	$area2 .="</b>";
	$test=$entity->meducator6a;
	if (is_Array($test))
	{	
		$test1= implode(',',$test);
		echo $test1;
	}
	else
	{
	$area2 .=$metadata->value;
	$area2 .="<br />";
	$area2 .="<br />";
	fwrite($ourFileHandle,"<".$metadata->name.">");
	fwrite($ourFileHandle,$metadata->value);	
	}
	
	fwrite($ourFileHandle,"</meducator6a>\r\n");

	
	
	
	$nik1 ='meducator6';
	$metadata=get_metadata_byname($nik,$nik1);
	$area2 .="<b>";
	$area2 .=$metadata->name;
	$area2 .=":";
	$area2 .="</b>";
	$area2 .=$metadata->value;
	$area2 .="<br />";
	$area2 .="<br />";
	fwrite($ourFileHandle,"<".$metadata->name.">");
	fwrite($ourFileHandle,$metadata->value);
	fwrite($ourFileHandle,"</".$metadata->name.">\r\n");
	
	$nik1 ='meducator7';
	$metadata=get_metadata_byname($nik,$nik1);
	$area2 .="<b>";
	$area2 .=$metadata->name;
	$area2 .=":";
	$area2 .="</b>";
	$area2 .=$metadata->value;
	$area2 .="<br />";
	$area2 .="<br />";
	fwrite($ourFileHandle,"<".$metadata->name.">");
	fwrite($ourFileHandle,$metadata->value);
	fwrite($ourFileHandle,"</".$metadata->name.">\r\n");	

	$nik1 ='meducator9';
	$metadata=get_metadata_byname($nik,$nik1);
	$area2 .="<b>";
	$area2 .=$metadata->name;
	$area2 .=":";
	$area2 .="</b>";
	$area2 .=$metadata->value;
	$area2 .="<br />";
	$area2 .="<br />";
	fwrite($ourFileHandle,"<".$metadata->name.">");
	fwrite($ourFileHandle,$metadata->value);
	fwrite($ourFileHandle,"</".$metadata->name.">\r\n");


	$nik1 ='meducator18b';
	$metadata=get_metadata_byname($nik,$nik1);
	$area2 .="<b>";
	$area2 .=$metadata->name;
	$area2 .=":";
	$area2 .="</b>";
	$area2 .=$metadata->value;
	$area2 .="<br />";
	$area2 .="<br />";
	fwrite($ourFileHandle,"<".$metadata->name.">");
	fwrite($ourFileHandle,$metadata->value);
	fwrite($ourFileHandle,"</".$metadata->name.">\r\n");

	$nik1 ='meducator18a';
	$metadata=get_metadata_byname($nik,$nik1);
	$area2 .="<b>";
	$area2 .=$metadata->name;
	$area2 .=":";
	$area2 .="</b>";
	$area2 .=$metadata->value;
	$area2 .="<br />";
	$area2 .="<br />";
	fwrite($ourFileHandle,"<".$metadata->name.">");
	fwrite($ourFileHandle,$metadata->value);
	fwrite($ourFileHandle,"</".$metadata->name.">\r\n");
	
/////////////////////////////////////////////////////////////////////////////////////////////////////
	$nik1 ='meducator25';
	$metadata=get_metadata_byname($nik,$nik1);
	$area2 .="<b>";
	$area2 .=$metadata->name;
	$area2 .=":";
	$area2 .="</b>";
	$area2 .=$metadata->value;
	$area2 .="<br />";
	$area2 .="<br />"; 
	if ($metadata->value!=NULL)
				$area2 .=",";
	if (is_array($friends) && sizeof($friends) > 0) {


		foreach($friends as $friend) {
				$area2 .="http://metamorphosis.med.duth.gr/pg/profile/";
				$area2 .= $friend->username;
				$area2 .=",";
		}
	
	} 
	$area2 .="<br />";
	$area2 .="<br />";
	fwrite($ourFileHandle,"<".$metadata->name.">");
	fwrite($ourFileHandle,$metadata->value);
	if ($metadata->value!=NULL)
			fwrite($ourFileHandle,",");
	if (is_array($friends) && sizeof($friends) > 0) {


		foreach($friends as $friend) {
			fwrite($ourFileHandle,"http://metamorphosis.med.duth.gr/pg/profile/");
			fwrite($ourFileHandle,$friend->username);
			fwrite($ourFileHandle,",");
		}
	
	} 
	
	
	
	fwrite($ourFileHandle,"</".$metadata->name.">\r\n");
//////////////////////////////////////////////////////////////////////////////////////////////////////////
	$entity=get_entity($nik);
	$nik1 ='meducator26';
	$metadata=get_metadata_byname($nik,$nik1);
	$area2 .="<b>";
	$area2 .="meducator26";
	$area2 .=":";
	$area2 .="</b>";
	$test=$entity->meducator26;
	if (is_Array($test))
	{	
		foreach($test as $t)
		{		$area2 .=$t;
				$area2 .=",";
		}
		$area2 .="<br />";
		$area2 .="<br />";
		fwrite($ourFileHandle,"<meducator26>");
			foreach($test as $t)
		{		fwrite($ourFileHandle,$t);
				fwrite($ourFileHandle,",");
		}
	
	}
	else
	{
	$area2 .=$metadata->value;
	$area2 .="<br />";
	$area2 .="<br />";
	fwrite($ourFileHandle,"<".$metadata->name.">");
	fwrite($ourFileHandle,$metadata->value);	
	}
	fwrite($ourFileHandle,"</meducator26>\r\n");
	

	$nik1 ='meducator27';
	$metadata=get_metadata_byname($nik,$nik1);
	$area2 .="<b>";
	$area2 .=$metadata->name;
	$area2 .=":";
	$area2 .="</b>";
	$area2 .=$metadata->value;
	$area2 .="<br />";
	$area2 .="<br />";
	fwrite($ourFileHandle,"<".$metadata->name.">");
	fwrite($ourFileHandle,$metadata->value);
	fwrite($ourFileHandle,"</".$metadata->name.">\r\n");


	$nik1 ='meducator22';
	$metadata=get_metadata_byname($nik,$nik1);
	$area2 .="<b>";
	$area2 .=$metadata->name;
	$area2 .=":";
	$area2 .="</b>";
	$area2 .=$metadata->value;
	$area2 .="<br />";
	$area2 .="<br />";
	fwrite($ourFileHandle,"<".$metadata->name.">");
	fwrite($ourFileHandle,$metadata->value);
	fwrite($ourFileHandle,"</".$metadata->name.">\r\n");	
	
	
	$nik1 ='meducator2';
	$metadata=get_metadata_byname($nik,$nik1);
	$area2 .="<b>";
	$area2 .=$metadata->name;
	$area2 .=":";
	$area2 .="</b>";
	$area2 .=$metadata->value;
	$area2 .="<br />";
	$area2 .="<br />";
	fwrite($ourFileHandle,"<".$metadata->name.">");
	fwrite($ourFileHandle,$metadata->value);
	fwrite($ourFileHandle,"</".$metadata->name.">\r\n");		


	$nik1 ='meducator8';
	$metadata=get_metadata_byname($nik,$nik1);
	$area2 .="<b>";
	$area2 .=$metadata->name;
	$area2 .=":";
	$area2 .="</b>";
	$area2 .=$metadata->value;
	$area2 .="<br />";
	$area2 .="<br />";
	fwrite($ourFileHandle,"<".$metadata->name.">");
	fwrite($ourFileHandle,$metadata->value);
	fwrite($ourFileHandle,"</".$metadata->name.">\r\n");	
	
	
	
	$nik1 ='meducator10';
	$metadata=get_metadata_byname($nik,$nik1);
	$area2 .="<b>";
	$area2 .=$metadata->name;
	$area2 .=":";
	$area2 .="</b>";
	$area2 .=$metadata->value;
	$area2 .="<br />";
	$area2 .="<br />";
	fwrite($ourFileHandle,"<".$metadata->name.">");
	fwrite($ourFileHandle,$metadata->value);
	fwrite($ourFileHandle,"</".$metadata->name.">\r\n");		


	$nik1 ='meducator11';
	$metadata=get_metadata_byname($nik,$nik1);
	$area2 .="<b>";
	$area2 .=$metadata->name;
	$area2 .=":";
	$area2 .="</b>";
	$area2 .=$metadata->value;
	$area2 .="<br />";
	$area2 .="<br />";
	fwrite($ourFileHandle,"<".$metadata->name.">");
	fwrite($ourFileHandle,$metadata->value);
	fwrite($ourFileHandle,"</".$metadata->name.">\r\n");	


	$nik1 ='meducator12';
	$metadata=get_metadata_byname($nik,$nik1);
	$area2 .="<b>";
	$area2 .=$metadata->name;
	$area2 .=":";
	$area2 .="</b>";
	$area2 .=$metadata->value;
	$area2 .="<br />";
	$area2 .="<br />";
	fwrite($ourFileHandle,"<".$metadata->name.">");
	fwrite($ourFileHandle,$metadata->value);
	fwrite($ourFileHandle,"</".$metadata->name.">\r\n");	

	$nik1 ='meducator13';
	$metadata=get_metadata_byname($nik,$nik1);
	$area2 .="<b>";
	$area2 .=$metadata->name;
	$area2 .=":";
	$area2 .="</b>";
	$area2 .=$metadata->value;
	$area2 .="<br />";
	$area2 .="<br />";
	fwrite($ourFileHandle,"<".$metadata->name.">");
	fwrite($ourFileHandle,$metadata->value);
	fwrite($ourFileHandle,"</".$metadata->name.">\r\n");	

	
	$nik1 ='meducator14';
	$metadata=get_metadata_byname($nik,$nik1);
	$area2 .="<b>";
	$area2 .=$metadata->name;
	$area2 .=":";
	$area2 .="</b>";
	$area2 .=$metadata->value;
	$area2 .="<br />";
	$area2 .="<br />";
	fwrite($ourFileHandle,"<".$metadata->name.">");
	fwrite($ourFileHandle,$metadata->value);
	fwrite($ourFileHandle,"</".$metadata->name.">\r\n");		
	
	
	$nik1 ='meducator15';
	$metadata=get_metadata_byname($nik,$nik1);
	$area2 .="<b>";
	$area2 .=$metadata->name;
	$area2 .=":";
	$area2 .="</b>";
	$area2 .=$metadata->value;
	$area2 .="<br />";
	$area2 .="<br />";
	fwrite($ourFileHandle,"<".$metadata->name.">");
	fwrite($ourFileHandle,$metadata->value);
	fwrite($ourFileHandle,"</".$metadata->name.">\r\n");		
	
	$nik1 ='meducator16';
	$metadata=get_metadata_byname($nik,$nik1);
	$area2 .="<b>";
	$area2 .=$metadata->name;
	$area2 .=":";
	$area2 .="</b>";
	$area2 .=$metadata->value;
	$area2 .="<br />";
	$area2 .="<br />";
	fwrite($ourFileHandle,"<".$metadata->name.">");
	fwrite($ourFileHandle,$metadata->value);
	fwrite($ourFileHandle,"</".$metadata->name.">\r\n");	


	$nik1 ='meducator17';
	$metadata=get_metadata_byname($nik,$nik1);
	$area2 .="<b>";
	$area2 .=$metadata->name;
	$area2 .=":";
	$area2 .="</b>";
	$area2 .=$metadata->value;
	$area2 .="<br />";
	$area2 .="<br />";
	fwrite($ourFileHandle,"<".$metadata->name.">");
	fwrite($ourFileHandle,$metadata->value);
	fwrite($ourFileHandle,"</".$metadata->name.">\r\n");	
	
	
	$nik1 ='meducator19';
	$metadata=get_metadata_byname($nik,$nik1);
	$area2 .="<b>";
	$area2 .=$metadata->name;
	$area2 .=":";
	$area2 .="</b>";
	$area2 .=$metadata->value;
	$area2 .="<br />";
	$area2 .="<br />";
	fwrite($ourFileHandle,"<".$metadata->name.">");
	fwrite($ourFileHandle,$metadata->value);
	fwrite($ourFileHandle,"</".$metadata->name.">\r\n");	

	$nik1 ='meducator24';
	$metadata=get_metadata_byname($nik,$nik1);
	$area2 .="<b>";
	$area2 .=$metadata->name;
	$area2 .=":";
	$area2 .="</b>";
	$area2 .=$metadata->value;
	$area2 .="<br />";
	$area2 .="<br />";
	fwrite($ourFileHandle,"<".$metadata->name.">");
	fwrite($ourFileHandle,$metadata->value);
	fwrite($ourFileHandle,"</".$metadata->name.">\r\n");	
	
	
	
    // layout the page
	 $body =elgg_view_layout('one_column', $area2);
    //$body = elgg_view_layout('one_column', $area2);
 
 	
    // draw the page
    page_draw($title, $body);
	fwrite($ourFileHandle,"</Educational Resource>\r\n");
	fclose($ourFileHandle);

?>