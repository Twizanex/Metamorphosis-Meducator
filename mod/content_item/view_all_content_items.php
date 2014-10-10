<?php
    // Load Elgg engine
    include_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
 
    // make sure only logged in users can see this page	
    gatekeeper();
 
    // set the title
    $title = "mEducator Social Network";
 
    // start building the main column of the page
    $area2 = elgg_view_title($title);
 
    // Add the form to this section
	
	global $CONFIG;

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
$goto_url="http://metamorphosis.med.duth.gr/meducator/graph.html";


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





////////////////////////////////////////////////
///////////////////////////////////////////////



$ST = "";
$ST .="<a href=\"{$CONFIG->wwwroot}mod/content_item/view_all_content_items.php\">CLICK HERE TO RELOAD GRAPH</a><br />";
$ST .= "<div class=\"contentWrapper\">
			<applet code=\"prefuse/demos/applets/RadialGraphView.class\"
			codebase=\"./\"
			archive=\"demos.jar\"
			width=\"1050\"	height=\"650\">
			If you can read this text, the applet is not working. Perhaps you do not have the Java 1.4.2 (or later) web plug-in installed?<br/>
			<param name=INPUT_FILE value=\"http://metamorphosis.med.duth.gr/mod/content_item/gmljava.xml\"/>
			</applet>
	</div>";
$ST .= "<a href=\"http://java.com\">Get Java here.</a><br />";
$ST .= "<b>Instructions for browsing the graph</b><br />";
$ST .= "Use Left Mouse button to see individual repurposing history<br />";
$ST .= "Use Double click on a node to see the resource's profile <br />";
//		</applet>";


    //$area2 .= $AR2;
	$area2 .= $ST;
	//$area2 .= elgg_view("admin/user_opt/search");
 
    // layout the page
    $body = elgg_view_layout('one_column', $area2);
 
 	
    // draw the page
    page_draw($title, $body);
	
?>

