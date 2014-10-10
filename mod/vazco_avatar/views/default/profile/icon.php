<?php

	/**
	 * Elgg profile icon
	 * 
	 * @package ElggProfile
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 * 
	 * @uses $vars['entity'] The user entity. If none specified, the current user is assumed.
	 * @uses $vars['size'] The size - small, medium or large. If none specified, medium is assumed. 
	 */

	// Get entity
		if (empty($vars['entity']))
			$vars['entity'] = $vars['user'];

		if ($vars['entity'] instanceof ElggUser) {
			
		$name = htmlentities($vars['entity']->name, ENT_QUOTES, 'UTF-8');
		$username = $vars['entity']->username;
		
		if ($icontime = $vars['entity']->icontime) {
			$icontime = "{$icontime}";
		} else {
			$icontime = "default";
		}
			
	// Get size
		if (!in_array($vars['size'],array('small','medium','large','tiny','master','topbar')))
			$vars['size'] = "medium";
			
	// Get any align and js
		if (!empty($vars['align'])) {
			$align = " align=\"{$vars['align']}\" ";
		} else {
			$align = "";
		}

	// Override
		if (isset($vars['override']) && $vars['override'] == true) {
			$override = true;
		} else $override = false;
		
		if (!$override) {
		
?>
<div class="usericon">
<div class="avatar_menu_button"><img src="<?php echo $vars['url']; ?>_graphics/spacer.gif" border="0" width="15px" height="15px" /></div>


	<?php
		if ((isadminloggedin()) || (!$vars['entity']->isBanned())) {
	 ?><a href="<?php echo $vars['entity']->getURL(); ?>" class="icon" ><?php 
		}
		
	}
	$iconSource = getAvatarSource($vars['entity'], $vars['size'], $vars['url']); 

	?><img src="<?php echo $iconSource; ?>" border="0" <?php echo $align; ?> title="<?php echo htmlentities($vars['entity']->name, ENT_QUOTES, 'UTF-8'); ?>" <?php echo $vars['js']; ?> /><?php

		if (!$override) {
	
	?></a>
</div>

<?php

	}
		}

?>