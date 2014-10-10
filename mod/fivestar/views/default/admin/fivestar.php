<?php

	global $CONFIG;
	
	$tab = $vars['tab'];
	
	switch($tab) {
		case 'settings':
			$settingsselect = 'class="selected"';
			break;
	}
	
?>
<div class="contentWrapper">
	<div id="elgg_horizontal_tabbed_nav">
		<ul>
			<li <?php echo $settingsselect; ?>><a href="<?php echo $CONFIG->wwwroot . 'mod/fivestar/admin.php?tab=settings'; ?>"><?php echo elgg_echo('fivestar:settings'); ?></a></li>
		</ul>
	</div>
<?php
	switch($tab) {
		case 'settings':
			echo elgg_view("fivestar/settings");
			break;
	}
?>
</div>
