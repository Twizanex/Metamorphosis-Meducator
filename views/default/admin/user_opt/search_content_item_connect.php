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
	 
	 
	 $selected_item = $_POST['cont_it_id'];
?>
<div id="search-box">
	 
	<form action="../content_item/select_content_items.php" method="get">
	
	<b><?php echo elgg_echo('content_item_search'); ?></b>
	 
	<?php

		echo elgg_view('input/text',array('internalname' => 'tag'));
	
	?>
	<input type="hidden" name="object" value="content_item" />
	<input type="hidden" name="cont_it_id" value="<?php if ($_POST['cont_it_id'] == null)
					{
						echo $_GET['cont_it_id'];
					}
					else{echo $_POST['cont_it_id'];} ?>" />
	<input type="submit" name="<?php echo elgg_echo('admin:user:label:seachbutton'); ?>" 
		value="<?php echo elgg_echo('admin:user:label:seachbutton'); ?>" />
		
		
					
	</form> 
</div>
