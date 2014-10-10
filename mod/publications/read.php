<?php
        /**
         * @package Elgg
         * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
         * @author Roger Curry, Grid Research Centre [curry@cpsc.ucalgary.ca]
         * @author Tingxi Tan, Grid Research Centre [txtan@cpsc.ucalgary.ca]
         * @link http://grc.ucalgary.ca/
         */


	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

	$post = (int) get_input('publicationpost');

	if ($publicationpost = get_entity($post)) {
		$comments = $publicationpost->getAnnotations('comments');
		set_page_owner($_SESSION['guid']);
		$page_owner = $_SESSION['user'];
		$area2 = elgg_view_entity($publicationpost, true);
		$title = sprintf(elgg_echo("publication:posttitle"),$page_owner->name,$publicationpost->title);
		$body = elgg_view_layout("two_column_left_sidebar", '', $area1 . $area2);
	} else {
		$body = elgg_view("publication/notfound");
		$title = elgg_echo("publication:notfound");
	}
		
	page_draw($title,$body);
		
?>
