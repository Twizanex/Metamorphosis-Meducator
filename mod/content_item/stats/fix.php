<?php
	$ourFileName = "keywordstats06092011.txt";		
	$ourFileHandle = fopen($ourFileName, 'r') or die("can't open file");
	$name="fixedstats.txt";
	$fp=fopen($name,'w') or die("can't open file");

	
	while ( $nik=fgets($ourFileHandle))
	{
	$doru=explode("|",$nik);
	if( strpos($doru[2],"#") >1 )
	{
		$doru[1]=0;
		$doru[2]='';
	}
	$whatever=implode("|",$doru);
	echo $whatever;
	fwrite($fp,$whatever);
	}
	
	
		fclose($fp);
	fclose($ourFileHandle);
	
	
	
	?>