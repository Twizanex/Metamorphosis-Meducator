<?php
    mt_srand ((double)microtime()*1000000);
    $maxran = 1000000;
    $random = mt_rand(0, $maxran);

    

    $html = "";
     require_once('recaptchalib.php');
  $publickey = "6Ldf1cASAAAAAHm-OcQfHZ8jpe3ZK5jxyTrzNxc_"; // you got this from the signup page
  echo recaptcha_get_html($publickey);
 //   $html .= "<div id=\"siteaccess-code\"><table><tr><td><img src='". $vars['url'] ."action/siteaccess/code?c=$random'></td> ";
 //   $html .= "<td><input type=\"text\" name=\"code\" maxlength=\"6\" /></td></tr></table></div>";
 //   $html .= elgg_view('input/hidden', array('internalname' => 'random', 'value' => $random));
    
    echo $html;
?>
