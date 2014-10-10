<?php

	$refresh_time = get_plugin_setting("ajax_new_mail_notificator_refresh_rate", "ajax_new_mail_notificator");

	if ($refresh_time == '') {
		$refresh_time = '60000';
	}
	
?>

<script type="text/javascript">

var refreshId = setInterval(function(){
 $('#ajax_new_mail_notificator_container').load('/mod/ajax_new_mail_notificator/lib/refreshstart.php?callback=true');
}, <?php echo $refresh_time; ?>);

$(document).ready(function(){
	// initial load of ajax_new_mail_notificator
	$('#ajax_new_mail_notificator_container').load('/mod/ajax_new_mail_notificator/lib/refreshstart.php');
    $('#ajax_new_mail_notificator_container').fadeIn(3000, function () {
	});

});

</script>


<div id="ajax_new_mail_notificator_container"></div>
	
		
<noscript>		
<?php
	    $num_messages = count_unread_messages();
	    
        if($num_messages  == 0)
	
			echo ""; 
		
        else{
			
			echo "<div id='ajax_new_mail_notificator_container'>";
			echo "<div class='index_box'>";
            echo "<h2>".elgg_echo('')."</h2>"; 
			echo "<div class='search_listing'>"; 

		    echo "<h3 class=\"new_messages_count\">" . $num_messages . elgg_echo("ajax_new_mail_notificator:new_message") . "</h3>" . 
			"<a href=\"" . $vars['url'] . "pg/messages/" . $_SESSION['user']->username ."\">" . elgg_echo("ajax_new_mail_notificator:read_message") . "</a>";
			

			echo '</div>';
			echo '</div>';
			echo '</div>';

	    }
?>		
</noscript>		
		
		
 








