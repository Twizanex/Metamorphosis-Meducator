<?php

    // Make sure we're logged in (send us to the front page if not)
    gatekeeper();
    action_gatekeeper();


    $guid = (int)get_input('id');
    $vote = (int)get_input('vote');

    $msg = fivestar_vote($guid, $vote);
       
    // Get the new rating
    $rating = fivestar_getRating($guid);

    $rating['msg'] = $msg;
    
    header('Content-type: application/json');
    echo json_encode($rating);
    exit;
?>
