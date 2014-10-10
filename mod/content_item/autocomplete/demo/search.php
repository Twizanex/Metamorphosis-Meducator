<?php

$q = strtolower($_GET["q"]);
if (!$q) return;
$username="elgg";
$password="";
$database="elgg";
mysql_connect('localhost',$username,$password);
@mysql_select_db($database) or die("Unable to select database");
$query= "SELECT * FROM elggmetastrings where string LIKE '%".$q."%'";
$items = mysql_query($query);
while($row = mysql_fetch_array($items, MYSQL_ASSOC))
{
					$nikolas=$row['string'];
					echo $nikolas."\n";
					}


?>