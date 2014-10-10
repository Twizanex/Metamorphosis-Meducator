<?php

	/**
	 * Elgg Sitemap plugin
	 * 
	 * @package
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Matthias Sutter email@matthias-sutter.de
	 * @copyright CubeYoo.de
	 * @link http://cubeyoo.de
         * @ package modification 22-11-2009
         * @ author Carlos Luis Sánchez Bocanegra carlosl.sanchez@gmail.com
         * @ link http://redes.epesca.org
	 * @ Modify: adding support for a better i18n givving all resource to a languages directory.
	 */
?>

<div class="contentWrapper">

    <div id="left">

    <div id="box">	
<h3><a href="<?php echo $vars['url']; ?>"><?php echo(elgg_echo("sitemap:start")); ?></a></h3>
<?php if (is_plugin_enabled('externalpages')) { ?>
<ul class="menu">
<li><a href="<?php echo $vars['url']; ?>pg/expages/read/About/"><?php echo(elgg_echo("sitemap:about")); ?></a></li>
<li><a href="<?php echo $vars['url']; ?>pg/expages/read/Terms/"><?php echo(elgg_echo("sitemap:terms")); ?></a></li>
<li><a href="<?php echo $vars['url']; ?>pg/expages/read/Privacy/"><?php echo(elgg_echo("sitemap:privacy")); ?></a></li>
<?php }; ?>
<?php if (!isloggedin()) { ?>
<li><a href="<?php echo $vars['url']; ?>account/register.php"><?php echo(elgg_echo("sitemap:register")); ?></a></li>
<li><a href="<?php echo $vars['url']; ?>account/forgotten_password.php"><?php echo(elgg_echo("sitemap:lostpassword")); ?></a></li>
<?php }; ?>
</ul>
</div>

<?php if (isloggedin()) { ?>
    <div id="box">
<h3><?php echo(" Manage Educational Resources"); ?></h3>
<ul class="menu">
<li><a href="<?php echo $vars['url']; ?>/mod/content_item/add.php"><?php echo("Create New"); ?></a></li>
<li><a href="<?php echo $vars['url']; ?>mod/content_item/view_all_content_items.php"><?php echo("Show Graph"); ?></a></li>
<li><a href="<?php echo $vars['url']; ?>mod/content_item/edit_content_items.php"><?php echo("Edit your Resources"); ?></a></li>
</ul>
    </div>
<?php }; ?>


<?php if (isloggedin()) { ?>
    <div id="box">
<h3><?php echo("Search Functions"); ?></h3>
<ul class="menu">
<li><a href="<?php echo $vars['url']; ?>/mod/mmsearch/"><?php echo("Basic Semantic Search"); ?></a></li>
<li><a href="<?php echo $vars['url']; ?>mod/mmdsearch/"><?php echo("Distributed Semantic Search"); ?></a></li>
<li><a href="<?php echo $vars['url']; ?>pg/members"><?php echo("Exploratory Search"); ?></a></li>
</ul>
    </div>
<?php }; ?>



<?php if (is_plugin_enabled('profile')) { ?>
<?php if (isloggedin()) { ?>
    <div id="box">
<h3><a href="<?php echo $vars['url']; ?>pg/settings/"><?php echo(elgg_echo("sitemap:settings")); ?></a></h3>
<ul class="menu">
<li><a href="<?php echo $vars['url']; ?>pg/settings/statistics/<?php echo $_SESSION['user']->username; ?>/"><?php echo(elgg_echo("sitemap:accountstatistics")); ?></a></li>
<li><a href="<?php echo $vars['url']; ?>pg/settings/plugins/<?php echo $_SESSION['user']->username; ?>"><?php echo(elgg_echo("sitemap:configureyourtools")); ?></a></li>
<li><a href="<?php echo $vars['url']; ?>mod/notifications/"><?php echo(elgg_echo("sitemap:notifications")); ?></a></li>
<?php if (is_plugin_enabled('groups')) { ?>
<li><a href="<?php echo $vars['url']; ?>mod/notifications/groups.php"><?php echo(elgg_echo("sitemap:groupnotifications")); ?></a></li>
<?php }; ?>
</ul>
    </div>
<?php }; ?>
<?php }; ?>


