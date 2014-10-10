<?php
require_once(dirname(__FILE__).'/profile.php');

function form_get_form_field_types() {
	// Load form field types model
    include(dirname(dirname(__FILE__))."/models/field_types.php"); 
    $form_custom_field_types = form_custom_field_type_manager();
	if ($form_custom_field_types) {
		return array_merge($form_field_types,$form_custom_field_types);
	} else {
		return $form_field_types;
	}
}

function form_custom_field_type_manager($type='',$label='',$input_view='',$output_view='') {
	static $form_custom_field_types;
	
	if (!isset($form_custom_field_types)) {
			$form_custom_field_types = array();
	}
	
	if ($type) {
		$obj = new stdClass();
		$obj->label = $label;
		$obj->input_view = $input_view;
		$obj->output_view = $output_view;
		$form_custom_field_types[$type] = $obj;		
	}
	return $form_custom_field_types;	
}

function form_delete_file($file_id) {
    
    $file = get_entity($file_id);
    
    if ($file->canEdit()) {				
		$thumbnail = $file->thumbnail;
		$smallthumb = $file->smallthumb;
		$largethumb = $file->largethumb;
		if ($thumbnail) {

			$delfile = new ElggFile();
			$delfile->owner_guid = $file->owner_guid;
			$delfile->setFilename($thumbnail);
			$delfile->delete();

		}
		if ($smallthumb) {

			$delfile = new ElggFile();
			$delfile->owner_guid = $file->owner_guid;
			$delfile->setFilename($smallthumb);
			$delfile->delete();

		}
		if ($largethumb) {

			$delfile = new ElggFile();
			$delfile->owner_guid = $file->owner_guid;
			$delfile->setFilename($largethumb);
			$delfile->delete();

		}
		
		return $file->delete();
	}
}

function form_generate_thumbnail($file,$fieldname) {
    // Generate thumbnail (if image)
    $prefix = "file/";
    $filestorename = strtolower(time().$_FILES[$fieldname]['name']);
    if (substr_count($file->getMimeType(),'image/'))
    {
    	$thumbnail = get_resized_image_from_existing_file($file->getFilenameOnFilestore(),60,60, true);
    	$thumbsmall = get_resized_image_from_existing_file($file->getFilenameOnFilestore(),153,153, true);
    	$thumblarge = get_resized_image_from_existing_file($file->getFilenameOnFilestore(),600,600, false);
    	if ($thumbnail) {
    		$thumb = new ElggFile();
    		$thumb->setMimeType($_FILES[$fieldname]['type']);
    		
    		$thumb->setFilename($prefix."thumb".$filestorename);
    		$thumb->open("write");
    		$thumb->write($thumbnail);
    		$thumb->close();
    		
    		$file->thumbnail = $prefix."thumb".$filestorename;
    		
    		$thumb->setFilename($prefix."smallthumb".$filestorename);
    		$thumb->open("write");
    		$thumb->write($thumbsmall);
    		$thumb->close();
    		$file->smallthumb = $prefix."smallthumb".$filestorename;
    		
    		$thumb->setFilename($prefix."largethumb".$filestorename);
    		$thumb->open("write");
    		$thumb->write($thumblarge);
    		$thumb->close();
    		$file->largethumb = $prefix."largethumb".$filestorename;
    			
    	}
    }
}

function form_handle_file_upload($fieldname,$access_id,$container_guid=0) {
    // Extract file from, save to default filestore (for now)
	$prefix = "file/";
	$file = new FilePluginFile();
	$filestorename = strtolower(time().$_FILES[$fieldname]['name']);
	$file->setFilename($prefix.$filestorename);
	$file->setMimeType($_FILES[$fieldname]['type']);
	
	$file->originalfilename = $_FILES[$fieldname]['name'];
	
	$file->subtype="file";
	
	$file->access_id = $access_id;
	
	$file->open("write");
	$file->write(get_uploaded_file($fieldname));
	$file->close();
	
	if ($container_guid)
		$file->container_guid = $container_guid;
	
	$file->simpletype = get_general_file_type($_FILES[$fieldname]['type']);
	
	$result = $file->save();
	
	if ($result) {
    	form_generate_thumbnail($file,$fieldname);
	}
	
	return $file->getGUID();
}


function form_vsort($original,$field,$descending = false) {
    if (!$original) {
        return $original;
    }
    $sortArr = array();
   
    foreach ( $original as $key => $item ) {
        $sortArr[ $key ] = $item->$field;
    }

    if ( $descending ) {
        arsort( $sortArr );
    } else {
        asort( $sortArr );
    }
   
    $resultArr = array();
    foreach ( $sortArr as $key => $value ) {
        $resultArr[ $key ] = $original[ $key ];
    }

    return $resultArr;
}

function form_parse_template($template,$vars,$prefix = '{$',$postfix = '}') {
    $tmp = array();
    foreach($vars as $k => $v)
    {
        $tmp[$prefix . $k . $postfix] = $v;
    }

    return str_replace(array_keys($tmp), array_values($tmp), $template);
}

function form_language_template($template) {
    return preg_replace_callback(
        '|\{\@([^}]*)\}|',
        create_function(
            // single quotes are essential here,
            // or alternative escape all $ as \$
            '$matches',
            'return elgg_echo($matches[1]);'
        ),
        $template
    );
}

function form_get_all_fields($user_id) {
    $fields = get_entities('object','form:field',$user_id,"",500);
    return form_vsort($fields,'internal_name');
}

function form_get_orphan_fields($user_id) {
    $maps = get_entities('object','form:field_map',$user_id,"",500);
    $field_ids = array();
    if ($maps) {
        foreach($maps as $map) {
            $field_ids[] = $map->field_id;
        }
        $field_ids = array_unique($field_ids);
    }
    $fields = get_entities('object','form:field',$user_id,"",500);
    $orphans = array();
    if ($fields) {
        foreach ($fields as $field) {
            if (!in_array($field->getGUID(),$field_ids)) {
                $orphans[] = $field;
            }
        }
    }
    return form_vsort($orphans,'internal_name');
}

function form_delete_orphan_fields($user_id) {
    $fields = form_get_orphan_fields($user_id);
    foreach($fields as $field) {
        $field->delete();
    }
} 

function form_delete($form_id) {
    $form = get_entity($form_id);
    // destroy the field map and then the form
    $maps = get_entities_from_metadata('form_id',$form_id,'object','form:field_map',$form->owner_guid,500);
    if ($maps) {
        foreach($maps as $map) {
            $map->delete();
        }
    }
    $form->delete();
}  

