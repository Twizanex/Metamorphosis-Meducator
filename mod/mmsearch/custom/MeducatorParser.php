<?php

  include_once(dirname(__FILE__) . "/class_rdf_parser.php");

  function my_statement_handler_parser(&$user_data, $subject_type, $subject, $predicate, $ordinal, $object_type, $object, $xml_lang)
  {
    if($user_data->replace_uri) 
      if(isset($user_data->uri_list[$predicate])) $predicate = $user_data->uri_list[$predicate];

    //echo $subject . " --> " . $predicate . " --> " . $object . "<br>\n";

    if(!isset($user_data->results[$subject])) $user_data->results[$subject] = array();
    if(!isset($user_data->results[$subject][$predicate])) $user_data->results[$subject][$predicate] = array();
    $user_data->results[$subject][$predicate][] = $object;
  }

  function my_warning_handler_parser($message)
  {
    echo $message . "<br>";
  }


  class MeducatorParser
  {
    var $results;
    var $removed_keys;
    var $uri_list;
    var $replace_uri;
    var $vocabs;

    function MeducatorParser($rdf, $replace_uri = false, $forEdit = false)
    {
      include(dirname(__FILE__) . "/meducator_uri_list.php");
      include(dirname(dirname(__FILE__)) . "/views/default/resources/properties_list.php");

      $this->results = array();
      $this->removed_keys = array();
      $this->uri_list = $uri_list;
      $this->replace_uri = $replace_uri;
      $this->vocabs = $properties_values;
      
      $parser = new Rdf_parser();
      $parser->rdf_parser_create( NULL );
      $parser->rdf_set_user_data( $this );
      $parser->rdf_set_statement_handler("my_statement_handler_parser");
      $parser->rdf_set_warning_handler("my_warning_handler_parser");
      $parser->rdf_parse($rdf, strlen($rdf), true);
      $parser->rdf_parser_free();

      $this->deleteDuplicates($this->results);
      $this->formatData($this->results);
      $this->eliminateEmptyArrays($this->results);
      
    //  print_r($this->results);
      
      $this->formatEducationalObjectives($this->results);
      $this->eliminateLocalIds($this->results);
      $this->formatIdentifiers($this->results);
      
      if($forEdit == false)
        $this->formatVocabularies($this->results);
      
      unset($this->removed_keys);
      //$this->renameKeys($this->results);
      //print_r($this->vocabs);
      //print_r($this->results);
      //unset($this->uri_list);
    }

    
    private function deleteDuplicates(&$data)
    {
      $prev = null;
      foreach($data as $key => &$value)
      {
        if(is_array($value)) $this->deleteDuplicates($value);
        if($prev != null)
          if(($prev == $value)&&(is_int($key))) unset($data[$key]);
        $prev = $value;
      }
    }
    

    //replace local IDs with actual data
    private function formatData(&$data)
    {
      foreach($data as $key => &$value)
      {
        if(is_array($value)) $this->formatData($value);
        else
        {
          $aux = $value;
          if(isset($this->results[$aux]))
          {
            $data[$key] = array();
            $data[$key][$aux] = $this->results[$aux];
            $this->removed_keys[$aux] = $this->results[$aux];
            unset($this->results[$aux]);
            $this->formatData($data[$key]);
          }
          elseif(isset($this->removed_keys[$aux]))
          {
            $data[$key] = array();
            $data[$key][$aux] = $this->removed_keys[$aux];
          }
        }
      }
    }


    private function eliminateEmptyArrays(&$data, $inRepurposing = false)
    {
      //the exception applies only to this specific key and not on it's subkeys
      $exceptions = array("creator", "discipline", "disciplineSpeciality", "resourceType", "mediaType", "educationalOutcomes",
                          "isRepurposedFrom", "isRepurposedTo", "isAccompaniedBy", "externalTerm", "hasRepurposingContext", "repurposedFrom", "subject");
      //the exception applies to all the subkeys
      $exceptions_sub = array("identifier");
      
      foreach($data as $key => &$value)
      {
        if(!in_array((string)$key, $exceptions_sub))
        {
          if((string)$key === "hasRepurposingContext") $inRepurposing = true;
          if(is_array($value))
          {
            if(is_array($data[$key])) $this->eliminateEmptyArrays($data[$key], $inRepurposing);
            if((count($value) <= 1)&&(!in_array((string)$key, $exceptions)))
            {
              if($inRepurposing && is_int($key))
              {
                $preserveKey = array_keys($value);
                $preserveKey = $preserveKey[0];
                $data[$preserveKey] = array_shift($value);
                unset($data[$key]);
              }
              elseif(substr($key, 0, 4) != "node") $data[$key] = array_shift($value);
            }
          }
          if((string)$key === "hasRepurposingContext") $inRepurposing = false;
        }
      }
    }
    
    
    private function eliminateSESAMELocalIDS(&$data)
    {
      foreach($data as $key => &$value)
      {
        
      }
    }


    private function formatIdentifiers(&$data)
    {
      //return;
      //define the keys involved
      $key_identifiers = ($this->replace_uri) ? "identifier" : "http://www.purl.org/meducator/ns/identifier";
      $key_instanceType = ($this->replace_uri) ? "identifierDescription" : "http://www.w3.org/2000/01/rdf-schema#description";
      $key_value = ($this->replace_uri) ? "label" : "http://www.w3.org/2000/01/rdf-schema#label";
      $key_repurposing = ($this->replace_uri) ? "hasRepurposingContext" : "http://www.purl.org/meducator/ns/hasRepurposingContext";
      $key_repFrom = ($this->replace_uri) ? "repurposedFrom" : "http://www.purl.org/meducator/ns/repurposedFrom";

      //go through all the results
      foreach($data as $resultKey => &$resultData)
      {
        if(is_array($resultData)) {
          if(isset($resultData[$key_identifiers])) {

            $identifiers = $resultData[$key_identifiers];
            $identifiers_new = array();

            //go through all the identifiers
            foreach($identifiers as $key => $value)
            {
              if(is_array($value))
                //check the information for each identifier
                foreach($value as $identifier => $info)
                {
                  $type = $info["identifierDescription"][0];  //this means the each identifier can have only one type
                  $value = $info["label"][0];
                  array_push($identifiers_new, array("description"=>$type, "value"=>$value));
                }  
                  /*for($i=0; $i<count($info[$key_instanceType]); $i++)
                  {
                    //isolate the type
                    $instanceType = explode(":", $info[$key_instanceType][$i]);
                    $instanceType = $instanceType[1];
                    //identify the correct value (if it is explicitly defined, we will take the "value" field / else the resource field)
                    if(isset($info[$key_value]))
                      if(isset($info[$key_value][$i])) $identifier = $info[$key_value][$i];
                    //add the identifier to the new list
                    if(!isset($identifiers_new[$instanceType])) $identifiers_new[$instanceType] = array();
                    $identifiers_new[$instanceType][] = $identifier;
                  }*/
            }
            $resultData[$key_identifiers] = $identifiers_new;
          }
        }
        
        //check the identifiers in the repurposing area
        if(isset($resultData[$key_repurposing]))
          foreach($resultData[$key_repurposing] as $keyRep => &$repInfo)
            if(isset($repInfo[$key_repFrom])) 
              $this->formatIdentifiers($repInfo);
      }
    }


    private function formatVocabularies(&$data)
    { 
      $key_vocabularies = array();
      foreach($this->vocabs as $key => $values)
        $key_vocabularies[] = substr($key, 4);
      
      foreach($data as $key => &$value)
      {
        for($i=0; $i<count($key_vocabularies); $i++)
        {
          $currentKey = ($this->replace_uri) ? $key_vocabularies[$i] : $this->getFullURI($key_vocabularies[$i]);
          if(isset($value[$currentKey]))
          {
            if(is_string($value[$currentKey]))
              $value[$currentKey] = $this->getLabelFromURI($key_vocabularies[$i], $value[$currentKey]);
            elseif(is_array($value[$currentKey]))
              foreach($value[$currentKey] as $index => $text)
              {
                $value[$currentKey][$index] = $this->getLabelFromURI($key_vocabularies[$i], $text);
              }
          }
        }
      }
    }


    private function formatEducationalObjectives(&$data)
    {
      $key_eduObj = ($this->replace_uri) ? "educationalObjectives" : "http://www.purl.org/meducator/ns/educationalObjectives";
      $this->formatRDFBagData($data, $key_eduObj);
    }


    //function to convert the BAG indexes to INT indexes
    private function formatRDFBagData(&$data, $key)
    {
      $aux_array = array();
      foreach($data as $id => &$value)
      {
        if(isset($value[$key]))
          if(is_array($value[$key]))
          {
            foreach($value[$key] as $index => $text)
              if($index != "type") $aux_array[] = $text;
            $value[$key] = $aux_array;
          }
      }
    }
    
    
    //function to eliminate all the local IDS generated by the parser
    private function eliminateLocalIds(&$data)
    {
      foreach($data as $key => &$value)
      {
        if(is_array($value)) $this->eliminateLocalIds($value);
        elseif(substr($value, 0, 6) == "#genid") $value = "";
      }
    }
    
    
    //function to reverse lookup: pass the KEYWORD to get the URI
    private function getFullURI($searchKey)
    {
      foreach($this->uri_list as $key => $value)
        if($value == $searchKey) return $key;
      return "";
    }
    
    
    //function to format all the strings from vocabs
    private function getLabelFromURI($vocab, $uri)
    {
      //try to identify the entry into the $vocab voabulary
      if(isset($this->vocabs["mdc:".$vocab][$uri]))
      {
        return $this->vocabs["mdc:".$vocab][$uri];
      }
      //no luck with the vocabulary: extract the information if we have a URI
      elseif(strrpos($uri, "#") !== FALSE)
      {
        $aux = explode("#", $uri);
        return $aux[1];
      }
      //no luck with any of the previous: return the original value
      else return $uri;
    }
  }

?>
