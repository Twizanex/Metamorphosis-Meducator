<?php
	global $CONFIG;

  	//Simplepie
	$num_items = $vars['entity']->feed_num_items;
	if (!isset($num_items)) $num_items = 10;

	$excerpt = $vars['entity']->excerpt;
	if (!isset($excerpt)) $excerpt = 0;

	$post_date = $vars['entity']->post_date;
	if (!isset($post_date)) $post_date = 0;

	//Pages
	$pages_num_items = $vars['entity']->pages_num_items;
	if (!isset($pages_num_items)) $pages_num_items = 10;
	
	//Activity
	$activity_num_items = $vars['entity']->activity_num_items;
	if (!isset($activity_num_items)) $activity_num_items = 10;	
	
	//Tidypics
	$tidypics_num_items = $vars['entity']->tidypics_num_items;
	if (!isset($tidypics_num_items)) $tidypics_num_items = 10;
	
	//Polls
	$polls_num_items = $vars['entity']->polls_num_items;
	if (!isset($polls_num_items)) $polls_num_items = 3;	
	
	//Events
	$events_num_items = $vars['entity']->events_num_items;
	if (!isset($events_num_items)) $events_num_items = 3;
	
	    
    //Show events by creation, not event date
	if (!$vars['entity']->events_bycreation) {
	    $vars['entity']->events_bycreation = "no";
    }
	
	//Login box text
	$loginbox_text = $vars['entity']->loginbox_text;

	//Widgets should have brief content. like on search?
	if (!$vars['entity']->brief_content) {
	    $vars['entity']->brief_content = "no";
    }
    	
	//Login box should be wide?
	if (!$vars['entity']->loginbox_wide) {
	    $vars['entity']->loginbox_wide = "yes";
    }
    
    //Can vote in polls on mainpage in
	if (!$vars['entity']->polls_fullview) {
	    $vars['entity']->polls_fullview = "no";
    }
    
    
    //Use 3 columns
	if (!$vars['entity']->show3columns) {
	    $vars['entity']->show3columns = "no";
    }

?>
<p><b><?php echo elgg_echo('vazco_mainpage:simplepie:edit:section');?></b></p>
<p>&nbsp;</p>
  <p>
    <?php echo elgg_echo("vazco_mainpage:simplepie:feed_url"); ?>
    <input type="text" onclick="this.select();" name="params[feed_url]" value="<?php echo htmlentities($vars['entity']->feed_url); ?>" />  
  </p>

  <p>
<?php echo elgg_echo('vazco_mainpage:simplepie:num_items'); ?>
	
<?php
	echo elgg_view('input/pulldown', array(
			'internalname' => 'params[feed_num_items]',
			'options_values' => array( '3' => '3',
                                 '5' => '5',
			                           '8' => '8',
			                           '10' => '10',
			                           '12' => '12',
			                           '15' => '15',
			                           '20' => '20',
			                         ),
			'value' => $num_items
		));
?>
  </p>

  <p>
<?php 
  echo elgg_view('input/hidden', array('internalname' => 'params[excerpt]', 'js' => 'id="params[excerpt]"', 'value' => $excerpt ));
  echo "<input class='input-checkboxes' type='checkbox' value='' name='excerptcheckbox' onclick=\"document.getElementById('params[excerpt]').value = 1 - document.getElementById('params[excerpt]').value;\" ";
  if ($excerpt) echo "checked='yes'";
  echo " />";
  echo ' ' . elgg_echo('vazco_mainpage:simplepie:excerpt');
?>
  </p>  

  <p>
<?php 
  echo elgg_view('input/hidden', array('internalname' => 'params[post_date]', 'js' => 'id="params[post_date]"', 'value' => $post_date ));
  echo "<input class='input-checkboxes' type='checkbox' value='' name='post_datecheckbox' onclick=\"document.getElementById('params[post_date]').value = 1 - document.getElementById('params[post_date]').value;\" ";
  if ($post_date) echo "checked='yes'";
  echo " />";
  echo ' ' . elgg_echo('vazco_mainpage:simplepie:post_date');
?>
  </p>  

<?php 
  $compat_url = $CONFIG->wwwroot . 'mod/vazco_mainpage/models/sp_compatibility_test.php';
  $permit_url = $CONFIG->wwwroot . 'mod/vazco_mainpage/permissions.php';

?>

<p>
	<a href="<?php echo $compat_url; ?>"><?php echo elgg_echo('vazco_mainapge:simplepie:comptest');?></a>
</p>

<p>
	<a href="<?php echo $permit_url; ?>"><?php echo elgg_echo('vazco_mainapge:simplepie:permtest');?></a>
</p>

<p>&nbsp;</p>
<p><b><?php echo elgg_echo('vazco_mainpage:pages:edit:section');?></b></p>
<p>&nbsp;</p>
<p>
<?php echo elgg_echo('vazco_mainpage:pages:num_items'); ?>
<?php
	echo elgg_view('input/pulldown', array(
			'internalname' => 'params[pages_num_items]',
			'options_values' => array( '3' => '3',
                                 '5' => '5',
			                           '8' => '8',
			                           '10' => '10',
			                           '12' => '12',
			                           '15' => '15',
			                           '20' => '20',
			                         ),
			'value' => $pages_num_items
		));
?>
</p>

<p>&nbsp;</p>
<p><b><?php echo elgg_echo('vazco_mainpage:polls:edit:section');?></b></p>
<p>&nbsp;</p>
<p>
<?php echo elgg_echo('vazco_mainpage:polls:num_items'); ?>
<?php
	echo elgg_view('input/pulldown', array(
			'internalname' => 'params[polls_num_items]',
			'options_values' => array( '1' => '1',
                                 '2' => '2',
			                           '3' => '3',
			                           '4' => '4',
			                           '5' => '5',
			                           '6' => '6',
			                           '7' => '7',
			                         ),
			'value' => $polls_num_items
		));
