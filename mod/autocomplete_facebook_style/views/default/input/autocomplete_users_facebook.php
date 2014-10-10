<?php
/**
 *	Autocomplete Facebook Style Plugin
 *	@package autocomplete facebook style
 *	@author Liran Tal <liran.tal@gmail.com>
 *	@license GNU General Public License (GPL) version 2
 *	@copyright (c) Liran Tal of Enginx 2009
 *	@link http://www.enginx.com
 **/
?>
<!-- autocomplete facebook style plugin by Liran Tal of Enginx http://www.enginx.com -->


<?php

// create a unique token to add to the input element's id
// in case there are a couple of instances of this input type
// so that the javascript code doesn't get all drunk (confused)
// while using uniqid() is probably better it is also slower
$token = rand(0,10000);

?>


<?php

	$class = $vars['class'];
	if (!$class)
		$class = "input-text";
	
	//force the autocomplete class
	$class = "autocomplete";
	
	$value = $vars['value'];
	
	$name = $vars['name'];
	if (!$name)
		$name = "autocomplete_facebook_style".$token;

?>


<script language="javascript" type="text/javascript" src="<?php echo $vars['url']; ?>mod/autocomplete_facebook_style/vendors/autocomplete_fcbkcomplete/jquery.fcbkcomplete.min.js"></script>
<script language="JavaScript">
	$(document).ready(function() 
	{        
	 
	  $("#<?=$name?>").fcbkcomplete({
		json_url: "<?php echo $vars['url']; ?>mod/autocomplete_facebook_style/autocomplete_myfriends.php",
		cache: false,
		filter_case: true,
		filter_hide: true,
		firstselected: true,
		filter_selected: true,
		newel: true        
	  });		 
	});
</script>

<div id="text"></div>
<div>
      <select id="<?=$name?>" name="<?=$name?>">
      </select>
</div>
