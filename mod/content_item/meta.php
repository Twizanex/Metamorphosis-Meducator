<?php
echo "176786";
//syndesi stin mysql
$username="elgg";
$password="";
$database="elgg";

mysql_connect('localhost',$username,$password);
@mysql_select_db($database) or die("Unable to select database");
$query1 ="SELECT distinct e.* from elggentities e join elggusers_entity u on e.guid = u.guid JOIN (SELECT subm1.*, s1.string FROM elggmetadata subm1 JOIN elggmetastrings s1 ON subm1.value_id = s1.id) AS m1 ON e.guid = m1.entity_guid where ((m1.name_id='877' AND m1.string like '%actor%')) and ( (1 = 1) and e.enabled='yes') and ( (1 = 1) and m1.enabled='yes') order by e.time_created desc limit 0,10";
$result1=mysql_query($query1);
print_r($result1);
?>