?>
</p>

<p>
    <?php echo elgg_echo('vazco_topbar:settings:polls_fullview'); ?> 
    <select name="params[polls_fullview]">
        <option value="yes" <?php if ($vars['entity']->polls_fullview != 'no') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('option:yes'); ?></option>
        <option value="no" <?php if ($vars['entity']->polls_fullview == 'no') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('option:no'); ?></option>
    </select>
</p>

<p>&nbsp;</p>
<p><b><?php echo elgg_echo('vazco_mainpage:events:edit:section');?></b></p>
<p>&nbsp;</p>
<p>
<?php echo elgg_echo('vazco_mainpage:events:num_items'); ?>
<?php
	echo elgg_view('input/pulldown', array(
			'internalname' => 'params[events_num_items]',
			'options_values' => array( '1' => '1',
                                 '2' => '2',
			                           '3' => '3',
			                           '4' => '4',
			                           '5' => '5',
			                           '6' => '6',
			                           '7' => '7',
			                         ),
			'value' => $events_num_items
		));
?>
</p>
<p>
    <?php echo elgg_echo('vazco_topbar:settings:events:bycreation'); ?> 
    <select name="params[events_bycreation]">
        <option value="yes" <?php if ($vars['entity']->events_bycreation != 'no') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('option:yes'); ?></option>
        <option value="no" <?php if ($vars['entity']->events_bycreation == 'no') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('option:no'); ?></option>
    </select>
</p>

<?php if (is_plugin_enabled('riverdashboard')){?>
	<p>&nbsp;</p>
	<p><b><?php echo elgg_echo('vazco_mainpage:activity:edit:section');?></b></p>
	<p>&nbsp;</p>
	<p>
	<?php echo elgg_echo('vazco_mainpage:activity:num_items'); ?>
	<?php
		echo elgg_view('input/pulldown', array(
				'internalname' => 'params[activity_num_items]',
				'options_values' => array( '3' => '3',
	                                 '5' => '5',
				                           '8' => '8',
				                           '10' => '10',
				                           '12' => '12',
				                           '15' => '15',
				                           '20' => '20',
				                         ),
				'value' => $activity_num_items
			));
	?>
	</p>
<?php }else{?>
	<p><?php echo elgg_echo('vazco_mainpage:activity:notset');?></p>
<?php }?>


<?php if (is_plugin_enabled('tidypics')){?>
	<p>&nbsp;</p>
	<p><b><?php echo elgg_echo('vazco_mainpage:tidypics:edit:section');?></b></p>
	<p>&nbsp;</p>
	<p>
	<?php echo elgg_echo('vazco_mainpage:tidypics:num_items'); ?>
	<?php
		echo elgg_view('input/pulldown', array(
				'internalname' => 'params[tidypics_num_items]',
				'options_values' => array( '3' => '3',
	                                 '5' => '5',
				                           '8' => '8',
				                           '10' => '10',
				                           '12' => '12',
				                           '15' => '15',
				                           '20' => '20',
				                         ),
				'value' => $tidypics_num_items
			));
	?>
	</p>
<?php }else{?>
	<p><?php echo elgg_echo('vazco_mainpage:tidypics:notset');?></p>
<?php }?>
<p>&nbsp;</p>
<p><a href="<?php echo $vars['url'];?>"><?php echo elgg_echo('vazco_topbar:preview');?></a> <?php echo elgg_echo('vazco_topbar:preview:description');?></p>
<p>&nbsp;</p>
<p><b><?php echo elgg_echo('vazco_mainpage:loginbox:edit:section');?></b></p>
<p>&nbsp;</p>
<p>
    <?php echo elgg_echo('vazco_topbar:settings:loginbox_text'); ?> 
    <?php echo elgg_view('input/longtext', array('internalname' => 'params[loginbox_text]', 'value' => $loginbox_text)); ?>
</p>
<p>
    <?php echo elgg_echo('vazco_topbar:settings:loginbox_wide'); ?> 
    <select name="params[loginbox_wide]">
        <option value="yes" <?php if ($vars['entity']->loginbox_wide != 'no') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('option:yes'); ?></option>
        <option value="no" <?php if ($vars['entity']->loginbox_wide == 'no') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('option:no'); ?></option>
    </select> 
    <?php echo elgg_echo('vazco_topbar:settings:loginbox_wide:desc');?>
</p>
<p>
    <?php echo elgg_echo('vazco_topbar:settings:show3columns'); ?> 
    <select name="params[show3columns]">
        <option value="yes" <?php if ($vars['entity']->show3columns != 'no') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('option:yes'); ?></option>
        <option value="no" <?php if ($vars['entity']->show3columns == 'no') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('option:no'); ?></option>
    </select>
</p>
<p>
    <?php echo elgg_echo('vazco_topbar:settings:brief_contents'); ?> 
    <select name="params[brief_content]">
        <option value="yes" <?php if ($vars['entity']->brief_content != 'no') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('option:yes'); ?></option>
        <option value="no" <?php if ($vars['entity']->brief_content == 'no') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('option:no'); ?></option>
    </select>
</p>
<p>
    <?php echo elgg_echo('vazco_topbar:settings:searchboxtext'); ?> 
    <?php echo elgg_view('input/longtext', array('internalname' => 'params[searchboxtext]', 'value' => $vars['entity']->searchboxtext)); ?>
</p>
    