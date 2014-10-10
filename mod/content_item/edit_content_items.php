<?php
    // Load Elgg engine
    include_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
 
    // make sure only logged in users can see this page	
    gatekeeper();
 
    // set the title
    $title = "Edit Educational Resource";
 
    // start building the main column of the page
    $area2 = elgg_view_title($title);
 
    // Add the form to this section
	
	global $CONFIG;
$AR2 = "";
//admin_gatekeeper();

//$query = "SELECT name, username FROM {$CONFIG->dbprefix}users_entity join {$CONFIG->dbprefix}_content_item_discrimination on {$CONFIG->dbprefix}users_entity.guid = {$CONFIG->dbprefix}_content_item_discrimination.guid"; 
//$query = "SELECT * FROM {$CONFIG->dbprefix}users_entity join {$CONFIG->dbprefix}_content_item_discrimination on {$CONFIG->dbprefix}users_entity.guid = {$CONFIG->dbprefix}_content_item_discrimination.guid and {$CONFIG->dbprefix}_content_item_discrimination.creator_guid = \"".$_SESSION['id']."\""; 
	if(issuperadminloggedin())
	{
		$query = "SELECT name, username FROM {$CONFIG->dbprefix}users_entity join {$CONFIG->dbprefix}_content_item_discrimination on {$CONFIG->dbprefix}users_entity.guid = {$CONFIG->dbprefix}_content_item_discrimination.guid"; 
	}
	else{
		$query = "SELECT * FROM {$CONFIG->dbprefix}users_entity join {$CONFIG->dbprefix}_content_item_discrimination on {$CONFIG->dbprefix}users_entity.guid = {$CONFIG->dbprefix}_content_item_discrimination.guid and {$CONFIG->dbprefix}_content_item_discrimination.creator_guid = \"".$_SESSION['id']."\""; 
	}
	
	
$result = get_data($query);

//Page Navigation
{
	//$user_per_page = 30;  //users diplayed per page
	
	$total_users = count($result);
	if ($result!=NULL)
	$AR2 .= "You have created ".$total_users." Educational Resources";
	else
	$AR2 .="You haven't created an Educational Resource";
	//$page = get_input("page");
	//if($page=="")
		//$page = 1;
	//$total_pages = ceil(floatval($total_users)/floatval($user_per_page));
	//if($page > 1)
	//	$AR2 .= "<a href=\"".$vars['url']."pg/user_contact_list/page/".($page - 1)."\">".elgg_echo("userclist:lastpage")."</a>";
	//$AR2 .= " | ".elgg_echo("userclist:onpage1")." ".$page." ".elgg_echo("userclist:onpage2")." ".$total_pages." ".elgg_echo("userclist:onpage3")." | ";
	//if(($total_pages - $page) > 0)
	//	$AR2 .= "<a href=\"".$vars['url']."pg/user_contact_list/page/".($page + 1)."\">".elgg_echo("userclist:nextpage")."</a>";
	//$AR2 .= "<br />";
}

$AR2 .= "<table class=\"uv_list\">";
if ($result!=NULL)
$AR2 .="<tr><td><b>Name</b></td></tr>";

//$offset = ($page-1)*$user_per_page;
if ($result!=NULL)
for($i=0;$i<$total_users;$i++)
{
	$row = $result[$i];
	$AR2 .= "<tr>";
	$j =$i + 1;
	$AR2 .= "<td>".$j.")  </td>";
	$AR2 .= "<td><a href=\"{$CONFIG->wwwroot}pg/profile/".$row->username."/edit/\">".$row->name."</a></td>";
	
	$AR2 .= "</tr>";
}

$AR2 .= "</table>";
$area2 .= "<div class=\"contentWrapper\">";
    $area2 .= $AR2;
	//$area2 .= $ST;
	//$area2 .= elgg_view("admin/user_opt/search");
$area2 .= "</div>";	
    // layout the page
	 $body =elgg_view_layout('two_column_left_sidebar', '', $area2);
    //$body = elgg_view_layout('one_column', $area2);
 
 	
    // draw the page
    page_draw($title, $body);
	
?>

