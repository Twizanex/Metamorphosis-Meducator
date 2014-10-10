
<?php

	  set_time_limit(360000);
    // Load Elgg engine
    include_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
	
//	admin_gatekeeper();
	
	global $CONFIG;
	
	
//create the users	

	$ourFileName = "massusers.txt";		
	$ourFileHandle = fopen($ourFileName, 'w') or die("can't open file");
	
	
	for ($i=1;$i<=50;$i++)
{
	$username='evaluator'.$i;
	$password='evaluat0r123';
	$name= 'Evaluation User '.$i;
	$email='eval@metamorphosis.med.duth.gr';
	$guid = register_user($username, $password, $name, $email, true);
	$new_user=get_entity($guid);
				$new_user->admin_created = true;
			$new_user->created_by_guid = get_loggedin_userid();
			$new_user->issimpleuser = "yes";
			$new_user->admin="yes";
	
	
	fwrite($ourFileHandle,$guid.'|');	
}
		fclose($ourFileHandle);
	
	
	/*	
	//delete the users	
		$gufile=file_get_contents("massusers.txt");
		$gus=explode("|",$gufile);
		foreach ($gus as $g)
		{
			$member=get_entity($g);
				$member->delete();
//			echo $member->name;
		}
	*/
		
		
		
?>