<?php
@apache_setenv('no-gzip', 1);
@ini_set('zlib.output_compression', 0);
@ini_set('implicit_flush', 1);
// This works ! 
ob_end_flush(); 
for($i=0;$i<10;$i++) { 
	echo "yeah :-))))\n"; 
	@ob_flush(); 
	flush();
	sleep(1); 
} 

?>