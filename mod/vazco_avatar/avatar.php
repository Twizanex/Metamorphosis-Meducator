<?php

	/**
	 * Tidypics Thumbnail
	 * 
	 */

	// Get engine
	include_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
		
	// Get file GUID
	$file_guid = (int) get_input('file_guid',0);
	
	// Get file thumbnail size
	$size = get_input('size','medium');
//dumpdie(get_entity($file_guid));		
	// Get file entity
	if ($file = get_entity($file_guid)) {

		if ($file->getSubtype() == "avatar") {					
			// Get file thumbnail
			if ($size == "large") {
				$thumbfile = $file->large;
			} 
			elseif ($size == "medium"){
				$thumbfile = $file->medium;
			} 
			elseif ($size == "small"){
				$thumbfile = $file->small;
			} 
			elseif ($size == "tiny"){
				$thumbfile = $file->tiny;
			}
			elseif ($size == "topbar"){
				$thumbfile = $file->topbar;
			}
			elseif ($size == "master"){
				$thumbfile = $file->master;
			}
			// Grab the file
			if ($thumbfile && !empty($thumbfile)) {
				$readfile = new ElggFile();

				$readfile->owner_guid = $file->owner_guid;
				$readfile->setFilename($thumbfile);
				//$mime = $file->getMimeType();
				$contents = $readfile->grabFile();
			}
		} //end subtype comparison
	} //end get_entity

	// Open error image if file was not found
	if (!isset($contents) || is_null($contents) || $file->getSubtype()!='avatar') {
		//$vars['url'].'mod/tidypics/graphics/img_error.jpg
		forward('mod/tidypics/graphics/img_error.jpg');
	} //end of default error image

	// Return the thumbnail and exit
	header("Content-type: ".$file->mimetype);
	echo $contents;
	exit;
?>