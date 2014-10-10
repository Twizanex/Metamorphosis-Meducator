<?php

	/**
	 * Elgg vazco_topbar plugin
	 * 
	 * @author Michal Zacher [michal.zacher@gmail.com]
	 * @website www.elggdev.com
	 */

	 
		$res_count=0;
		$total_count=0;
		$query1= "SELECT guid FROM {$CONFIG->dbprefix}_content_item_discrimination WHERE is_content_item = \"1\"";
				$result1 = mysql_query($query1);
				while($row = mysql_fetch_array($result1, MYSQL_ASSOC))
				{
						$nikolas1=$row['guid'];
						if (get_entity($nikolas1))
						$res_count++;
				}
		$query2= "SELECT * FROM {$CONFIG->dbprefix}users_entity";
				$result2 = mysql_query($query2);
				while($row = mysql_fetch_array($result2, MYSQL_ASSOC))
				{
						$total_count++;
				}
				
			$user_count=$total_count-$res_count	;
		
			 
if (isloggedin()) {?>
	<div id="elgg_topbar_container_middle">
		<a href="<?php echo $vars['url']; ?>action/logout"><small><?php echo elgg_echo('logout'); ?></small></a>
	</div>
<?php }else{
	//pokaz panel logowania
		if ($vars['disable_security']!=true)
		{
			$ts = time();
			$token = generate_action_token($ts);
			$security_header = elgg_view('input/hidden', array('internalname' => '__elgg_token', 'value' => $token));
			$security_header .= elgg_view('input/hidden', array('internalname' => '__elgg_ts', 'value' => $ts));
		}

		?>
		<div id="elgg_topbar_container_middle">			
			<form id="loginform_top" action="<?php echo $vars['url']; ?>action/login" method="POST" >
			<a class="loginbox_top_link" href="<?php echo $vars['url'];?>account/register.php"></a>
				<a class="loginbox_top_link" href="<?php echo $vars['url'];?>account/forgotten_password.php"></a>
				<?php echo "There are currently ".$user_count." users and ".$res_count." Educational Resources registered on the platform." ; ?>

		
			</form>
		</div>
<?php 	
	}
?>