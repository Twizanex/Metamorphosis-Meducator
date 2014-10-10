<?php
/**
* This library is free software; you can redistribute it and/or
* modify it under the terms of the GNU Lesser General Public
* License as published by the Free Software Foundation; either
* version 2.1 of the License, or (at your option) any later version.
*
* This library is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
* Lesser General Public License for more details.
*
* You should have received a copy of the GNU Lesser General Public
* License along with this library; if not, write to the Free Software
* Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*
* Copy of GNU Lesser General Public License at: http://www.gnu.org/copyleft/lesser.txt
* Contact author at: hveluwenkamp@myrealbox.com
*
**/


/**
*Generates RDF of a multidimensional array. Called as a static class. 
*@author Evangelia Mitsopoulou @email:evangelia.mitsopoulou@hotmail.com
*@version 1.0
**/


/**
* Based on HV_ArrayToRDFCollection.php. from Herman Veluwenkamp @version 1.0
* Modification Date of HV_ArrayToRDFCollection.php: 20/12/2010
**/

class RDFExportClass {

  //elements qualifiers 
  
  public static $mdc_qualifier= 'mdc:';
  public static $foaf_qualifier= 'foaf:';
  public static $rdf_qualifier= 'rdf:';
  public static $sioc_qualifier= 'sioc:';
  public static $rdfs_qualifier= 'rdfs:';
  public static $sameAs_qualifier= 'owl:';
  public static $rdf_bag= 'rdf:_';    
 
 //attribute name for indicating identifiers type
  public static $attributename_parseType = 'xsi:type';  
  
  // it is part of the assigned value to the previous attribute 
  public static $attr_uri= 'dcterms:';
  
 public static $uri_rights = 'http://purl.org/meducator/licenses';
  
   
// Array of metadata associated with incoming data array.It is used for the handling of attributes 
  
  public static $metadata = array();

  
/* 
Returns the URL of the current Page
The reutrn value of this function is assigned to the first rdf:about of the RDF document, where is located the URL of the metadata instance file
*/

  private static  function currentPageURL() {
    $curpageURL = 'http';
    if (!array_key_exists("HTTPS", $_SERVER)) $_SERVER["HTTPS"] = "";
    if ($_SERVER["HTTPS"] == "on") {$curpageURL.= "s";}
    $curpageURL.= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
    $curpageURL.= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    } else {
    $curpageURL.= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    }
    return $curpageURL;
    }
  
/**
 Generates RDF. Take as param : 
 - $data, which is an array, 
 - $spacer, whih is used to add blank space before a tag element
 - $expanded, which is the variable that holds the generated rdf 
*/

