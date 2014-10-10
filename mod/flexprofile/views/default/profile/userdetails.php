<?php

	/**
	 * Elgg user display (details)
	 * 
	 * @package ElggProfile
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 * 
	 * @uses $vars['entity'] The user entity
	 */

// Load flexprofile model
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/models/model.php");

// Load form profile model
require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) . "/form/models/profile.php");

if ($vars['full'] == true) {
	$iconsize = "large";
} else {
	$iconsize = "medium";
}

// wrap all profile info
echo "<div id=\"profile_info\">";

?>

<table cellspacing="0">
<tr>
<td>

<?php	
	
	// wrap the icon and links in a div
	echo "<div id=\"profile_info_column_left\">";
	
	echo "<div id=\"profile_icon_wrapper\">";
	// get the user's main profile picture
	echo elgg_view(
						"profile/icon", array(
												'entity' => $vars['entity'],
												//'align' => "left",
												'size' => $iconsize,
												'override' => true,
											  )
					);


    echo "</div>";
    echo "<div class=\"clearfloat\"></div>";
     // display relevant links			
    echo elgg_view("profile/profilelinks", array("entity" => $vars['entity']));
       
    // close profile_info_column_left
    
    if ($vars['full'] == true) {

        $form = flexprofile_get_profile_form($vars['entity']);
  		if ($form) {
	        if ($form->profile_format !== 'tabbed') {
	        	$body = '';
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
				}
				print $body;
	        }
		}
    }
	
    echo "</div>";

?>
</td>
<td>
	
	<div id="profile_info_column_middle" >
<?php
    $editdetails = elgg_echo("profile:edit");
    $body = '';
	if ($vars['entity']->canEdit()) {
        $body .= <<<END
	<p class="profile_info_edit_buttons">
		<a href="{$vars['url']}mod/profile/edit.php?username={$vars['entity']->username}">
		$editdetails</a>
	</p>
END;

	}
	// Simple XFN
	$rel = "";
	if (page_owner() == $vars['entity']->guid)
		$rel = 'me';
	else if (check_entity_relationship(page_owner(), 'friend', $vars['entity']->guid))
		$rel = 'friend';
		
	// display the users name
	$body .= "<h2><a href=\"" . $vars['entity']->getUrl() . "\" rel=\"$rel\">" . $vars['entity']->name . "</a></h2>";

	//insert a view that can be extended
	$body .= elgg_view("profile/status", array("entity" => $vars['entity']));
	// display the users name
	//$body .= "<h2><a href=\"" . $vars['entity']->getUrl() . "\">" . $vars['entity']->name . "</a></h2>";

	if ($vars['full'] == true) {
		if ($form) {
			if ($form->profile_format != 'tabbed') {		
		        // do right column
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
			} else {
				$body .= elgg_view('flexprofile/extended',array('entity'=>$vars['entity'],'embedded'=>true));
			}
		} else {
			$body .= elgg_echo('form:error_no_profile_form');
		}
	}
	
	echo $body;
			
        
	
	?>
	</div><!-- /#profile_info_column_middle -->

</td>
</tr>

<tr>
<td colspan="2">
	<?php
	if ($form) {
		if ($form->profile_format != 'tabbed') {
			if ($data['bottom']) {
				echo '<div id="profile_info_column_right">';
		    	foreach($data['bottom'] as $item) {		
		    		echo '<b>'.$item->title.'</b>';
		    		echo '<br /><br />';
		    		echo $item->value;
		    		echo '<br />';
		        }
		        echo '</div><!-- /#profile_info_column_right -->';
		    }
		}
	}
    ?>

</td>
</tr>


</table>



</div><!-- /#profile_info -->