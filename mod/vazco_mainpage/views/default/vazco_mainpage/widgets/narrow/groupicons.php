<?php
	/**
	 * Elgg vazco_mainpage plugin
	 * 
	 * @author Michal Zacher [michal.zacher@gmail.com]
	 */

	if(is_plugin_enabled('groups')){
		//newest groups
		$groups = get_entities('group','',0,4,false, false, false);
?> 
        <!-- display latest groups -->
	    <div class="index_box">
            <h2><?php echo elgg_echo("custom:groups"); ?></h2>
            <div class="contentWrapper">
        	<?php
				if(isset($groups)) {
                    //display member avatars
                    foreach($groups as $group){
                        echo "<div class=\"index_members\">";
                        echo elgg_view("vazco_mainpage/groups/icon",array('entity' => $group, 'size' => 'small'));
                        echo "</div>";
                    }
                }else{
                    echo "<p><?php echo elgg_echo('custom:nogroups'); ?>.</p>";
                }
            ?>
            <div class="clearfloat"></div>
	        </div>
    	</div>  	
<?php
	}
?>