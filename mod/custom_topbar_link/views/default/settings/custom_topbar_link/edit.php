<p>
	<?php echo elgg_echo('customtopbarlink:adminsettings'); ?>
	<ul><li><?php echo elgg_echo('customtopbarlink:admintext'); ?> <input type="text" value="<?php echo $vars['entity']->linktext; ?>" name="params[linktext]" /></li>
	<li><?php echo elgg_echo('customtopbarlink:adminurl'); ?> <input type="text" value="<?php echo $vars['entity']->linkurl; ?>" name="params[linkurl]" /></li></ul>
</p>
