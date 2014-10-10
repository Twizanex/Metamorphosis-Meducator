<?php
 // Load Elgg engine
    include_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
 
	echo '<link href="views/default/css.php" rel="stylesheet" type="text/css" />';
	//include_once(dirname(dirname(dirname(__FILE__))) . "/mod/content_item/views/default/css.php");
    // make sure only logged in users can see this page	
    gatekeeper();
 
    // set the title
    $title = "History of Content Item";
 
    // start building the main column of the page
    $area2 = elgg_view_title($title);
	//$area2 .= elgg_view("admin/user_opt/search_content_item_connect");
    // Add the form to this section
	
	global $CONFIG;
	
	
		$selected_c_i = 1071;
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////// 	NEW CREATE OBJECT GRAPH   //////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//$item_id= 528;
$item_id = $selected_c_i;
echo $item_id."-------<br/>";

//$items_array = find_all_the_connected_elements (select_objects_connectd_with($item_id));
//$items_xml = xml_string_create_for_guid_array($items_array);

$items_xmlstringStart = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<graphml xmlns=\"http://graphml.graphdrawing.org/xmlns\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:schemaLocation=\"http://graphml.graphdrawing.org/xmlns http://graphml.graphdrawing.org/xmlns/1.0/graphml.xsd\">
<graph id=\"ELGG\" edgedefault=\"directed\">
<key id=\"name\" for=\"node\" attr.name=\"name\" attr.type=\"string\"/>
<key id=\"username\" for=\"node\" attr.name=\"username\" attr.type=\"string\"/>";
//echo $xmlstringNode;
//echo $xmlstringEdge;
$items_xmlstringEnd = "</graph>
</graphml>"; 
$items_xml = "nik";
$items_xml_file = $items_xmlstringStart.$items_xml.$items_xmlstringEnd;
$item_file = $item_id."_new";
createNewXmlFile($items_xml_file, $item_file);

///////////////////
///////////////////  Returns all the connected elements in an array
///////////////////
array find_all_the_connected_elements(array $init_array)
{
	$num_elem = count($init_array);
	if ($num_elem != 0)
	{
		$myarray = $init_array;
		for($i = 0; $i < $num_elem ; $i++)
		{
			$connected_object_array = select_objects_connectd_with($init_array[$i]);
			if($connected_object_array != NULL)
			{
				$myarray2 = find_all_the_connected_elements($connected_object_array);
				$myarray = array_merge($myarray, $myarray2);
			}
		}
		return $myarray;
	}
	else
		return NULL;
}
///////////////////
///////////////////  Returns all the connected elements in an array
///////////////////
array select_objects_connectd_with( int $mycontent_item)
{

	$username="elgg";
	$password="";
	$database="elgg";
	$goto_url="http://metamorphosis.med.duth.gr/meducator/graph.html";
	
	mysql_connect('localhost',$username,$password);
	@mysql_select_db($database) or die("Unable to select database");

	$query1 = "SELECT guid_one FROM elggentity_relationships WHERE guid_two = '".$mycontent_item."'" ;
	$result1=mysql_query($query1);
	
	$array_gid_1[0] = $mycontent_item;
	$array_gid_2;
	//posous users vrike
	$num1=mysql_numrows($result1);
	
	for($i = 1; $i <= $num1; $i++)
	{
		$array_gid_1[$i] = mysql_result($result1,$i,"guid_one");
	}
	
	$query2 = "SELECT guid_two FROM elggentity_relationships WHERE guid_one = '".$mycontent_item."'" ;
	$result2=mysql_query($query2);
	$num2=mysql_numrows($result2);
	
	$j=-1;
	for($i = 0; $i < $num2; $i++)
	{
		$temp = mysql_result($result1,$i,"guid_two");
		if( array_search($temp, $array_gid_1) === false)
		{
			$j++;
			$array_gid_2[$j] = $temp;
		}
	}
	
	//merge the two arrays array_gid_1 & array_gid_2 to array_gid
	
	array_gid = array_merge($array_gid_1, $array_gid_2);
	
	if(count(array_gid) == 0)
		return NULL;
	else
		return array_gid;
	
}	
	
string xml_string_create_for_guid_array(array $array_gid)
{

	$query_objects = "SELECT guid,name,username FROM elggusers_entity ORDER BY name" ;
	$result_objects=mysql_query($query_objects);
	
	//posous users vrike
	$num_objects=mysql_numrows($result_objects);
	
	$string_xml_users= "";
	
	// loop gia na tous emfanisoume olous
	$i=0;
	while ($i < $num_objects) {
		$friend =NULL;
		$username=mysql_result($result1,$i,"username");
		$userid=mysql_result($result1,$i,"guid");
		$name=mysql_result($result1,$i,"name");
			if(array_search($userid, $array_gid) !== false)
			{
				$string_xml_users .= "<node id=\"".$userid."\">\r\n";
				$string_xml_users .= "<data key=\"name\">".$name."</data>\r\n";
				$string_xml_users .= "<data key=\"username\">".$username."</data>\r\n";
				$string_xml_users .= "</node>\r\n";
			}
		$i++;
	}
	
	return $string_xml_users;
}


function createNewXmlFile($xmlstring, $file)
{
	$filename = $file.".xml";
	
	
	
	
		if (!$handle = fopen($filename, 'wx')) {
			 echo "Cannot open file ($filename)";
			 exit;
		}
	
		// Write to our opened file.
		if (fwrite($handle, $xmlstring) === FALSE) {
			echo "Cannot write to file ($filename)";
			exit;
		}
		else
		echo "Success, wrote to file ($filename)";
	
		fclose($handle);
	
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////// END  	 CREATE OBJECT GRAPH   ////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	

$body =elgg_view_layout('one_column', $area2);
	 page_draw($title, $body);
?>