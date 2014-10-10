<?php

	if (is_plugin_enabled('tidypics')){	
		$limit = get_plugin_setting('tidypics_num_items','vazco_mainpage');
		if (!isset($limit) || !$limit) $limit = 10;	
	
		$images = get_entities('object','image',0,0,$limit, false, false);
?>
<div class="index_box">
	<h2><?php echo elgg_echo("custom:tidypics"); ?></h2>
	
	<div class="search_listing">
	<?php 
		
		if(isset($images)) {
			$counter = -1;
			echo '<div class="frontpage_tidypics_box_narrow">';
						
			foreach($images as $image){
				$counter +=1;
				if ($counter == 2){
					$counter = 0;
					echo "</div>";
					echo '<div class="frontpage_tidypics_box_narrow">';
				}
				
				echo "<div class=\"tidypics_index\">";
				echo elgg_view("vazco_mainpage/tidypics/icon", array(
						'mimetype' => $image->mimetype
						,'thumbnail' => $image->thumbnail
						,'file_guid' => $image->guid
						,'link' => $image->getUrl()
						,'size' => 'small'
					));
				echo "</div>"; //tidypics_index
			}
			echo '</div>';
		}else{
			echo elgg_echo('vazco_mainpage:tidypics:noactivity');
		}
	?>
	</div>
</div>
<?php }?>