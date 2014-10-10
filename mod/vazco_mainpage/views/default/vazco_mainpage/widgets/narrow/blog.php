<?php 
	if(is_plugin_enabled('blog')){
		//grab the latest 4 blog posts. to display more, change 4 to something else
		$blogs = list_entities('object','blog',0,4,false, false, false);		
?> 
        <!-- latest blogs -->
        <div class="index_box">
            <h2><?php echo elgg_echo("custom:blogs"); ?></h2>
            <?php 
                if (isset($blogs)) 
                    echo $blogs; //display blog posts
            ?>
        </div>
<?php
	}	
?>