function form_get_field_definition($field_id) {
    
    // get the field data
    
    $field = get_entity($field_id);
    
    // get the choice data, if any
    
    return $field;
}

function form_get_field_choices($field_id) {
    $field = get_entity($field_id);
    return get_entities_from_metadata('field_id',$field_id,'object','form:field_choice',$field->owner_guid,500,0,"e.guid asc");
}

function form_set_field_definition() {
    $form_action = get_input('form_action','');
    //system_message ('form_action: '.$form_action);
    $form_id = get_input('form_id',0);
    $form = get_entity($form_id);
    if ($form_action == 'change') {
        $field_id = get_input('field_id',0);
        $field = get_entity($field_id);
    } else {
        $field = new ElggObject();
        $field->subtype = 'form:field';
        $field->owner_guid = $form->owner_guid;
        // field access is always public as real access is controlled at the form level
        $field->access_id = ACCESS_PUBLIC;
        $field->save();
    }
    
    $field->title = get_input('title','');
    $field->description = get_input('description','');
   
    $field->internal_name = get_input('internal_name','');

    $field->required = get_input('required',0);
    $field->admin_only = get_input('admin_only',0);
    $field->profile = get_input('profile',0);
    $field->tab = get_input('category','');
    $field->subtype_filter = get_input('subtype_filter','');
    $field->field_type = get_input('field_type','');
    $field->invisible = get_input('invisible',0);
    $field->area = get_input('area','');
    $field->default_access = get_input('default_access','');
    $field->choice_type = get_input('choice_type','');
    $field->orientation = get_input('orientation','');
    $field->default_value = get_input('default_value','');
    $field->is_keyword_tag = get_input('is_keyword_tag',0);
    
    // I use strlower to avoid an Elgg metastrings table issue
    
    if (strtolower($field->field_type) == 'contact') {
        $field->field_type = get_input('contact_type','');
    }
    $field->save();
    if ($form_action == 'change') {
        // delete the old field choices as they will be recreated with possibly new values below
        $field_choices = get_entities_from_metadata('field_id', $field->getGUID(), 'object', 'form:field_choice', $field->owner_guid,500);
        if ($field_choices) {
            foreach($field_choices as $field_choice) {
                $field_choice->delete();
            }
        }
    } else {
        // this is a new field, so add it to the current form
        $field_id = $field->getGUID();
        $map = new ElggObject();
        $map->subtype = 'form:field_map';
        $map->owner_guid = $form->owner_guid;
        // field map access is always public as real access is controlled at the form level
        $map->access_id = ACCESS_PUBLIC;
        $map->save();
        $map->field_id = $field_id;
        $map->form_id = $form_id;
        $map->display_order = 100000;
        $map->save();
        form_reorder($field->form_id);
    }
    
    $number_of_options = get_input('number_of_options',0);
    
    for ($i = 0; $i < $number_of_options; $i++) {
        $option = new ElggObject();
        $option->subtype = 'form:field_choice';
        $option->owner_guid = $form->owner_guid;
        // field option access is always public as real access is controlled at the form level
        $option->access_id = ACCESS_PUBLIC;
        $option->field_id = $field->getGUID();
        $option->value = get_input('option_'.$i.'_value','');
        $option->label = get_input('option_'.$i.'_label','');
        if ($option->value) {
            $option->save();
        }
    }  
}

function form_field_delete($field_id) {
    // remove this field from all the forms it is on
        
    $maps = get_entities_from_metadata('field_id', $field_id, 'object', 'form:field_map',0,500);
    if ($maps) {
        foreach($maps as $map) {
            $map->delete();
        }
    }
    
    // delete the field itself
    
    $field = get_entity($field_id);
    $field->delete();
}

function form_field_remove($form_id, $field_id) {
    // remove this field from this specific form
        
    $map = form_get_map($form_id,$field_id);
    if ($map) {
        $map->delete();
    }
}

function form_reorder($form_id) {
    $maps = form_get_maps($form_id);
    $order = array();
    $map_array = array();
    $i = 1;
    foreach($maps as $map) {
        $map_id = $map->getGUID();
        $order[$map_id] = $i * 10;
        $map_array[$map_id] = $map;
        $i++;
    }
    foreach($order as $map_id => $display_order) {
        $map = $map_array[$map_id];
        $map->display_order = $display_order;
        $map->save();
    }
}

// TODO: replace maps with the relationship API if possible

function form_get_maps($form_id) {
    $maps = get_entities_from_metadata('form_id',$form_id,'object','form:field_map',0,500);    
    return form_vsort($maps,'display_order');
}

function form_get_map($form_id, $field_id) {
    $maps = get_entities_from_metadata_multi(array('form_id'=>$form_id,'field_id'=>$field_id),'object','form:field_map',0,500);
    if ($maps) {
        return $maps[0];
    } else {
        return null;
    }
}

// Move a form/field map up
function form_field_moveup($form_id,$field_id) {
    $map = form_get_map($form_id, $field_id);
    if ($map) {
        $map->display_order = $map->display_order - 11;
        $map->save();
        form_reorder($form_id);
    }
}

// Move a form/field map down
function form_field_movedown($form_id,$field_id) {
    $map = form_get_map($form_id, $field_id);
    if ($map) {
        $map->display_order = $map->display_order + 11;
        $map->save();
        form_reorder($form_id);
    }
}

// Move a form/field map to the top
function form_field_movetop($form_id,$field_id) {
    $map = form_get_map($form_id, $field_id);
    if ($map) {
        $map->display_order = 0;
        $map->save();
        form_reorder($form_id);
    }
}

// Move a form/field map to the bottom
function form_field_movebottom($form_id,$field_id) {
    $map = form_get_map($form_id, $field_id);
    if ($map) {
        $map->display_order = 100000;
        $map->save();
        form_reorder($form_id);
    }
}

function form_get_fields($form_id) {
    $fields = array();
    $maps = form_get_maps($form_id);
    if ($maps) {
        foreach($maps as $map) {
            $fields[] = get_entity($map->field_id);
        }
    }
    return $fields;
}

