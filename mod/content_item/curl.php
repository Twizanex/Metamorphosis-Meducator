<?php 

$dat ="http://metamorphosis.med.duth.gr/mod/content_item/nikolas.rdf";

$response = file_get_contents($dat);

 
$URL = "http://meducator.open.ac.uk/resourcesrestapi/rest/meducator/";
 
 			$ch = curl_init($URL);
			curl_setopt($ch, CURLOPT_MUTE, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
			curl_setopt($ch, CURLOPT_POSTFIELDS, "$response");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$output = curl_exec($ch);
			curl_close($ch);

   echo $output;
   
?>