<?php

	/**
	 * Elgg pageshell
	 * The standard HTML header that displays across the site
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @author Curverider Ltd
	 * @link http://elgg.org/
	 * 
	 * @uses $vars['config'] The site configuration settings, imported
	 * @uses $vars['title'] The page title
	 * @uses $vars['body'] The main content of the page
	 * @uses $vars['messages'] A 2d array of various message registers, passed from system_messages()
	 */
	 
	 // Set title
		if (empty($vars['title'])) {
			$title = $vars['config']->sitename;
		} else if (empty($vars['config']->sitename)) {
			$title = $vars['title'];
		} else {
			$title = $vars['config']->sitename . ": " . $vars['title'];
		}
		
		global $autofeed;
		if (isset($autofeed) && $autofeed == true) {
			$url = $url2 = full_url();
			if (substr_count($url,'?')) {
				$url .= "&view=rss";
			} else {
				$url .= "?view=rss";
			}
			if (substr_count($url2,'?')) {
				$url2 .= "&view=odd";
			} else {
				$url2 .= "?view=opendd";
			}
			$feedref = <<<END
			
	<link rel="alternate" type="application/rss+xml" title="RSS" href="{$url}" />
	<link rel="alternate" type="application/odd+xml" title="OpenDD" href="{$url2}" />
			
END;
		} else {
			$feedref = "";
		}
		
		$version = get_version();
		$release = get_version(true);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="ElggRelease" content="<?php echo $release; ?>" />
	<meta name="ElggVersion" content="<?php echo $version; ?>" />
	<meta name="google-site-verification" content="iwEGV3ZmrorMLc47xmkgjtFfW_Wh1d2HfbIkfBwqyqo" />
	<title><?php echo $title; ?></title>
	<meta name="description" content="This is an Educational Resource provided by it's author for the mEducator Project." /> 
	<html xmlns:ntb="http://www.nitobi.com">
	<link type="text/css" media="screen" rel="stylesheet" href="<?php echo $vars['url']; ?>_css/colorbox.css" />
	
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script> 
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js"></script>
	<script type="text/javascript" src="<?php echo $vars['url']; ?>vendors/jquery/jquery.form.js"></script>
	<script type="text/javascript" src="<?php echo $vars['url']; ?>_css/js.php?lastcache=<?php echo $vars['config']->lastcache; ?>&js=initialise_elgg&viewtype=<?php echo $vars['view']; ?>"></script>
<script type="text/javascript" src="<?php echo $vars['url']; ?>mod/fisheye_menu/views/default/js/fisheye.js"></script>
	<script type="text/javascript" src="<?php echo $vars['url']; ?>vendors/jquery/jquery.colorbox-min.js"></script> 
	<script type="text/javascript" src="<?php echo $vars['url']; ?>vendors/jquery.validate.min.js"></script> 

	
<script type="text/javascript">
			$(document).ready(function(){
				$(".example5").colorbox({iframe:true, innerWidth:740, innerHeight:606});
				$(".example6").colorbox({iframe:true, innerWidth:620, innerHeight:420});
				

			});
		</script>


<?php
	global $pickerinuse;
	if (isset($pickerinuse) && $pickerinuse == true) {
?>
	<!-- only needed on pages where we have friends collections and/or the friends picker -->
	<script type="text/javascript" src="<?php echo $vars['url']; ?>vendors/jquery/jquery.easing.1.3.packed.js"></script>
	<script type="text/javascript" src="<?php echo $vars['url']; ?>_css/js.php?lastcache=<?php echo $vars['config']->lastcache; ?>&js=friendsPickerv1&viewtype=<?php echo $vars['view']; ?>"></script>
<?php
	}
?>
	<!-- include the default css file -->
	<link rel="stylesheet" href="<?php echo $vars['url']; ?>_css/css.css?lastcache=<?php echo $vars['config']->lastcache; ?>&viewtype=<?php echo $vars['view']; ?>" type="text/css" />
	
	<?php 
		echo $feedref;
		echo elgg_view('metatags',$vars); 
	?>
	<script type="text/javascript">
		 jQuery(document).ready(function($) {
		 });
	</script>


</head>

<body onload="onLoad()">
