<?php
	/**
	 * Provide a way of setting your password
	 * 
	 * @package Elgg
	 * @subpackage Core

	 * @author Curverider Ltd

	 * @link http://elgg.org/
	 */

	$user = page_owner_entity();
	
	if ($user) {
			if ($user1 = page_owner()) {
			$selected_item = $user1;
		}
		
		//echo "Selected"+$selected_item;
		
		$query = "SELECT * FROM {$CONFIG->dbprefix}_content_item_discrimination WHERE guid = \"".$selected_item."\" and is_content_item = \"1\"";
		 
		$result = get_data($query);
?>
	<h3><?php if($result == null) echo elgg_echo('user:set:password'); ?></h3>
	<p>
		<?php if($result == null) echo elgg_echo('user:password:label'); ?> 
		<?php
			if($result == null) echo elgg_view('input/password',array('internalname' => 'password'));
		?></p><p>
		<?php if($result == null) echo elgg_echo('user:password2:label'); ?> <?php
			if($result == null) echo elgg_view('input/password',array('internalname' => 'password2'));
		?>
	</p>

<?php } ?>