<div id="textarea " style="display:none" >

<?php 
echo "mEducator";


global $CONFIG;
$AR2 = "";
admin_gatekeeper();

$query = "SELECT name, username, email FROM {$CONFIG->dbprefix}users_entity"; 
$result = get_data($query);

//Page Navigation
{
	$user_per_page = 30;  //users diplayed per page
	
	$total_users = count($result);
	$AR2 .= elgg_echo("userclist:totalpages")." = ".$total_users;
	$page = get_input("page");
	if($page=="")
		$page = 1;
	$total_pages = ceil(floatval($total_users)/floatval($user_per_page));
	if($page > 1)
		$AR2 .= "<a href=\"".$vars['url']."pg/user_contact_list/page/".($page - 1)."\">".elgg_echo("userclist:lastpage")."</a>";
	$AR2 .= " | ".elgg_echo("userclist:onpage1")." ".$page." ".elgg_echo("userclist:onpage2")." ".$total_pages." ".elgg_echo("userclist:onpage3")." | ";
	if(($total_pages - $page) > 0)
		$AR2 .= "<a href=\"".$vars['url']."pg/user_contact_list/page/".($page + 1)."\">".elgg_echo("userclist:nextpage")."</a>";
	$AR2 .= "<br />";
}

$AR2 .= "<table class=\"uv_list\"><tr><td><b>Nickname</b></td><td><b>Username</b></td><td><b>Email</b></td></tr>";

$offset = ($page-1)*$user_per_page;

for($i=$offset;$i<$offset+$user_per_page;$i++)
{
	$row = $result[$i];
	$AR2 .= "<tr>";
	$AR2 .= "<td><a href=\"{$CONFIG->wwwroot}pg/profile/{$row->username}\">".$row->name."</a></td>";
	$AR2 .= "<td>".$row->username."</td>";
	$AR2 .= "<td>".$row->email."</td>";
	$AR2 .= "</tr>";
}

$AR2 .= "</table>";
echo $AR2;
//	echo elgg_echo($AR2);
	//echo elgg_view('', array('action' => "{$vars['url']}action/useradd_content_item", 'body' => $form_body));


 ?>



<?php 
//add submenu options
    if (get_context() == "content_item") {
	add_submenu_item(elgg_echo('content_item:create'), $CONFIG->wwwroot . "mod/content_item/add.php");
	add_submenu_item(elgg_echo('content_item:view'), $CONFIG->wwwroot . "mod/content_item/add.php");
	add_submenu_item(elgg_echo('content_item:connect'), $CONFIG->wwwroot . "mod/content_item/add.php");
	
    }
	
	?>
</div>