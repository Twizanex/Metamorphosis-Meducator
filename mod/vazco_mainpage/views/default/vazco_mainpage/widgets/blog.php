<?php 
	if(is_plugin_enabled('blog')){
		//grab the latest 4 blog posts. to display more, change 4 to something else
		//$blogs = list_entities('object','blog',0,4,false, false, false);
		$blogs = get_entities('object','blog',0,"",4,0,false);

?> 
        <!-- latest blogs -->
        <div class="index_box">
            <h2><?php echo elgg_echo("custom:blogs"); ?></h2>
            <?php 
                foreach ($blogs as $blog){
                    echo elgg_view('vazco_mainpage/objects/blog', array('entity'=>$blog)); //display blog posts
                }
            ?>
        </div>
<?php
	}
?>