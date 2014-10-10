<?php
	/**
	 * Elgg groups plugin full profile view.
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

// Load form model
require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) . "/form/models/model.php");

// Load form profile model
require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) . "/form/models/profile.php");

// Load flexgroupprofile model
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/models/model.php");

	if ($vars['full'] == true) {
		$iconsize = "large";
	} else {
		$iconsize = "medium";
	}
	
?>

<div id="groups_info_column_right"><!-- start of groups_info_column_right -->
    <div id="groups_icon_wrapper"><!-- start of groups_icon_wrapper -->
				
        <?php
		    echo elgg_view(
					"groups/icon", array(
												'entity' => $vars['entity'],
												//'align' => "left",
												'size' => $iconsize,
											  )
					);
        ?>
				
    </div><!-- end of groups_icon_wrapper -->
	<div id="group_stats"><!-- start of group_stats -->
	    <?php
							
		    echo "<p><b>" . elgg_echo("groups:owner") . ": </b><a href=\"" . get_user($vars['entity']->owner_guid)->getURL() . "\">" . get_user($vars['entity']->owner_guid)->name . "</a></p>";
								
	    ?>
	    <p><?php echo elgg_echo('groups:members') . ": " . get_entities_from_relationship('member', $vars['entity']->guid, true, 'user', '', 0, '', 9999, 0, true); ?></p>
    </div><!-- end of group_stats -->
<?php
	if ($vars['full'] == true) {
	    $body = '';
	    $form = flexgroupprofile_get_profile_form($vars['entity'],$vars['entity']->group_profile_category);
	    if ($form) {
		    if (!in_array($form->profile_format,array('tabbed','wide_tabbed'))) {
		    	// a little bit weird, but actually in the default design 
		    	// this column is displayed on the left (not right) - so show left items
			    $data = form_get_data_for_profile_summary_display($form, $vars['entity']);
			    // do left column
			    if ($data['left']) {
			    	foreach($data['left'] as $item) {
			    		$value = $item->value;
			    		if (!empty($value)) {
			    				
			    			//This function controls the alternating class
			    			$even_odd = ( 'odd' != $even_odd ) ? 'odd' : 'even';
			    			$body .= "<p class=\"{$even_odd}\"><b>";
			    			$body .= $item->title.':</b> ';
			    			$body .= $item->value;		
			    		}
			    	}
			    	echo $body;
			    }
		    }
	    }
	}
?>
</div><!-- end of groups_info_column_right -->

<div id="groups_info_column_left"><!-- start of groups_info_column_left --> 
    <?php
        if ($vars['full'] == true) {
        	$body = '';
		    // do right column
		    // handle description, which is hardcoded to be first
		    $body .= '<div class="contentWrapper">';
    		$body .= $vars['entity']->description;
    		$body .= '</div>';
        	if ($form) {
				if (!in_array($form->profile_format,array('tabbed','wide_tabbed'))) {
				    // a little bit weird, but actually in the default design 
				    // this column is displayed on the right (not left) - so show right items
				    if ($data['right']) {
				    	foreach($data['right'] as $item) {
				    		$value = $item->value;
				    		if (!empty($value)) {
				    				
				    			//This function controls the alternating class
				    			$even_odd = ( 'odd' != $even_odd ) ? 'odd' : 'even';
				    			$body .= "<p class=\"{$even_odd}\"><b>";
				    			$body .= $item->title.':</b> ';
				    			$body .= $item->value;
				
				    		}
				    	}
				    }
				} else if ($form->profile_format == 'tabbed') {
					$body .= '<div class="contentWrapper">';
					$body .= elgg_view('flexgroupprofile/extended',array('entity'=>$vars['entity'],'embedded'=>true));
					$body .= '</div>';
				}
        	} else {
        		$body .= elgg_echo('form:error_no_group_profile_form');
        	}
        	echo $body;
		}
	?>
</div><!-- end of groups_info_column_left -->

<div id="groups_info_wide">
<?php
if ($form->profile_format == 'wide_tabbed') {
	echo '<br clear="both" /><div class="contentWrapper">';
	echo elgg_view('flexgroupprofile/extended',array('entity'=>$vars['entity'],'embedded'=>true));
	echo '</div>';
}
?>

	<p class="groups_info_edit_buttons">
	
<?php
	if ($vars['entity']->canEdit()) 
	{

?>
			
		<a href="<?php echo $vars['url']; ?>mod/groups/edit.php?group_guid=<?php echo $vars['entity']->getGUID(); ?>"><?php echo elgg_echo("edit"); ?></a>
		
			
<?php
	
	}

    if ($vars['full'] == true) {
    	if ($form) {
	    	if (!in_array($form->profile_format,array('tabbed','wide_tabbed'))) {
			    // do bottom
			    if ($data['bottom']) {
				    foreach($data['bottom'] as $item) {	
				    	echo '<b>'.$item->title.'</b>';
				    	echo '<br /><br />';
				    	echo $item->value;
				    }
			    }
	    	}
    	}
	}
?>
	
	</p>
</div>