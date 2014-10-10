<?php
/**
	 * Elgg flex profile edit form
	 * Allows user to edit profile
	 * 
	 * @package Elgg
	 * @subpackage Form
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Kevin Jardine <kevin@radagast.biz>
	 * @copyright Radagast Solutions 2008
	 * @link http://radagast.biz/
	 */

	 // Load flexprofile model
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/models/model.php");

$user = $vars['entity'];
$form = flexprofile_get_profile_form($user);
if ($form) {
	$tab_data = flexprofile_get_data_for_edit_form($form, $user);

echo '<div class="contentWrapper">';
echo '<form action="'.$vars['url'].'action/flexprofile/edit" method="post" enctype="multipart/form-data">';
echo elgg_view('form/forms/display_form_content',array('tab_data'=>$tab_data,'description'=>'','preview'=>0,'form'=>$form,'form_data_id'=>0));

?>

	<p>
		<input type="hidden" name="username" value="<?php echo page_owner_entity()->username; ?>" />
		<input type="submit" class="submit_button" value="<?php echo elgg_echo("save"); ?>" />
	</p>

</form>
<?php
} else {
	echo elgg_echo('form:error_no_profile_form');
}
echo '</div>';

?>