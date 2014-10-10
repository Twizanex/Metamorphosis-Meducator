<?php

	/**
	 * Elgg header contents
	 * This file holds the header output that a user will see
	 * 
	 * @package Elgg
	 * @subpackage Core

	 * @author Curverider Ltd

	 * @link http://elgg.org/
	 **/
	 
?>



<?php 
if(!isloggedin()) { ?>
<div id="page_container">
<div id="page_wrapper">
	<!-- display the page title -->
   <table cellpadding="0" cellspacing="0" border="0" width="100%">
        <tr>
            <td class="Logo_Repeater" align="center" valign="top">
                <table cellpadding="0" cellspacing="0" border="0" width="986px">
                    <tr>
                        <td class="Logo_Top" nowrap="nowrap" />
                        <td class="Logo_Text" nowrap="nowrap" onclick="window.open('<?php echo $vars['url']; ?>','_self');" onmouseover="this.style.cursor='pointer';" />
                        <td class="Logo_Separator" nowrap="nowrap" />
                        <td class="Logo_SmallText" nowrap="nowrap" align="left">
                            <br /> <br />
                            a Semantic social environment
                            <br />
                             to share educational resources
                           
                            
                        </td>
                  <td class="Logo_Repeater_Blank" nowrap="nowrap" >
						<br /> <br />
						<?php echo (!isset($CONFIG->disable_registration) || !($CONFIG->disable_registration)) ? "<a href=\"{$vars['url']}account/register.php\">" . elgg_echo('register') . "</a> | " : "";
echo "<a href=\"{$vars['url']}account/forgotten_password.php\">" . elgg_echo('user:password:lost') . "</a></p>"; 
						?>
						</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td class="Banner_Repeater" align="center" valign="top">
                <table cellpadding="0" cellspacing="0" border="0" width="986px">
                    <tr>
                        <td>
                            <table cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td class="Logo_Bottom" />
                                    <td />
                                </tr>
                                <tr>
								
                                    <td class="Video_Icon"><a  href="http://metamorphosis.med.duth.gr/mod/content_item/about.php" title="About MetaMorphosis+"><img src="<?php echo $vars['url']; ?>_graphics/video_Icon.jpg" /></a> </td>
                                    <td class="Video_Text" />
                                </tr>
                                <tr>
                                    <td class="Presentation_Icon"> <a href="http://metamorphosis.med.duth.gr/mod/content_item/deliv.php" title="Documentation Related to this Environment"><img src="<?php echo $vars['url']; ?>_graphics/presentation_Icon.jpg" /></a> </td>
                                    <td class="Presentation_Text"  />
                                </tr>
                                <tr>
                                    <td colspan="2" class="Banner_BottomRepeater" />
                                </tr>
                            </table>
                        </td>
                        <td class="BannerImage" />
                        <td>
                            <table cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td id="LoginRegisterModule" class="LoginRegister">
                       <?php 	global $CONFIG;
	
	$form_body = "<p class=\"loginbox\"><label>" . elgg_echo('username') . "<br />" . elgg_view('input/text', array('internalname' => 'username', 'class' => 'login-textarea')) . "</label>";
//	$form_body .= "<br />";
	$form_body .= "<label>" . elgg_echo('password') . "<br />" . elgg_view('input/password', array('internalname' => 'password', 'class' => 'login-textarea')) . "</label><br />";
	$form_body .= elgg_view('input/submit', array('value' => elgg_echo('login'))) . "<label>&nbsp&nbsp&nbsp <input type=\"checkbox\" name=\"persistent\" value=\"true\" />".elgg_echo('user:persistent')."</label></p>";
	$form_body .= "<p class=\"loginbox\">";
	$form_body .= "";
	$form_body .= ""; 
	
	//<input name=\"username\" type=\"text\" class="general-textarea" /></label>
	
	$login_url = $vars['url'];
	if ((isset($CONFIG->https_login)) && ($CONFIG->https_login))
		$login_url = str_replace("http", "https", $vars['url']);
?>
	
<div id="login-box">

		<?php 
			echo elgg_view('input/form', array('body' => $form_body, 'action' => "{$login_url}action/login"));
		?>
		</div>


                                    </td>
                                </tr>
                                <tr>
                                    <td class="Banner_BottomRepeater" />
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
	</table>
<?php }
else {  ?>
<div id="page_container1">
<div id="page_wrapper">
    <table cellpadding="0" cellspacing="0" border="0" width="100%">
        <tr>
            <td class="Logo_Repeater" align="center" valign="top">
                <table cellpadding="0" cellspacing="0" border="0" width="986px">
                    <tr>
                        <td class="Logo_Top" nowrap="nowrap" />
                        <td class="Logo_Text" nowrap="nowrap" onclick="window.open('<?php echo $vars['url']; ?>','_self');" onmouseover="this.style.cursor='pointer';" />
                        <td class="Logo_Separator" nowrap="nowrap" />
                        <td class="Logo_SmallText" nowrap="nowrap" align="left">
	<?php	 /*				<div id="google_translate_element"></div><script> 
function googleTranslateElementInit() {
  new google.translate.TranslateElement({
    pageLanguage: 'en'
  }, 'google_translate_element');
}
</script><script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script> */?>
                            <br />
							<br />
                            a Semantic social environment
                            <br />
                            to share educational resources
                         
                        </td>
                        <td class="Logo_Repeater_Blank" nowrap="nowrap">
						<?php echo "Welcome, ".$vars['user']->name; 
							echo "<br />";
							echo "<a href=\"".$vars['url']."action/logout \"><img src=\"".$vars['url']."_graphics/log.jpg\" alt=\"Logout\" /></a>";
						
						
						?>
						</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td class="Inner_Banner_Repeater" align="center" valign="top">
                <table cellpadding="0" cellspacing="0" border="0" width="986px">
                    <tr>
                        <td class="FishEye_Background">
  <br /> <br />        <?php             //     [FISH EYE MENU]
		  echo "\n"; 
echo "<div id=\"fisheye\">\n"; 
echo "	<ul id=\"fisheye_menu\">\n"; 
echo "  \n"; 
echo "  <li><a class=\"dock-item\" href=\"".$vars['url']."pg/profile/".$vars['user']->username."\"><img src=\"".$vars['url']."_graphics/profile.png\" alt=\"Profile\" /><span >"."My Profile"."</span></a> </li>\n"; 
echo "  <li><a class=\"dock-item\" href=\"".$vars['url']."mod/content_item/add.php"."\"><img src=\"".$vars['url']."_graphics/book.png\" alt=\"Educational Resources\" /><span >"."Manage Educational Resources"."</span></a> </li>\n"; 
//echo "<li><font color=\"white\" ><b>Search <br />triplestore:</b> </font></li>";
echo "  <li><a class=\"dock-item\" href=\"".$vars['url']."mod/mmsearch/"."\"><img src=\"".$vars['url']."_graphics/Dashboard.png\" alt=\"MMSEARCH\" /><span >"."Basic Semantic Search"."</span></a> </li>\n"; 
//echo "<li><font color=\"white\" ><b>Distributed <br /> Search:</b> </font></li>";
echo "  <li><a class=\"dock-item\" href=\"".$vars['url']."mod/mmdsearch/"."\"><img src=\"".$vars['url']."_graphics/globe.png\" alt=\"MMSEARCH\" /><span >"."Distributed Semantic Search"."</span></a> </li>\n"; 
echo "  <li><a class=\"dock-item\" href=\"".$vars['url']."pg/members/"."\"><img src=\"".$vars['url']."_graphics/search.png\" alt=\"Exploratory Search\" /><span >"."Exploratory Search"."</span></a> </li>\n";
echo "  <li><a class=\"dock-item\" href=\"".$vars['url']."pg/dashboard\"><img src=\"".$vars['url']."_graphics/info.png\" alt=\"Dashboard\" /><span >"."Dashboard"."</span></a> </li>\n"; 
echo "  <li><a class=\"dock-item\" href=\"".$vars['url']."mod/publications/everyone.php"."\"><img src=\"".$vars['url']."_graphics/pub.png\" alt=\"Publications\" /><span >"."Scientific Publications"."</span></a> </li>\n"; 
echo "  <li><a class=\"dock-item\" href=\"".$vars['url']."pg/bookmarks/".$vars['user']->username."/items/"."\"><img src=\"".$vars['url']."_graphics/favorite.png\" alt=\"Bookmarks\" /><span >"."Bookmarks"."</span></a> </li>\n"; 
echo "  <li><a class=\"dock-item\" href=\"".$vars['url']."pg/file/".$vars['user']->username."\"><img src=\"".$vars['url']."_graphics/files.png\" alt=\"Files\" /><span >"."Files"."</span></a> </li>\n"; 
echo "  <li><a class=\"dock-item\" href=\"".$vars['url']."pg/groups/world/ \"><img src=\"".$vars['url']."_graphics/capture.png\" alt=\"Groups\" /><span >"."Groups"."</span></a> </li>\n"; 
echo "  <li><a class=\"dock-item\" href=\"".$vars['url']."mod/companion/"."\"><img src=\"".$vars['url']."_graphics/col.png\" alt=\"Collections of Resources\" /><span >"."Collections of Resources"."</span></a> </li>\n"; 

echo "  <li><a class=\"dock-item\" href=\"".$vars['url']."pg/messages/".$vars['user']->username."\"><img src=\"".$vars['url']."_graphics/mail.png\" alt=\"Messages\" /><span >"."Private Messages"."</span></a> </li>\n";


if(issuperadminloggedin())
echo "  <li><a class=\"dock-item\" href=\"".$vars['url']."pg/admin/plugins \"><img src=\"".$vars['url']."_graphics/database.png\" alt=\"Administration\" /><span >"."SuperAdmin Menus"."</span></a> </li>\n"; 
echo "\n"; 
echo "			</ul>\n"; 
echo "</div>\n"; 
 ?>
                        </td>
                    </tr>
                </table>
				<?php } ?>