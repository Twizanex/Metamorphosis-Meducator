<?php

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
$query1 = "SELECT guid,name,username FROM elggusers_entity ORDER BY name" ;
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
fwrite($ourFileHandle,"</node>\r\n");
$i++;
}



$query2 = "SELECT guid_one,relationship,guid_two FROM elggentity_relationships where relationship='friend' " ;
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

//echo'<meta HTTP-EQUIV="refresh" CONTENT="3;URL='.$goto_url.'">';
?>