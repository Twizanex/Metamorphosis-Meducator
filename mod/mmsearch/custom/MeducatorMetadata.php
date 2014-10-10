<?php

  class MeducatorMetadata
  {
    var $ID;
    var $data;
    var $uri_list;

    function MeducatorMetadata($ID, $data)
    {
        include(dirname(__FILE__) . "/meducator_uri_list.php");
      
        $this->ID = $ID;
        $this->data = $data;
        $this->uri_list = $uri_list;
    }
    
    public function getID()
    {
      return $this->ID;
    }

    public function getData($key, $secondTry = false)
    {
        if(count($this->data) == 0) return "";
    
        if(isset($this->data[$key])) 
        {
          if(($key == "creator")||($key == "metadataCreator")) 
            //return the creators
            return $this->getCreator($key);
          elseif (($key == "rights") || ($key == "quality"))
            return $this->forceToArray($key);
          elseif (($key == "subject") || ($key == "disciplineSpeciality") || ($key == "discipline"))
            return $this->putLabelBack($key);
          elseif($key == "hasRepurposingContext")
            return $this->formatHasRepurposingContext();
          else 
            return $this->data[$key];
        }
        elseif($secondTry == false) //data not found with the searched key and is the first try
        {
          //establish $key is URI or property_name
          if(isset($this->uri_list[$key]))
            //key is URI; try to find data with property_name
            return $this->getData($this->uri_list[$key], true);
          else
            //key is property_name; try to find data with URI
            return $this->getData($this->getKeyURI($key), true);
        }
        
        return "";
    }
    
    //function that formats the creators correctly
    private function getCreator($key)
    {
      $aux = array();
      if(!is_array($this->data[$key]))
        $aux[] = array("name" => $this->data[$key]);
      else
        foreach($this->data[$key] as $index => $data) {
          if(is_array($data)) $aux[] = $data;
          else $aux[] = array("name" => $data);
        }
        
      return $aux;
    }
    
    //converts strings to array of strings
    private function forceToArray($key)
    {
      if(!is_array($this->data[$key]))
      {
        $aux = array();
        $aux[] = $this->data[$key];
      }
      else $aux = $this->data[$key];
      
      return $aux;
    }
    
    //converts strings to arrays with [label] key
    private function putLabelBack($key)
    {
      if(!is_array($this->data[$key]))
      {
        $aux = array();
        $aux[]["label"] = $this->data[$key];
      }
      else 
      {
        $aux = $this->data[$key];
        foreach($aux as $index => $value)
          if(!is_array($value))
          {
            $aux[$index] = array();
            $aux[$index]["label"] = $value;
          }
      }
      
      return $aux;
    }
    
    //solves the repurposedFrom missing key problem
    private function formatHasRepurposingContext()
    {
      $response = array();
      if(is_array($this->data["hasRepurposingContext"]))
        foreach($this->data["hasRepurposingContext"] as $repC)
        {
          if(!is_array($repC))
          {
           $new_array = array();
           $new_array["repurposedFrom"] = $repC;
           $response[] = $new_array;
          }
          else $response[] = $repC;
        }
        return $response;
    }
    
    //function to reverse lookup: pass the KEYWORD to get the URI
    private function getKeyURI($searchKey)
    {
      foreach($this->uri_list as $key => $value)
        if($value == $searchKey) return $key;
      return "";
    }
  }

?>
