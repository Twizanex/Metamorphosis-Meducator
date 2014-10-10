<?php

  class MeducatorMetadata
  {
    var $ID;
    var $data;

    function MeducatorMetadata($ID)
    {
      $this->ID = $ID;
      $this->data = array();
    }

    public function addData($dataType, $data)
    {
      include(dirname(__FILE__) . "/meducator_uri_list.php");

      if(isset($uri_list[$dataType]))
      {
        $key = $uri_list[$dataType];

        //check to see if this is the first value of a specific type
        if(!isset($this->data[$key]))
        {
          //add new values
          $this->data[$key] = $data;
        }
        else  //we have multiple values (ex: Identifier)
        {
          //convert to array if necessary
          if(!is_array($this->data[$key]))
          {
            $aux = $this->data[$key];
            $this->data[$key] = array();
            $this->data[$key][] = $aux;
          }
          $this->data[$key][] = $data;
        }
      }
    }

    public function getData($dataType)
    {
      if(!isset($this->data[$dataType])) return null;
      return $this->data[$dataType];
    }
  }

?>