<?php if (is_plugin_enabled('friends')) { ?>
<?php if (isloggedin()) { ?>
    <div id="box">	

<h3><a href="<?php echo $vars['url']; ?>pg/friends/admin<?php echo $_SESSION['user']->username; ?>"><?php echo(elgg_echo("sitemap:friends")); ?></a></h3>
<ul class="menu">
<li><a href="<?php echo $vars['url']; ?>pg/friendsof/<?php echo $_SESSION['user']->username; ?>"><?php echo(elgg_echo("sitemap:friendsof")); ?></a></li>
<li><a href="<?php echo $vars['url']; ?>pg/collections/admin"><?php echo(elgg_echo ("sitemap:collectionsoffriends")); ?></a></li>
<li><a href="<?php echo $vars['url']; ?>pg/collections/add"><?php echo(elgg_echo ("sitemap:Newfriendscollection")); ?></a></li>
<?php if (is_plugin_enabled('invitefriends')) { ?>
<li><a href="<?php echo $vars['url']; ?>mod/invitefriends/"><?php echo(elgg_echo ("sitemap:invitefriends")); ?></a></li>
<?php }; ?>
</ul>
    </div>
<?php }; ?>
<?php }; ?>

<?php if (is_plugin_enabled('messages')) { ?>
<?php if (isloggedin()) { ?>
    <div id="box">	

<h3><a href="<?php echo $vars['url']; ?>pg/messages/<?php echo $_SESSION['user']->username; ?>"><?php echo(elgg_echo("sitemap:messages")); ?></a></h3>
<ul class="menu">
<li><a href="<?php echo $vars['url']; ?>mod/messages/send.php"><?php echo(elgg_echo("sitemap:composeamessage")); ?></a></li>
<li><a href="<?php echo $vars['url']; ?>mod/messages/sent.php"><?php echo(elgg_echo ("sitemap:sentmessages")); ?></a></li>
</ul>
    </div>
<?php }; ?>
<?php }; ?>



<?php if (is_plugin_enabled('riverdashboard')) { ?>
<?php if (isloggedin()) { ?>
    <div id="box">	
<h3><a href="<?php echo $vars['url']; ?>pg/dashboard/"><?php echo(elgg_echo("sitemap:dashboard")); ?></a></h3>
<ul class="menu">
<li><a href="<?php echo $vars['url']; ?>pg/dashboard/?type=&display=friends&content="><?php echo(elgg_echo("sitemap:friendsactivity")); ?></a></li>
<li><a href="<?php echo $vars['url']; ?>pg/dashboard/?type=&display=mine&content="><?php echo(elgg_echo("sitemap:myactivity")); ?></a></li>
</ul>
    </div>
<?php }; ?>
<?php }; ?>

	

<?php if (is_plugin_enabled('blog')) { ?>
    <div id="box">	
<h3><a href="<?php echo $vars['url']; ?>mod/blog/everyone.php"><?php echo(elgg_echo("sitemap:allsiteblogs")); ?></a></h3>

<?php if (isloggedin()) { ?>
<ul class="menu">
<li><a href="<?php echo $vars['url']; ?>pg/blog/<?php echo $_SESSION['user']->username; ?>"><?php echo(elgg_echo("sitemap:yourblog")); ?></a></li>
<li><a href="<?php echo $vars['url']; ?>pg/blog/<?php echo $_SESSION['user']->username; ?>/friends/"><?php echo(elgg_echo("sitemap:friendsblog")); ?></a></li>
<li><a href="<?php echo $vars['url']; ?>pg/blog/<?php echo $_SESSION['user']->username; ?>/new/"><?php echo(elgg_echo("sitemap:writeablogpost")); ?></a></li>
</ul>
<?php }; ?>
    </div>
<?php }; ?>
   

