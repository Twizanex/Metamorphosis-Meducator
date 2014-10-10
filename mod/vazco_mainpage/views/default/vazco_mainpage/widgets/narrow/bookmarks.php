<?php
	/**
	 * Elgg vazco_mainpage plugin
	 * 
	 * @author Michal Zacher [michal.zacher@gmail.com]
	 */

if(is_plugin_enabled('bookmarks')){
	//grab the latest bookmarks
	$bookmarks = list_entities('object','bookmarks',0,4,false, false, false);
?>
        <!-- display latest bookmarks -->
    	<div class="index_box">
            <h2><?php echo elgg_echo("custom:bookmarks"); ?></h2>
            <?php 
            
                if (isset($bookmarks)) 
                    echo $bookmarks; //display bookmarks
	
            ?>
        </div>
<?php
	}
?>