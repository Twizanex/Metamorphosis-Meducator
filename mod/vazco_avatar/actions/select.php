<?php 
	global $CONFIG;
	$avatarId = get_input('file_guid',0);
	if ($avatarId){
		$userId = get_loggedin_userid();
		create_metadata($userId, 'avatar', $avatarId,'', $userId, ACCESS_PUBLIC);
		create_metadata($userId, 'icontime', time(),'', 0, ACCESS_PUBLIC);

		//add to river
		add_to_river('river/user/default/profileiconupdate','update',$_SESSION['user']->guid,$_SESSION['user']->guid);

		system_message(elgg_echo('avatar:selected'));
	} 
	else{
		register_error('vazco_avatar:noavatarselected');
	}
	forward($CONFIG->wwwroot . "mod/profile/editicon.php");
?>