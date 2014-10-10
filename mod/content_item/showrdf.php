<?php
header("Content-type: text/xml"); 
    include_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");


global $CONFIG;


	
$nik=$_GET['id'];
       $address = $CONFIG->API_URL . "eidsearch?id=" . $nik;
        $rdf_info = connectToSesame($address);
echo $rdf_info;
			?>