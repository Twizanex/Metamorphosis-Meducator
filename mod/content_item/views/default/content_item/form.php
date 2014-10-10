

<div class="contentWrapper">
<?php echo elgg_view('account/forms/useradd_content_item', array('show_admin'=>true)); 	//echo "<b>Please make sure the name you choose is descriptive. Could be the Resource's title or something similar</b>";?>
</div>
 
 <?php
//add submenu options
	
    if (get_context() == "content_item") {
	add_submenu_item(elgg_echo('content_item:create'), $CONFIG->wwwroot . "mod/content_item/add.php");
	add_submenu_item(elgg_echo('content_item:view'), $CONFIG->wwwroot . "mod/content_item/view_all_content_items.php");
	add_submenu_item(elgg_echo('content_item:edit'), $CONFIG->wwwroot . "mod/content_item/edit_content_items.php");
	add_submenu_item(elgg_echo('content_item:connect'), $CONFIG->wwwroot . "mod/content_item/connect_content_items.php");
    }

	
	?>
