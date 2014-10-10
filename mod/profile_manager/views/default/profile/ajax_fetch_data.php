<?php 
# fetch bioportal data via Ajax call

$searchfor = isset($_GET['searchfor']) ? strip_tags(trim($_GET['searchfor'])) : "";

$xml ="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
$xml.="<results>\n";

if (!empty($searchfor)) {
  $url="http://rest.bioontology.org/bioportal/search/".urlencode($searchfor)."?apikey=aca21147-c8a0-4d73-9a94-6effd466bb11";
  $biology = file_get_contents($url);
  $dom = new DOMDocument();
  $dom->loadXML($biology);
  
  # echo $biology; # DEBUG
  # echo "<hr>\n"; # DEBUG

  $searchBeans=$dom->getElementsByTagName('searchBean');
  $hits=0;
  $labels=array(); # label
  $conceptIds=array(); # URL
  $preferredNames=array(); # text to display

  # loop through the results fro the biomed portal
  # replace double quotes with single quotes
  foreach ($searchBeans as $bean) {
    $v=$bean->getElementsByTagName('ontologyDisplayLabel')->item(0)->nodeValue;
    $v=str_replace('"',"'",trim($v));
    $labels[]=$v;
    
    $v=$bean->getElementsByTagName('conceptId')->item(0)->nodeValue;
    $v=str_replace('"',"'",trim($v));
    $conceptIds[]=$v;
    
    $v=$bean->getElementsByTagName('preferredName')->item(0)->nodeValue;
    $v=str_replace('"',"'",trim($v));
    $v=str_replace('&',"&amp;",trim($v));
    $preferredNames[]=$v;
    $hits++;
  }

  for ($i=0; $i < $hits; $i++) {
    $xml.="  <hit>\n";
    $xml.="    <label>".$labels[$i]."</label>\n";
    $xml.="    <conceptId>".$conceptIds[$i]."</conceptId>\n";
    $xml.="    <preferredName>".$preferredNames[$i]."</preferredName>\n";
    $xml.="  </hit>\n";
  }
}

# separate section to list unique names of ontologies in returned results
$xml.="  <uniquesOntologies>\n";
$uniqueLabels=array_unique($labels); # make an array of the unique ontologies appearing in the results
sort($uniqueLabels);
foreach ($uniqueLabels as $u) {
  $xml.="    <ontology>\n";
  $xml.="      <name>".$u."</name>\n";
  $xml.="    </ontology>\n";
}
$xml.="  </uniquesOntologies>\n";
$xml.="</results>\n";

header("Content-Type: application/xml; charset=utf-8");
echo $xml;
?>