function form_set_form_definition() {
    $form_action = get_input('form_action','');
    if ($form_action == 'change') {
        $form = get_entity(get_input('form_id',0));
    } else if ($form_action == 'add') {
        $form = new ElggObject();
        $form->subtype = 'form:form';
        $username = get_input('username','');
        $user = get_user_by_username($username);
        $form->owner_guid = $user->getGUID();
    }
    $form->access_id = get_input('access_id','');
    $profile = get_input('profile',0);
    $form->profile = $profile;
	$form->name = get_input('form_name','');
	$form->title = get_input('form_title','');
	$form->description = get_input('description','');
	$form->listing_description = get_input('form_listing_description','');
	$form->response_text = get_input('response_text','');
	if ($form->profile) {
	    $form->profile_category = get_input('profile_category','');
	    $form->profile_format = get_input('profile_format','');
    }
	$form->list_template = get_input('list_template','');
	$form->display_template = get_input('display_template','');
	$form->allow_comments = get_input('allow_comments',0);
	$form->email_form = get_input('email_form',0);
	$form->email_to = get_input('email_to','');
	$form->enable_create_menu = get_input('enable_create_menu',0);
	$form->create_menu_title = get_input('create_menu_title','');
	$form->enable_display_menu = get_input('enable_display_menu',0);
	$form->display_menu_title = get_input('display_menu_title','');
	$form->allow_recommendations = get_input('allow_recommendations',0);
	
	$form->save();
    
    return $form;
}

function form_set_search_definition($search_definition_id) {
    $form_action = get_input('form_action','');
    if ($form_action == 'change') {
        $sd = get_entity($search_definition_id);
    } else if ($form_action == 'add') {
        $form_id = get_input('form_id',0);
        $sd = new ElggObject();
        $sd->subtype = 'form:search_definition';
        //$sd->owner_guid = get_entity($form_id)->owner_guid;
        $sd->access_id = ACCESS_PUBLIC;
    }
    $sd->title = get_input('search_title','');
    $sd->description = get_input('search_description','');
    $sd->form_id = get_input('form_id','');
    $sd->internalname = get_input('internalname','');
    $sd->search_fields = get_input('search_fields','');
    $sd->search_order = get_input('search_order','');
    //$sd->expiryfield = get_input('expiryfield','');
    $sd->menu = trim(get_input('menu',''));
    if ($sd->menu) {
    	$sd->creates_menu = 1;
    } else {
    	$sd->creates_menu = 0;
    }
    $sd->show_profiles = get_input('show_profiles','');
    $sd->list_template = get_input('list_template','');
    $sd->gallery_template = get_input('gallery_template','');
	
	$sd->save();
    
    return $sd;
}

function form_set_menu_items() {
	global $CONFIG;
	
	// add display links to menu
	$entities = get_entities_from_metadata('enable_display_menu',1,'object','form:form');
	if ($entities) {
		foreach($entities as $form) {
			add_menu(form_form_t($form,'display_menu_title'),$CONFIG->wwwroot.'mod/form/my_forms.php?form_view=all&id='.$form->getGUID());
		}
	}
	
	// add create links to menu
	$entities = get_entities_from_metadata('enable_create_menu',1,'object','form:form');
	if ($entities) {
		foreach($entities as $form) {
			add_menu(form_form_t($form,'create_menu_title'),$CONFIG->wwwroot.'mod/form/form.php?id='.$form->getGUID());
		}
	}
	
	// add search links
	$entities = get_entities_from_metadata('creates_menu',1,'object','form:search_definition');
	if ($entities) {
		foreach($entities as $sd) {
			$form = get_entity($sd->form_id);
			add_menu(form_search_definition_t($form,$sd,'menu'),$CONFIG->wwwroot.'mod/form/search.php?sid='.$sd->getGUID());
		}
	}
}

function form_search_definition_delete($search_definition_id) {
    $sd = get_entity($search_definition_id);
    $sd->delete();
}

function form_field_type_to_view($field_type,$mode) {
	$form_field_types = form_get_form_field_types();
	// circumvent Elgg metadata bug
	$field_type = strtolower($field_type);
	
	// special handling for choices and contacts
	// TODO: remove the need for special handling
	
    if ($mode == 'output') {         
    	if (in_array($field_type,array('email','url'))) {
    		$view = 'output/'.$field_type;
    	} else if (in_array($field_type,array('aim','msn','skype','icq'))) {
    		$view = 'output/text';
    	} else {
    		$view = $form_field_types[$field_type]->output_view;
    	}
    } else {
    	if (in_array($field_type,array('radio','checkboxes'))) {
    		$view = 'form/input/'.$field_type;
    	} else if ($field_type == 'pulldown') {
    		$view = 'input/pulldown';
    	} else if (in_array($field_type,array('email','url','aim','msn','skype','icq'))) {
    		$view = 'input/text';
    	} else {
    		$view = $form_field_types[$field_type]->input_view;
    	}
    }
    return $view;
}

function form_get_data_from_form_submit($form_id=0) {
	$data = array();
	if (!$form_id) {
		$form_id = get_input('form_id',0);
	}
	if ($form_id) {
		$fields = form_get_fields($form_id);
		if ($fields) {
			foreach($fields as $field) {
				$value = get_input('form_data_'.$field->internal_name,'');
				if ($value) {
					$data[$field->internal_name] = $value;
				}
			}			
		}
	}
	
	return $data;
}

function form_get_data($form_data_id) {
    $data = array();
    $md = get_metadata_for_entity($form_data_id);
    if ($md) {
        foreach ($md as $item) {
        	if (isset($data[$item->name])) {
        		// more than one item of the same name, so make this into an array of values
        		// this can happen for tag fields, for example
        		if (is_array($data[$item->name]->value)) {
        			$data[$item->name]->value[] = $item->value;
        		} else {
        			$data[$item->name]->value = array($data[$item->name]->value,$item->value);
        		}
        	} else {
            	$data[$item->name] = new StdClass();
            	$data[$item->name]->value = $item->value;
        	}
        }
    }
    
    // add the access_id, title and description as these
    // could conceivably be changed as well
    $fd = get_entity($form_data_id);
    $extras = array('access_id'=>$fd->access_id,'title'=>$fd->title,'description'=>$fd->description);
    foreach ($extras as $k => $v) {
        $data[$k] = new StdClass();
        $data[$k]->value = $v;
    }        
    
    return $data;
}

function form_delete_data($form_data_id) {
    
    $form_data = get_entity($form_data_id);
    
    // delete image data, if any
    $maps = form_get_maps($form_data->form_id);
    if ($maps) {
        foreach($maps as $map) {
            $field = get_entity($map->field_id);
            if ($field->field_type == 'image_upload') {
                $internalname = $field->internal_name;
                $file_id = $form_data->$internalname;
                if ($file_id) {
                    form_delete_file($file_id);
                }
            }
        }
    }
    return $form_data->delete();
}

