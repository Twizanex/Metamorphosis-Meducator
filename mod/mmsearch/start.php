<?php
  global $CONFIG;

  function mmsearch_init()
  {
    global $CONFIG;
    //extend css if style is required
    //elgg_extend_view('css','css/jquery-ui-1.8.7.custom');
    
    //add a widget
    //add_widget_type('twitter',"Twitter","This is your twitter feed");

    //add a menu
    add_menu(elgg_echo('search'), $CONFIG->wwwroot . "mod/mmsearch/");
    if (get_context() == "mmsearch") {
      add_submenu_item(elgg_echo('Search'),$CONFIG->wwwroot."mod/mmsearch/?username=" . $_SESSION['user']->username);
      add_submenu_item(elgg_echo('Saved searches'),"#");
      add_submenu_item(elgg_echo('Favorites resources'),"#");
    }
    

  }
  register_elgg_event_handler('init','system','mmsearch_init');

  register_action("mmsearch/save", false, $CONFIG->pluginspath . "mmsearch/actions/save.php");
  register_action("mmsearch/search", false, $CONFIG->pluginspath . "mmsearch/actions/search.php");
?>