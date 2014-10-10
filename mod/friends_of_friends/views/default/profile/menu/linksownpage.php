<?php

	/**
	 * Friends of friends.
	 * 
	 * @package friends_of_friends
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Pedro Prez
	 * @copyright 2009
	 * @link http://www.pedroprez.com.ar/
 */
?>

	<p class="user_menu_friends">
		<a href="<?php echo $vars['url']; ?>pg/friends/<?php echo $vars['entity']->username; ?>/"><?php echo elgg_echo("friends"); ?></a>	
	</p>
	<?php if(!$CONFIG->mod->friends_of_friends->config->hidefriendsof): ?>
		<p class="user_menu_friends_of">
			<a href="<?php echo $vars['url']; ?>pg/friendsof/<?php echo $vars['entity']->username; ?>/"><?php echo elgg_echo("friends:of"); ?></a>	
		</p>
	<?php endif; ?>
	<p class="user_menu_friends_of_friends">
		<a href="<?php echo $vars['url']; ?>pg/friendsoffriends/<?php echo $vars['entity']->username; ?>/"><?php echo elgg_echo("friendsoffriends"); ?></a>	
	</p>