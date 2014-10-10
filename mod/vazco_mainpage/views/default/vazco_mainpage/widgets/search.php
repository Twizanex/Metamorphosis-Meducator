<?php $text = get_plugin_setting('searchboxtext','vazco_mainpage')?>
<div class="index_box">
	<h2><?php echo elgg_echo('custom:search');?></h2>
	<div class="search_listing">
	<?php if ($text){?>
		<p class="search_box"><?php echo $text;?></p>
	<?php }?>
	<form id="searchform" action="<?php echo $vars['url']; ?>search/" method="get">
		<input type="text" size="21" name="tag" value="Search" onclick="if (this.value=='Search') { this.value='' }" class="search_box_input" />
		<input type="submit" value="Go" class="search_box_button" />
	</form>
	</div>
</div>