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
	
	$selected_c_i = $_GET['content_item'];
	//echo $selected_c_i;

	////////////////////////////////////////////////////////
/////////// NIKOLAS ////////////////////////////////////
////////////////////////////////////////////////////////
//////////////								////////////
//////////////   java_graphml_gen.php		////////////
//////////////								////////////
//////////////								////////////
////////////////////////////////////////////////////////

//syndesi stin mysql
$username="elgg";
$password="";
$database="elgg";
$goto_url="http://galinos.med.duth.gr:81/meducator/graph.html";

mysql_connect('localhost',$username,$password);
@mysql_select_db($database) or die("Unable to select database");

$ourFileName = "gmljava.xml";
$ourFileHandle = fopen($ourFileName, 'w') or die("can't open file");
fwrite($ourFileHandle,"<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n");
fwrite($ourFileHandle,"<graphml xmlns=\"http://graphml.graphdrawing.org/xmlns\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:schemaLocation=\"http://graphml.graphdrawing.org/xmlns http://graphml.graphdrawing.org/xmlns/1.0/graphml.xsd\">\r\n" );


//fwrite($ourFileHandle,"<graphml> \r\n");
fwrite($ourFileHandle,"<graph id=\"ELGG\" edgedefault=\"directed\">\r\n");
// prwto query gia olous tous users
fwrite($ourFileHandle,"<key id=\"name\" for=\"node\" attr.name=\"name\" attr.type=\"string\"/>\r\n" );
fwrite($ourFileHandle,"<key id=\"username\" for=\"node\" attr.name=\"username\" attr.type=\"string\"/>\r\n" );

$query1 = "SELECT elggusers_entity.guid,elggusers_entity.name,elggusers_entity.username,elgg_content_item_discrimination.guid  FROM elggusers_entity JOIN elgg_content_item_discrimination ON elggusers_entity.guid=elgg_content_item_discrimination.guid ORDER BY elggusers_entity.name" ;
$result1=mysql_query($query1);

//posous users vrike
$num=mysql_numrows($result1);

// loop gia na tous emfanisoume olous
$i=0;
while ($i < $num) {
$friend =NULL;
$username=mysql_result($result1,$i,"username");
$userid=mysql_result($result1,$i,"guid");
$name=mysql_result($result1,$i,"name");
fwrite($ourFileHandle,"<node id=\"$userid\">\r\n");
fwrite($ourFileHandle,"<data key=\"name\">$name</data>\r\n");
fwrite($ourFileHandle,"<data key=\"username\">$username</data>\r\n");
fwrite($ourFileHandle,"</node>\r\n");
$i++;
}

$query2 = "SELECT elggentity_relationships.guid_one,elggentity_relationships.relationship,elggentity_relationships.guid_two,elgg_content_item_discrimination.guid FROM elggentity_relationships JOIN elgg_content_item_discrimination ON elggentity_relationships.guid_one=elgg_content_item_discrimination.guid where elggentity_relationships.relationship='friend' " ;
$result2=mysql_query($query2);
$num2=mysql_numrows($result2);

$k=0;
while ($k < $num2)
{
	$parent=mysql_result($result2,$k,"guid_two");
	$child=mysql_result($result2,$k,"guid_one");
	$query3 = "SELECT name FROM elggusers_entity WHERE guid=$parent" ;
	$result3=mysql_query($query3);
	$so=mysql_result($result3,0,"name");
	$query4 = "SELECT name FROM elggusers_entity WHERE guid=$child" ;
	$result4=mysql_query($query4);
	$ta=mysql_result($result4,0,"name");
	
	
	fwrite($ourFileHandle,"<edge id=\"e$k\" source=\"$parent\" target=\"$child\"/>\r\n");	
	$k++;
}
fwrite($ourFileHandle,"</graph>\r\n");
fwrite($ourFileHandle,"</graphml>");
fclose($ourFileHandle);
mysql_close();





////////////////////////////////////////////////
///////////////////////////////////////////////END OF NIKOLAS
//////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					/////////////////////////////// Create Object Graph ////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	 
	$xml = simplexml_load_file("gmljava.xml");

	//$myContentItem = "Object 48";
	
$myNode =0;
$file = $selected_c_i;
$checkedNodes;
$counter = 0;
$xmlstringEdge = "";