</div>
   

	
<div id="right">

   		<div id="fernglas">
			<img src="<?php echo $vars['url']; ?>mod/sitemap/graphics/sitemap.png" border="0" />
        </div>



<?php if (is_plugin_enabled('groups')) { ?>
    <div id="box">	
<h3><a href="<?php echo $vars['url']; ?>pg/groups/world/"><?php echo(elgg_echo("sitemap:allsitegroups")); ?></a></h3>
<ul class="menu">
<li><a href="<?php echo $vars['url']; ?>pg/groups/world/?filter=pop"><?php echo(elgg_echo("sitemap:populargroups")); ?></a></li>
<li><a href="<?php echo $vars['url']; ?>pg/groups/world/?filter=active"><?php echo(elgg_echo("sitemap:latestgroupdiscussion")); ?></a></li>

<?php if (isloggedin()) { ?>
<li><a href="<?php echo $vars['url']; ?>pg/groups/owned/<?php echo $_SESSION['user']->username; ?>"><?php echo(elgg_echo("sitemap:groupsyouown")); ?></a></li>
<li><a href="<?php echo $vars['url']; ?>pg/groups/member/<?php echo $_SESSION['user']->username; ?>"><?php echo(elgg_echo("sitemap:yourgroups")); ?></a></li>
<li><a href="<?php echo $vars['url']; ?>pg/groups/new/"><?php echo(elgg_echo("sitemap:createanewgroup")); ?></a></li>
<?php }; ?>
</ul>
    </div>

<?php }; ?>



<?php if (is_plugin_enabled('file')) { ?>
    <div id="box">	
<h3><a href="<?php echo $vars['url']; ?>mod/file/world.php"><?php echo(elgg_echo("sitemap:allsitefiles")); ?></a></h3>

<?php if (isloggedin()) { ?>
<ul class="menu">
<li><a href="<?php echo $vars['url']; ?>pg/file/<?php echo $_SESSION['user']->username; ?>"><?php echo(elgg_echo("sitemap:yourfiles")); ?></a></li>
<li><a href="<?php echo $vars['url']; ?>pg/file/<?php echo $_SESSION['user']->username; ?>/friends/"><?php echo(elgg_echo("sitemap:yourfriendsfiles")); ?></a></li>
<li><a href="<?php echo $vars['url']; ?>pg/file/<?php echo $_SESSION['user']->username; ?>/new/"><?php echo(elgg_echo("sitemap:uploadafile")); ?></a></li>
</ul>
<?php }; ?>
    </div>

<?php }; ?>



<?php if (is_plugin_enabled('pages')) { ?>
    <div id="box">	
<h3><a href="<?php echo $vars['url']; ?>mod/pages/world.php"><?php echo(elgg_echo("sitemap:allsitepages")); ?></a></h3>
<ul class="menu">
<li><a href="<?php echo $vars['url']; ?>pg/pages/owned/<?php echo $_SESSION['user']->username; ?>"><?php echo(elgg_echo("sitemap:pagesHome")); ?></a></li>
<?php if (isloggedin()) { ?>
<li><a href="<?php echo $vars['url']; ?>pg/pages/welcome/"><?php echo(elgg_echo("sitemap:editwelcomemessage")); ?></a></li>
<?php }; ?>
</ul>
    </div>

<?php }; ?>



