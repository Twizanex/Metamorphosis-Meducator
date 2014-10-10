<?php

	/**
	 * Elgg index page for web-based applications
	 * 
	 * @package Elgg
	 * @subpackage Core

	 * @author Curverider Ltd

	 * @link http://elgg.org/
	 */

	/**
	 * Start the Elgg engine 
	 */
		define('externalpage',true);
		require_once(dirname(__FILE__) . "/engine/start.php");
                /*
                //check if the user has made his choice (link from index.html)
                if(isset($_GET["opt"]) && ($_GET["opt"] == "start"))
                  //if yes, set the flag
                  $_SESSION["notFirstTime"] = true;
                
                //check if there is any indication of the users choice
                if(!isset($_SESSION["notFirstTime"]) || ($_SESSION["notFirstTime"] !== true ))
                {
                  //if not, display index.html
                  header("location: index.html");
                  exit();
                }
                */
		if (!trigger_plugin_hook('index','system',null,false)) {
	
			/**
		      * Check to see if user is logged in, if not display login form
		      **/
				
				if (isloggedin()) forward('pg/dashboard/');
			
	        //Load the front page
	        	global $CONFIG;
	        	$title = elgg_view_title(elgg_echo('content:latest'));
	        	set_context('search');
		        $content = list_registered_entities(0,10,true,false,array('object','group'));
		        set_context('main');
		        global $autofeed;
		        $autofeed = false;
		        $content = elgg_view_layout('two_column_left_sidebar', '', $title . $content, elgg_view("account/forms/login"));
		        page_draw(null, $content);
		
		}



?>