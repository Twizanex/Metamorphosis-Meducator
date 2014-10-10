<?php

	/**
	 * Elgg header contents 
	 * This file holds the header output that a user will see
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2009
	 * @link http://elgg.org/
	 **/
	 
?>

<div id="page_container">
<div id="page_wrapper">

<div id="layout_header">
<div id="wrapper_header">
	<!-- display the page title 
	<h1><a href="<?php echo $vars['url']; ?>"><?php echo $vars['config']->sitename; ?></a></h1>
	-->
	
	
	<div id="site_logo">
		<a href="<?php echo $vars['url']; ?>">
			<img src="<?php echo $vars['url']; ?>mod/custom_white_theme/graphics/example-theme-logo.gif" border="0" />
		</a>
	</div>
	
<?php
     if (isloggedin()) {
?>

<div id="navigation-bar">
<ul>
<li class="navlist"><a class="home-icon" href="<?php echo $vars['url']; ?>"></a></li>
<li class="navlist"><a class="myprofile-icon" href="<?php echo $_SESSION['user']->getURL(); ?>"></a></li>
<li class="navlist"><a class="dashboard-icon" href="<?php echo $vars['url']; ?>pg/dashboard/"></a></li>
<li class="navlist"><a class="mail-icon" href="<?php echo $vars['url']; ?>pg/messages/admin"></a></li>
<li class="navlist"><a class="settings-icon" href="<?php echo $vars['url']; ?>pg/settings/"></a></li>
</ul>
</div>


</div><!-- /#wrapper_header -->
</div><!-- /#layout_header -->

<?php } else { ?>

<div id="navigation-bar">
<ul>
<li class="navlist"><a class="home-icon" href="<?php echo $vars['url']; ?>"></a></li>
<li class="navlist"><a class="signup-icon" href="<?php echo $vars['url']; ?>account/register.php"></a></li>
<li class="navlist"><a class="about-icon" href="<?php echo $vars['url']; ?>pg/expages/read/About/"></a></li>
</ul>
</div>

</div><!-- /#wrapper_header -->
</div><!-- /#layout_header -->

<?php

    }
?>