<?php

  require_once(dirname(__FILE__) . "/MeducatorParser.php");
  class MMSearchResults
  {
    var $parser;

    function MMSearchResults($rdf_content, $replace_uris = true)
    {
      $this->parser = new MeducatorParser($rdf_content, $replace_uris);
    }

    public function getResults()
    {
      return $this->parser->results;
    }

    private function getAuthors($authorType, $resourceID)
    {
      //identify $authorType key
      $list = &$this->parser->results[$resourceID][$authorType];
      $response = "";
      if($authorType == "creator")
      {
        foreach($list as $key=>$value)
        {
          if($response != "") $response .= ", ";
          if(is_array($value)) $response .= $value["name"];
          else $response .= $value;
        }
      }
      else
        $response = ($list["name"]) ? $list["name"] : "[unknown]";

      return $response;
    }


    public function resultsDisplayList()
    {
      $response = array();
      foreach($this->parser->results as $key=>$value)
      {
        //print_r($value);

        $aux = new stdClass();
        $aux->ID = $key;
        $aux->title = ($value["title"]) ? $value["title"] : "[unspecified]";
        //print_r($value["seeAlso"]);
        $aux->internalID = $this->getGUID($value["seeAlso"]);

        $ent = get_entity($aux->internalID);
        $aux->profileURI = "pg/profile/" . $ent->username;

        $aux->resourceCreated = ($value["created"]) ? $value["created"] : "[unknown]";
        $aux->metadataCreated = ($value["metadataCreated"]) ? $value["metadataCreated"] : "[unknown]";
        $aux->resourceLanguage = ($value["language"]) ? $value["language"] : "[unspecified]";
        $aux->metadataLanguage = ($value["metadataLanguage"]) ? $value["metadataLanguage"] : "[unspecified]";
        //$aux->description = $value["description"];
        $ipr = ($value["rights"]) ? $value["rights"] : "[unspecified]";
        //IPR
        if(is_string($ipr) && ($ipr != "[unspecified]"))
          if(($pos = strrpos($ipr, "#")) !== false) $ipr = substr ($ipr, $pos+1);
        $aux->IPR = $ipr;
        //authors
        $aux->resourceAuthors = $this->getAuthors("creator", $key);
        $aux->metadataAuthors = $this->getAuthors("metadataCreator", $key);

        $response[] = $aux;
      }
      return $response;
    }
    
    
    public function DisplayResourcesList()
    {
      //print_r($this->parser->results);
      
      $response = array();
      foreach($this->parser->results as $key=>$value)
      {
        $aux = new stdClass();
        $aux->internalID = $this->getGUID($value["seeAlso"], "resourcesList");
        //put in the results only the entities that are not created from the repurposing specs.
        if($aux->internalID != "")
        {
          $aux->ID = $key;
          $aux->title = ($value["title"]) ? $value["title"] : "[unspecified]";
          $ent = get_entity($aux->internalID);
          $aux->profileURI = "pg/profile/" . $ent->username;
          $aux->resourceIdentifier = ($value["identifier"]) ? $value["identifier"][0]["value"] : "";
          $response[] = $aux;
        }
      }
      return $response;
    }


    public function displayDetails()
    {
      $response = "";
      foreach($this->parser->results as $key=>$value)
      {
        //print_r($value);
        foreach($value as $skey=>$val)
          if(is_string($val) && (substr($skey, 0, 4) != "http"))
          {
            $response .= '<div class="details_label">' . $this->formatLabel($skey) . '</div>';
            $response .= '<div class="details_content">' . $val . '</div>';
            $response .= '<div class="clear_div" style="height: 10px;"></div>';
          }
      }
      if($response == "") $response = "No information found !";
      return $response;
    }


    public function displaySpecificResultsList($propertyList)
    {
      $response = array();
      $properties = explode(";", $propertyList);
      foreach($this->parser->results as $key=>$value)
      {
        $aux = array();
        for($i=0; $i<count($properties); $i++)
        {
          $subKey = substr($properties[$i], 4);

          //deal with empty fields/arrays
          if(is_array($value[$subKey])) {
            if(count($value[$subKey]) == 0) $aux[$subKey] = "[unspecified]";
          }
          elseif($value == "") $aux[$subKey] = "[unspecified]";
          //extract the values
          elseif(($subKey == "creator") || ($subKey == "metadataCreator"))
            $aux[$subKey] = $this->getAuthors ($subKey, $key);
          else $aux[$subKey] = $value[$subKey];
        }
        $response[] = $aux;
      }
      return $response;
    }


    private function getGUID($text, $context = "")
    {
        $text = explode("#", $text);
        if($context == "resourcesList")
          if($text[0] == "http://metamorphosis.med.duth.gr/uid") return $text[1];
          else return "";
        return $text[count($text) - 1];
    }
    

    private function formatLabel($label)
    {
      $pattern = '/([A-Z])/';
      $replacement = ' ${1}';
      //return ucword(preg_replace( $pattern, $replacement, $label ));
      return ucwords(preg_replace( $pattern, $replacement, $label ));
    }
  }

?>