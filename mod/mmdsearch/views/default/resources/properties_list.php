<?php

  include_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/custom/class_rdf_parser.php");

  function my_statement_handler(&$user_data, $subject_type, $subject, $predicate, $ordinal, $object_type, $object, $xml_lang)
  {
    if(($predicate == "http://www.w3.org/2004/02/skos/core#prefLabel") || ($predicate == "http://www.w3.org/2000/01/rdf-schema#label"))
      $user_data[$subject] = $object;
  }

  function getVocabularyData($vocabularyFile)
  {
    $rdf = file_get_contents(dirname(dirname(dirname(dirname(__FILE__)))) . "/repository/vocabularies/" . $vocabularyFile);

    $data = array();
    $parser = new Rdf_parser();
    $parser->rdf_parser_create( NULL );
    $parser->rdf_set_user_data( $data );
    $parser->rdf_set_statement_handler("my_statement_handler");
    $parser->rdf_parse($rdf, strlen($rdf), true);
    $parser->rdf_parser_free();

    asort($data);
    return $data;
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

  //read vocabulary information for mdc:mediaType
  $vocab = getVocabularyData("mediaType.rdf");
  $properties_values["mdc:mediaType"] = array();
  foreach($vocab as $key => $value)
    $properties_values["mdc:mediaType"][$key] = $value;

  //read vocabulary information for mdc:resourceType
  $vocab = getVocabularyData("resourceType.rdf");
  $properties_values["mdc:resourceType"] = array();
  foreach($vocab as $key => $value)
    $properties_values["mdc:resourceType"][$key] = $value;
  
  //read vocabulary information for mdc:resourceType
  $vocab = getVocabularyData("licenses.rdf");
  $properties_values["mdc:rights"] = array();
  foreach($vocab as $key => $value)
    $properties_values["mdc:rights"][$key] = $value;

  //read vocabulary information for mdc:repurposingContexts
  $vocab = getVocabularyData("repurposing.rdf");
  $properties_values["mdc:repurposingContexts"] = array();
  foreach($vocab as $key => $value)
    $properties_values["mdc:repurposingContexts"][$key] = $value;

  //read vocabulary information for mdc:educationalLevel
  $vocab = getVocabularyData("educationalLevel.rdf");
  $properties_values["mdc:educationalLevel"] = array();
  foreach($vocab as $key => $value)
    $properties_values["mdc:educationalLevel"][$key] = $value;

  //read vocabulary information for mdc:educationalLevel
  $vocab = getVocabularyData("educationalOutcome.rdf");
  $properties_values["mdc:educationalOutcomes"] = array();
  foreach($vocab as $key => $value)
    $properties_values["mdc:educationalOutcomes"][$key] = $value;

?>