function form_get_input_display_item($form,$field,$data=null,$prepopulate=true,$format_view='form/display_field') {
    global $CONFIG;
    $internalname = $field->internal_name;
    $access_id = '';
    //print($internalname.':');
    if (!isset($field->admin_only) || !$field->admin_only || isadminloggedin()) {
        if (!isset($data)) {
            $data = array();
        }
        if ($prepopulate) {
            if (isset($data[$internalname])) {
                $value = $data[$internalname]->value;
                $access_id = $data[$internalname]->access_id;            
            } else {
                $value = $field->default_value;
                // must do this because Elgg has trouble with metadata set to "0"
			    // in Elgg 1.5 using a simple reference
			    $m = get_metadata_byname($field->getGUID(),'default_access');
                if ($m && ($m->value || ($m->value === 0) || ($m->value === '0'))) {
                	$access_id = $m->value;
                } else {
                	$access_id = get_default_access();
                }
            }
        } else {
            $value = '';
        }
        // use strtolower to get around the Elgg metadata case problem
        $field_type = strtolower($field->field_type);
        if ($field_type == 'image_upload') {
            if ($value) {
                $image_url = $CONFIG->wwwroot.'mod/file/thumbnail.php?size=small&file_guid='.$value;
                $view_prefix = '<p><img src="'.$image_url.'"></p>';
                //$view_prefix = $value.print_r($image_entity,true).elgg_view('graphics/icon',array('entity'=>$image_entity,'size'=>'small'));
            } else {
                $view_prefix = '';
            }
        }
        $formfieldname = 'form_data_'.$internalname;            
        $vars = array('internalname'=>$formfieldname,'value'=>$value);
        //print($field->internal_name.','.$field->field_type.','.$field->choice_type.','.$field->default_value.'<br />');
        if (strtolower($field->field_type) == 'choices') {
            $vars['orientation'] = $field->orientation;
            $field_type = $field->choice_type;
            $choices = form_get_field_choices($field->getGUID());
            if ($choices) {
                if ($choices[0]->label) {
                    $options_values = array();
                    if (!$prepopulate && $field_type == 'pulldown') {
                        // force an empty default option
                        $options_values[''] = '';
                    }
                    foreach($choices as $choice) {
                        $options_values[$choice->value] = form_choice_t($form,$field,$choice);
                    }
                    $vars['options_values'] = $options_values;
                    $vars['options'] = $options_values;
                } else {
                    $options = array();
                    if (!$prepopulate && $field_type == 'pulldown') {
                        // force an empty default option
                        $options[''] = '';
                    }
                    foreach($choices as $choice) {
                        $options[$choice->value] = $choice->value;
                    }
                    $vars['options'] = $options;
                }
            }
                        
        } else {
            $field_type = $field->field_type;
        }
        //print $field_type.'#';
        $view = form_field_type_to_view($field_type,"input");
        //print ($field_type.':'.$view.'; ');
        
        $html = elgg_view($format_view, array('field'=>$view_prefix.elgg_view($view,$vars),
            'title'=>form_field_t($form,$field,'title'),'description'=>form_field_t($form,$field,'description')));
    } else {
        if ($prepopulate) {
            if (isset($data[$internalname]) && $data[$internalname]->value) {
                $value = $data[$internalname]->value;            
            } else {
                $value = $field->default_value;
            }
        } else {
            $value = '';
        }
        $html = elgg_view('input/hidden',array('internalname'=>$internalname, 'value'=>$value));
    }
    $item = new StdClass();
    $item->default_access = $access_id;
    $item->internalname = $field->internal_name;
    $item->html = $html;
    return $item;
}

// returns an array of fields keyed and filtered by a name list
// values can be prepopulated if values are present in the $data array
// and hidden if listed in the $hidden array

function form_display_filtered($form,$namelist,$data=null,$prepopulate=true,$hidden=null) {
    $filtered = array();
    $maps = form_get_maps($form->getGUID());
    if ($maps) {
        $map_list = array();
        foreach($maps as $map) {
            $field = get_entity($map->field_id);
            $map_list[$field->internal_name] = $field;
        }
        
        foreach ($namelist as $name) {
            if (isset($map_list[$name])) {
            	if (isset($hidden) && isset($hidden[$name]) && $hidden[$name]) {
            		// hardcode this as a hidden field
            		if (isset($data) && isset($data[$name])) {
            			$value = $data[$name]->value;
            		} else {
            			$value = '';
            		}
            		$f = new stdClass;
            		$f->internalname = $name;
            		$f->default_access = ACCESS_PUBLIC;
            		$f->html = elgg_view('input/hidden', array('internalname'=>'form_data_'.$name,'value'=>$value));
            		$filtered[$name]= $f;
            	} else {
                	$filtered[$name] = form_get_input_display_item($form,$map_list[$name],$data,$prepopulate);
            	}
            }
        }
    }
    return $filtered;                
}

// returns collections of field items keyed by tab

function form_display_by_tab($form,$data=null,$prepopulate=true,$hidden=null) {
    $tabs = array();
    $maps = form_get_maps($form->getGUID());
    if ($maps) {
        foreach($maps as $map) {
            $field = get_entity($map->field_id);
            if (!$field->admin_only || isadminloggedin() ) {
            	// don't display admin fields to non-admins
	            if (isset($hidden) && isset($hidden[$name]) && $hidden[$name]) {
	            	// hardcode this as a hidden field
	            	$name = $field->internal_name;
	            	if (isset($data) && isset($data[$name])) {
	            		$value = $data[$name]->value;
	            	} else {
	            		$value = '';
	            	}
	            	$f = new stdClass;
	            	$f->internalname = $name;
	            	$f->html = elgg_view('input/hidden', array('internalname'=>'form_data_'.$name,'value'=>$value));
	            	$item = $f;
	            } else {
	            	$item = form_get_input_display_item($form,$field,$data,$prepopulate);
	            }
	            if (!$field->tab) {
	                if ($form->translate) {
	                    $tab = form_tab_t($form,elgg_echo('form:basic_tab_label'));
	                } else {
	                    $tab = elgg_echo('form:basic_tab_label');
	                }
	            } else {
	                $tab = form_tab_t($form,$field->tab);
	            }
	            
	            if (!isset($tabs[$tab])) {
	                $tabs[$tab] = array();
	            }
	            $tabs[$tab][] = $item;
            }
        }
    }
    return $tabs;
}

// Return the form fields (indexed by tab), optionally prepopulated with data

function form_get_data_for_edit_form($form,$data=null) {
        
    $tab_data = array();
    $tabs = form_display_by_tab($form,$data);
    // just flatten the result and return
    if ($tabs) {
        foreach ($tabs as $tab => $tab_items) {
            $tab_data[$tab] = '';
            foreach ($tab_items as $item) {
                $tab_data[$tab] .= $item->html;
            }
        }
    }
    return $tab_data;
}