$xmlstringNode = "";
/*
echo $xml->getName() . "----<br />";

echo "<br>-------------------------<br/>";
echo "myContentItem = ".$myContentItem."<br/>";
echo "myNode = ".findObjectNode($myContentItem, $xml)."<br/>";

echo "<br>-------------------------<br/>";
*/




$query = "SELECT username FROM elggusers_entity WHERE guid = \"".$selected_c_i."\"";

//echo $query;		
		$result = get_data($query);
		$myrow = "";
		if($result == null)
		{
			echo "error";
		}
		else{
			$myrow = $result[0];
			}
		//print_r($myrow);
		$myContentItem = $myrow->username;
		//print_r($result);
		//echo $myContentItem;
		//$myNode = findObjectNode($selected_c_i, $xml);

$myNode = findObjectNode($myContentItem, $xml);
//print_r($myNode);
stathis($myNode, $xml);

//echo "<br>this is=".(string)findONodeOfObject(564, $xml)."-end-<br>";

$xmlstringStart = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<graphml xmlns=\"http://graphml.graphdrawing.org/xmlns\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:schemaLocation=\"http://graphml.graphdrawing.org/xmlns http://graphml.graphdrawing.org/xmlns/1.0/graphml.xsd\">
<graph id=\"ELGG\" edgedefault=\"directed\">
<key id=\"name\" for=\"node\" attr.name=\"name\" attr.type=\"string\"/>
<key id=\"username\" for=\"node\" attr.name=\"username\" attr.type=\"string\"/>";
//echo $xmlstringNode;
//echo $xmlstringEdge;
$xmlstringEnd = "</graph>
</graphml>"; 
///////////////////////////////////////////////////////////
//Bgazw tis diples seires sta egdes
/*$file3 = $selected_c_i."temp";
createNewXmlFile("<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n<a>\n".$xmlstringEdge."\n</a>", $file3);

$file3 = $file3.".xml";
echo $file3;
//$xmlstringEdgefile = simplexml_load_file($file3);
*/
$xmlstringEdgefile = simplexml_load_string("<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n<a>\n".$xmlstringEdge."\n</a>");
$xmlfinaledges="";
$myedgearray[0]="";
$g=0;
	foreach($xmlstringEdgefile->children() as $child_1)
  	{
		$ch_id = $child_1['id']." ";
		
		if((!in_array($ch_id, $myedgearray )))
		{
			$myedgearray[$g++]=$ch_id;
			$xmlfinaledges .= "<edge id=\"".$ch_id."\" source=\"".$child_1['source']."\" target=\"".$child_1['target']."\"/>\n";
		}
	}
	//return $xmlcleanedges;




//$xmlstring = $xmlstringStart.$xmlstringNode."\n".$xmlstringEdge.$xmlstringEnd;
$xmlstring = $xmlstringStart.$xmlstringNode."\n".$xmlfinaledges.$xmlstringEnd;

createNewXmlFile($xmlstring, $file);

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
	
		//echo "Success, wrote to file ($filename)";
	
		fclose($handle);
	
}


function findObjectNode($myContentItem, $xml)
{
	foreach($xml->children() as $child1)
  	{
		foreach($child1->children() as $child2)
		{
			foreach($child2->children() as $child3)
			{			
				if($child3 == $myContentItem)
				{
					$myNode = $child2['id'];
					return $myNode;
				}
			}		
		}
	}
}


function findONodeOfObject($node, $xml)
{
	foreach($xml->children() as $child1)
  	{
		foreach($child1->children() as $child2)
		{
			foreach($child2->children() as $child3)
			{
			
				if($node == $child2['id'])
				{
					$mContItem = $child3;
					return $mContItem;
				}
			}	
		}
	}
}

function findUsernameNodeOfObject($node, $xml)
{
	foreach($xml->children() as $child1)
  	{
		foreach($child1->children() as $child2)
		{
			foreach($child2->children() as $child3)
			{
				if($node == $child2['id'])
				{
						if ($child3['key'] == "username")
						{
							$mContItem = $child3;
							return $mContItem;
						}
				}
			}	
		}
	}
}


