<?php

	/**
	 * Elgg add action
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

	require_once(dirname(dirname(__FILE__)) . "/engine/start.php");

	//admin_gatekeeper(); // Only admins can make someone an admin
	//action_gatekeeper();
	
	// Get variables

	global $CONFIG;
	//$username = get_input('username');
	//$password = get_input('password');
	//$password2 = get_input('password2');
	//$email = get_input('email');
	$name = get_input('name');
	$ident=get_input('identifier');
	
	
	
	
//	$no_space_name = str_replace(" ","_", $name );
	$username = md5(uniqid(mt_rand(), true));
	$password = "123456$#@%";
	$password2 = $password;
	$email = "educationalresource@metamorfosis.meducator.duth";
	
	$admin = get_input('admin');
	if (is_array($admin)) $admin = $admin[0];
	
	// For now, just try and register the user
/*	try {
		if (
			(
				(trim($password)!="") &&
				(strcmp($password, $password2)==0) 
			) &&
			($guid = register_user($username, $password, $name, $email, true))
		) {
			$new_user = get_entity($guid);
			if (($guid) && ($admin))
				$new_user->admin = 'yes';
			
			$new_user->admin_created = true;
			
			
			notify_user($new_user->guid, $CONFIG->site->guid, elgg_echo('useradd:subject'), sprintf(elgg_echo('useradd:body'), $name, $CONFIG->site->name, $CONFIG->site->url, $username, $password));
			
			system_message(sprintf(elgg_echo("adduser:ok"),$CONFIG->sitename));
		} else {
			register_error(elgg_echo("adduser:bad"));
		}
	} catch (RegistrationException $r) {
		register_error($r->getMessage());
	}
*/

	try {
		if (
			(
				(trim($password)!="") &&
				(strcmp($password, $password2)==0) 
			) &&
			($guid = register_user($username, $password, $name, $email, true))
		) {
			$new_user = get_entity($guid);
			if (($guid) && ($admin))
				$new_user->admin = 'yes';
			$new_user->issimpleuser="no";
			if ($_SESSION['user']->guid)
			$new_user->creatorg=$_SESSION['user']->guid;
			else
				$new_user->creatorg=get_input('creatorg');
			$new_user->extparent=1;
			$new_user->meducator3=$name;
			$new_user->meducator2=$ident;
			$new_user->meducator2_type='URI';
			
			$new_user->admin_created = true;
			
			//notify_user($new_user->guid, $CONFIG->site->guid, elgg_echo('useradd:subject'), sprintf(elgg_echo('useradd:body'), $name, $CONFIG->site->name, $CONFIG->site->url, $username, $password));
			
//			system_message(sprintf(elgg_echo("content_item_ok"),$CONFIG->sitename));
			
			
			$current_user_id = $new_user->creatorg;
			$db = mysql_connect('localhost','elgg','');
			if (!$db) {
			   die('Could not connect: ' . mysql_error());
			}
			mysql_select_db("elgg",$db);
			
			$qr_insert_content_item = "INSERT INTO `elgg_content_item_discrimination` ( `did`, `guid` , `is_content_item` , `creator_guid` )
				VALUES ('".$guid."', '".$guid."', '1', '".$current_user_id."')";

			$result_insert_content_item=mysql_query($qr_insert_content_item,$db);

			if(!$result_insert_content_item)
			{
			  register_error(elgg_echo("content_item_bad"));
			}

		//////////////////////////////////////////////////////////////////////
		//////////////////////////////////////////////////////////////////////		
		} else {
			register_error(elgg_echo("content_item_bad"));
			forward($_SERVER['HTTP_REFERER']);
		}
	} catch (RegistrationException $r) {
		register_error($r->getMessage());
		forward($_SERVER['HTTP_REFERER']);
	}
	
	////// ADD icon /////
	
	
	$cont_item_ID = $guid;
	
		//$avatarId = get_input('148',0);
$avatarId = 3039;
	if ($avatarId){
		$userId = $guid;
		create_metadata($userId, 'avatar', $avatarId,'', $userId, ACCESS_PUBLIC);
		create_metadata($userId, 'icontime', time(),'', 0, ACCESS_PUBLIC);

		//add to river
		add_to_river('river/user/default/profileiconupdate','update',$cont_item_ID,$cont_item_ID);

	//	system_message(elgg_echo('avatar:selected'));
	} 
	else{
		register_error('vazco_avatar:noavatarselected');
	}
	////

	

	


	$myforward_link = "http://metamorphosis.med.duth.gr/mod/content_item/createrdf.php?id=".$new_user->guid."&update=";
	forward($myforward_link);
	//forward($_SERVER['HTTP_REFERER']);
	exit;
?>