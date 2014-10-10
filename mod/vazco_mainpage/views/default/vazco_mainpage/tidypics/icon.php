<?php
	/**
	 * Elgg tidypic icon
	 * Optionally you can specify a size.
	 * 
	 * @package ElggFile
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	global $CONFIG;
		
if($vars['album']){
	echo "<img src=\"{$CONFIG->wwwroot}mod/vazco_mainpage/graphics/icons/no_pic.gif\" border=\"0\" />";
}
else{
	
	$mime = $vars['mimetype'];
	if (isset($vars['thumbnail'])) {
		$thumbnail = $vars['thumbnail'];
	} else {
		$thumbnail = false;
	}
	
	$size = $vars['size'];
	if ($size != 'large') {
		$size = 'small';
	}
	echo '<a href='.$vars['link'].'>';
		if ($thumbnail && strpos($mime, "image/")!==false)
			echo "<img class='tidypics_frontpage' src=\"{$vars['url']}action/tidypics/icon?file_guid={$vars['file_guid']}\" border=\"0\" />";
		else 
		{
			if ($size == 'large')
				echo "<img src=\"{$CONFIG->wwwroot}mod/vazco_mainpage/graphics/icons/no_pic.gif\" border=\"0\" />";
			else
				echo "<img src=\"{$CONFIG->wwwroot}mod/vazco_mainpage/graphics/icons/no_pic.gif\" border=\"0\" />";
		}
	echo '</a>';
}
?>