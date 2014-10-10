<?php
    // Load Elgg engine
    include_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
 
	echo '<link href="views/default/css.php" rel="stylesheet" type="text/css" />';
	////////////////////////////////////////////////////////////
//////////////////////////map//////////////////////////////
///////////////////////////////////////////////////////////

    require_once('GoogleMapAPI-2.5/GoogleMapAPI.class.php');
	
    $map = new GoogleMapAPI('map');
    // setup database for geocode caching
    $map->setDSN('mysql://elgg@localhost/');
    // enter YOUR Google Map Key
    $map->setAPIKey('ABQIAAAAQNUInQTwgn5VDDyl8bOByxSM5HRgLZx_FTvWflhOcXW1FpA9rhSc6ancYc0NEjrJi8yIkmEMFXzv5Q');
   
    // create some map markers
	
    //$map->addMarkerByAddress("Kozani","Stathis","<b>Stathis</b><a href=\"http://metamorphosis.med.duth.gr/pg/profile/stathis\">Stathis Konstantinidis</a>","" );
	//$map->addMarkerByAddress("GR","Nikolas","<b>Nikolas</b>","" );
	//$map->addMarkerByAddress("Mallorca","S","<b>S</b>","" );
	//$map->addMarkerByAddress("Stuttgardt","Germany","<b>Germany</b>","" );
	

	///// Show repurposed objects for content_item (<-content item id)
	$selected_c_i = $_GET['content_item'];
	
	
	
	/*
	 *		show_history_on_map($selected_c_i, $map);  -> Gia na deiksei ta repurposed antikeimena
	 *		Gia na treksei http://metamorphosis.med.duth.gr/mod/content_item/show_map_content_item.php?content_item=1220
	 *
	 *		show_all_repurposed_content_items_on_map($map);  -> Gia na deiksei ola ta repurposed content items.
	 
	 
	 *
	 */
	 ////////////
	//show_history_on_map($selected_c_i, $map);
	show_all_repurposed_content_items_on_map($map);
	//show_all_content_items_on_map($map);
	
	
	
	function show_history_on_map($s_c_i, $map)
	{
		if($s_c_i!=null)
		{
			// read history
			$my_map_history_file = $s_c_i.".xml";
			//$mapxml_history = simplexml_load_file("gmljava.xml");
			$mapxml_history = simplexml_load_file($my_map_history_file);
			
			$mg1=0.1;
			$mg2=0.1;
			
			$map_ci = "";
			$i=0;
			foreach($mapxml_history->children() as $child_map1)
			{
				foreach($child_map1->children() as $child_map2)
				{
					$childname =$child_map2->getName();
					$map_geocod1 = "";
					$map_geocod2 = "";
					
					if($childname == "edge")
					{
						$c_i_s = (int)$child_map2['source'];
						$c_i_t = (int)$child_map2['target'];
						
						$query_owner = "SELECT creator_guid FROM elgg_content_item_discrimination WHERE guid = '".$c_i_s."'";
						//echo $query_owner."<br/>";
						$result_owner=mysql_query($query_owner);
						$ci_owner = mysql_fetch_row($result_owner);
						//echo "<br/>".$ci_owner[0]."<br/>";
						if ($ci_owner[0]!= "" && (!in_array($c_i_s, $map_ci)) )
						{
							//
							//echo "if 1 ".$c_i_s."<br/>";
							$query_username = "SELECT username FROM elggusers_entity WHERE guid = '".$c_i_s."'";
							$result_username = mysql_query($query_username);
							$ci_name = mysql_fetch_row($result_username);
							//
							
							$entity=get_entity($ci_owner[0]);
							
							
							///$entity->meducator3
							$entity_object = get_entity($c_i_s);
							if($entity_object->meducator3 != "")
							{
								$tag_map_text = "<b><a href=\"http://metamorphosis.med.duth.gr/pg/profile/".$ci_name[0]."\">".$entity_object->meducator3."</a></b><br/>Licence: ".$entity_object->meducator18b."<br/>Language: ".$entity_object->meducator24."<br/>Creators: ".$entity_object->meducator20;
							}
							else
							{
								$tag_map_text = "<b><a href=\"http://metamorphosis.med.duth.gr/pg/profile/".$ci_name[0]."\">".$ci_name[0]."</a></b>";
							}
							
							$map_geocod1 = $map->getGeocode($entity->Location);
							$map_geocod1['lon'] += $mg1;
							$mg1 += 0.15;
							//$map->addMarkerByAddress($entity->Location,(string)$c_i_s,$tag_map_text,"" );
							$map->addMarkerByCoords($map_geocod1['lon'],$map_geocod1['lat'],(string)$c_i_s,$tag_map_text,"");
							$map_ci[$i] = $c_i_s;
							$i++;
							
						}
						else	// Gia na enwsoume ta shmeia twn content items metaksi tous
						{
							//echo "else 1 ".$c_i_s."<br/>";
							$query_username = "SELECT username FROM elggusers_entity WHERE guid = '".$c_i_s."'";
							$result_username = mysql_query($query_username);
							$ci_name = mysql_fetch_row($result_username);
							$entity=get_entity($ci_owner[0]);
							$map_geocod1 = $map->getGeocode($entity->Location);
							$map_geocod1['lon'] += $mg1;
						}
						
						$query_owner = "SELECT creator_guid FROM elgg_content_item_discrimination WHERE guid = '".$c_i_t."'";
						//echo $query_owner."<br/>";
						$result_owner=mysql_query($query_owner);
						$ci_owner = mysql_fetch_row($result_owner);
						//echo "<br/>".$ci_owner[0]."<br/>";
						if ($ci_owner[0]!= "" && (!in_array($c_i_t, $map_ci)) )
						{
							//echo "if 2 ".$c_i_t."<br/>";
							//
							$query_username = "SELECT username FROM elggusers_entity WHERE guid = '".$c_i_t."'";
							$result_username = mysql_query($query_username);
							$ci_name = mysql_fetch_row($result_username);
							$entity=get_entity($ci_owner[0]);
							
							$entity_object = get_entity($c_i_t);
							if($entity_object->meducator3 != "")
							{
								$tag_map_text = "<b><a href=\"http://metamorphosis.med.duth.gr/pg/profile/".$ci_name[0]."\">".$entity_object->meducator3."</a></b><br/>Licence: ".$entity_object->meducator18b."<br/>Language: ".$entity_object->meducator24."<br/>Creators: ".$entity_object->meducator20;
							}
							else
							{
								$tag_map_text = "<b><a href=\"http://metamorphosis.med.duth.gr/pg/profile/".$ci_name[0]."\">".$ci_name[0]."</a></b>";
							}
							
							

							$map_geocod2 = $map->getGeocode($entity->Location);
							$map_geocod2['lat'] += $mg2;
							$mg2 += 0.15;
							//$map->addMarkerByAddress($entity->Location,(string)$c_i_t,$tag_map_text,"" );
							$map->addMarkerByCoords($map_geocod2['lon'],$map_geocod2['lat'],(string)$c_i_t,$tag_map_text,"");
							//$map->addMarkerByAddress("GR","Nikolas","<b>Nikolas</b>","" );
							$map_ci[$i] = $c_i_t;
							$i++;
							
						}
						else	// Gia na enwsoume ta shmeia twn content items metaksi tous
						{
							//echo "else 2 ".$c_i_t." ";
							$query_username = "SELECT username FROM elggusers_entity WHERE guid = '".$c_i_t."'";
							$result_username = mysql_query($query_username);
							$ci_name = mysql_fetch_row($result_username);
							$entity=get_entity($ci_owner[0]);
							$map_geocod2 = $map->getGeocode($entity->Location);
							$map_geocod2['lat'] += $mg2;
							//echo $map_geocod2['lat']."<br/>";
						}
						
						//$map->addMarkerByAddress("GR","Nikolas","<b>Nikolas</b>","" );
						//$map->addMarkerByAddress("DE","Stathis","<b>Stathis</b>","" );
						//$map->addPolyLineByAddress("GR","DE",'',5,30);
						if($map_geocod1 != "" && $map_geocod2 != "" )
						{
							//echo "Grammh ".$c_i_s." --> ".$c_i_t."<br/>";
							$map->addPolyLineByCoords($map_geocod1['lon'],$map_geocod1['lat'],$map_geocod2['lon'],$map_geocod2['lat'],'',5,50);
						}
					}	
				}
			}
		}
	}
	
	function show_all_repurposed_content_items_on_map($map)
	{
		
			// read history
			$mapxml_history = simplexml_load_file("gmljava.xml");
			
			$mg1=0.1;
			$mg2=0.1;
			
			$map_ci = "";
			$i=0;
			foreach($mapxml_history->children() as $child_map1)
			{
				foreach($child_map1->children() as $child_map2)
				{
					$childname =$child_map2->getName();
					$map_geocod1 = "";
					$map_geocod2 = "";
					
					if($childname == "edge")
					{
						$c_i_s = (int)$child_map2['source'];
						$c_i_t = (int)$child_map2['target'];
						
						$query_owner = "SELECT creator_guid FROM elgg_content_item_discrimination WHERE guid = '".$c_i_s."'";
						//echo $query_owner."<br/>";
						$result_owner=mysql_query($query_owner);
						$ci_owner = mysql_fetch_row($result_owner);
						//echo "<br/>".$ci_owner[0]."<br/>";
						if ($ci_owner[0]!= "" && (!in_array($c_i_s, $map_ci)) )
						{
							//
							$query_username = "SELECT username FROM elggusers_entity WHERE guid = '".$c_i_s."'";
							$result_username = mysql_query($query_username);
							$ci_name = mysql_fetch_row($result_username);
							//
							
							$entity=get_entity($ci_owner[0]);
							
							$entity_object = get_entity($c_i_s);
							if($entity_object->meducator3 != "")
							{
								$tag_map_text = "<b><a href=\"http://metamorphosis.med.duth.gr/pg/profile/".$ci_name[0]."\">".$entity_object->meducator3."</a></b><br/>Licence: ".$entity_object->meducator18b."<br/>Language: ".$entity_object->meducator24."<br/>Creators: ".$entity_object->meducator20;
							}
							else
							{
								$tag_map_text = "<b><a href=\"http://metamorphosis.med.duth.gr/pg/profile/".$ci_name[0]."\">".$ci_name[0]."</a></b>";
							}
							
							
							$map_geocod1 = $map->getGeocode($entity->Location);
							$map_geocod1['lon'] += $mg1;
							$mg1 += 0.1;
							//$map->addMarkerByAddress($entity->Location,(string)$c_i_s,$tag_map_text,"" );
							$map->addMarkerByCoords($map_geocod1['lon'],$map_geocod1['lat'],(string)$c_i_s,$tag_map_text,"");
							$map_ci[$i] = $c_i_s;
							$i++;
							
						}
						else	// Gia na enwsoume ta shmeia twn content items metaksi tous
						{
							$query_username = "SELECT username FROM elggusers_entity WHERE guid = '".$c_i_s."'";
							$result_username = mysql_query($query_username);
							$ci_name = mysql_fetch_row($result_username);
							$entity=get_entity($ci_owner[0]);
							$map_geocod1 = $map->getGeocode($entity->Location);
							$map_geocod1['lon'] += $mg1;
						}
						
						$query_owner = "SELECT creator_guid FROM elgg_content_item_discrimination WHERE guid = '".$c_i_t."'";
						//echo $query_owner."<br/>";
						$result_owner=mysql_query($query_owner);
						$ci_owner = mysql_fetch_row($result_owner);
						//echo "<br/>".$ci_owner[0]."<br/>";
						if ($ci_owner[0]!= "" && (!in_array($c_i_t, $map_ci)) )
						{
							//
							$query_username = "SELECT username FROM elggusers_entity WHERE guid = '".$c_i_t."'";
							$result_username = mysql_query($query_username);
							$ci_name = mysql_fetch_row($result_username);
							$entity=get_entity($ci_owner[0]);
							
							$entity_object = get_entity($c_i_t);
							if($entity_object->meducator3 != "")
							{
								$tag_map_text = "<b><a href=\"http://metamorphosis.med.duth.gr/pg/profile/".$ci_name[0]."\">".$entity_object->meducator3."</a></b><br/>Licence: ".$entity_object->meducator18b."<br/>Language: ".$entity_object->meducator24."<br/>Creators: ".$entity_object->meducator20;
							}
							else
							{
								$tag_map_text = "<b><a href=\"http://metamorphosis.med.duth.gr/pg/profile/".$ci_name[0]."\">".$ci_name[0]."</a></b>";
							}
							

							$map_geocod2 = $map->getGeocode($entity->Location);
							$map_geocod2['lat'] += $mg2;
							$mg2 += 0.1;
							//$map->addMarkerByAddress($entity->Location,(string)$c_i_t,$tag_map_text,"" );
							$map->addMarkerByCoords($map_geocod2['lon'],$map_geocod2['lat'],(string)$c_i_t,$tag_map_text,"");
							//$map->addMarkerByAddress("GR","Nikolas","<b>Nikolas</b>","" );
							$map_ci[$i] = $c_i_t;
							$i++;
						}
						else	// Gia na enwsoume ta shmeia twn content items metaksi tous
						{
							$query_username = "SELECT username FROM elggusers_entity WHERE guid = '".$c_i_t."'";
							$result_username = mysql_query($query_username);
							$ci_name = mysql_fetch_row($result_username);
							$entity=get_entity($ci_owner[0]);
							$map_geocod2 = $map->getGeocode($entity->Location);
							$map_geocod2['lat'] += $mg2;
						}
						
						//$map->addMarkerByAddress("GR","Nikolas","<b>Nikolas</b>","" );
						//$map->addMarkerByAddress("DE","Stathis","<b>Stathis</b>","" );
						//$map->addPolyLineByAddress("GR","DE",'',5,30);
						if($map_geocod1 != "" && $map_geocod2 != "")
						{
							$map->addPolyLineByCoords($map_geocod1['lon'],$map_geocod1['lat'],$map_geocod2['lon'],$map_geocod2['lat'],'#FF0000',5,50);
						}
					}	
				}
			}
		
	}
	
	
	function show_all_content_items_on_map($map)
	{
		
			// read history
			$mapxml_history = simplexml_load_file("gmljava.xml");
			
			$mg1=0.3;
			$mg2=0.3;
			
			$map_ci = "";
			$i=0;
			foreach($mapxml_history->children() as $child_map1)
			{
				foreach($child_map1->children() as $child_map2)
				{
					$childname =$child_map2->getName();
					$map_geocod1 = "";
					$map_geocod2 = "";
					
					if($childname == "node")
					{
						
						$c_i_id = (int)$child_map2['id'];
												
						$query_owner = "SELECT creator_guid FROM elgg_content_item_discrimination WHERE guid = '".$c_i_id."'";
						//echo $query_owner."<br/>";
						$result_owner=mysql_query($query_owner);
						$ci_owner = mysql_fetch_row($result_owner);
						//echo "<br/>".$ci_owner[0]."<br/>";
						if ($ci_owner[0]!= "" && (!in_array($c_i_id, $map_ci)) )
						{
							//
							$query_username = "SELECT username FROM elggusers_entity WHERE guid = '".$c_i_id."'";
							$result_username = mysql_query($query_username);
							$ci_name = mysql_fetch_row($result_username);
							//
							
							$entity=get_entity($ci_owner[0]);
							
							$entity_object = get_entity($c_i_id);
							if($entity_object->meducator3 != "")
							{
								$tag_map_text = "<b><a href=\"http://metamorphosis.med.duth.gr/pg/profile/".$ci_name[0]."\">".$entity_object->meducator3."</a></b><br/>Licence: ".$entity_object->meducator18b."<br/>Language: ".$entity_object->meducator24."<br/>Creators: ".$entity_object->meducator20;
							}
							else
							{
								$tag_map_text = "<b><a href=\"http://metamorphosis.med.duth.gr/pg/profile/".$ci_name[0]."\">".$ci_name[0]."</a></b>";
							}
							
							
							
							$map_geocod1 = $map->getGeocode($entity->Location);
							if($i%2==0)
							{
								$map_geocod1['lon'] += $mg1;
								$mg1 += 0.15;
							}
							else
							{
								$map_geocod1['lat'] += $mg2;
								$mg2 += 0.15;
							}
							//$map->addMarkerByAddress($entity->Location,(string)$c_i_s,$tag_map_text,"" );
							$map->addMarkerByCoords($map_geocod1['lon'],$map_geocod1['lat'],(string)$c_i_s,$tag_map_text,"");
							$map_ci[$i] = $c_i_s;
							$i++;
							
						}
					}	
				}
			}
		
	}
	//$entity=get_entity(2);
	//echo "My Location".$entity->Location."<br/>";
	//$map->addMarkerByCoords(5,5,"BlaBla","<b>BlaBla3232</b>","This is Stathis tooltip");
    

	/*$myHTML_map = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">
    <html xmlns=\"http://www.w3.org/1999/xhtml\" xmlns:v=\"urn:schemas-microsoft-com:vml\">
    <head>";
	*/
	
	/*$myHTML_map .= "<!-- necessary for google maps polyline drawing in IE -->
    <style type=\"text/css\">
      v\:* {
        behavior:url(#default#VML);
      }
    </style>
    </head>";
	*/
    //<body onload=\"onLoad()\">";
	//include_once(dirname(dirname(dirname(__FILE__))) . "/mod/content_item/views/default/css.php");
    // make sure only logged in users can see this page	
	echo $myHTML_map;
	
