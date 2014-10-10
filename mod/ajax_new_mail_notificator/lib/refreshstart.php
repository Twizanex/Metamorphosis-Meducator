<?php

	require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/engine/start.php');

	    $num_messages = count_unread_messages();
	    
        if($num_messages  == 0)
	
			echo ""; 
		
        else{
			
			echo "<div id='ajax_new_mail_notificator_container'>";
			echo "<div class='index_box'>";
            echo "<h2>".elgg_echo('')."</h2>"; 
			echo "<div class='search_listing'>"; 
		    echo "<h3 class=\"new_messages_count\">" . $num_messages . elgg_echo("ajax_new_mail_notificator:new_message") . "</h3>";
            echo "<a href=\"" . $vars['url'] . "/pg/messages/" . $_SESSION['user']->username ."\">" . elgg_echo("ajax_new_mail_notificator:read_message") . "</a>";			

			echo '</div>';
			echo '</div>';
			echo '</div>';

	    }
?> 