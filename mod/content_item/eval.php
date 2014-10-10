<?php //THIS IS A TEST?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<title>Education Resource Evaluation</title>

<link rel="stylesheet" type="text/css" href="rating/rating.css" media="screen"/>
<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript" >google.load("jquery","1.3.2");</script>
<script type="text/javascript" src="rating/rating.js"></script>
<?php include('rating/rating.php'); ?>
</script>
</head>


<body>

<?php
$username="elgg";
$password="";
$database="elgg";
mysql_connect('localhost',$username,$password);
@mysql_select_db($database) or die("Unable to select database");
$nik=$_GET['id'];
$query= "SELECT guid,name FROM elggusers_entity where guid=\"".$nik."\"";
$result = mysql_query($query);
while($row = mysql_fetch_array($result, MYSQL_ASSOC))
					$nikolas=$row['name'];

?>

<p>Evaluation of: <br /><strong>"<?php echo $nikolas ?> "</strong></p>
<p>&nbsp;</p>
<p>Please provide scores for the following indicators.</p>
<div style="width: 832px">
<?php


echo "Pedagogical value";

 rating_form("indicator1".$nik);
echo "Appropriateness of the proposed learning context:" ;
 rating_form("indicator2".$nik);
echo "Appropriateness of the proposed target audience:" ;
 rating_form("indicator3".$nik);
echo "Appropriateness of presentation and/or content type:";
 rating_form("indicator4".$nik);
 ?>
</div>
<p>&nbsp;</p>
<p>&nbsp;</p>
Thank you for your input

  </body>

</html>
