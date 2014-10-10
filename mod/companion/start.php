<?php
global $CONFIG;
 
register_action("companion/save", false, $CONFIG->pluginspath . "companion/actions/save.php");
register_action("companion/delete", false, $CONFIG->pluginspath . "companion/actions/delete.php");
	register_action("companion/edit",false,$CONFIG->pluginspath . "companion/actions/edit.php");
	if (get_context() == "companion") {
add_submenu_item("View all Collections of Educational Resources",$CONFIG->wwwroot."mod/companion/");
add_submenu_item("Create new Collection of Educational Resources",$CONFIG->wwwroot."mod/companion/add.php");
}
?>