/**
	 * Returns a view of a list of entities, plus navigation. It is intended that this function
	 * be called from other wrapper functions.
	 * 
	 * @see list_entities
	 * @see list_user_objects
	 * @see list_user_friends_objects
	 * @see list_entities_from_metadata
	 * @see list_entities_from_metadata_multi
	 * @see list_entities_from_relationships
	 * @see list_site_members
	 *
	 * @param array $entities List of entities
	 * @param int $count The total number of entities across all pages
	 * @param int $offset The current indexing offset
	 * @param int $limit The number of entities to display per page
	 * @param true|false $fullview Whether or not to display the full view (default: true)
	 * @param true|false $viewtypetoggle Whether or not to allow users to toggle to gallery view
	 * @return string The list of entities
	 */
function form_view_entity_list($entities, $form, $count, $offset, $limit, $fullview = true, $viewtypetoggle = true) {
	
	$count = (int) $count;
	$offset = (int) $offset;
	$limit = (int) $limit;
	
	$context = get_context();
	
	$html = elgg_view('form/entity_list',array(
											'entities' => $entities,
											'form' => $form,
											'count' => $count,
											'offset' => $offset,
											'limit' => $limit,
											'baseurl' => $_SERVER['REQUEST_URI'],
											'fullview' => $fullview,
											'context' => $context, 
											'viewtypetoggle' => $viewtypetoggle,
											'viewtype' => get_input('search_viewtype','list'), 
										  ));
		
	return $html;
	
}

function form_get_field_output($form,$field,$value) {
	$form_id = $form->getGUID();
	if ($form->profile) {
    	$profile = $form->profile;
    } else {
    	$profile = 0;
    }
    $type_array = array('form_data','user','group');
	if (strtolower($field->field_type) == "choices") {
		$choices = form_get_field_choices($field->getGUID());
		if (is_array($value)) {
			$value_array = array();
			foreach($value as $value2) {
				$this_choice = '';
				foreach($choices as $choice) {
					if ($choice->value == $value2) {
						$this_choice = $choice;
						break;
					}
				}
				if ($this_choice) {
					$value_array[$value2] = form_choice_t($form,$field,$this_choice);
				} else {
					$value_array[$value2] = $value2;
				}
			}
			return elgg_view("form/output/choice",array('form_id'=>$form_id,'type'=>$type_array[$profile],'internalname'=>$field->internal_name,'value'=>$value_array));
		} else {
			$this_choice = '';
			foreach($choices as $choice) {
				if ($choice->value == $value) {
					$this_choice = $choice;
					break;
				}
			}
			if ($this_choice) {
				return elgg_view("form/output/choice",array('form_id'=>$form_id,'type'=>$type_array[$profile],'internalname'=>$field->internal_name,'value'=>$value,'label'=>form_choice_t($form,$field,$this_choice)));
			} else {
				return elgg_view("form/output/choice",array('form_id'=>$form_id,'type'=>$type_array[$profile],'internalname'=>$field->internal_name,'value'=>$value));
			}
		}
	} else {
		$view = form_field_type_to_view($field->field_type,"output");
		return elgg_view($view,array('form_id'=>$form_id,'type'=>$type_array[$profile],'internalname'=>$field->internal_name,'value'=>$value));
	}
}

// assumes that form data is stored as metadata on a form_data object

function form_view_entities($entities, $form, $viewtype) {

	global $CONFIG;

	$html = '';
	$qs = $_SERVER["QUERY_STRING"];

	if (is_array($entities) && sizeof($entities) > 0) {

		if (in_array($viewtype,array('list','display'))) {
			$commentable = $form->allow_comments;
			$recommendable = $form->allow_recommendations;
		} else {
			$commentable = $false;
			$recommendable = $false;
		}

		foreach($entities as $entity) {
			$form_data_id = $entity->getGUID();
			$entity_url = $entity->getURL();
			$md_get = get_metadata_for_entity($form_data_id);
			$md = array();
			foreach ($md_get as $m) {
				if (isset($md[$m->name])) {
					if (!is_array($md[$m->name]->value)) {
						$md[$m->name]->value = array($md[$m->name]->value);						
					}
					$md[$m->name]->value[] = $m->value;			
				} else {
					$md[$m->name] = new StdClass();
					$md[$m->name]->value = $m->value;
					$md[$m->name]->name = $m->name;
				}
			}

			// add title and description
			$extras = array('title'=>$entity->title,'description'=>$entity->description);
			foreach ($extras as $k => $v) {
				$md[$k] = new StdClass();
				$md[$k]->value = $v;
				$md[$k]->name = $k;
			}
			$vars = array();
			$form_id = $form->getGUID();
			$fields = form_get_fields($form_id);
			if ($fields) {
				foreach ($fields as $field) {
					$internalname = $field->internal_name;
					if (isset($md[$internalname])) {
						$item = $md[$internalname];
						$vars[$item->name] = form_get_field_output($form,$field,$item->value);
						if ($field->field_type == 'image_upload') {
							$vars[$item->name.':thumb'] = elgg_view($view,array('value'=>$item->value,'size'=>'small'));
						} else if ($field->field_type == 'video_box') {
							$vars[$item->name.':thumb'] = elgg_view($view,array('value'=>$item->value,'size'=>'thumb'));
						}
					} else {
						// just return empty strings
						$vars[$internalname] ='';
						if ($field->field_type == 'image_upload') {
							$vars[$internalname.':thumb'] = '';
						} else if ($field->field_type == 'video_box') {
							$vars[$internalname.':thumb'] = '';
						}
					}
				}
			}

			$comment_bit = '';
			$recommend_bit = '';

			if ($commentable) {
				if ($viewtype == 'display') {
					$comment_bit = elgg_view_comments($entity);
				} else {
					$num_comments = elgg_count_comments($entity);
					if ($num_comments == 1) {
						$comment_bit = $num_comments.elgg_echo('form:comment');
					} else {
						$comment_bit = $num_comments.elgg_echo('form:comments');
					}
				}
			}

			if ($recommendable) {
				$number_of_recommendations = count_annotations($form_data_id, 'object', 'form_data', 'form:recommendation');
				// count_annotations had a bug, but should be fixed now, so comment out this workaround

				//$number_of_recommendations = form_count(get_annotations($form_data_id, 'object', 'form_data', 'form:recommendation', 1, 0, 500));
				if (isloggedin() && ($viewtype == 'display')) {
					$number_of_my_recommendations = form_count(get_annotations($form_data_id, 'object', 'form_data', 'form:recommendation', 1,$_SESSION['user']->getGUID()));
					$user_guid = $_SESSION['user']->getGUID();
					if ($number_of_my_recommendations == 0){
						$recommendation_template = '<a href="%s">'.elgg_echo("form:recommend_this"). '</a>';
						$my_recommend_bit = ' ['.sprintf($recommendation_template,$CONFIG->wwwroot.'action/form/manage_form_data?form_action=recommend&d='.$form_data_id).']';
					} else {
						$my_recommend_bit = '';
					}
				} else {
					$my_recommend_bit = '';
				}
				if ($number_of_recommendations == 1) {
					$recommend_bit = sprintf(elgg_echo('form:recommendation'),$number_of_recommendations).$my_recommend_bit;
				} else {
					$recommend_bit = sprintf(elgg_echo('form:recommendations'),$number_of_recommendations).$my_recommend_bit;
				}
			}

			if ($viewtype == 'list') {
				if ($recommend_bit && $comment_bit) {
					$annotation_bit = $recommend_bit.', '.$comment_bit;
				} else {
					$annotation_bit = $recommend_bit.$comment_bit;
				}
			} else {
				$annotation_bit = $recommend_bit.'<br /><br />'.$comment_bit;
			}

			if (trim($form->list_template) || trim($form->display_template)) {

				$vars['_url'] = $CONFIG->wwwroot;
				 
				$vars['_user_message_link'] = '<a href="'.$CONFIG->wwwroot.'mod/messages/send.php?send_to='.$entity->owner_guid
				.'">'.elgg_echo('form:send_a_message').'</a>';
				 
				$vars['_full_view_link'] = '<a href="'.$entity_url.'">'.elgg_echo('form:full_view').'</a>';
				$vars['_full_view_url'] = $entity_url;
				$vars['_friendlytime'] = friendly_time($entity->time_created);
				$owner_entity = $entity->getOwnerEntity();
				$owner_url = $owner_entity->getUrl();
				$vars['_owner_url'] = $owner_url;
				$vars['_owner_name'] = $owner_entity->name;
				$vars['_owner_username'] = $owner_entity->username;
				$vars['_owner_message_link'] = $vars['_user_message_link'];
				$vars['_owner_icon'] = elgg_view(
					"profile/icon", array(
									'entity' => $owner_entity,
									'size' => 'small',
				)
				);
				$vars['_annotations']   =  $annotation_bit;
			}

			if ($viewtype == 'display') {
				$template = $form->display_template;
				if (trim($template)) {
					$content = form_parse_template($template,$vars);
					$content = form_language_template($content);
				} else {
					$content = elgg_view('form/default_form_data_display',array('entity'=>$entity,'annotations'=>$annotation_bit));
				}
			} else if ($viewtype == 'list') {
				$template = $form->list_template;
				if (trim($template)) {
					$content = form_parse_template($template,$vars);
					$content = form_language_template($content);
				} else {
					$content = elgg_view('form/default_form_data_listing',array('entity'=>$entity,'annotations'=>$annotation_bit));
				}
			} else {
				$content = sprintf(elgg_echo('form:submitted'),friendly_time($entity->time_created));
				if ($entity->time_updated != $entity->time_created) {
					$content .= ", ".sprintf(elgg_echo('form:updated'),friendly_time($entity->time_updated));
				}
			}

			$html .= $content;
		}
	}
	return $html;
}

