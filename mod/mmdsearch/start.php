<?php
  global $CONFIG;

  function mmdsearch_init()
  {
    global $CONFIG;
    //extend css if style is required
    //elgg_extend_view('css','css/jquery-ui-1.8.7.custom');

    //add a widget
    //add_widget_type('twitter',"Twitter","This is your twitter feed");

    //add a menu
    add_menu(elgg_echo('search'), $CONFIG->wwwroot . "mod/mmdsearch/");
    if (get_context() == "mmdsearch") {
      add_submenu_item(elgg_echo('Search'),$CONFIG->wwwroot."mod/mmdsearch/?username=" . $_SESSION['user']->username);
      add_submenu_item(elgg_echo('Saved searches'),"#");
      add_submenu_item(elgg_echo('Favorites resources'),"#");
    }
    

  }
  register_elgg_event_handler('init','system','mmdsearch_init');

  register_action("mmdsearch/save", false, $CONFIG->pluginspath . "mmdsearch/actions/save.php");
  register_action("mmdsearch/dsearch", false, $CONFIG->pluginspath . "mmdsearch/actions/dsearch.php");
?>