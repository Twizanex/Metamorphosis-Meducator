<?php
$API_URL = "http://localhost:8080/openrdf-sesame";
$extras= "/repositories/stefan?select * {where ?S mdc:language ?O}";

 $ch = curl_init($API_URL.$extras);
  curl_setopt($ch, CURLOPT_MUTE, 1);
	curl_setopt($ch, CURLOPT_URL, $API_URL.$extras);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
    curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    $output = curl_exec($ch);
    curl_close($ch);


	echo $output;



?>