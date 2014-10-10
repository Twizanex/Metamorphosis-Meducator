<?php
	/**
	 * Elgg add user form. 
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

	$admin_option = false;
	//if (($_SESSION['user']->admin) && ($vars['show_admin'])) 
		$admin_option = true;
		
	$form_body = "<p><label>" ."Please input your resource's title" . "<br />" . elgg_view('input/text' , array('internalname' => 'name')) . "</label></p>";
//	$form_body="<input type='hidden' name='i_am_hidden' value='' />";
	//$form_body.="<p><input type='text' name='i_am_not_hidden' value='' /></p>";
	
	//$form_body .= "<p><label>" . elgg_echo('email') . "<br />" . elgg_view('input/text' , array('internalname' => 'email')) . "</label></p>";
	//$form_body .= "<p><label>" . elgg_echo('username') . "<br />" . elgg_view('input/text' , array('internalname' => 'username')) . "</label></p>";
	//$form_body .= "<p><label>" . elgg_echo('password') . "<br />" . elgg_view('input/password' , array('internalname' => 'password')) . "</label></p>";
	//$form_body .= "<p><label>" . elgg_echo('passwordagain') . "<br />" . elgg_view('input/password' , array('internalname' => 'password2')) . "</label></p>";
	
	if ($admin_option)
		//$form_body .= "<p>" . elgg_view('input/checkboxes', array('internalname' => "admin", 'options' => array(elgg_echo('admin_option'))));
		;
	
	$form_body .= elgg_view('input/submit', array('internalname' => 'submit', 'value' => elgg_echo('createContentItem'))) . "</p>";
?>

	
	<div id="add-box">
	<h2><?php echo elgg_echo('addContentItem'); ?></h2>
		<?php //echo elgg_echo('adduser'); ?>
		<?php echo elgg_view('input/form', array('action' => "{$vars['url']}action/useradd_content_item", 'body' => $form_body)) ?>
	</div>