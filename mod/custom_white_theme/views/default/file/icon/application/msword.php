<?php

	if ($vars['size'] == 'large') {
		$ext = '_lrg';
	} else {
		$ext = '';
	}
	echo "<img src=\"{$CONFIG->wwwroot}mod/custom_white_theme/graphics/file_icons/word{$ext}.gif\" border=\"0\" />";

?>