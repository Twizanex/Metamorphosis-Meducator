<?php

	if ($vars['size'] == 'large') {
		$ext = '_lrg';
	} else {
		$ext = '';
	}
	echo "<img src=\"{$CONFIG->wwwroot}mod/custom_white_theme/graphics/file_icons/archive{$ext}.gif\" border=\"0\" />";

?>