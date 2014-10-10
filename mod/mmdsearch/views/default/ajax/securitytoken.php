<?php
  $ts = time();
  $token = generate_action_token($ts);

  echo $token;
?>
