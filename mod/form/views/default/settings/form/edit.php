<?php
$options = array(elgg_echo('form:yes')=>'yes',
	elgg_echo('form:no')=>'no',
);

if (form_get_user_content_status()) {
	$form_user_content_area = 'yes';
} else {
	$form_user_content_area = 'no';
}

$body = '';

$body .= elgg_echo('form:user_content_status_title');
$body .= '<br />';
$body .= elgg_view('input/radio',array('internalname'=>'params[user_content_area]','value'=>$form_user_content_area,'options'=>$options));

echo $body;

?>