<?php
	set_time_limit(360000);
	ini_set('display_errors', true);
	error_reporting(-1);

	require_once "simple_html_dom.php";
	require_once dirname(__FILE__) . '/AcccaAutoload.php';
	
	$html = file_get_html('http://www.virtualpatients.eu/referatory/');
  
	$titles = array();
	foreach($html->find('.vp_title') as $element)
		$titles[] = $element->plaintext;
	   
	$keywords = array();
	foreach($html->find('.vp_keywords') as $element)
		$keywords[] = $element->plaintext;
	   
	$language = array();
	foreach($html->find('.vp_language') as $element)
		$language[] = $element->plaintext;
	
	/*$onlyEnglishKeywords = array();
	for($i=0; $i<count($titles); $i++)
		if($language[$i] == "English") 
			$onlyEnglishKeywords[] = $keywords[$i];
	
	asort($onlyEnglishKeywords);
	for($i = 0; $i < count($onlyEnglishKeywords); $i++)
		if($onlyEnglishKeywords[$i-1] == $onlyEnglishKeywords[$i])
		{
			array_splice($onlyEnglishKeywords, $i, 1);
			$i--;
		}*/

	/**
	 * Accca Informations
	 */
	define('ACCCA_WSDL_URL','http://www.biosemantics.org/ACCCA/AnnotationWebservicePort?wsdl');
	define('ACCCA_USER_LOGIN','');
	define('ACCCA_USER_PASSWORD','');
	
	/**
	 * Wsdl instanciation infos
	 */
	$wsdl = array();
	$wsdl[AcccaWsdlClass::WSDL_URL] = ACCCA_WSDL_URL;
	$wsdl[AcccaWsdlClass::WSDL_CACHE_WSDL] = WSDL_CACHE_NONE;
	$wsdl[AcccaWsdlClass::WSDL_TRACE] = true;
	if(ACCCA_USER_LOGIN !== '')
		$wsdl[AcccaWsdlClass::WSDL_LOGIN] = ACCCA_USER_LOGIN;
	if(ACCCA_USER_PASSWORD !== '')
		$wsdl[AcccaWsdlClass::WSDL_PASSWD] = ACCCA_USER_PASSWORD;
	// etc....
	
	$counter = 1;
	for($i=0; $i<count($titles); $i++)
		if($language[$i] == "English") 
		{
			echo ($counter++).". ".$keywords[$i] . "<br>";
	//		continue;
			
			$acccaServiceGet = new AcccaServiceGet($wsdl);
			// sample call for AcccaServiceGet::getAnnotation()
			if($acccaServiceGet->getAnnotation(new AcccaStructGetAnnotation($keywords[$i])))
			{
				$test=$acccaServiceGet->getResult();
				//print_r($test);
				$a1=$test->return;
				$a2=$a1->return;
				$a3=$a2->simpleAnnotationList;
				//	print_r ($a3);
				foreach ($a3 as $list){
					echo $list->conceptText." is a ".$list->conceptType."=";
					///////////////MEDGLE
					if($list->conceptText) {
					$term=$list->conceptText;
					$medgle= "http://api.medgle.com/1/getinfo.xml?q="."\"$term\"";
				
					//THE CURL CODE TO RETRIEVE THE XML from medgle
   $ch1 = curl_init($medgle);
	curl_setopt($ch1, CURLOPT_URL, $medgle);
	
    curl_setopt($ch1, CURLOPT_HEADER, 0);
	curl_setopt($ch1, CURLOPT_RETURNTRANSFER,1);
    $output = curl_exec($ch1);
    curl_close($ch1);
	if($output)
					$xml=new SimpleXMLElement($output);
	else 
					$xml=NULL;
					
					if(!$xml)  ///////this doesnt work might wanna check the output somehow
					{
					echo ("=term not matched in MEDGLE <br>");
//					exit;
					}
			else {
				$type=$xml->resulttype;		//to get the type (only works if its diagnosis and it is a term found in medgle)
				echo $type."<br>";
	
					if (!$type)
					{ 
					$type=$xml->query;
					$type1=$type->querytype;
					if ($type1)
					echo $type1."<br>";
					else echo ("term not matched in MEDGLE ");
					}
				}
				
				
				}

				}
				
				
				
				
				
				echo "<br>-----------------------------------------------------------<br><br>" ;
			}
			else
				print_r($acccaServiceGet->getLastError());
		}
?>