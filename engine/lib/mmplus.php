<?php

    //$API_URL = "http://meducator.open.ac.uk/resourcesrestapi/rest/meducator/auth/";
    $CONFIG->API_URL = "http://meducator.open.ac.uk/resourcesrestapi/rest/meducator/auth/";
    $CONFIG->DISTRIBUTED_API_URL = "http://smartlink.open.ac.uk/servicerestapi/restapi/";

    function connectToSesame($url, $data = "",$del="",$put="")
    {
        $ch = curl_init($url);
    		curl_setopt($ch, CURLOPT_MUTE, 1);
		if (($data!="")&&($put!="YES"))
                {
			curl_setopt($ch, CURLOPT_POST, 1);
                      curl_setopt($ch, CURLOPT_POSTFIELDS, "$data");
                }
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml','Authorization: Basic '.'bWV0YW1vcnBob3NpczptM3RhbTBycGgwc2lz'));
		
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	//		curl_setopt(CURLOPT_USERPWD, '[metamorphosis]:[m3tam0rph0sis]');
		if ($del=="YES")
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');

			
		if ($put=="YES"){
                  //$data = http_build_query($data);
			$len =strlen($data);
		//	 $fh = fopen('php://memory', 'rw'); 
		//	     fwrite($fh, $data);  
		//			rewind($fh); 

				$putData = tmpfile();

fwrite($putData, $data);
fseek($putData, 0);

				
			curl_setopt($ch, CURLOPT_INFILE, $putData);  
			curl_setopt($ch, CURLOPT_INFILESIZE, $len);  
			curl_setopt($ch, CURLOPT_PUT, true);
		}
                
                $output = curl_exec($ch);
			curl_close($ch);
                if ($put=="YES")  
                        fclose($putData);

        return $output;
    }
?>