function stathis($myNode, $xml)
{
	global $xmlstringNode;
	
	$xmlstringNode .= "\n<node id=\"".$myNode."\">";
	$xmlstringNode .= "\n";
	$xmlstringNode .= "<data key=\"name\">".findONodeOfObject($myNode, $xml)."</data>\n";
	$xmlstringNode .= "<data key=\"username\">".findUsernameNodeOfObject($myNode, $xml)."</data>";
	$xmlstringNode .= "\n";
	$xmlstringNode .= "</node>";
	
	global $checkedNodes;
	global $counter;
	
	$checkedNodes[$counter++] = $myNode;
	$condentNodes = NULL;
	$condentNodes = findConnectedNodes ($myNode, $xml);
	
	
	//print_r($condentNodes);
	
	//echo "-".$condentNodes[0]."-";
	//echo "-".$condentNodes[1]."-";
	//echo count($condentNodes)."c";
		
		$nmc = count($condentNodes);
		if($nmc > 0)
		{
			for ($i = 0; $i <$nmc; $i++)
			{
				$numNode = (int)$condentNodes[$i];
				stathis($numNode, $xml);
			}
		}
}


function findConnectedNodes ($myNode, $xml)
{
	global $xmlstringEdge;
	global $checkedNodes;
	
	$newnodes;
	$i = 0;
	foreach($xml->children() as $child1)
  	{
		foreach($child1->children() as $child2)
		{
			$childname =$child2->getName();
			if($childname == "edge")
			{
					if(($myNode == (int)$child2['source']))
					{
						$xmlstringEdge .= "<edge id=\"".$child2['id']."\" source=\"".$child2['source']."\" target=\"".$child2['target']."\"/>";
						$xmlstringEdge .= "\n";
						if(!in_array((int)$child2['target'], $checkedNodes))		// If the node hasn'y been checked yet
							$newnodes[$i++] = (int)$child2['target'];
					}
					if(($myNode == (int)$child2['target']) )
					{
						$xmlstringEdge .= "<edge id=\"".$child2['id']."\" source=\"".$child2['source']."\" target=\"".$child2['target']."\"/>";
						$xmlstringEdge .= "\n";
						if(!in_array((int)$child2['source'], $checkedNodes))		// If the node hasn'y been checked yet
							$newnodes[$i++] = (int)$child2['source'];
					}
			}	
		}
	}
	
	return $newnodes;
}


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			/////////////////////////////// E N D    of Create Object Graph ////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	 
	 
	 
	 // Load the Elgg framework
		require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
		
	// Make sure we're logged in
		if (!isloggedin()) forward();

	$page_owner = page_owner_entity();
		if ($page_owner === false || is_null($page_owner)) {
			$page_owner = $_SESSION['user'];
			set_page_owner($page_owner->getGUID());
		}

	// set title
	
		$area2 = elgg_view_title(elgg_echo('profile:showcontentitemlabel'));
		$area2 .= elgg_view("profile/showgraph");
		
	//	Create java applet for showing Graph
	
		$ST = "";
		$ST .="<a href=\"{$CONFIG->wwwroot}mod/content_item/show_history_content_item.php?content_item=".$file."\">CLICK HERE TO RELOAD GRAPH</a><br />";
		//$ST .="<div align=\"right\"><a href=\"{$CONFIG->wwwroot}mod/profile/edit.php\">GO BACK</a><br /></div>";
		$ST .= "<div class=\"contentWrapper\">
					<applet code=\"prefuse/demos/applets/partial_content_test_Applet.class\"
					codebase=\"./\"
					archive=\"demos.jar\"
					width=\"910\"	height=\"650\">
					If you can read this text, the applet is not working. Perhaps you do not have the Java 1.4.2 (or later) web plug-in installed?<br/>
					<param name=INPUT_FILE value=\"".$file.".xml\"/>
					</applet>
			</div>";
		$ST .= "<a href=\"http://java.com\">Get Java here.</a><br />";
		$ST .= "<b>Instructions for browsing the graph</b><br />";
		$ST .= "Use Left Mouse button to see individual repurposing history<br />";
		$ST .= "Use Double click on a node to see the resource's profile <br />";
		
		

		$area2 .= $ST;
	// Get the form and correct canvas area
		$body = elgg_view_layout('one_column', $area2);
		//$body = elgg_view_layout("two_column_left_sidebar", '', $area2);
		
	// Draw the page
		//page_draw(elgg_echo("profile:showgraph"),$body);
	 $body =elgg_view_layout('one_column', $area2);
	 page_draw($title, $body);

?>