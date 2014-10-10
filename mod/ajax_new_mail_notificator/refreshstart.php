<?php

    /**
	 * Elgg ajax_new_mail_notificator plugin
	 * 
	 * @package
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Matthias Sutter email@matthias-sutter.de
	 * @copyright CubeYoo.de
	 * @link http://cubeyoo.de
	 */
	 
	// Start Elgg engine
	require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/engine/start.php');

	    $num_messages = count_unread_messages();
	    
        if($num_messages  == 0)

			echo ""; 
		
        else{

			echo "<div id='ajax_new_mail_notificator_box_image'>"; 
			echo "<div id='ajax_new_mail_notificator_box'>"; 
		    echo "<img id='image' src='" . $vars['url'] . "_graphics/river_icons/river_icon_status.gif' /><h3 class=\"new_messages_count\">" . $num_messages . elgg_echo("ajax_new_mail_notificator:new_message") . "</h3>";
		  	echo "<a id='link' href=\"" . $vars['url'] . "pg/messages/" . $_SESSION['user']->username ."\">" . elgg_echo("ajax_new_mail_notificator:read_message") . "</a>";
			
			echo '</div>';
			echo '</div>';

	    }
