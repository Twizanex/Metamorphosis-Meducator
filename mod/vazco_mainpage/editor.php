<?php
	/**
	 * Elgg vazco_mainpage plugin
	 * 
	 * @author Michal Zacher [michal.zacher@gmail.com]
	 */

// load Elgg engine
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/engine/start.php");
require_once(dirname(__FILE__) . "/models/mainpage_widgets.php");

// make sure only admins can view this
admin_gatekeeper ();
set_context ( 'admin' );

// set admin user for user block
set_page_owner ( $_SESSION ['guid'] );

// vars required for action gatekeeper
$ts = time ();
$token = generate_action_token ( $ts );
$context = 'profile';

// create the view
$content = elgg_view ( "vazco_mainpage/mainpage_editor", array ('token' => $token, 'ts' => $ts, 'context' => $context ) );

// Display main admin menu
page_draw ( 'Default profile widgets for new users', $content );
