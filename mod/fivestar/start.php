<?php

    include dirname(__FILE__) . "/lib/simple_html_dom.php";

    function fivestar_init() {

        fivestar_settings();

        $style = get_plugin_setting('style');

        if ($style == 'basic') {
            extend_view('css', 'fivestar/basic');
        }

        extend_view('metatags','fivestar/metatags');

        register_plugin_hook('display', 'view', 'fivestar_view');
    }

    /**
     * This method is called when the view plugin hook is triggered. 
     * If a matching view config is found then the fivestar widget is
     * called.
     * 
     * @param  integer  $hook The hook being called. 
     * @param  integer  $type The type of entity you're being called on. 
     * @param  string   $return The return value.
     * @param  array    $params An array of parameters for the current view
     * @return string   The html
     */
    function fivestar_view($hook, $entity_type, $returnvalue, $params) {

        global $is_admin;

        $lines = explode("\n", get_plugin_setting('view'));
        foreach ($lines as $line) {
            $options = array();
            $parms = explode(",", $line);
            foreach ($parms as $parameter) {
                preg_match("/^(\S+)=(.*)$/", trim($parameter), $match);
                $options[$match[1]] = $match[2];
            }

            if ($options['view'] == $params['view']) {
                list($status, $html) = fivestar_widget($returnvalue, $params, $options);
                if (!$status) {
                    continue;
                } else {
                    return($html);
                }
            }
        }
    }

    /**
     * Handles voting on an entity
     * 
     * @param  integer  $guid  The entity guid being voted on
     * @param  integer  $vote The vote
     * @return string   A status message to be returned to the client
     */
    function fivestar_vote($guid, $vote) {

        $entity = get_entity($guid);

        $msg = null;

        if ($annotation = get_annotations($entity->guid, $entity->type, $entity->subtype, 'fivestar', '', $_SESSION['user']->guid, 1)) {
            if ($vote == 0 && (int)get_plugin_setting('change_cancel')) {
                delete_annotation($annotation[0]->id);
                $msg = elgg_echo('fivestar:deleted');
            } else if (get_plugin_setting('change_cancel')) {
                update_annotation($annotation[0]->id, 'fivestar', $vote, 'integer', $_SESSION['user']->guid, 2);
                $msg = elgg_echo('fivestar:updated');
            } else {
                $msg = elgg_echo('fivestar:nodups');
            }
        } else if ($vote > 0) {
            $entity->annotate('fivestar', $vote, 2);
        } else {
            $msg = elgg_echo('fivestar:novote');
        }

        return($msg);
    }

    /**
     * Get an the current rating for an entity
     * 
     * @param  integer  $guid  The entity guid being voted on
     * @return array    Includes the current rating and number of votes
     */
    function fivestar_getRating($guid) {

        $rating = array('rating' => 0, 'votes' => 0);
        $entity = get_entity($guid);

        if (count($entity->getAnnotations('fivestar', 9999))) {
            $rating['rating'] = $entity->getAnnotationsAvg('fivestar');
            $rating['votes'] = count($entity->getAnnotations('fivestar', 9999));

            $modifier = 100 / (int)get_plugin_setting('stars');
            $rating['rating'] = round($rating['rating'] / $modifier, 1);
        }

        return($rating);
    }

    /**
     * Inserts the fivestar widget into the current view
     * 
     * @param  string   $returnvalue  The original html
     * @param  array    $params  An array of parameters for the current view
     * @param  array    $guid  The fivestar view configuration
     * @return string   The original view or the view with the fivestar widget inserted
     */
    function fivestar_widget($returnvalue, $params, $options) {

        $guid = $params['vars']['entity']->guid;

        if (!$guid) { return; }

        $widget = elgg_view("fivestar/fivestar", array(
            'fivestar_guid' => $guid
        ));

        // get the DOM
        $html = str_get_html($returnvalue);

        $match = 0;
        foreach ($html->find($options['tag']) as $element) {
            if ($element->$options['attribute'] == $options['attribute_value']) {
                $element->innertext .= $options['before_html'] . $widget . $options['after_html'];
                $match = 1;
                break;
            }
        }

        $returnvalue = $html;
        return(array($match, $returnvalue));
    }

    /**
     * Checks to see if the current user has already voted on the entity
     * 
     * @param  guid   The entity guid
     * @return bool   Returns true/false
     */
    function fivestar_hasVoted($guid) {

        $entity = get_entity($guid);

        $annotation = get_annotations($entity->guid, $entity->type, $entity->subtype, 'fivestar', '', $_SESSION['user']->guid, 1);

        if (is_object($annotation[0])) {
            return(true);
        }

        return(false);
    }

    /**
     * Set default settings
     * 
     */
    function fivestar_settings() {

        // Set plugin defaults

        if (!(int)get_plugin_setting('stars')) {
            set_plugin_setting('stars', '5');
        }

        $change_vote = (int)get_plugin_setting('change_vote');
        if ($change_vote == 0) {
            set_plugin_setting('change_cancel', 0);
        } else {
            set_plugin_setting('change_cancel', 1);
        }

        if (!get_plugin_setting('style')) {
            set_plugin_setting('style', 'basic');
        }

        if (!get_plugin_setting('view')) {
            $view = 'view=object/blog, tag=div, attribute=class, attribute_value=contentWrapper singleview, before_html=<br />
view=object/image, tag=div, attribute=id, attribute_value=tidypics_wrapper
view=object/groupforumtopic, tag=div, attribute=class, attribute_value=search_listing, before_html=<br />
view=object/poll, tag=div, attribute=class, attribute_value=contentWrapper singleview, before_html=<br /><br />
view=poll/listing, tag=div, attribute=class, attribute_value=search_listing, before_html=<br />
view=object/page_top, tag=div, attribute=class, attribute_value=search_listing, before_html=<br />
view=pages/pageprofile, tag=div, attribute=class, attribute_value=contentWrapper, before_html=<br />
view=object/file, tag=div, attribute=class, attribute_value=search_listing, before_html=<br />
view=object/file, tag=div, attribute=class, attribute_value=filerepo_controls';
            set_plugin_setting('view', $view);
        }

        if (get_plugin_setting('view') == 'object/blog|div|class|contentWrapper singleview, object/image|div|calss|tidypics_info') {
            $view = 'view=object/blog, tag=div, attribute=class, attribute_value=contentWrapper singleview, before_html=<br />
view=object/image, tag=div, attribute=id, attribute_value=tidypics_wrapper
view=object/groupforumtopic, tag=div, attribute=class, attribute_value=search_listing, before_html=<br />
view=forum/topicposts, tag=div, attribute=class, attribute_value=topic_post
view=object/poll, tag=div, attribute=class, attribute_value=contentWrapper singleview, before_html=<br /><br />
view=poll/listing, tag=div, attribute=class, attribute_value=search_listing, before_html=<br />
view=object/page_top, tag=div, attribute=class, attribute_value=search_listing, before_html=<br />
view=pages/pageprofile, tag=div, attribute=class, attribute_value=contentWrapper, before_html=<br />
view=object/file, tag=div, attribute=class, attribute_value=search_listing, before_html=<br />
view=object/file, tag=div, attribute=class, attribute_value=filerepo_controls';
            set_plugin_setting('view', $view);
        }

    }

    register_elgg_event_handler('init','system','fivestar_init');
    register_action("fivestar/rate", false, $CONFIG->pluginspath . "fivestar/actions/rate.php");
    register_action("fivestar/settings", false, $CONFIG->pluginspath . "fivestar/actions/settings.php");

?>
