<?php
	/**
	 * Elgg vazco_mainpage plugin
	 * 
	 * @author Michal Zacher [michal.zacher@gmail.com]
	 */
	if(is_plugin_enabled('file')){
		//grab the latest files
		$files = list_entities('object','file',0,4,false, false, false);		
?> 	
        <!-- display latest files -->
        <div class="index_box">
            <h2><?php echo elgg_echo("custom:files"); ?></h2>
            <?php 
                if (!empty($files)) {
                    echo $files;//this will display files
                }else{
                    echo "<p><?php echo elgg_echo('custom:nofiles'); ?></p>";
                }
            ?>
        </div>
<?php
	}	
?>