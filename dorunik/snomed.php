<?php

$snoterm="proteinuria";

//$snomed="http://rest.bioontology.org/bioportal/concepts/46896?conceptid=29738008&apikey=aca21147-c8a0-4d73-9a94-6effd466bb11";
$snomed="http://rest.bioontology.org/bioportal/search/?query=$snoterm&ontologyids=1353&isexactmatch=1&apikey=aca21147-c8a0-4d73-9a94-6effd466bb11";

echo $snomed;

$ch = curl_init($snomed);
	curl_setopt($ch, CURLOPT_URL, $snomed);
	
    curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    $output = curl_exec($ch);
    curl_close($ch);
//DISPLAY THE XML (or whatever)  snomed
	//echo $output;
	
	$xml=new SimpleXMLElement($output);
	print_r($xml);
	echo "<br> --------------------------------------------------------------------- <br>";
	


//////////////////////////THIS FUNCTION GIVES US THE CURRENT NON OBSOLETE TERM ID in an exactmatch search 
findCurrentVersion($xml);

function findCurrentVersion($version)
{

	foreach ($version->children() as $child)
	{
		if ($child->getName()=="searchResultList")
			foreach ($child as $c){
				if ($c->isObsolete == 0)
					echo $c->conceptIdShort."<br>";}
	}
	findCurrentVersion($child);
}

/*THIS FUNCTION GIVES US THE TERM TYPE (DISEASE,SYMPTOM ETC) in the properties of a specific term 
findType($xml);

function findType($xmlObj,$depth=0) {
  foreach($xmlObj->children() as $child) {
 //   echo str_repeat('-',$depth).">".$child->getName().": ".$child."<br>";
		
	if ($child=="Semantic_Type") { 
	echo ($xmlObj->list->string);
	}
    findType($child,$depth+1);
  }
}
*/

?>