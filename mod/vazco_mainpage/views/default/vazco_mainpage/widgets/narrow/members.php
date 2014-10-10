<?php 
	/**
	 * Elgg vazco_mainpage plugin
	 * 
	 * @author Michal Zacher [michal.zacher@gmail.com]
	 */

	//get the newest members who have an avatar
	$newest_members = get_entities_from_metadata('icontime', '', 'user', '', 0, 10);
?>
<!-- latest members -->
        <div class="index_box">
            <h2><?php echo elgg_echo("custom:members"); ?></h2>
            <div class="contentWrapper">
            <?php 
                if(isset($newest_members)) {
                    //display member avatars
                    foreach($newest_members as $members){
                        echo "<div class=\"index_members\">";
                        echo elgg_view("profile/icon",array('entity' => $members, 'size' => 'small'));
                        echo "</div>";
                    }
                }
            ?>
	        <div class="clearfloat"></div>
	        </div>
        </div>