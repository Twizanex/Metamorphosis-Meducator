<?php
        // Get the Elgg framework
                require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

                gatekeeper();

                $context = get_context();

                $title = elgg_view_title(elgg_echo('memberlist'));

                set_context('search');

                $result = list_entities('user');


        // Display main admin menu
                page_draw(elgg_echo("memberlist"),
                                                elgg_view_layout("two_column_left_sidebar",
                                                                                '',
                                                                                $title . elgg_view("everybody/user") . $result)
                                                                                );

?>