function form_recommend($form_data_id) {
    if (isloggedin()) {
        $user_guid = $_SESSION['user']->getGUID();
        $number_of_my_recommendations = form_count(get_annotations($form_data_id, 'object', 'form_data', 'form:recommendation', 1,$user_guid));
        if ($number_of_my_recommendations == 0) {
            create_annotation($form_data_id, 'form:recommendation', 1, 'integer', $user_guid, ACCESS_PUBLIC);
            return true;
        } else {
            return false;
        }
    }
    return false; 
}

// TODO - determine if this next function is needed anymore
function form_count($s) {
    if (!isset($s) || !is_array($s)) {
        return 0;
    } else {
        return count($s);
    }
}

// sets the form data if valid and returns with an error status
// this is only for form_data objects
// user and group profiles use form_set_data in models/profile.php

function form_set_data_from_form($form_data_id = 0) {
    global $CONFIG;
    
    $form_id = get_input('form_id');
    $form = get_entity($form_id);
    $maps = form_get_maps($form_id);
    if ($maps) {
    	// fields are no longer stored in an array
        //$form_data = get_input('form_data',array());
        if ($form_data_id) {
            $fd = get_entity($form_data_id);
        } else {
            $fd = new ElggObject();
            $fd->subtype = 'form_data';
            if (isloggedin()) {
                $fd->owner_guid = $_SESSION['user']->getGUID();
            } else {
                // no owner
                $fd->owner_guid = 0;
            }
            // Same as form unless this is changed by the form submit
            $fd->access_id = $form->access_id;
            $fd->form_id = $form_id;
        }
        $form_data = array();
        $images = array();
        $invite_box_name = '';
        $result = new StdClass();
        $result->error_status = false;
        $result->missing = array();
        $result_form_data = array();
        foreach($maps as $map) {
            $field = get_entity($map->field_id);
            $internalname = $field->internal_name;
            $item = new StdClass();
            $item->value = '';
            $form_data[$internalname] = get_input('form_data_'.$internalname,'');
            if ($field->field_type == 'tags'){
            	// KJ - I reverse the array to fix an annoying Elgg tag order bug
            	// I will remove this workaround when the bug is fixed
				$form_data[$internalname] = array_reverse(string_to_tag_array($form_data[$internalname]));
            }
            if ($field->field_type == 'image_upload') {
                // special handling for images
                $images[] = $internalname;
            } else if ($field->field_type == 'invite_box') {
                // special handling for invite box
                $invite_box_name = 'form_data_'.$internalname;
            } else if ($field->field_type == 'access') {
            	// set both values in case the internal name is not "access_id"
            	$fd->$internalname = $form_data[$internalname];
            	$fd->access_id = $form_data[$internalname];
                $item->value = $form_data[$internalname];
            } else if(isset($form_data[$internalname])) {
                $fd->$internalname = $form_data[$internalname];
                $item->value = $form_data[$internalname];                    
            } else {
                $fd->$internalname = '';
            }
            
            if ($field->required && (!isset($form_data[$internalname]) || (trim($form_data[$internalname]) === ''))) {
                $result->error_status = true;
                $result->error_reason = 'missing';
                $result->missing[] = $field;
            } 
            $result_form_data[$internalname] = $item;
        }
        $result->form_data = $result_form_data;
        if ($result->error_status) {
            return $result;
        } else {
            // looks good
            
            if ($form->email_form) {
                form_send_results($form,$result,$fd->owner_guid);
                return $result;
            } else {
            
                // set language and save          
                if (empty($CONFIG->language)) {
                    $fd->_language = 'en';
                } else {
                    $fd->_language = $CONFIG->language;
                }
                if ($fd->save()) {
                    // success, so save images
                    foreach($images as $image) {
                        $formfieldname = 'form_data_'.$image;
                        // don't do anything if this field is blank
                        if (isset($_FILES[$formfieldname]['name']) && trim($_FILES[$formfieldname]['name'])) {
                            if ($fd->$image) {
                                // delete the old file
                                form_delete_file($fd->$image);
                            }
                            $fd->$image = form_handle_file_upload($formfieldname,$fd->access_id);
                        }
                    }
                    // added to keep the river happy
                    if (!trim($fd->title)) {
                    	$fd->title = sprintf(elgg_echo('form:river:title'),$form->title);
                    }
                    if (!$form_data_id)
                    	add_to_river('river/object/form_data/create','create',get_loggedin_userid(),$fd->getGUID());
                    // TODO: avoid this second save
                    $fd->save();
                    // handle invitations if required
                    if ($invite_box_name) {
                        form_send_invitations($invite_box_name,$fd->getGUID());
                    }
                    return $result;
                } else {
                    $result->error_status = true;
                    $result->error_reason = 'save_failed';
                    return result;
                }
            }
        }
    }
}

