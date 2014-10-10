<?php
	require_once(dirname(dirname(__FILE__))."/models/mainpage_widgets.php");
	global $CONFIG;
	
	if (!isadminloggedin()){
		register_error('vazco_mainpage:norights');
		forward(get_input('forward_url', $_SERVER['HTTP_REFERER']));
	}
	// Get common variables
	$access_id = ACCESS_PUBLIC;
	
	$file_path = $CONFIG->pluginspath.'vazco_mainpage/graphics/bckg/';
	$vertical_pos = get_input('vertical_pos','middle');
	$horizontal_pos = get_input('horizontal_pos','center');
	$repeat = get_input('repeat','no-repeat');
	$remove_background = get_input('remove_images',null);
	$name = null; 
	foreach($_FILES as $key => $sent_file) {
		if (!empty($sent_file['name'])) {
			$name = $_FILES[$key]['name'];
			$mime = $_FILES[$key]['type'];

			//make sure file is an image
			if (($mime == 'image/jpeg' || $mime == 'image/gif' || $mime == 'image/png' || $mime == 'image/pjpeg')) {
				if (!move_uploaded_file($_FILES[$key]['tmp_name'],$file_path.$name)){ 
					array_push($not_uploaded, $name);
				} 				
			} else { // file is not a supported image type 
				array_push($not_uploaded, $name);
			} //end of mimetype block
		} //end of file name empty check
	} //end of for loop
	$mainpageWidgets = new mainpageWidgets();
	$mainpageWidgets->setBackground($name,$vertical_pos,$horizontal_pos,$repeat);
	if ($remove_background)
		$mainpageWidgets->removeBackground();

	if (count($not_uploaded) == 0) {
		system_message(elgg_echo("vazco_mainpage:bckg:saved"));
	} else {
		$error = elgg_echo("vazco_mainpage:bckg:fail");
		register_error($error);
	} //end of upload check
	
	if (count($uploaded_images)>0) {
		//upload succesfull, redirect to the main page
		forward($CONFIG->wwwroot);
	} else {
		forward(get_input('forward_url', $_SERVER['HTTP_REFERER'])); //upload failed, so forward to previous page
	}

?>