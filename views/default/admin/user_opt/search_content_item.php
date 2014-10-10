<?php
	/**
	 * Elgg user search box.
	 * 
	 * @package mEducator
	 * @subpackage Core mEducator
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author mEducator
	 * @copyright mEducator 2008-2009
	 * @link http://www.mEducator.org
	 */
?>
<div id="search-box">
	<form action="<?php echo $vars['url']; ?>search/" method="get">
	<b><?php echo elgg_echo('admin:user:label:searchitem'); ?></b>
	<b><?php echo $vars['url']; ?>search/ </b> 
	<?php

		echo elgg_view('input/text',array('internalname' => 'tag'));
	
	?>
	<input type="hidden" name="object" value="user" />
	<input type="submit" name="<?php echo elgg_echo('admin:user:label:seachbutton'); ?>" 
		value="<?php echo elgg_echo('admin:user:label:seachbutton'); ?>" />
	</form> 
</div>
