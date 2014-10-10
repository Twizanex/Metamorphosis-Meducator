<?php
error_reporting(E_ALL ^ E_NOTICE);
$embedded = true;
include_once("services/qooxdoo/elggMan.php");

function westorElggMan_init()
{
	global $CONFIG;

	$adminOnlyOption = westorElggMan_get_plugin_setting('adminOnlyOption', 'westorElggMan');
  $isAdmin = method_exists ( $_SESSION['user'] , "isAdmin" ) ? $_SESSION['user']->isAdmin() : ($_SESSION['user']->admin || $_SESSION['user']->siteadmin);
	if ($adminOnlyOption == 'yes' && ! $isAdmin) {
		return;
	}

	if (isloggedin()) {
		if (file_exists($CONFIG->path . "mod/westorElggMan/source/index.php")) {
			westorElggMan_menu_add(elgg_echo('ElggMan_'), $CONFIG->wwwroot . "mod/westorElggMan/source/index.php");
		} else {
			westorElggMan_menu_add(elgg_echo('ElggMan_'), $CONFIG->wwwroot . "mod/westorElggMan/build/index.php");
		}
	}
	// register cron avaery minute task
	westorElggMan_register_plugin_hook('cron', 'minute', 'westorElggMan_cron_handler');
	// override permissions for the myaccess context

	westorElggMan_register_plugin_hook('container_permissions_check', 'all', 'westorElggMan_permissions_check');
	westorElggMan_register_plugin_hook('permissions_check', 'all', 'westorElggMan_permissions_check');
}

/**
 * Overrides default permissions for the myaccess context
 */
function westorElggMan_permissions_check($hook_name, $entity_type, $return_value, $parameters) {
	if (westorElggMan_get_context() == 'westorElggMan') {
		return true;
	}
	return null;
}


function westorElggMan_cron_handler($hook, $entity_type, $returnvalue, $params)
{
	global $CONFIG;

	// old elgg bevore 1.7.0
	global $is_admin;
	$is_admin = true;

	if (function_exists("elgg_set_ignore_access")) {
		// new function for access overwrite
		elgg_set_ignore_access(true);
	}

	$context = westorElggMan_get_context();
	westorElggMan_set_context('westorElggMan');
	$prefix = $CONFIG->dbprefix;
	$sql = "SELECT {$prefix}metadata.entity_guid
FROM (({$prefix}metadata AS {$prefix}metadata_1 INNER JOIN {$prefix}metastrings AS {$prefix}metastrings_3
ON {$prefix}metadata_1.name_id = {$prefix}metastrings_3.id) INNER JOIN {$prefix}metastrings
AS {$prefix}metastrings_2 ON {$prefix}metadata_1.value_id = {$prefix}metastrings_2.id) INNER JOIN (({$prefix}metadata INNER JOIN {$prefix}metastrings ON {$prefix}metadata.name_id = {$prefix}metastrings.id) INNER JOIN {$prefix}metastrings AS {$prefix}metastrings_1 ON {$prefix}metadata.value_id = {$prefix}metastrings_1.id) ON {$prefix}metadata_1.entity_guid = {$prefix}metadata.entity_guid
WHERE ((({$prefix}metastrings.string)='waitForSend') AND (({$prefix}metastrings_1.string)='1')
AND (({$prefix}metastrings_3.string)='hiddenTo') AND (({$prefix}metastrings_2.string)<>'1'))";
	// and (scheduled is null || scheduled <= now());
	try {
		$result = get_data($sql);
	} catch (Exception $e) {
		westorElggMan_set_context($context);
		throw new Exception($e);
	}
	if (is_array($result)) {
		$elggMan = new class_elggMan();
		foreach($result as $row) {
			$message = get_entity($row->entity_guid);
			if (is_object($message) && $message->getSubtype() == "messages") {
				$elggMan->sendMsgNow($message);
			}
		}
	}
	westorElggMan_set_context($context);
}

// Default event handlers for plugin functionality
register_elgg_event_handler('init', 'system', 'westorElggMan_init');


// function wrapper
function westorElggMan_menu_add($menu_name, $menu_url) {
	if (function_exists("elgg_register_menu_item")) {
		return elgg_register_menu_item('site', array('name' => $menu_name, 'text' => $menu_name,
		'href' => $menu_url));
	} else {
		return add_menu($menu_name, $menu_url);		
	}
}

function westorElggMan_get_plugin_setting($name, $plugin_id = "") {
	if (function_exists("elgg_get_plugin_setting")) {
		return elgg_get_plugin_setting($name, $plugin_id);
	} else {
		return get_plugin_setting($name, $plugin_id);
	}
}

function westorElggMan_set_plugin_setting($name, $value, $plugin_id = null) {
	if (function_exists("elgg_set_plugin_setting")) {
		return elgg_set_plugin_setting($name, $value, $plugin_id);
	} else {
		return set_plugin_setting($name, $value, $plugin_id);
	}
}

function westorElggMan_register_plugin_hook($hook, $type, $callback, $priority = 500) {
	if (function_exists("elgg_register_plugin_hook_handler")) {
		return elgg_register_plugin_hook_handler($hook, $type, $callback, $priority);
	} else {
		return register_plugin_hook($hook, $type, $callback, $priority);		
	}
}

function westorElggMan_set_context($context) {
	if (function_exists("elgg_set_context")) {
		return elgg_set_context($context);
	} else {
		return set_context($context);
	}
}

function westorElggMan_get_context() {
	if (function_exists("elgg_get_context")) {
		return elgg_get_context();
	} else {
		return get_context();
	}
}

function westorElggMan_get_entities_from_relationship($relationship, $relationship_guid,
$inverse_relationship = false, $type = "", $subtype = "", $owner_guid = 0,
$order_by = "", $limit = 10, $offset = 0, $count = false, $site_guid = 0) {
  if (function_exists("elgg_entities_from_relationship")) {
	  $options = array();
	  $options['relationship'] = $relationship;
	  $options['relationship_guid'] = $relationship_guid;
	  $options['inverse_relationship'] = $inverse_relationship;
	  if ($type) {
	    $options['types'] = $type;
	  }
	  if ($subtype) {
	    $options['subtypes'] = $subtype;
	  }
	  if ($owner_guid) {
	    $options['owner_guid'] = $owner_guid;
	  }
	  $options['limit'] = $limit;
	  if ($offset) {
	    $options['offset'] = $offset;
	  }
	  if ($order_by) {
	    $options['order_by'];
	  }
	  if ($site_guid) {
	    $options['site_guid'];
	  }
	  if ($count) {
	    $options['count'] = $count;
	  }
	  return elgg_get_entities_from_relationship($options);
  } else {
    return get_entities_from_relationship($relationship, $relationship_guid,
		$inverse_relationship, $type, $subtype, $owner_guid,
		$order_by, $limit, $offset, $count, $site_guid);
  }
}

?>