private static function generateRDF($data,$spacer='', $expanded='') {
      	  
	  if (is_array($data) or is_object($data)) {
	  while (list($key, $val) = each($data)) {
	  		
	      if (is_array($val)) {
		   
		   switch($key) {
		     case ($key=='creator' || $key=='subject' || $key=='externalTerm' || $key=='discipline'|| $key=='disciplineSpeciality' || $key=='isRepurposedTo' || $key=='isRepurposedFrom'): 
			  		while (list($key2, $val2) = each($data[$key])){			
			  			$expanded .= $spacer.'<'.self::$mdc_qualifier.$key.'>';
						if($key=='externalTerm') {
						$expanded .=  $spacer.'<rdf:Description>'."\n";}
						else{											
						$expanded .=  $spacer.'<rdf:Description rdf:about="'.$key.$key2.'">'."\n";}
              			$expanded  = self::generateRDF($val2, $spacer.'  ', $expanded, $key2);
			  			$expanded .=  $spacer.'</rdf:Description>'."\n";
			  			$expanded .= '</'.self::$mdc_qualifier.$key.'>'."\n"; }
			 continue;
			 case 'metadataCreator':	 
			 			{			 
			  			$expanded .= $spacer.'<'.self::$mdc_qualifier.$key.'>';
			  			$expanded .=  $spacer.'<rdf:Description>'."\n";
              			$expanded  = self::generateRDF($val, $spacer.'  ', $expanded, $key);
			  			$expanded .=  $spacer.'</rdf:Description>'."\n";
			  			$expanded .= '</'.self::$mdc_qualifier.$key.'>'."\n";		  
           				}
			 continue;			 
			 case 'sameAs':
			 		while (list($key2, $val2) = each($data['sameAs'])){
			 			$attribute = ' '; 
			     		$attribute .= ' rdf:resource="'.$val2.'"';
			 			$expanded .= $spacer.'<'.self::$sameAs_qualifier.$key.$attribute.'/>'."\n";	 		
						}
			 continue;			 
			 case 'identifier':
			 		while (list($key2, $val2) = each($data['identifier'])){
			 			 while (list($key3, $val3) = each($data['identifier'][$key2])){
			                 $attribute = ' '; 
							 if ($key2=='ISBN'){	
							 $attribute .= ' '.self::$attributename_parseType.'="'.self::$attr_uri.$key2.'"'.' rdf:value="'.$val3.'"';}
							 else { $attribute .= ' '.self::$attributename_parseType.'="'.self::$attr_uri.$key2.'"'.' rdf:resource="'.$val3.'"';}
							 $expanded .= $spacer.'<'.self::$mdc_qualifier.$key.$attribute.'/>';} 		
						 }
			 continue;
			 case ($key=='resourceType' || $key=='mediaType' || $key=='educationalOutcomes'):
			 		while (list($key2, $val2) = each($data[$key])){
			 			$attribute = ' '; 
			 			$attribute .= ' rdf:resource="'.$val2.'"';
			 			$expanded .= $spacer.'<'.self::$mdc_qualifier.$key.$attribute.'/>'."\n";	 		
			   		}
			 continue;	 
			 case 'educationalObjectives': 
					$expanded .= $spacer.'<'.self::$mdc_qualifier.$key.'>';
			  		$expanded .=  $spacer.'<rdf:Bag>'."\n";
			  		while (list($key2, $val2) = each($data['educationalObjectives'])){			
			   			$expanded .= $spacer.'<'.self::$rdf_bag.$key2.'>'.$val2.'</'.self::$rdf_bag.$key2.'>'."\n";
					}
					$expanded .= '</rdf:Bag>'."\n";
			 		$expanded .= $spacer.'</'.self::$mdc_qualifier.$key.'>'; 
			 continue;
			 case $key=='isAccompaniedBy': 
			  		while (list($key2, $val2) = each($data[$key])){	
						$expanded .= $spacer.'<'.self::$mdc_qualifier.$key.'>';
						$expanded .=  $spacer.'<rdf:Description rdf:about="AccompaniedResource'.$key2.'">'."\n"; 
              			$expanded  = self::generateRDF($val2, $spacer.'  ', $expanded, $key2);
			  			$expanded .=  $spacer.'</rdf:Description>'."\n";
			  			$expanded .= '</'.self::$mdc_qualifier.$key.'>'."\n";} 
			 continue;
			 	   
		   } //end of switch     			 		
			
		} //end of case where key is an array element
		
	
		else {
          $attribute = '';
          
          
		  //check if an element has attributes
		  
		  if (isset(self::$metadata[$key]['parseType'])) {
            
            switch (self::$metadata[$key]['parseType']) {
              	case 'ResourceClass':
                $attribute .= ' rdf:resource="'.$val.'"';
                $val = '';
                break;
				case 'Rights':
                $attribute .= ' rdf:resource="'.self::$uri_rights.'#'.$val.'"';
                $val = '';
                break;
				case 'OwlsameAs':
				$attribute .= ' rdf:resource="'.$val.'"';
				$val = '';
                break;	
				case 'ResourceType':
                 $attribute .= ' rdf:resource="'.$val.'"';
                $val = '';
                break;
				case 'MediaType':
                $attribute .= ' rdf:resource="'.$val.'"';
                $val = '';
                break;
              case 'Literal':
              case 'Identifier':
              default:	
                $attribute .= ' '.self::$attributename_parseType.'="'.self::$metadata[$key]['parseType'].'"';
                break;
            }
          } //end checking of attributes
		  
		  
		  // type the element 
		  
		  if (isset($key)){
		  	switch ($key){ 
			case'type':
			$expanded .= $spacer.'<'.self::$rdf_qualifier.$key.$attribute.'>'.$val.'</'.self::$rdf_qualifier.$key.'>'."\n";
	  		continue;
			case'name':
			$expanded .= $spacer.'<'.self::$foaf_qualifier.$key.$attribute.'>'.$val.'</'.self::$foaf_qualifier.$key.'>'."\n";
	  		continue;
			case 'mbox_sha1sum':
			$expanded .= $spacer.'<'.self::$foaf_qualifier.$key.$attribute.'>'.$val.'</'.self::$foaf_qualifier.$key.'>'."\n";
	  		continue;
			case'memberOf':
			$expanded .= $spacer.'<'.self::$sioc_qualifier.$key.$attribute.'>'.$val.'</'.self::$sioc_qualifier.$key.'>'."\n";
	  		continue;
			case'label':
			$expanded .= $spacer.'<'.self::$rdfs_qualifier.$key.$attribute.'>'.$val.'</'.self::$rdfs_qualifier.$key.'>'."\n";
	  		continue;
			default:
			$expanded .= $spacer.'<'.self::$mdc_qualifier.$key.$attribute.'>'.$val.'</'.self::$mdc_qualifier.$key.'>'."\n";
	  		continue;			
				}
			} //end of typing element 
			
        } 		
	  } // end of while. End of tranversing the array -> $data 
	  
    } else $expanded .= $spacer.'<'.$data.'>'."\n";//case where $data is not an array or an object
    return $expanded;
  }  
  

  /**
  * Generates the wrapper around the RDF and inserts the generated RDF.
  *
  * @param string $data Array to be processed.
  * @returns string RDF serialised as XML
  */ 
    
  public static function exportRDF($data) {
            
    
	// Namespaces Definition  
	
	$rdf   = '<rdf:RDF xmlns:xs="http://www.w3.org/2001/XMLSchema"';
	$rdf  .= ' xmlns:dcterms="http://purl.org/dc/terms/"';
	$rdf  .= ' xmlns:sioc="http://rdfs.org/sioc/ns#"';
	$rdf  .= ' xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#"';
	$rdf  .= ' xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"';
	$rdf  .= ' xmlns:dc="http://purl.org/dc/elements/1.1/"';
	$rdf  .= ' xmlns:foaf="http://xmlns.com/foaf/0.1/"';
	$rdf  .= ' xmlns:mdc="http://www.purl.org/meducator/ns/"';
	$rdf  .= ' xmlns:owl="http://www.w3.org/2002/07/owl#"';
	$rdf  .= ' xmlns:skos="http://www.w3.org/2004/02/skos/core#"';
	$rdf  .= ' xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
	
	
    //RDF Body 
	
    $rdf .= '  <rdf:Description rdf:about="'.self::currentPageURL().'">'."\n";
    $rdf .= self::generateRDF($data, '    ');
    $rdf .= '  </rdf:Description>'."\n";
    
    $rdf .= '</rdf:RDF>';
    
    return $rdf;
  }
  

  /**
  * Generates and outputs the RDF including the HTTP content type header.
  *
  * @param string $data Array to be processed.
  * @returns void
  */     
  public static function output($data) {
    header('content-type: text/xml');
    $rdf = self::exportRdf($data);
    echo $rdf;
  }
}



?>
