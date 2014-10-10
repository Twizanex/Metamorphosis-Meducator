<?php

  include_once(dirname(__FILE__) . "/class_rdf_parser.php");

  function my_services_statement_handler(&$user_data, $subject_type, $subject, $predicate, $ordinal, $object_type, $object, $xml_lang)
  {
    //echo $subject . " --> " . $predicate . " --> " . $object . "<br>\n";
    $predicate = explode("#", $predicate);
    $predicate = $predicate[count($predicate) - 1];

    if(!isset($user_data->results[$subject])) $user_data->results[$subject] = array();
    if(!isset($user_data->results[$subject][$predicate])) $user_data->results[$subject][$predicate] = $object;
    else
    {
      if(!is_array($user_data->results[$subject][$predicate]))
      {
        $aux = $user_data->results[$subject][$predicate];
        $user_data->results[$subject][$predicate] = array();
        $user_data->results[$subject][$predicate][] = $aux;
      }
      $user_data->results[$subject][$predicate][] = $object;
    }
  }

  class ServicesParser
  {
    var $results;
 
    function ServicesParser($rdf)
    {
      $this->results = array();
  
      $parser = new Rdf_parser();
      $parser->rdf_parser_create( NULL );
      $parser->rdf_set_user_data( $this );
      $parser->rdf_set_statement_handler("my_services_statement_handler");
      $parser->rdf_parse($rdf, strlen($rdf), true);
      $parser->rdf_parser_free();
    }
  }

?>
