<?php

  include_once(dirname(__FILE__) . "/class_rdf_parser.php");

  function my_statement_handler(&$user_data, $subject_type, $subject, $predicate, $ordinal, $object_type, $object, $xml_lang)
  {
    $entry = new stdClass();
    $entry->subject_type = $subject_type;
    $entry->subject = $subject;
    $entry->predicate = $predicate;
    $entry->ordinal = $ordinal;
    $entry->object_type = $object_type;
    $entry->object = $object;
    $entry->xml_lang = $xml_lang;

    if($user_data->replace_uri) 
      if(isset($user_data->uri_list[$predicate])) $predicate = $user_data->uri_list[$predicate];

    //echo $subject . " --> " . $predicate . " --> " . $object . "<br>\n";

    if(!isset($user_data->results[$subject])) $user_data->results[$subject] = array();
    if(!isset($user_data->results[$subject][$predicate])) $user_data->results[$subject][$predicate] = array();
    $user_data->results[$subject][$predicate][] = $object;
  }

  function my_warning_handler($message)
  {
    echo $message . "<br>";
  }


  class MeducatorParser
  {
    var $results;
    var $uri_list;
    var $replace_uri;
    var $whereAmI;

    function MeducatorParser($rdf, $replace_uri = false)
    {
      include_once(dirname(__FILE__) . "/meducator_uri_list.php");

      $this->results = array();
      $this->whereAmI = array();
      $this->uri_list = $uri_list;
      $this->replace_uri = $replace_uri;

      $parser = new Rdf_parser();
      $parser->rdf_parser_create( NULL );
      $parser->rdf_set_user_data( $this );
      $parser->rdf_set_statement_handler("my_statement_handler");
      $parser->rdf_set_warning_handler("my_warning_handler");
      $parser->rdf_parse($rdf, strlen($rdf), true);
      $parser->rdf_parser_free();
      
      $this->deleteDuplicates($this->results);
      $this->formatData($this->results);
      //$this->formatIdentifiers($this->results);
      $this->eliminateEmptyArrays($this->results);
      $this->formatRights($this->results);
      $this->formatEducationalObjectives($this->results);
      $this->eliminateLocalIds($this->results);
      
      //print_r($this->results);
      
      //$this->renameKeys($this->results);
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
    

    private function formatData(&$data)
    {
      foreach($data as $key => &$value)
      {
        array_push($this->whereAmI, $key);
        if(is_array($value)) $this->formatData($value);
        else
        {
          $aux = $value;
          if(isset($this->results[$aux]))
          {
            //echo implode(",", $this->whereAmI) . "\n";
            //echo "process key: " . $aux . "\n";
            //echo "in_array: " . var_dump(in_array("isAccompaniedBy", $this->whereAmI, true)) . "\n";
            if(!in_array("isAccompaniedBy", $this->whereAmI, true))
            {
              //echo "\nis processed\n\n";
              $data[$key] = array();
              $data[$key][$aux] = $this->results[$aux];
              unset($this->results[$aux]);
              $this->formatData($data[$key]);
            }
          }
        }
        array_pop($this->whereAmI);
      }
    }


    private function eliminateEmptyArrays(&$data)
    {
      //the exception applies only to this specific key and not on it's subkeys
      $exceptions = array("creator", "discipline", "disciplineSpeciality", "resourceType", "mediaType", "educationalOutcomes", 
                          "isRepurposedFrom", "isRepurposedTo", "isAccompaniedBy", "externalTerm");
      //the exception applies to all the subkeys
      $exceptions_sub = array("identifier");
      
      foreach($data as $key => &$value)
      {
        if(!in_array((string)$key, $exceptions_sub))
        {
          if(is_array($value))
          {
            if(is_array($data[$key])) $this->eliminateEmptyArrays($data[$key]);
            if((count($value) <= 1)&&(!in_array((string)$key, $exceptions))) $data[$key] = array_shift($value);
          }
        }
      }
    }


    private function formatIdentifiers(&$data)
    {
      //define the keys involved
      $key_identifiers = ($this->replace_uri) ? "identifier" : "http://www.purl.org/meducator/ns/identifier";
      $key_instanceType = ($this->replace_uri) ? "instanceType" : "http://www.w3.org/2001/XMLSchema-instancetype";
      $key_value = ($this->replace_uri) ? "instanceValue" : "http://www.w3.org/1999/02/22-rdf-syntax-ns#value";

      //go through all the results
      foreach($data as $resultKey => &$resultData)
      {
        $identifiers = $resultData[$key_identifiers];
        $identifiers_new = array();

        //go through all the identifiers
        foreach($identifiers as $key => $value)
        {
          if(is_array($value))
            //check the information for each identifier
            foreach($value as $identifier => $info)
              //one identifier can have more than one type; check them all
              for($i=0; $i<count($info[$key_instanceType]); $i++)
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
              }
        }
        $resultData[$key_identifiers] = $identifiers_new;
      }
    }


    private function formatRights(&$data)
    { 
      $key_rights = ($this->replace_uri) ? "rights" : "http://www.purl.org/meducator/ns/rights";
      foreach($data as $key => &$value)
      {
        if(isset($value[$key_rights]))
          if(is_string($value[$key_rights]))
          {
            $aux = explode("#", $value[$key_rights]);
            $value[$key_rights] = $aux[1];
          }
          elseif(is_array($value[$key_rights]))
            foreach($value[$key_rights] as $index => $text)
            {
              $aux = explode("#", $text);
              $value[$key_rights][$index] = $aux[1];
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
  }

?>