<?php if (is_plugin_enabled('bookmarks')) { ?>
    <div id="box">
<h3><a href="<?php echo $vars['url']; ?>mod/bookmarks/everyone.php"><?php echo(elgg_echo("sitemap:allsitebookmarks")); ?></a></h3>

<?php if (isloggedin()) { ?>
<ul class="menu">
<li><a href="<?php echo $vars['url']; ?>pg/bookmarks/<?php echo $_SESSION['user']->username; ?>/inbox"><?php echo(elgg_echo("sitemap:bookmarksinbox")); ?></a></li>
<li><a href="<?php echo $vars['url']; ?>pg/bookmarks/<?php echo $_SESSION['user']->username; ?>/items"><?php echo(elgg_echo("sitemap:mybookmarkeditems")); ?></a></li>
<li><a href="<?php echo $vars['url']; ?>pg/bookmarks/<?php echo $_SESSION['user']->username; ?>/friends/items"><?php echo(elgg_echo("sitemap:friendsbookmarks")); ?></a></li>
<li><a href="<?php echo $vars['url']; ?>pg/bookmarks/<?php echo $_SESSION['user']->username; ?>/bookmarklet"><?php echo(elgg_echo("sitemap:getbookmarklet")); ?></a></li>
</ul>
<?php }; ?>
    </div>

<?php }; ?>

	


<?php if (is_plugin_enabled('members')) { ?>
<?php if (isloggedin()) { ?>

    <div id="box">
<h3><a href="<?php echo $vars['url']; ?>mod/members/index.php"><?php echo(elgg_echo("sitemap:allsitemembers")); ?></a></h3>
<ul class="menu">
<li><a href="<?php echo $vars['url']; ?>mod/members/index.php?filter=pop"><?php echo(elgg_echo("sitemap:popularmembers")); ?></a></li>
<li><a href="<?php echo $vars['url']; ?>mod/members/index.php?filter=active"><?php echo(elgg_echo("sitemap:activemembers")); ?></a></li>
</ul>
    </div>
<?php }; ?>
<?php }; ?>



<?php if (is_plugin_enabled('tidypics')) { ?>
    <div id="box">
<h3><a href="<?php echo $vars['url']; ?>pg/photos/world/"><?php echo(elgg_echo("sitemap:allsitephotoalbums")); ?></a></h3>


<ul class="menu">

<?php if (isloggedin()) { ?>
<div id="line">
<li><a href="<?php echo $vars['url']; ?>pg/photos/owned/<?php echo $_SESSION['user']->username; ?>"><?php echo(elgg_echo("sitemap:yourphotoalbums")); ?></a></li>
<li><a href="<?php echo $vars['url']; ?>pg/photos/friends/<?php echo $_SESSION['user']->username; ?>"><?php echo(elgg_echo("sitemap:yourfriendsphotoalbums")); ?></a></li>
<li><a href="<?php echo $vars['url']; ?>pg/photos/yourmostviewed"><?php echo(elgg_echo("sitemap:yourmostviewedphotoalbums")); ?></a></li>
<li><a href="<?php echo $vars['url']; ?>pg/photos/mostrecent/<?php echo $_SESSION['user']->username; ?>"><?php echo(elgg_echo("sitemap:yourmostrecentphotoalbums")); ?></a></li>
<li><a href="<?php echo $vars['url']; ?>pg/photos/new/<?php echo $_SESSION['user']->username; ?>"><?php echo(elgg_echo("sitemap:createnewphotoalbum")); ?></a></li>
</div>

<?php }; ?>


<li><a href="<?php echo $vars['url']; ?>pg/photos/mostrecent"><?php echo(elgg_echo("sitemap:mostrecentimages")); ?></a></li>
<li><a href="<?php echo $vars['url']; ?>pg/photos/mostviewed"><?php echo(elgg_echo("sitemap:Mostviewedimages")); ?></a></li>
<li><a href="<?php echo $vars['url']; ?>pg/photos/recentlyviewed"><?php echo(elgg_echo("sitemap:recentlyviewedimages")); ?></a></li>
<li><a href="<?php echo $vars['url']; ?>pg/photos/recentlycommented"><?php echo(elgg_echo("sitemap:recentlycommentedimages")); ?></a></li>
</ul>

    </div>

<?php }; ?>




</div>



	
<div class="clearfloat"></div>
    
	</div>	
	

