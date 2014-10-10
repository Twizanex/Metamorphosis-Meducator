<?php 

    global $CONFIG;
    $settings_url = $CONFIG->wwwroot . 'mod/fivestar/admin.php?tab=settings';
?>

<p>
<a href="<?php echo $settings_url; ?>"><?php echo elgg_echo('fivestar:admin_settings'); ?></a>
</p>

