<?php
	/**
	 * Provide a way of setting your email
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
		
		
		$query = "SELECT * FROM {$CONFIG->dbprefix}_content_item_discrimination WHERE guid = \"".$selected_item."\" and is_content_item = \"1\"";
		 
		$result = get_data($query);
?>
	<h3><?php echo elgg_echo('email:settings'); ?></h3>
	<p>
		<?php echo "Email Address"; ?>:
		<?php

		 echo elgg_view('input/email',array('internalname' => 'email', 'value' => $user->email));
		
		?> 
	</p>

<?php } ?>