<?php
/**
	 * Elgg flex profile model
	 * Functions to save and display profile data
	 * 
	 * @package Elgg
	 * @subpackage Form
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Kevin Jardine <kevin@radagast.biz>
	 * @copyright Radagast Solutions 2008
	 * @link http://radagast.biz/
	 */

// Load form model
require_once(dirname(dirname(dirname(__FILE__))) . "/form/models/model.php");

// Load form profile model
require_once(dirname(dirname(dirname(__FILE__))) . "/form/models/profile.php");

// Eventually this will be very flexible and return different forms for
// different entities.
// Right now it just returns the first public profile form available.

function flexprofile_get_profile_form($user=null) {
    return form_get_latest_public_profile_form(1);
}    

// use the specified profile form to return the data (indexed by summary area) from the specified user

function flexprofile_get_data_for_summary_display($form, $user) {
    $form_id = $form->getGUID();
    $data = form_get_data_from_profile($form_id,$user);
    $area_data = array();
    $maps = form_get_maps($form_id);
    if ($maps) {
        foreach($maps as $map) {
            $field = get_entity($map->field_id);
            //print($field->internal_name.','.$field->field_type.','.$field->choice_type.','.$field->default_value.'<br />');
            $internalname = $field->internal_name;
            if (isset($data[$internalname]) && $data[$internalname]->value) {
                $area = $field->area;
                if ($area) {
                    if (!isset($area_data[$area])) {
                        $area_data[$area] = array();
                    }
                    $item = new StdClass();
                    $item->internalname = $internalname;
                    $item->title = form_field_t($form,$field,'title');
                    $item->description = form_field_t($form,$field,'description');
                    if ($field->field_type == 'choices') {
                        $item->fieldtype = $field->choice_type;
                        $choices = form_get_field_choices($field->getGUID());
                        $this_choice = '';
                        foreach($choices as $choice) {
                            if ($choice->value == $data[$internalname]->value) {
                                $this_choice = $choice;
                                break;
                            }
                        }
                        $item->value = form_choice_t($form,$field,$this_choice);
                    } else {
                        $item->fieldtype = $field->field_type;
                        $item->value = $data[$internalname]->value;
                    }
                    
                    $area_data[$area][] = $item;
                }
            }
        }
    }
    return $area_data;
}
    

// Return the form fields (indexed by tab), optionally prepopulated with data from the specified user.

function flexprofile_get_data_for_edit_form($form, $user=null) {

    if ($user) {
        $data = form_get_data_from_profile($form->getGUID(),$user);
    } else {
        $data = array();
    }
    
    $tab_data = array();
    $tabs = form_display_by_tab($form,$data);
    
    // add access control pulldowns
    if ($tabs) {
        foreach ($tabs as $tab => $tab_items) {
            $tab_data[$tab] = '';
            foreach ($tab_items as $item) {
                $internalname = $item->internalname;
                $access_id = $item->default_access;
                
                $access_bit = '<p class="form-field-access">';
                $access_bit .= elgg_view('input/access', array('internalname' => 'flexprofile_access['.$internalname.']','value'=>$access_id));
                $access_bit .= '</p>';
                $tab_data[$tab] .= $item->html.$access_bit;
            }
        }
    }
    return $tab_data;
}
    
function flexprofile_set_data($entity,$data) {
    global $CONFIG;
    
    $entity_guid = $entity->getGUID();
    
    foreach($data as $name => $item) {
    	remove_metadata($entity_guid, $name);
    	$value = $item->value;
    	if (is_array($value)) {
    		// currently tags are the only field_type returning multiple values
			$i = 0;
			foreach($value as $interval) {
				$i++;
				if ($i == 1) { $multiple = false; } else { $multiple = true; }
				create_metadata($entity_guid, $name, $interval, 'text', $entity_guid, $item->access_id, $multiple);
			}
		} else {
    		create_metadata($entity_guid, $name, $value, '', $entity_guid, $item->access_id);
		}
    }
}


?>