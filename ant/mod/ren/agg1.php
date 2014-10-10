<?php
 //to tell the browser to display it as xml
//header("Content-type: text/xml");	
set_time_limit(360000);

$terms=array("proteinuria","hypertension","obesity","diabetes","hyperlipidaemia","metabolic syndrome","proteinuria","dyslipidaemia","inflammation");

foreach ($terms as $term)
{
	$ourFileName = $term."_medgle.xml";		
	$ourFileHandle = fopen($ourFileName, 'w') or die("can't open file");
	$fn= $term."_medplus.xml";
	$fp=fopen($fn, 'w') or die("can't open file");




//THE API
$medgle= "http://api.medgle.com/1/getinfo.xml?q="."\"$term\"";
$mplus="http://wsearch.nlm.nih.gov/ws/query?db=healthTopics&term="."\"$term\"";

//THE CURL CODE TO RETRIEVE THE XML from medgle
   $ch = curl_init($medgle);
  curl_setopt($ch, CURLOPT_MUTE, 1);
	curl_setopt($ch, CURLOPT_URL, $medgle);
	
    curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    $output = curl_exec($ch);
    curl_close($ch);
//DISPLAY THE XML (or whatever)  medgle
	echo $output;
	fwrite($ourFileHandle,$output);
	
/*	
	//Do the parsing
	
	$xml=new SimpleXMLElement($output);
	$nik=$xml->diagnosis[0];

	echo "SYMPTOMS: <br />";
		foreach ($nik->diagnosis_symptoms as $test)
		echo $test."<BR /><BR />";

		//$mplus="http://wsearch.nlm.nih.gov/ws/query?db=healthTopics&term="."\"$test\"";

	echo "RELATED DIAGNOSES: <br />";
	foreach ($nik->related_diagnoses->related_diagnosis as $test)
		echo $test."<BR /><BR />";

		echo "Medication: <br />";
	foreach ($nik->medications->medication as $test)
	echo $test."<BR /><BR />";
	
	*/


//THE CURL CODE TO RETRIEVE THE XML from mplus
   $ch = curl_init($mplus);
  curl_setopt($ch, CURLOPT_MUTE, 1);
	curl_setopt($ch, CURLOPT_URL, $mplus);
	
    curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    $output = curl_exec($ch);
    curl_close($ch);
	
//DISPLAY THE XML (or whatever)  medgle
	echo $output;
	fwrite ($fp,$output);
		
	
	fclose($fp);
	fclose($ourFileHandle);
	}
	
?>