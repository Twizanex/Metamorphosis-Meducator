<?php
	require_once "simple_html_dom.php";
	$html = file_get_html('http://www.virtualpatients.eu/referatory/');
  
	$titles = array();
	foreach($html->find('.vp_title') as $element)
		$titles[] = $element->plaintext;
	   
	$keywords = array();
	foreach($html->find('.vp_keywords') as $element)
	{
		$aux = explode(", ", $element->plaintext);
		$keywords[] = $aux;
	}
	   
	$language = array();
	foreach($html->find('.vp_language') as $element)
		$language[] = $element->plaintext;
	
	$counter = 1;
	for($i=0; $i<count($titles); $i++)
		if($language[$i] == "English") echo ($counter++).". ".$titles[$i] . "<br>";
	
	//print_r($keywords);
?>