////////////////////////////////////////////////////////////
//////////////////////////end map 1//////////////////////////////
///////////////////////////////////////////////////////////
	
	
    gatekeeper();
 
    // set the title
    $title = "Map of Content Item";
 
    // start building the main column of the page
    $area2 = elgg_view_title($title);
	//$area2 .= elgg_view("admin/user_opt/search_content_item_connect");
    // Add the form to this section
	
	global $CONFIG;
	
	//$selected_c_i = $_GET['content_item'];
	 
		
	// Make sure we're logged in
		if (!isloggedin()) forward();

	
	// set title

		//$area2 = elgg_view_title(elgg_echo('profile:showcontentitemlabel'));
		$area2 .= elgg_view("profile/showgraph");
////////////////////////////////////////////////////////////
//////////////////////////map2//////////////////////////////
///////////////////////////////////////////////////////////

   /* require_once('GoogleMapAPI-2.5/GoogleMapAPI.class.php');
	
    $map = new GoogleMapAPI('map');
    // setup database for geocode caching
    $map->setDSN('mysql://elgg@localhost:81/elgg');
    // enter YOUR Google Map Key
    $map->setAPIKey('ABQIAAAAQNUInQTwgn5VDDyl8bOByxTn2aORatyZyU46qAvXBfOijvU79hSczO6cDTdYULHuwTIH54k9rc-_Ng');
   
    // create some map markers
	
	//print_r($map);//
 //echo "STATHIS2";	
    //$map->addMarkerByAddress("Thessaloniki");
//echo "STATHIS3";	
//string $address the map address to mark (street/city/state/zip)
    //$map->addMarkerByAddress('826 P St Lincoln NE 68502','Old Chicago','<b>Old Chicago</b>');
	//$map->getGeocode("3 Ioustinianou Kozani Greece 50100");
	//echo "STATHIS3";
    $map->addMarkerByAddress("Servia Kozanis","Valentinos","<b>Valentino's</b>","" );
	$map->addMarkerByCoords(5,5,"BlaBla","<b>BlaBla3232</b>","This is Stathis tooltip");
    

	$myHTML_map = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">
    <html xmlns=\"http://www.w3.org/1999/xhtml\" xmlns:v=\"urn:schemas-microsoft-com:vml\">
    <head>";
	$myHTML_map .= $map->printHeaderJS();
    $myHTML_map .= $map->printMapJS();
	$myHTML_map .= "<!-- necessary for google maps polyline drawing in IE -->
    <style type=\"text/css\">
      v\:* {
        behavior:url(#default#VML);
      }
    </style>
    </head>
    <body onload=\"onLoad()\">";
	
	
	*/
	$myHTML_map2 = $map->printHeaderJS();
    $myHTML_map2 .= $map->printMapJS();
    //$myHTML_map2 .= "<table border=1>
    //<tr><td>";
   // $myHTML_map2 .= $map->printMap();
    //$myHTML_map2 .= "</td><td>";
    //$myHTML_map2 .= $map->printSidebar();
   // $myHTML_map2 .= "</td></tr>
    //</table>
   // </body>
    //</html>";