function form_send_results($form,$result,$submitter) {
    global $CONFIG;
    
    if ($form->email_to) {
        if ($submitter) {
            $user = get_entity($submitter);
            $current_time = date('r');
            $message = sprintf(elgg_echo('form:results_send_header'),$user->name,$user->username,$form->title,$form->name,$current_time);
        } else {
            $message = sprintf(elgg_echo('form:results_send_anonymous_header'),$form->title,$form->name,$current_time);
        }
        foreach ($result->form_data as $key => $item) {
            $message .= "\n\n*".$key."*\n\n";
            $message .= $item->value;
        }
        
        $site = get_entity($CONFIG->site_guid);
        if ($site->email) {
        	// this should be defined as of Elgg 1.1
        	$from = $site->email;
        } else {
        	$from = 'noreply@' . get_site_domain($CONFIG->site_guid);
        }
        $subject = sprintf(elgg_echo('form:results_send_subject'),$form->title);
        form_send_email(array($form->email_to), $from, $subject, $message);
    }
}

function form_send_invitations($invite_box_name,$form_data_id) {
    global $CONFIG;
    
    $contacts = trim(get_input($invite_box_name.'_contacts',''));
    if ($contacts) {
        $user_message = trim(get_input($invite_box_name.'_message',''));
        $url = $CONFIG->wwwroot.'mod/form/display_object.php?d='.$form_data_id;
        $message = sprintf(elgg_echo('form:invite_message'),$_SESSION['user']->name,$url);
        if ($user_message) {
            $message .= sprintf(elgg_echo('form:user_message'),$user_message);
        }
        $user_list = array();
        $email_address_list = array();
        // handle comma separators
        $contacts2 = explode(",",$contacts);
        // handle new line separators as well
        $contact_list = array();
        foreach($contacts2 as $contact) {
            $contact_list = array_merge($contact_list,explode("\n",$contact));
        }
        
        foreach ($contact_list as $contact) {
            $contact = trim($contact);
            if (strpos($contact,'@') === false) {
                $user = get_user_by_username($contact);
                if ($user && $user_id = $user->getGUID()) {
                    $user_list[] = $user_id;
                }
            } else {
                $email_address_list[] = $contact;
            }
        }
        
        $subject = sprintf(elgg_echo('form:invite_subject'),$_SESSION['user']->name);
        
        if ($user_list) {                
            $from = $_SESSION['user']->getGUID();
            //print_r($user_list);
            //print $subject;
            //print $message;
            // need to force email for now as Elgg 1 notification does not seem to work without it
            notify_user($user_list, $from, $subject, $message, null, array('email'));
        }
        
        if ($email_address_list) {
	        $site = get_entity($CONFIG->site_guid);
	        if ($site->email) {
	        	// this should be defined as of Elgg 1.1
	        	$from = $site->email;
	        } else {
	        	$from = 'noreply@' . get_site_domain($CONFIG->site_guid);
	        }
            form_send_email($email_address_list, $from, $subject, $message);
        }
    }
}

function form_send_email($to_list, $from, $subject, $message) {
    $headers = "From: $from";
    foreach($to_list as $to) {
        mail($to,$subject,$message,$headers);
    }
}

function form_get_field_id_from_name($existing_field_name,$user_guid) {
    $fields = get_entities_from_metadata('internal_name', $existing_field_name, 'object', 'form:form_field', $user_guid,500);
    if ($fields) {
        $field_id = $fields[0]->getGUID();
    } else {
        $field_id = 0;
    }
    return $field_id;
}

function form_get_field_from_name($existing_field_name,$user_guid) {
    $fields = get_entities_from_metadata('internal_name', $existing_field_name, 'object', 'form:form_field', $user_guid,500);
    if ($fields) {
        return $fields[0];
    } else {
        return null;
    }
}

function form_add_existing_field($form_id,$field_id) {
    $form = get_entity($form_id);
    
    // do nothing if this field is already on the form
    $map = form_get_map($form_id,$field_id);
    if (!$map) {    
        // Initialise a new ElggObject
		$map = new ElggObject();
    	// Tell the system it's a form_fields_map
    	$map->subtype = "form:field_map";
    	// Set its owner to the form user
    	$map->owner_guid = $form->owner_guid;
    	// anyone needs to be able to access this to make sure that the Elgg permissions system does not
    	// cause problems
    	$map->access_id = ACCESS_PUBLIC;
        $map->form_id = $form_id;
        $map->field_id = $field_id;
        $map->display_order = 100000;
        if ($map->save()) {
            form_reorder($form_id);
            return true;
        } else {
            return false;
        }
    }
    
    return false;
}

// The next function is inefficient because it may get all the data and then 
// throw a lot of it away.
// Will rewrite when the Elgg API gets better or may need a custom SQL select.

