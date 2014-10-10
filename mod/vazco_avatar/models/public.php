<?php 
	function getAvatarSource($entity, $size, $url){
		$iconSource = $entity->getIcon($size);
		if ($entity->avatar){
			if ($file = get_entity($entity->avatar))
				$iconSource = $url."mod/vazco_avatar/avatar.php?file_guid=".$entity->avatar."&size=".$size;
		}
		return $iconSource;
	}
	
	//set logged in user's icon to the one from the avatar
	function setUserIcon($entity, $size){
		global $CONFIG;
		if ($entity->avatar){
			if ($file = get_entity($entity->avatar)){
				$iconSource = $CONFIG->wwwroot."mod/vazco_avatar/avatar.php?file_guid=".$entity->avatar."&size=".$size;
				$_SESSION['user']->setIcon($iconSource,$size);
				return true;
			}
		}
		return false;
	}
?>