////////////////////////////////////////////////////////////
//////////////////////////end map 2//////////////////////////////
///////////////////////////////////////////////////////////

////////////////////////Start repurposing selection//////////////////

	//$query_all_content_items = "SELECT guid FROM elgg_content_item_discrimination";
	//$result_all_content_items_query($query_all_content_items);
	//$num=mysql_numrows($result_all_content_items_query);
	
	/*for($i=0; $i<$num; $i++)
	{
		$ci = mysql_fetch_row($result_all_content_items_query);
		if ($entity($ci[0]))
		{
			echo $ci[0]."<br/>";
		// Vres an exei repurposed ths tade kathgorias
		// An exei apeikonise to sthn othoni
		
		}
	}
	*/
/*
$nik1 ='meducator26';
	  $content_item_id = $selected_c_i //id tou antikeimenou
	  
	$entity=get_entity($content_item_id);
	$nik1 ='meducator26';
	$metadata=get_metadata_byname($content_item_id,$nik1);
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
	 */
////////////////////////End repurposing selection//////////////////
		//$area2 .= $myHTML_map;
		//$area1 = $myHTML_map;
		$area2 .= $myHTML_map2;
		
		$area2 .= "<div class=\"mymap\">
		<script type=\"text/javascript\" charset=\"utf-8\">
		if (GBrowserIsCompatible()) {
				document.write('<div id=\"map\" style=\"width: 600px; height: 500px\"><\/div>');
				} else {
				document.write('<b>Javascript must be enabled in order to use Google Maps.<\/b>');
				}

</script></div>";
		//$area2 .= "<div id=\"sidebar_map\"></div>";

		
	// Get the form and correct canvas area
		$body = elgg_view_layout('one_column', $area2);
		//$body = elgg_view_layout("two_column_left_sidebar", $area1 , $area2);
		
	// Draw the page
		//page_draw(elgg_echo("profile:showgraph"),$body);
	 //$body =elgg_view_layout('one_column', $area2);
	 //$title2 = $area1;
	// $title2 .= $title;
	 page_draw($title, $body);
?>