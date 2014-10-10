<?php
require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
 
$limit = get_input('count',16);
$mode = get_input('type',10);
switch ($mode){
	case "m_latest":
		//$members = get_entities_from_metadata('icontime', '', 'user', '', 0, 10);
		$members = get_entities("user","",0,'',$limit,0);
		break;
	case "m_views":
		$members = get_entities_by_relationship_count('friend', true,'','',0,$limit);
		break;
	case "m_com":
		$members = find_active_users(600,$limit,$offset);
		break;
}

if(isset($members)) {
//display member avatars
	foreach($members as $member){
		echo "<div class=\"index_members\">";
		echo elgg_view("profile/icon",array('entity' => $member, 'size' => 'small'));
		echo "</div>";
	}
}
?>
<div class="clearfloat"/>