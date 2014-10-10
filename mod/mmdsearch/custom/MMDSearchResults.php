<?php

  require_once(dirname(__FILE__) . "/MeducatorParser.php");
  require_once(dirname(__FILE__) . "/ServicesParser.php");
  class MMDSearchResults
  {
    var $parser;
    var $services_parser;
    var $service_uri;

    function MMDSearchResults($search_type, $rdf_content, $replace_uris = true)
    {
      if($search_type == "services")
        $this->services_parser = new ServicesParser($rdf_content);
      else
        $this->parser = new MeducatorParser($rdf_content, $replace_uris);
      
      $fis = fopen(dirname(__FILE__) . "/aux_output.txt", "w");
      fwrite($fis, print_r($this->parser->results, true));
      fwrite($fis, "done");
      fclose($fis);
    }

    public function getResults()
    {
      return $this->parser->results;
    }

    public function getServices()
    {
      return $this->services_parser->results;
    }

    private function getAuthors($authorType, $resourceID)
    {
      //identify $authorType key
      $list = &$this->parser->results[$resourceID][$authorType];
      $response = "";
      if(($authorType == "creator")||($authorType == "metadataCreator"))
      {
        if(is_array($list))
        {
          if(isset($list["name"]))
            $response = $list["name"];
          else
          {
            foreach($list as $key=>$value)
            {
              if($response != "") $response .= ", ";
              if(is_array($value)) $response .= $value["name"];
              else $response .= $value;
            }
          }
        }
        else if($list) $response = $list;
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
        $aux->internalID = $value["seeAlso"];
        
        $aux->title = ($this->isValid($value["title"])) ? $value["title"] : "[unspecified]";
        if($aux->title == "[unspecified]") continue;
        
        $aux->resourceCreated = ($this->isValid($value["created"])) ? $value["created"] : "[unknown]";
        $aux->metadataCreated = ($this->isValid($value["metadataCreated"])) ? $value["metadataCreated"] : "[unknown]";
        $aux->resourceLanguage = ($this->isValid($value["language"])) ? $value["language"] : "[unspecified]";
        $aux->metadataLanguage = ($this->isValid($value["metadataLanguage"])) ? $value["metadataLanguage"] : "[unspecified]";
        $ipr = ($this->isValid($value["rights"])) ? $value["rights"] : "[unspecified]";
        //IPR
        if(is_string($ipr) && ($ipr != "[unspecified]"))
          if(($pos = strrpos($ipr, "#")) !== false) $ipr = substr ($ipr, $pos+1);
        $aux->IPR = $ipr;
        //authors
        $aux->resourceAuthors = $this->getAuthors("creator", $key);
        $aux->metadataAuthors = $this->getAuthors("metadataCreator", $key);
        //identification of the source service
        $aux->serviceUri = $this->service_uri;
        //all the resource details
        $aux->resource_details = $this->displayDetails($key);
        $response[] = $aux;
      }
      return $response;
    }


    public function displayDetails($resourceId)
    {
      $response = "";
      foreach($this->parser->results as $key=>$value)
      {
        //check to see if this is the required resource
        if($key != $resourceId) continue;
        print_r($val);

        foreach($value as $skey=>$val)
          if((substr($skey, 0, 4) != "http") && (substr($val, 0, 5) != "#geni") && (substr($val, 0, 5) != "genid"))
          {
            $response .= '<div class="details_label">' . $this->formatLabel($skey) . '</div>';
            if(is_string($val))
              $response .= '<div class="details_content">' . $val . '</div>';
            else
              $response .= '<div class="details_content">' . $this->prepareRecursiveDetailes($val) . '</div>';
            $response .= '<div class="clear_div" style="height: 10px;"></div>';
          }
      }
      if($response == "") $response = "No information found !";
      return $response;
    }

    private function formatLabel($label)
    {
      $pattern = '/([A-Z])/';
      $replacement = ' ${1}';
      //return ucword(preg_replace( $pattern, $replacement, $label ));
      return ucwords(preg_replace( $pattern, $replacement, $label ));
    }

    private function isValid($text)
    {
        if(!$text) return false;
        if(substr($text, 0, 6) == "#genid") return false;
        if(substr($text, 0, 5) == "genid") return false;
        return true;
    }
    
    private function prepareRecursiveDetailes($val)
    {
      $response = "";
      if(is_array($val))
      {
        foreach($val as $key => $data)
        {
          if((strlen($response) > 3)&&(substr($response, -4) != "<br>")) $response .= ", ";
          if(is_array($data)) 
          {
            $responseX = $this->prepareRecursiveDetailes($data);
            if($responseX != "") $response .= $responseX . "<br>";
          }
          else $response .= $this->extractUsefulInfo($data);
        }
      }
      else $response = $val;
      
      return $response;
    }
    
    private function extractUsefulInfo($info)
    {
      if(strpos($info, "#") !== FALSE)
      {
        $answer = explode("#", $info);
        return $answer[1];
      }
      elseif($info == "http://xmlns.com/foaf/0.1/Person") return "";
      else
      {
        include(dirname(__FILE__) . "/meducator_uri_list.php");
        $auxK = array_keys($uri_list);
        for($i=0; $i<count($auxK); $i++)
          if(strtolower($info) == strtolower($auxK[$i]))
            return "";
      }
      return $info;
    }
  }

?>