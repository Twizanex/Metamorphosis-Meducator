<?php

?>

    <script type="text/javascript" src="<?php echo $vars['url']; ?>mod/fivestar/js/ui.stars.js" ></script>

    <script type="text/javascript">
        function fivestar(id, token, ts) {
            $("#fivestar-form-"+id).children().not("select").hide();

            // Create stars for: Rate this
            $("#fivestar-form-"+id).stars({
                cancelShow: 1,
                cancelValue: 0,
                captionEl: $("#caption"),
                callback: function(ui, type, value)
                {
                    // Disable Stars while AJAX connection is active
                    ui.disable();

                    // Display message to the user at the begining of request
                    $("#fivestar-messages-"+id).text("<?php echo elgg_echo('saving'); ?>").stop().css("opacity", 1).fadeIn(30);

                    $.post("<?php echo $vars['url']; ?>action/fivestar/rate", {id: id, vote: value, __elgg_token: token, __elgg_ts: ts}, function(db)
                    {
                        // Select stars from "Average rating" control to match the returned average rating value
                        $("#fivestar-form-"+id).stars("select", Math.round(db.rating));

                        // Update other text controls...
                        $("#fivestar-votes-"+id).text(db.votes);
                        $("#fivestar-rating-"+id).text(db.rating);

                        // Display confirmation message to the user
                        if (db.msg) {
                            $("#fivestar-messages-"+id).text(db.msg).stop().css("opacity", 1).fadeIn(30);
                        } else {
                            $("#fivestar-messages-"+id).text("<?php echo elgg_echo('fivestar:rating_saved'); ?>").stop().css("opacity", 1).fadeIn(30);
                        }

                        // Hide confirmation message and enable stars for "Rate this" control, after 2 sec...
                        setTimeout(function(){
                                $("#fivestar-messages-"+id).fadeOut(1000, function(){ui.enable()})
                        }, 2000);
                    }, "json");
                }
            });

            // Create element to use for confirmation messages
            $('<div class="fivestar-messages" id="fivestar-messages-'+id+'"/>').appendTo("#fivestar-form-"+id);
        };
    </script>
