<?php
	global $CONFIG;
	
	if (!$maxFileSize || ((int) $maxFileSize) < 1 || $maxFileSize > 1048576) {
		$maxfilesize = 10240; //if file size is less than 1KB or greater than 1GB, default to 10MB
	} else {
		$maxfilesize = (int) $maxFileSize;
	}
	$maxfilesize = 1024 * $maxfilesize; //convert to bytes
?>
<script language="javascript">
<!--

var state = 'none';

function showhide(layer_ref) {
	if (state == 'block') {
	state = 'none';
	}
	else {
	state = 'block';
	}
	if (document.all) { //IS IE 4 or 5 (or 6 beta)
	eval( "document.all." + layer_ref + ".style.display = state");
	}
	if (document.layers) { //IS NETSCAPE 4 or below
	document.layers[layer_ref].display = state;
	}
	if (document.getElementById &&!document.all) {
	hza = document.getElementById(layer_ref);
	hza.style.display = state;
	}
	return false;
}
//-->
</script>
<div class="contentWrapper">
<form action="<?php echo $vars['url']; ?>action/vazco_avatar/upload" enctype="multipart/form-data" method="post">
	<p style="line-height:1.6em;">
		<label><?php echo elgg_echo("avatars:upload"); ?></label><br />
		<p><i><?php echo elgg_echo("vazco_avatar:settings:maxfilesize") . ' ' . $maxfilesize; ?></i></p>
		<p><?php echo elgg_echo('vazco_avatar:upload:info');?></p><br/>
		<div align="center" class="tidypics_loader" id="tidypics_loader" name="tidypics_loader" style="display:none;"><center><img alt="..." border="0" src="<?php echo $vars['url'].'mod/vazco_avatar/graphics/loader.gif' ?>" /></center></div>
	  <ol id="tidypics_image_upload_list">
<?php
		for($x = 0; $x < 10; $x++){
			echo '<li>' . elgg_view("input/file",array('internalname' => "upload_$x")) . "</li>\n";
		}	  
?>
		</ol>
	</p>				
		<p>
			<input type="submit" value="<?php echo elgg_echo("save"); ?>" onclick="showhide('tidypics_loader');" />
		</p>

</form>
</div>