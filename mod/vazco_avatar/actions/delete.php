<?php 
	require_once(dirname(dirname(__FILE__))."/models/avatar.php");
	global $CONFIG;
	
	if (!isadminloggedin()){
		register_error('vazco_avatar:norights');
		forward(get_input('forward_url', $_SERVER['HTTP_REFERER']));
	}

	$guid = (int) get_input('file_guid');

	if ($avatar = get_entity($guid)) {
		if ($avatar->canEdit()) {
			$subtype = $avatar->getSubtype();
			$container = get_entity($avatar->container_guid);
			
			if ($subtype!='avatar') forward(get_input('forward_url', $_SERVER['HTTP_REFERER'])); //back off if not an avatar
			
			$images = array($avatar);

			//loop through all avatar's images and delete them
			foreach($images as $im) {
				deleteAvatar($im->large, $im->getOwner());
				deleteAvatar($im->medium, $im->getOwner());
				deleteAvatar($im->small, $im->getOwner());
				deleteAvatar($im->tiny, $im->getOwner());
				deleteAvatar($im->topbar, $im->getOwner());
				deleteAvatar($im->master, $im->getOwner());
						
				if ($im) { //delete actual image file
					$delfile = new ElggFile($im->getGUID());
					$delfile->owner_guid = $im->getOwner();
					//$delfile->setFilename($im->originalfilename);
					if (!$delfile->delete()) {
						register_error(elgg_echo("avatar:notdeleted"));
					} else {
						system_message(elgg_echo("avatar:deleted"));
					}
				} //end delete actual image file
			} //end looping through each image to delete it

		} else { //user does not have permissions to delete this image or album
			$container = $_SESSION['user'];
			register_error(elgg_echo("avatar:notdeleted"));
		} //end of canEdit() comparison

	} else { // unable to get Elgg entity
		register_error(elgg_echo("avatar:notdeleted"));			
	} //end of get_entitty()

	forward($CONFIG->wwwroot . 'pg/vazco_avatar/edit');
?>