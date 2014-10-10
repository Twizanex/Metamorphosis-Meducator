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
$value = $vars['entity']->hidefriendsof;
?>
<p>
		<h4><?php echo elgg_echo('friends:of'); ?>: </h4>
	<?php
		echo elgg_view('input/pulldown', array('internalname' => 'params[hidefriendsof]', 'value' => $value, 'options_values' => array('0' => elgg_echo('friends_of_friends:setting:showfriendsof'),'1' => elgg_echo('friends_of_friends:setting:hidefriendsof')) ));
	?>
</p>