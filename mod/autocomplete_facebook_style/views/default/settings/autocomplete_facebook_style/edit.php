<?php
/**
 *	Autocomplete Facebook Style Plugin
 *	@package autocomplete facebook style
 *	@author Liran Tal <liran.tal@gmail.com>
 *	@license GNU General Public License (GPL) version 2
 *	@copyright (c) Liran Tal of Enginx 2009
 *	@link http://www.enginx.com
 **/


// create a unique token to add to the input element's id
// in case there are a couple of instances of this input type
// so that the javascript code doesn't get all drunk (confused)
// while using uniqid() is probably better it is also slower
$token = rand(0,10000);
?>

Example #1: Autocomplete friends...

<?php
	$config['name'] = "autocomplete_my_".$token;
	echo elgg_view('input/autocomplete_users_facebook', $config);
?>

<p>Please donate
<br/>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="11238545">
<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
</p>
