<?php
/**
 * Edit group profile
 * 
 * @package FlexGroupProfile
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Kevin Jardine <kevin@radagast.biz>
 * @copyright Radagast Solutions 2008
 * @link http://radagast.biz/
 */

// Load form model

require_once(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))) . "/form/models/model.php");

// Load form profile model
require_once(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))) . "/form/models/profile.php");

// Load flexgroupprofile model
require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) . "/models/model.php");

$group = $vars['entity'];
$group_config = flexgroupprofile_get_profile_config($group->group_profile_category);
if ($group) {
	$group_profile_category = get_input('group_profile_category',$group->group_profile_category);
} else {
	$group_profile_category = get_input('group_profile_category','');
}
if ($group_profile_category) {
	$fp = form_get_profile_config($group_profile_category,'group');
} else {
	$fp = '';
}
?>
<div class="contentWrapper">
<?php if ($fp && $fp->new_group_description) {echo $fp->new_group_description;} else { echo elgg_echo("form:new_group_description"); } ?>
</div>
<div class="contentWrapper">
<form action="<?php echo $vars['url']; ?>action/flexgroupprofile/edit" enctype="multipart/form-data" method="post">

	<p>
		<label><?php if ($fp && $fp->group_icon) {echo $fp->group_icon;} else {echo elgg_echo("groups:icon");} ?><br />
		<?php

			echo elgg_view("input/file",array('internalname' => 'icon'));
		
		?>
		</label>
	</p>
	
	<p>
		<label>
			<?php if ($fp && $fp->group_name) {echo $fp->group_name;} else { echo elgg_echo("groups:name"); } ?><br />
			<?php echo elgg_view("input/text",array(
															'internalname' => 'name',
															'value' => $vars['entity']->name,
															)); ?>
		</label>
	</p>
	<p>
		<label>
			<?php if ($fp && $fp->group_description) {echo $fp->group_description;} else { echo elgg_echo("groups:description"); } ?><br />
			<?php echo elgg_view("input/longtext",array(
															'internalname' => 'description',
															'value' => $vars['entity']->description,
															)); ?>
		</label>
	</p>
<?php
	$form = flexgroupprofile_get_profile_form($group,$group_profile_category);
	if ($form) {
		$tab_data = form_get_data_for_profile_edit_form($form, $group, $group_profile_category);
		echo elgg_view('form/forms/display_form_content',array('tab_data'=>$tab_data,'description'=>'','preview'=>0,'form'=>$form,'form_data_id'=>0));
	} else {
		echo '<p>'.elgg_echo('form:error_no_group_profile_form').'</p>';
	}
	
	if (isadminloggedin() || ($group_config->group_owner_can_transfer_ownership && ($group->getOwner() == $_SESSION['user']->getGUID()))) {
		// let admins or optionally group owners transfer ownership
		if ($group) {
			$group_owner_username = get_entity($group->getOwner())->username;
		} else {
			$group_owner_username = get_loggedin_user()->username;
		}
?>
<br />
		<label>
			<?php echo elgg_echo('flexgroupprofile:owner_label'); ?><br />
			<?php echo elgg_view('input/text', array('internalname' => 'group_owner_username','value' => $group_owner_username)); ?>
		</label>
		<p class="description"><?php echo elgg_echo('flexgroupprofile:owner_description');?></p>
<?php
}
 ?>

	<p>
		<label>
			<?php echo elgg_echo('groups:membership'); ?><br />
			<?php echo elgg_view('input/access', array('internalname' => 'membership','value' => $vars['entity']->membership, 'options' => array( ACCESS_PRIVATE => elgg_echo('groups:access:private'), ACCESS_PUBLIC => elgg_echo('groups:access:public')))); ?>
		</label>
	</p>
	
	
    <?php
		if (isset($vars['config']->group_tool_options)) {
			foreach($vars['config']->group_tool_options as $group_option) {
				$group_option_toggle_name = $group_option->name."_enable";
				if ($group_option->default_on) {
					$group_option_default_value = 'yes';
				} else {
					$group_option_default_value = 'no';
				}
?>	
    <p>
			<label>
				<?php echo $group_option->label; ?><br />
				<?php

					echo elgg_view("input/radio",array(
									"internalname" => $group_option_toggle_name,
									"value" => $vars['entity']->$group_option_toggle_name ? $vars['entity']->$group_option_toggle_name : $group_option_default_value,
									'options' => array(
														elgg_echo('groups:yes') => 'yes',
														elgg_echo('groups:no') => 'no',
													   ),
													));
				?>
			</label>
	</p>
	<?php
		}
	}
	?>
	<p>
		<?php
			if ($vars['entity'])
			{ 
			?><input type="hidden" name="group_guid" value="<?php echo $vars['entity']->getGUID(); ?>" /><?php 
			}
		?>
		<input type="hidden" name="user_guid" value="<?php echo page_owner_entity()->guid; ?>" />
		<input type="submit" class="submit_button" value="<?php echo elgg_echo("save"); ?>" />
		
	</p>

</form>
</div>

<div class="contentWrapper">
<div id="delete_group_option">
	<form action="<?php echo $vars['url'] . "action/groups/delete"; ?>">
		<?php
			if ($vars['entity'])
			{ 
				$warning = elgg_echo("groups:deletewarning");
			?>
			<input type="hidden" name="group_guid" value="<?php echo $vars['entity']->getGUID(); ?>" />
			<input type="submit" name="delete" value="<?php echo elgg_echo('groups:delete'); ?>" onclick="javascript:return confirm('<?php echo $warning; ?>')"/><?php 
			}
		?>
	</form>
</div><div class="clearfloat"></div>
</div>
