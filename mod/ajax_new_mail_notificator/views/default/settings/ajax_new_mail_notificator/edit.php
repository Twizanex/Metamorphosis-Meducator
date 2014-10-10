<?php

?>
<p>



	<?php echo elgg_echo('ajax_new_mail_notificator:admin:ajax_new_mail_notificator_refresh_rate'); ?>

      <select name="params[ajax_new_mail_notificator_refresh_rate]">
	    <option value="0" <?php if ($vars['entity']->ajax_new_mail_notificator_refresh_rate == 0) echo "selected=\"yes\" "; ?>>0 <?php echo elgg_echo('ajax_new_mail_notificator:admin:off'); ?></option>
        <option value="10000" <?php if ($vars['entity']->ajax_new_mail_notificator_refresh_rate == 10000) echo "selected=\"yes\" "; ?>>10 <?php echo elgg_echo('ajax_new_mail_notificator:admin:seconds'); ?></option>
        <option value="20000" <?php if ($vars['entity']->ajax_new_mail_notificator_refresh_rate == 20000) echo "selected=\"yes\" "; ?>>20 <?php echo elgg_echo('ajax_new_mail_notificator:admin:seconds'); ?></option>
        <option value="30000" <?php if (!$vars['entity']->ajax_new_mail_notificator_refresh_rate || $vars['entity']->ajax_new_mail_notificator_refresh_rate == 30000) echo "selected=\"yes\" "; ?>>30 <?php echo elgg_echo('ajax_new_mail_notificator:admin:seconds'); ?></option>
        <option value="40000" <?php if ($vars['entity']->ajax_new_mail_notificator_refresh_rate == 40000) echo "selected=\"yes\" "; ?>>40 <?php echo elgg_echo('ajax_new_mail_notificator:admin:seconds'); ?></option>
        <option value="50000" <?php if ($vars['entity']->ajax_new_mail_notificator_refresh_rate == 50000) echo "selected=\"yes\" "; ?>>50 <?php echo elgg_echo('ajax_new_mail_notificator:admin:seconds'); ?></option>		
		<option value="60000" <?php if ($vars['entity']->ajax_new_mail_notificator_refresh_rate == 60000) echo "selected=\"yes\" "; ?>>60 <?php echo elgg_echo('ajax_new_mail_notificator:admin:seconds'); ?></option>
      </select>	
	
</p>