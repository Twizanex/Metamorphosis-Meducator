<?php
	/**
	 * Elgg vazco_mainpage plugin
	 * 
	 * @author Michal Zacher [michal.zacher@gmail.com]
	 */
	if(is_plugin_enabled('file')){
		//grab the latest files
		$files = get_entities('object','file',0,"",4);		
		
?> 	
        <!-- display latest files -->
        <div class="index_box">
            <h2><?php echo elgg_echo("custom:files"); ?></h2>
            <?php 
                if (!empty($files)) {
                    foreach ($files as $file){
						$vars['entity'] = $file;
						$info = "<p> <a href=\"{$file->getURL()}\">{$file->title}</a></p>";
						$info .= "<p class=\"owner_timestamp\"><a href=\"{$vars['url']}pg/file/{$owner->username}\">{$owner->name}</a> {$friendlytime}";
						$numcomments = elgg_count_comments($file);
						if ($numcomments)
							$info .= ", <a href=\"{$file->getURL()}\">" . sprintf(elgg_echo("comments")) . " (" . $numcomments . ")</a>";
						$info .= "</p>";
						
						// $icon = elgg_view("profile/icon",array('entity' => $owner, 'size' => 'small'));
						
						if ($file->smallthumb)
							$icon = "<a href=\"{$file->getURL()}\"><img src=\"{$vars['url']}mod/file/thumbnail.php?size=small&file_guid={$file->getGUID()}\" border=\"0\" /></a>";
						else
							$icon = "<a href=\"{$file->getURL()}\">" . elgg_view("file/icon", array("mimetype" => $mime, 'thumbnail' => $file->thumbnail, 'file_guid' => $file->guid, 'size' => 'small'	)). "</a>";
						echo elgg_view_listing($icon, $info);

                    }
                }else{
                    echo "<p><?php echo elgg_echo('custom:nofiles'); ?></p>";
                }
            ?>
        </div>
<?php
	}	
?>