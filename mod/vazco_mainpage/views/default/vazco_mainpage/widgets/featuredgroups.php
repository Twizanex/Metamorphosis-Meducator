<?php
	/**
	 * Elgg vazco_mainpage plugin
	 * 
	 * @author Michal Zacher [michal.zacher@gmail.com]
	 */

	if(is_plugin_enabled('groups')){
		//newest groups
		$groups = list_entities('group','',0,4,false, false, false);
		$groups = list_entities_from_metadata("featured_group", "yes", "group", "", 0, 10);
?> 
        <!-- display latest groups -->
	    <div class="index_box">
            <h2><?php echo elgg_echo("custom:featuredgroups"); ?></h2>
        <?php 
                if (!empty($groups)) {
                    echo $groups;//this will display groups
                }else{
                    echo "<p><?php echo elgg_echo('custom:nogroups'); ?>.</p>";
                }
            ?>
    	</div>
<?php
	}	
?>