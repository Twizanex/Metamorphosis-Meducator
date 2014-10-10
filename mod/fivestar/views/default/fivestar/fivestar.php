<?php

    $ts = time ();
    $token = generate_action_token ($ts);

    $guid = isset($vars['fivestar_guid']) ? $vars['fivestar_guid'] : $vars['entity']->guid;

    if (!$guid) { return; }

    $rating = fivestar_getRating($guid);
    $stars = (int)get_plugin_setting('stars', 'fivestar');

    $pps = 100 / $stars;

    $checked = '';

    $disabled = '';
    if (!isloggedin()) {
        $disabled = 'disabled="disabled"';
    }

    if (!(int)get_plugin_setting('change_cancel', 'fivestar')) {
        if (fivestar_hasVoted($guid)) {
            $disabled = 'disabled="disabled"';
        }
    }


?>

    <div class="fivestar-ratings-<?php echo $guid; ?>">
        <form id="fivestar-form-<?php echo $guid; ?>" style="width: 200px" action="" method="post">
            <?php for ($i = 1; $i <= $stars; $i++) { ?>
                <?php if (round($rating['rating']) == $i) { $checked = 'checked="checked"'; } ?>
                    <input type="radio" name="rate_avg" <?php echo $checked; ?> <?php echo $disabled; ?> value="<?php echo $pps * $i; ?>" />
                    <?php $checked = ''; ?>
            <?php } ?>
                <input type="submit" value="Rate it!" />
        </form>

        <br />
        <span>
            <span id="fivestar-rating-<?php echo $guid; ?>"><?php echo $rating['rating']; ?></span>/<?php echo $stars; ?> stars (<span id="fivestar-votes-<?php echo $guid; ?>"><?php echo $rating['votes']; ?></span> votes)
        </span>
    </div>

    <script type="text/javascript">
        jQuery(
            fivestar(<?php echo $guid; ?>, '<?php echo $token; ?>', '<?php echo $ts; ?>')
        );
    </script>



