<?php
 	 
$value = trim($vars['value']);
$size = trim($vars['size']);
if (!$size) {
    $size = 'large';
}

print '<img src="'.$vars['url'].'/mod/file/thumbnail.php?size='.$size.'&file_guid='.$value.'">';

?>