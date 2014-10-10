<?php
	if (is_plugin_enabled('riverdashboard')){ 
		$limit = get_plugin_setting('activity_num_items','vazco_mainpage');
		if (!isset($limit) || !$limit) $limit = 10;	
		$type = '';
		$subtype = '';
		$objects = elgg_view_river_items(0, 0, '', $type, $subtype, '',$limit,0,0,false);
?>
	<div class="index_box">
		<h2><?php echo elgg_echo("custom:activity"); ?></h2>
		<div class="search_listing">
		<?php 
			if ($objects != ''){
				echo $objects;
			}else{
				echo elgg_echo('vazco_mainpage:activity:noactivity');
			}
		?>
		</div>
	</div>

<?php }?>