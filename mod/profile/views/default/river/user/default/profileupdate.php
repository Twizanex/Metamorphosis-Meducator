<?php

	$performed_by = get_entity($vars['item']->object_guid); // $statement->getSubject();
	//$performed_by = get_entity($vars['item']->guid);
	$url = "<a href=\"{$performed_by->getURL()}\">{$performed_by->name}</a>";
	$string = sprintf(elgg_echo("profile:river:update"),$url);


	
?>

<?php echo $string; ?>