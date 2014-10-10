<?php
 //to tell the browser to display it as xml
//header("Content-type: text/xml");	


//$term="proteinuria"; //symptom
//$term="diabetes";	//disease
//$term="Immunoglobulin"; //treatment
//$term="electrocardiography ";
$term="Bacterial Meningitis";
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
//	echo $output;
	
	
	
	//Do the parsing
	
	$xml=new SimpleXMLElement($output);

	
	if(!$xml)  ///////this doesnt work might wanna check the output somehow
{
echo ("term not matched");
exit;
}
	
	
	//	print_r($xml);    //to show the xml tables
	
	$type=$xml->resulttype;		//to get the type (only works if its diagnosis and it is a term found in medgle)
	echo $type;
	
	if (!$type)
	{ 
	$type=$xml->query;
		$type1=$type->querytype;
		if ($type1)
			echo $type1;
		else echo ("term not matched");
	}
	else { 	
	
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
	
		}   //else ends here

/* MEDLINE PLUS
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

		*/
	
	
	
	
?>