function form_get_data_with_search_conditions($conditions,$sd,$limit,$offset) {
    global $CONFIG;
    
    $search_order_field = trim($sd->search_order);
    $form = get_entity($sd->form_id);
    // will return at most 500 search results
    if ($form->profile == 1) {
    	// this is a profile form, so get the data from users
        $entities = get_entities_from_metadata_multi($conditions, 'user', '',0,500);
    } else if ($form->profile == 2) {
    	// this is a profile form, so get the data from groups
    	// if a profile category is set for this form, restrict the search
    	// to groups of that category
    	if ($form->profile_category) {
    		$conditions['group_profile_category'] = $form->profile_category;
    	}
        $entities = get_entities_from_metadata_multi($conditions, 'group', '',0,500);
    } else if ($conditions) {
        $conditions['form_id'] = $sd->form_id;
        $entities = get_entities_from_metadata_multi($conditions, 'object', 'form_data',0,500);
    } else {
        // if no conditions, return everything from the relevant form
        $entities = get_entities_from_metadata('form_id', $sd->form_id, 'object', 'form_data',0,500);
    }
    if ($entities) {
    	if (!$form->profile) {
	        // filter by language if appropriate
	        $new_results2 = array();
	        $view_languages = array();
	        if (isloggedin()) {
	            $key = 'form:view_content_languages';
	            if (!empty($_SESSION['user']->$key)) {
	                $view_languages = explode(',',$_SESSION['user']->$key);
	            }
	        }
	        // we can always see content in the current language
	        $view_languages[] = $CONFIG->language;
	        
	        foreach($entities as $entity) {
	            if (empty($entity->$_language) || in_array($entity->$_language,$view_languages)) {
	                $new_results2[] = $entity;
	            }
	        }
    	} else {
    		$new_results2 = $entities;
    	}        
            
        // sort by search order if required
        if($search_order_field) {
            $new_results3 = form_vsort($new_results2,$search_order_field);
        } else {
            $new_results3 = $new_results2;
        }
        // now slice the results using limit and offset
        $count = count($new_results3);
        return array($count,array_slice($new_results3,$offset,$limit));
    } else {
        return array(0,$entities);
    }
//    } else {
//        // no special handling required
//        return get_entities_from_metadata_multi($conditions, 'object', 'form_data', 0, $limit, $offset, "", 0, false);
//    }
}

// this is a simpler version of the function above that does not require a search definition
// TODO: filter for more than the current language

function form_get_data_with_search_conditions_simple($conditions,$type,$form_id,$limit,$offset) {
    global $CONFIG;
    
    if ($type == 'user') {
    	// this is a user profile form, so get the data from users
        $entities = get_entities_from_metadata_multi($conditions, 'user','',0,$limit,$offset);
        $count = get_entities_from_metadata_multi($conditions, 'user','',0,$limit,$offset,"",0,true);
    } else if ($type == 'group') {
    	// this is a user profile form, so get the data from groups
        $entities = get_entities_from_metadata_multi($conditions, 'group','',0,$limit,$offset);
        $count = get_entities_from_metadata_multi($conditions, 'group','',0,$limit,$offset,"",0,true);
    } else {
    	if (!$conditions) {
    		$conditions = array();
    	}
        $conditions['form_id'] = $form_id;
        $conditions['_language'] = $CONFIG->language;
        $entities = get_entities_from_metadata_multi($conditions, 'object','form_data',0,$limit,$offset);
        $count = get_entities_from_metadata_multi($conditions, 'object','form_data',0,$limit,$offset,"",0,true);
    }
    return array($count,$entities);
}

// returns a piece of form text

function form_form_t($form,$t) {
  if ($form->translate) {
      return elgg_echo('form:formtrans:'.$form->name.':'.$t);
  } else {
      return $form->$t;
  }
}

// returns a piece of field text

function form_field_t($form,$field,$t) {
  if ($form->translate) {
      return elgg_echo('form:fieldtrans:'.$field->internal_name.':'.$t);
  } else {
      return $field->$t;
  }
}

// returns the choice label

function form_choice_t($form,$field,$choice) {
  if ($form->translate) {
      return elgg_echo('form:fieldchoicetrans:'.$field->internal_name.':'.$choice->value);
  } else {
      return $choice->label;
  }
}

// returns a piece of search definition text

function form_search_definition_t($form,$sd,$t) {
  if ($form->translate) {
      return elgg_echo('form:sdtrans:'.$form->name.':'.$sd->internalname.':'.$t);
  } else {
      return $sd->$t;
  }
}

// returns the tab text

function form_tab_t($form,$t) {
  if ($form->translate) {
      return elgg_echo('form:tabtrans:'.$t);
  } else {
      return $t;
  }
}

function form_set_translation_status($form_id,$translate) {
    $form = get_entity($form_id);
    $form->translate = $translate;
    $form->save();
}


function form_get_user_content_status() {
	$form_user_content_area = get_plugin_setting('user_content_area', 'form');
	if ($form_user_content_area) {
		return ($form_user_content_area == 'yes')?true:false;
	} else {
		// use old method
	    $form_config = get_entities('object','form:config');
	    if ($form_config) {
	        return $form_config[0]->user_content_status;
	    }	    
	    return false;
	}
}

function form_set_user_content_status($status) {
    $form_config = get_entities('object','form:config');
    if (!$form_config) {
        $form_config = new ElggObject();
        $form_config->subtype = 'form:config';
        $form_config->owner_guid = $_SESSION['user']->getGUID();
        $form_config->access_id = ACCESS_PUBLIC;
    } else {
        $form_config = $form_config[0];
    }
    
    $form_config->user_content_status = $status;
    $form_config->save();
}

function form_type_options() {
	
	return array (
		0 => elgg_echo('form:content_type'),
		1 => elgg_echo('form:user_profile_type'),
		2 => elgg_echo('form:group_profile_type'),
		3 => elgg_echo('form:file_type'),
	);
}

function form_get_tabbed_output_display($form,$data) {
	$form_id = $form->getGUID();
	$tab_data = array();
    $maps = form_get_maps($form_id);
    if ($maps) {
        foreach($maps as $map) {
            $field = get_entity($map->field_id);
            if ($field->field_type != 'access') {
	            $internalname = $field->internal_name;
	            if (isset($data[$internalname]) && $data[$internalname]->value) {
		            if (!$field->tab) {
		                if ($form->translate) {
		                    $tab = form_tab_t($form,elgg_echo('form:basic_tab_label'));
		                } else {
		                    $tab = elgg_echo('form:basic_tab_label');
		                }
		            } else {
		                $tab = form_tab_t($form,$field->tab);
		            }
		            if (!isset($tab_data[$tab])) {
		            	$tab_data[$tab] = '';
		            }
		            $title = form_field_t($form,$field,'title');
		            $value = form_get_field_output($form,$field,$data[$internalname]->value);
		            $tab_data[$tab] .= elgg_view('form/extended_display_field',array('title'=>$title,'value'=>$value));
	            }
            }
        }
    }
    return $tab_data;
}

?>