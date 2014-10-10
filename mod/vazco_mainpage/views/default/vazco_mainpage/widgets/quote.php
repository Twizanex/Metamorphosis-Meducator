<div class="index_box">
	<h2><?php echo elgg_echo('custom:quote');?></h2>
	<div class="search_listing">
	<?php 
		if (is_plugin_enabled('quoteoftheday')){
			$todays_quote = get_featured_quote_entity();
			
			if($todays_quote){
				if (isloggedin()){
		    		echo elgg_view_entity($todays_quote);
				}else{
		    		echo $todays_quote->description;
		    	}
			}
			else{
				echo elgg_echo('quoteoftheday:emptyset');
			}
		}
	?>
	</div>
</div>