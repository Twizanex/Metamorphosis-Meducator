<?php
include_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

$body = //"HELLO";
 list_entities('object','companion',0,10,false);


    $body = elgg_view_layout('two_column_left_sidebar', '', $body);
 
page_draw("Collections of Educational Resources",$body);
?>