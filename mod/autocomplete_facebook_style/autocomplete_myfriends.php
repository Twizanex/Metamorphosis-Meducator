<?php
/**
 *	Autocomplete Facebook Style Plugin
 *	@package autocomplete facebook style
 *	@author Liran Tal <liran.tal@gmail.com>
 *	@license GNU General Public License (GPL) version 2
 *	@copyright (c) Liran Tal of Enginx 2009
 *	@link http://www.enginx.com
 **/


require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

global $CONFIG;

$user_guid = $_SESSION['user']->getGUID();
$user = get_user($user_guid);
$friends = $user->getFriends("", 1000, $offset = 0);

echo elgg_view('autocomplete/autocomplete_entities', array('entities' => $friends));

?>
