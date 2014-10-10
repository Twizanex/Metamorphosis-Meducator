<?php

	/**
	 * Elgg pageshell
	 * The standard HTML page shell that everything else fits into
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 * 
	 * @uses $vars['config'] The site configuration settings, imported
	 * @uses $vars['title'] The page title
	 * @uses $vars['body'] The main content of the page
	 * @uses $vars['messages'] A 2d array of various message registers, passed from system_messages()
	 */

	// Set the content type
	header("Content-type: text/html; charset=UTF-8");

	// Set title
		if (empty($vars['title'])) {
			$title = $vars['config']->sitename;
		} else if (empty($vars['config']->sitename)) {
			$title = $vars['title'];
		} else {
			$title = $vars['config']->sitename . ": " . $vars['title'];
		}

?>

<?php echo elgg_view('page_elements/header', $vars); ?>
<script type="text/javascript" src="<?php echo $vars['url']; ?>mod/fisheye_menu/views/default/js/fisheye.js"></script>
	
<?php //echo elgg_view('page_elements/elgg_topbar', $vars); ?>
<?php echo elgg_view('page_elements/header_contents', $vars); ?>

<!-- main contents -->
    
<!-- display any system messages -->
<?php echo elgg_view('messages/list', array('object' => $vars['sysmessages'])); ?>


<!-- canvas -->
<div id="layout_canvas">
	<div class="content<?php if (get_context() == "main"){?> mainpage_content<?php }?>">
<center>
<a href="http://www.meducator.net"><img src="http://www.meducator.net//files/banners/b1_160x33_wb.jpg" border="0" alt="mEducator" /></a>
<?php if (isadminloggedin()) {

echo "\n"; 
echo "<div id=\"fisheye\">\n"; 
echo "	<ul id=\"fisheye_menu\">\n"; 
echo "  \n"; 
echo "  <li><a class=\"dock-item\" href=\"".$vars['url']."pg/profile/".$vars['user']->username."\"><img src=\"".$vars['url']."_graphics/profile.png\" alt=\"Profile\" /><span >"."My Profile"."</span></a> </li>\n"; 
echo "  <li><a class=\"dock-item\" href=\"".$vars['url']."mod/content_item/add.php"."\"><img src=\"".$vars['url']."_graphics/book.png\" alt=\"Educational Resources\" /><span >"."Manage Educational Resources"."</span></a> </li>\n"; 
echo "  <li><a class=\"dock-item\" href=\"".$vars['url']."pg/members/"."\"><img src=\"".$vars['url']."_graphics/search.png\" alt=\"Metadata Search\" /><span >"."Metadata Search"."</span></a> </li>\n";
echo "  <li><a class=\"dock-item\" href=\"".$vars['url']."mod/content_item/show_map_content_item.php"."\"><img src=\"".$vars['url']."_graphics/globe.png\" alt=\"EGeolocation of Resources\" /><span >"."Geolocation of Resources"."</span></a> </li>\n"; 
echo "  <li><a class=\"dock-item\" href=\"".$vars['url']."pg/dashboard\"><img src=\"".$vars['url']."_graphics/info.png\" alt=\"Dashboard\" /><span >"."Dashboard"."</span></a> </li>\n"; 
echo "  <li><a class=\"dock-item\" href=\"".$vars['url']."mod/publications/"."\"><img src=\"".$vars['url']."_graphics/pub.png\" alt=\"Publications\" /><span >"."Scientific Publications"."</span></a> </li>\n"; 
echo "  <li><a class=\"dock-item\" href=\"".$vars['url']."pg/blog/".$vars['user']->username."\"><img src=\"".$vars['url']."_graphics/blog.png\" alt=\"Blogs\" /><span >"."Blogs"."</span></a> </li>\n"; 
echo "  <li><a class=\"dock-item\" href=\"".$vars['url']."pg/bookmarks/".$vars['user']->username."/items/"."\"><img src=\"".$vars['url']."_graphics/favorite.png\" alt=\"Bookmarks\" /><span >"."Bookmarks"."</span></a> </li>\n"; 
echo "  <li><a class=\"dock-item\" href=\"".$vars['url']."pg/file/".$vars['user']->username."\"><img src=\"".$vars['url']."_graphics/files.png\" alt=\"Files\" /><span >"."Files"."</span></a> </li>\n"; 
echo "  <li><a class=\"dock-item\" href=\"".$vars['url']."pg/groups/world/ \"><img src=\"".$vars['url']."_graphics/capture.png\" alt=\"Groups\" /><span >"."Groups"."</span></a> </li>\n"; 
echo "  <li><a class=\"dock-item\" href=\"".$vars['url']."mod/companion/"."\"><img src=\"".$vars['url']."_graphics/col.png\" alt=\"Collections\" /><span >"."Collections of Resources"."</span></a> </li>\n"; 

echo "  <li><a class=\"dock-item\" href=\"".$vars['url']."pg/messages/".$vars['user']->username."\"><img src=\"".$vars['url']."_graphics/mail.png\" alt=\"Messages\" /><span >"."Private Messages"."</span></a> </li>\n";
echo "  <li><a class=\"dock-item\" href=\"".$vars['url']."action/logout \"><img src=\"".$vars['url']."_graphics/login.png\" alt=\"Logout\" /><span >"."Logout"."</span></a> </li>\n"; 

if(issuperadminloggedin())
echo "  <li><a class=\"dock-item\" href=\"".$vars['url']."pg/admin/plugins \"><img src=\"".$vars['url']."_graphics/database.png\" alt=\"Administration\" /><span >"."SuperAdmin Menus"."</span></a> </li>\n"; 
echo "\n"; 
echo "			</ul>\n"; 
echo "</div>\n"; 
 }
else if (!isloggedin()){
echo "<div id=\"cu3er-container\">\n"; 
echo "    <a href=\"http://www.adobe.com/go/getflashplayer\">\n"; 
echo "        <img src=\"http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif\" alt=\"Get Adobe Flash player\" />\n"; 
echo "    </a>\n"; 
echo "</div>\n";
} 
echo "<hr width=100%><!-- horizontal rule change the width here  -->\n"; 
echo "\n"; 

 
 
?>
</center>
<?php echo $vars['body']; ?>

<div class="clearfloat"></div>
</div><!-- /#layout_canvas -->



<!-- footer -->
<?php echo elgg_view('page_elements/footer', $vars); ?>
