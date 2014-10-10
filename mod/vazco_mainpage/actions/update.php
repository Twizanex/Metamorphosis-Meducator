<?php
/**
 * Elgg default_widgets plugin.
 *
 * @package DefaultWidgets
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU
 * @author Milan Magudia & Curverider
 * @copyright HedgeHogs.net & Curverider Ltd
 * 
 **/
require_once(dirname(dirname(__FILE__)) . "/models/mainpage_widgets.php");
// validate user is an admin
admin_gatekeeper ();

// validate action
action_gatekeeper ();

//encapsulate the :: with something, and uncapsulate it on load

// get parameters
$leftbar = str_replace ( '::0', '', $_POST['debugField1'] );
$middlebar = str_replace ( '::0', '', $_POST['debugField2'] );
$rightbar = str_replace ( '::0', '', $_POST['debugField3'] );

// make sure enough parameters are set
if (isset ( $leftbar ) && isset ( $middlebar ) && isset ( $rightbar )) {
	
	// join widgets into a single string
	$widgets = $leftbar . '%~~%' . $middlebar . '%~~%' . $rightbar;

	$widgetsObj = new mainpageWidgets(get_plugin_setting('show3columns','vazco_mainpage'));
	$success = $widgetsObj->saveFromString($widgets);
	
	// save the object or report error
	if ($success) {
		system_message ( elgg_echo ( 'vazco_mainpage:update:success' ) );
		$entity->state = "active";
		forward ();
	} else {
		register_error ( elgg_echo ( 'vazco_mainpage:update:failed' ) );
		forward ( 'pg/vazco_mainpage/');
	}

} else {
	// report incorrect parameters error
	register_error ( elgg_echo ( 'defaultwidgets:update:noparams' ) );
	forward ('pg/vazco_mainpage/');
}
