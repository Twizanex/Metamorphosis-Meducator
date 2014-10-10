<?php 
	require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/thickboxlibraries.php");
	
	//set owner to site admin.
	$owner = VAZCO_AVATAR_ADMIN;
	$limit = 100;
	$avatars = get_entities("object", "avatar", $owner, "", $limit, 0, false);
?>

<div id="tidypics_album_widget_container">
<?php echo elgg_echo('vazco_avatar:edit:description');?>
	<div class="avatar_container">
	<?php
		$counter = 0;
	 	echo '<div class="avatar_wrapper">';
		foreach($avatars as $avatar) {
			$counter++;
			if ($counter == 6){
				echo '</div><div class="avatar_wrapper">';
				$counter = 0;
			}	
			echo '<div class="avatar_box">';
				echo '<div class="avatar_icon_container"><a class="thickbox" href="'.$vars['url'].'mod/vazco_avatar/avatar.php?file_guid='.$avatar->guid.'&size=large"><img class="avatar_icon" src="'.$vars['url'].'mod/vazco_avatar/avatar.php?file_guid='.$avatar->guid.'" border="0" class="tidypics_album_cover"  alt="avatar' . $avatar->guid . '"/></a></div>';
				echo "<a class='avatar_delete_link' href='".$vars['url']."action/vazco_avatar/delete?file_guid=".$avatar->guid."'>".elgg_echo('avatar:delete')."</a>";
			echo '</div>';
		}
		echo "</div>";
	?>
	</div>
</div>