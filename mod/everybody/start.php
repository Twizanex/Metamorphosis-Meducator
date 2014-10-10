<?php

function everybody_init() {

  global $CONFIG;

// Set up menu for logged in users
	register_translations($CONFIG->pluginspath . "everybody/languages/");
         if (isloggedin()) {
              add_menu(elgg_echo('everybody'), $CONFIG->wwwroot ."pg/everybody");
         }
	register_page_handler('everybody','everybody_page_handler');
}

                function everybody_page_handler($page) {
                                @include(dirname(__FILE__) . "/index.php");
                                return true;
                }

register_elgg_event_handler('init','system','everybody_init');
?>
