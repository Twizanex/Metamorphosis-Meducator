<?php
	if (is_plugin_enabled('pages')){ 
		$limit = get_plugin_setting('pages_num_items','vazco_mainpage');
		if (!isset($limit) || !$limit) $limit = 10;	
		$objects = list_entities("object", "page_top", 0, $limit, false);
?>
	<div class="index_box listing_narrow">
		<h2><?php echo elgg_echo("custom:pages"); ?></h2>
		<div class="search_listing">
		<?php 
		if ($objects != '')
			echo $objects;
		else
			echo elgg_echo('vazco_mainpage:pages:nopages');
		?>
		</div>
	</div>

<?php }?>