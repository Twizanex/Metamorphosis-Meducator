<?php

  include_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/custom/class_rdf_parser.php");
if(!function_exists("extractVocabValues")) {
  function my_statement_handler_vocabs(&$user_data, $subject_type, $subject, $predicate, $ordinal, $object_type, $object, $xml_lang)
  {
    if(array_key_exists($predicate, $user_data["map"]))
    {
      $predicate = $user_data["map"][$predicate];
      if(!isset($user_data["values"][$subject])) $user_data["values"][$subject] = array();
      if($predicate == "narrower")
      {
        if(!isset($user_data["values"][$subject][$predicate])) $user_data["values"][$subject][$predicate] = array();
        $user_data["values"][$subject][$predicate][] = $object;
      }
      else $user_data["values"][$subject][$predicate] = $object;
    }
  }

  function getVocabularyData($vocabularyFile)
  {
    $rdf = file_get_contents(dirname(dirname(dirname(dirname(__FILE__)))) . "/repository/vocabularies/" . $vocabularyFile);

    $data = array();
    $data["values"] = array();
    $data["map"] = array();
    $data["map"]["http://www.w3.org/2000/01/rdf-schema#label"] = "label";
    $data["map"]["http://www.w3.org/2004/02/skos/core#prefLabel"] = "prefLabel";
    $data["map"]["http://www.w3.org/2004/02/skos/core#narrower"] = "narrower";
    
    $parser = new Rdf_parser();
    $parser->rdf_parser_create( NULL );
    $parser->rdf_set_user_data( $data );
    $parser->rdf_set_statement_handler("my_statement_handler_vocabs");
    $parser->rdf_parse($rdf, strlen($rdf), true);
    $parser->rdf_parser_free();

    $data = $data["values"];
    //print_r($data);
    return $data;
  }
  
  function extractVocabStructure($entryPoint, $data)
  {
    $response = array();
    if($entryPoint != "")
    {
      for($i=0; $i<count($data[$entryPoint]["narrower"]); $i++)
      {
        $auxURI = $data[$entryPoint]["narrower"][$i];
        if(isset($data[$auxURI]["narrower"]))
        {
          $response[$auxURI] = array();
          $response[$auxURI]["label"] = $data[$auxURI]["label"];
          $response[$auxURI]["values"] = extractVocabStructure($auxURI, $data);
        }
        else $response[] = $auxURI;
      }
    }
    return $response;
  }
  
  
    function extractVocabValues($data)
    {
      $response = array();
      foreach($data as $key => $val)
        if(!isset($val["narrower"]))
          if(isset($val["prefLabel"])) $response[$key] = $val["prefLabel"];
          else $response[$key] = $val["label"];
      return $response;
    }
  }
  

  $properties_list = array();
  $properties_list[""] = "-- select property --";
  $properties_list["mdc:title"] = "Title";
  $properties_list["mdc:identifier"] = "Identifier";
  $properties_list["mdc:rights"] = "IPR rights";
  $properties_list["mdc:language"] = "Resource language";
  $properties_list["mdc:metadataLanguage"] = "Metadata language";
  $properties_list["mdc:creator"] = "Author name";
  $properties_list["mdc:metadataCreator"] = "Metadata author name";
  $properties_list["mdc:created"] = "Resource creation date";
  $properties_list["mdc:metadataCreated"] = "Metadata creation date";
  $properties_list["mdc:citation"] = "Citation";
  $properties_list["mdc:subject"] = "Keywords";
  $properties_list["mdc:description"] = "Description";
  $properties_list["mdc:technicalDescription"] = "Technical description";
  $properties_list["mdc:resourceType"] = "Resource type";
  $properties_list["mdc:mediaType"] = "Media type";
  $properties_list["mdc:discipline"] = "Discipline";
  $properties_list["mdc:disciplineSpeciality"] = "Discipline speciality";
  $properties_list["mdc:educationalLevel"] = "Educational level";
  $properties_list["mdc:educationalContext"] = "Educational context";
  $properties_list["mdc:teachingLearningInstructions"] = "Teaching learning instructions";
  $properties_list["mdc:assessmentMethods"] = "Assessment methods";
  $properties_list["mdc:educationalPrerequisites"] = "Educational prerequisites";
  $properties_list["mdc:educationalObjectives"] = "Educational objectives";
  $properties_list["mdc:educationalOutcomes"] = "Educational outcomes";
  $properties_list["mdc:repurposingContext"] = "Repurposing context";

  //controlled vocabularies values
  $properties_values = array();
  $data_structure = array();

  //read vocabulary information for mdc:mediaType
  $vocab = getVocabularyData("mediaType.rdf");
  $entryPoint = "http://purl.org/meducator/mediaType/";
  $properties_values["mdc:mediaType"] = array();
  $data_structure["mdc:mediaType"] = array();
  $properties_values["mdc:mediaType"] = extractVocabValues($vocab);
  $data_structure["mdc:mediaType"] = extractVocabStructure($entryPoint, $vocab);
 
  //read vocabulary information for mdc:resourceType
  $vocab = getVocabularyData("resourceType.rdf");
  $entryPoint = "http://purl.org/meducator/resourceType/";
  $properties_values["mdc:resourceType"] = array();
  $data_structure["mdc:resourceType"] = array();
  $properties_values["mdc:resourceType"] = extractVocabValues($vocab);
  $data_structure["mdc:resourceType"] = extractVocabStructure($entryPoint, $vocab);
  
  //read vocabulary information for mdc:resourceType
  $vocab = getVocabularyData("licenses.rdf");
  $properties_values["mdc:rights"] = array();
  $properties_values["mdc:rights"] = extractVocabValues($vocab);

  //read vocabulary information for mdc:repurposingContexts
  $vocab = getVocabularyData("repurposing.rdf");
  $properties_values["mdc:repurposingContexts"] = array();
  $properties_values["mdc:repurposingContexts"] = extractVocabValues($vocab);

  //read vocabulary information for mdc:educationalLevel
  $vocab = getVocabularyData("educationalOutcome.rdf");
  $entryPoint = "http://purl.org/meducator/educationalOutcome/";
  $properties_values["mdc:educationalOutcomes"] = array();
  $data_structure["mdc:educationalOutcomes"] = array();
  $properties_values["mdc:educationalOutcomes"] = extractVocabValues($vocab);
  $data_structure["mdc:educationalOutcomes"] = extractVocabStructure($entryPoint, $vocab);

  //read vocabulary information for mdc:educationalLevel
  $vocab = getVocabularyData("educationalLevel.rdf");
  $properties_values["mdc:educationalLevel"] = array();
  $properties_values["mdc:educationalLevel"] = extractVocabValues($vocab);
  
  /*$auxH = fopen("Koula.txt", "w");
  foreach($properties_values as $key => $infos)
  {
    fwrite($auxH, $key . "\n\n");
    foreach($infos as $info)
      fwrite($auxH, $info . "\n");
    fwrite($auxH, "\n\n\n\n");
  }
  fclose($auxH);
  //print_r($properties_values);*/
  
?>
