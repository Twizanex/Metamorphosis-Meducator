<?php
	/**
         * @package Elgg
         * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
         * @author Tingxi Tan, Grid Research Centre [txtan@cpsc.ucalgary.ca]
         * @link http://grc.ucalgary.ca/
         */

//get options
if(!$json_url = $vars['json_url']){
	register_error('Please provide a JSON URL for the Content Map');
	return;
}
if(!$default_zoom = $vars['default_zoom']) $default_zoom = 2;
if(!$timer = $vars['timer']) $timer = 4000;
if(!isset($vars['pan'])) $pan = true;
else $pan = $vars['pan'];
if(!isset($vars['mapcontrol'])) $mapcontrol = false;
else $mapcontrol = $vars['mapcontrol'];
if(!$centre = $vars['centre']) $centre = array(0,0);	

?>

<script type='text/javascript'>
	$(document).ready(function(){
		//load the default map centred at $centre
		<?php if($mapcontrol){?>
			load_map('content_map_div',<?php echo $centre[0];?>,<?php echo $centre[1];?>,<?php echo $default_zoom;?>, true);
		<?php }else{?>
			load_map('content_map_div',<?php echo $centre[0];?>,<?php echo $centre[1];?>,<?php echo $default_zoom;?>, false);
		<?php }?>
		//call pan or no pan 
		<?php if($pan){?>
			plot_content('<?php echo $json_url;?>','<?php echo $timer;?>');
		<?php } else {?>
			plot_content('<?php echo $json_url;?>','<?php echo $timer;?>', false);
		<?php }?>
	});
	$(document).unload(function(){
		//google map clean up
		GUnload();
	});
</script>

<div id='content_map_div' class='content_map'></div>
